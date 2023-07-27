//Imports for necessary WordPress libraries
import { __ } from "@wordpress/i18n";
import { Placeholder, Button, BaseControl } from "@wordpress/components";
import { video } from "@wordpress/icons";
import { useState } from "@wordpress/element";

/**
 * Creates the Placeholder for the default videoblock.
 * @param {*} props
 * @returns JSX element
 */
const CustomPlaceholder = ({ attributes, setAttributes }) => {
  const [inputURL, setInputURL] = useState(attributes.url);

  const handleSubmit = (event) => {
    event.preventDefault();
    setAttributes({ url: inputURL });
  };
  
  return (
    <Placeholder icon={video} label={__("Add your Video URL", "rrze-video")}>
      <p>
        {__(
          "Add your Video URL from FAU Videoportal, YouTube, ARD, ZDF or Vimeo.",
          "rrze-video"
        )}
      </p>
      <br />
      <form onSubmit={handleSubmit}>
        <BaseControl
          label={__("Video URL", "rrze-video")}
          id="rrze-video-url"
          style={{ width: "100%" }}
          className="rrze-video-editor-input"
        >
          <input
            className="rrze-video-input-field"
            type="url"
            value={inputURL}
            onChange={(event) => setInputURL(event.target.value)}
            placeholder={__("Update the Video URL", "rrze-video")}
            style={{ width: "100%" }}
          />
        </BaseControl>
        <Button isPrimary type="submit">
          {__("Embed Video from URL", "rrze-video")}
        </Button>
      </form>
    </Placeholder>
  );
};

export default CustomPlaceholder;
