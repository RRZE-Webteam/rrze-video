import { __ } from "@wordpress/i18n";
import {
  RadioControl,
  CheckboxControl,
  __experimentalText as Text,
  __experimentalDivider as Divider,
} from "@wordpress/components";
import { useBlockProps } from "@wordpress/block-editor";
import { useState, useEffect } from "@wordpress/element";
import apiFetch from "@wordpress/api-fetch";
import { isTextInString } from "./utils";

const CategorySelector = (props) => {
  const [videoCategories, setVideoCategories] = useState([]);
  const [selectedCategories, setSelectedCategories] = useState({});

  const blockProps = useBlockProps();
  const { attributes, setAttributes } = props;

  const handleOnChangeVideoCat = (categoryId, newValue) => {
    setAttributes({
      rand: newValue,
    });
  };

  /**
   * Uses RestAPI to retrieve all available video category IDs
   * @returns Array of VideoIDs
   */
  const retrieveAvailableCategoryId = () => {
    return wp
      .apiFetch({
        path: "/wp/v2/rrze-video/",
      })
      .then((data) => {
        let buffer = [];
        data.forEach((element) => {
          buffer.push(element.genre);
        });
        let flatBuffer = buffer.flat();
        let uniqueBuffer = [...new Set(flatBuffer)];
        return uniqueBuffer;
      })
      .catch((error) => {
        console.log(error);
        return [];
      });
  };

  /**
   * Creates a category object from a category ID
   * @param {*} categoryId
   * @returns Object with category information
   * @see retrieveAvailableCategoryId
   */
  const retrieveCategoryInformation = (categoryId) => {
    return wp
      .apiFetch({
        path: `/wp/v2/genre/${categoryId}`,
      })
      .then((data) => {
        let buffer = {
          slug: data.slug,
          name: data.name,
          id: data.id,
          parent: data.parent,
        };
        return buffer;
      })
      .catch((error) => {
        console.log(error);
        return {};
      });
  };

  useEffect(() => {
    retrieveAvailableCategoryId().then((categoryIds) => {
      Promise.all(categoryIds.map(retrieveCategoryInformation))
        .then((categories) => {
          setVideoCategories(categories);
        })
        .catch((error) => {
          console.log(error);
        });
    });
  }, []);

  return (
    <>
      {videoCategories && (
        <RadioControl
          label="Categories"
          options={[
            {
              label: __(
                "Don't show random videos.",
                "rrze-video"
              ),
              value: "",
            },
            ...videoCategories.map((cat) => ({
              label: cat.name,
              value: cat.slug,
            })),
          ]}
          onChange={(value) => {
            if (value === "") {
              handleOnChangeVideoCat(null, "");
            } else {
              const selectedCategory = videoCategories.find(
                (cat) => cat.slug === value
              );
              handleOnChangeVideoCat(
                selectedCategory.id,
                selectedCategory.slug
              );
            }
          }}
          selected={attributes.rand}
        />
      )}
    </>
  );
};

export default CategorySelector;
