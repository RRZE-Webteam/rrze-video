//Imports for necessary WordPress libraries
import { CheckboxControl } from "@wordpress/components";
import {HeadingSelectorInspector} from "../CustomComponents/HeadingSelector";
import { __ } from "@wordpress/i18n";

//Imports for helper functions
import { isTextInString } from "../HelperFunctions/utils";


/**
 * Handles the show attribute and displays a checkbox for users to select different options.
 * This component also uses HeadingSelectorInspector to show boxes for the hstart attribute.
 * @param {*} attributes
 * @param {*} setAttributes
 * @returns JSX element
 * @see HeadingSelectorInspector
 * @see utils.js
 */
const ShowSelector = ({attributes, setAttributes}) => {
  const updateShowAttribute = (newValue) => {
    let existingValues = attributes.show
      ? attributes.show.toLowerCase().split(",")
      : [];
    if (!existingValues.includes(newValue.toLowerCase())) {
      setAttributes({
        show: attributes.show ? `${attributes.show},${newValue}` : newValue,
      });
    } else {
      let newValues = existingValues.filter(
        (value) => value !== newValue.toLowerCase()
      );
      setAttributes({ show: newValues.join(",") });
    }
  };

  return (
    <>
      <CheckboxControl
        label={__("Show Title", "rrze-video")}
        checked={isTextInString("Title", attributes.show)}
        onChange={() => updateShowAttribute("title")}
      />
      {isTextInString("Title", attributes.show) && (
        <HeadingSelectorInspector
          attributes={attributes}
          setAttributes={setAttributes}
        />
      )}
      <CheckboxControl
        label={__("Show Videolink", "rrze-video")}
        checked={isTextInString("link", attributes.show)}
        onChange={() => updateShowAttribute("link")}
      />
      <CheckboxControl
        label={__("Show Metadata", "rrze-video")}
        checked={isTextInString("meta", attributes.show)}
        onChange={() => updateShowAttribute("meta")}
      />
      <CheckboxControl
        label={__("Show Description", "rrze-video")}
        checked={isTextInString("desc", attributes.show)}
        onChange={() => updateShowAttribute("desc")}
      />
    </>
  );
};

export default ShowSelector;