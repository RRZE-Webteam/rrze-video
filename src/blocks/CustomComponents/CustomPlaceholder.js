//Imports for necessary WordPress libraries
import { __ } from "@wordpress/i18n";
import { Placeholder, Button, BaseControl } from "@wordpress/components";
import { video } from "@wordpress/icons";
import { useState } from "@wordpress/element";

import { whichProviderIsUsed } from "../HelperFunctions/utils";

/**
 * Creates the Placeholder for the default videoblock.
 * @param {*} props
 * @returns JSX element
 */
const CustomPlaceholder = ({ attributes, setAttributes }) => {
  const [inputURL, setInputURL] = useState(attributes.url);

  const handleSubmit = (event) => {
    event.preventDefault();
    const url = inputURL;

    const shortsRegex = /(www\.youtube\.com\/)shorts\//;
    const youtubeRegex = /(www\.youtube\.com\/)watch\?v=/;
    const embedRegex = /(www\.youtube\.com\/)embed\//;

    let newAttributes = {};

    if (shortsRegex.test(url)) {
      newAttributes = {
        aspectratio: "9/16",
        provider: "youtube",
        orientation: "vertical",
        url: url.replace(shortsRegex, "$1embed/"),
      };
    } else if (youtubeRegex.test(url)) {
      newAttributes = {
        aspectratio: "16/9",
        provider: "youtube",
        orientation: "landscape",
        url: url.replace(youtubeRegex, "$1embed/"),
      };
    } else if (embedRegex.test(url)) {
      newAttributes = {
        aspectratio: "16/9",
        provider: "youtube",
        orientation: "landscape",
        url: url, // no need to replace the url, since it's already an embed link
      };
    } else {
      let providername = whichProviderIsUsed(url);
      newAttributes = {
        aspectratio: "16/9",
        provider: providername,
        orientation: "landscape",
        url: url,
      };
    }

    setAttributes(newAttributes);
  };

  const onChangeURL = (event) => {
    const url = event.target.value;
    setInputURL(url);
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
            onChange={(event) => onChangeURL(event)}
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
