import { __ } from "@wordpress/i18n";
import {
  Placeholder,
  Button,
  ButtonGroup,
  ToolbarGroup,
  ToolbarItem,
  PanelBody,
  BaseControl,
  CheckboxControl,
  __experimentalText as Text,
  __experimentalDivider as Divider,
} from "@wordpress/components";
import { more, reset, video } from "@wordpress/icons";
import {
  useBlockProps,
  BlockControls,
  InspectorControls,
} from "@wordpress/block-editor";
import { ServerSideRender } from "@wordpress/editor";
import { useState, useEffect } from "@wordpress/element";
import apiFetch from "@wordpress/api-fetch";
import { isTextInString } from './utils';

const CategorySelector = (props) => {
  const [videoCategories, setVideoCategories] = useState([]);
  const [selectedCategories, setSelectedCategories] = useState({});

  const blockProps = useBlockProps();
  const { attributes, setAttributes } = props;
  
  const handleOnChangeVideoCat = (categoryId, newValue) => {
    let existingValues = attributes.rand
      ? attributes.rand.toLowerCase().split(",")
      : [];
    if (!existingValues.includes(newValue.toLowerCase())) {
      setAttributes({
        rand: attributes.rand ? `${attributes.rand},${newValue}` : newValue,
      });
    }

    if (existingValues.includes(newValue.toLowerCase())) {
      let newValues = existingValues.filter(
        (value) => value !== newValue.toLowerCase()
      );
      setAttributes({ rand: newValues.join(",") });
    }

    setSelectedCategories((prevState) => {
      const newState = { ...prevState };
      newState[categoryId] = !newState[categoryId];
      return newState;
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
      {videoCategories &&
        videoCategories.map((cat) => (
          <CheckboxControl
            key={cat.id}
            label={cat.name}
            onChange={() => handleOnChangeVideoCat(cat.id, cat.slug)}
            checked={isTextInString(`${cat.slug}`, attributes.rand)}
          />
        ))}
    </>
  );
};

export default CategorySelector;
