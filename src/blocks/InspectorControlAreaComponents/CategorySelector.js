//Imports for necessary WordPress libraries
import { __ } from "@wordpress/i18n";
import {
  RadioControl,
  __experimentalDivider as Divider,
  __experimentalSpacer as Spacer,
  Notice,
} from "@wordpress/components";
import { useState, useEffect } from "@wordpress/element"; // eslint-disable-line import/no-unresolved

/**
 * Creates the Category Selection for the video library of the videoblock
 * @param {*} param0 
 * @returns
 * @see CustomInspectorControls
 */
const CategorySelector = ({attributes, setAttributes}) => {
  const [videoCategories, setVideoCategories] = useState([]);

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
    // eslint-disable-next-line no-undef
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
    // eslint-disable-next-line no-undef
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
      {videoCategories.length > 0 && (
        <RadioControl
          label={__("Categories", "rrze-video")}
          options={[
            {
              label: __("Don't show random videos.", "rrze-video"),
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
      {videoCategories.length === 0 && (
        <>
          <Divider />
          <Spacer>
            <Notice status="info" isDismissible={false}>
              {__(
                "Add Categories via Dashboard > Video Library > Categories to use this feature.",
                "rrze-video"
              )}
            </Notice>
          </Spacer>
        </>
      )}
    </>
  );
};

export default CategorySelector;
