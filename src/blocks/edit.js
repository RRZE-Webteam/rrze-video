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

import CategorySelector from "./CategorySelector";
import "./editor.scss";

/**
 * Checks if a text is part of a comma separated string
 * @param {*} text The text to check
 * @param {*} commaSeparatedString The comma separated string to check against
 * @returns
 */
const isTextInString = (text, commaSeparatedString) => {
  let commaSeparatedStringLowerCase = commaSeparatedString.toLowerCase();
  let array = commaSeparatedStringLowerCase.split(",");

  if (array.includes(text.toLowerCase())) {
    return true;
  } else {
    return false;
  }
};

export default function Edit(props) {
  const blockProps = useBlockProps();
  const { attributes, setAttributes } = props;
  const { id, url, rand } = attributes;

  const [inputURL, setInputURL] = useState(url);

  /**
   * Handles the submit event of the form
   * @param {*} event
   */
  const handleSubmit = (event) => {
    event.preventDefault();
    setAttributes({ url: inputURL });
  };

  /**
   * Resets the VideoURL Parameter
   */
  const resetUrl = () => {
    setAttributes({ url: "" });
    setInputURL("");
  };

  const updateShowAttribute = (newValue) => {
    let existingValues = attributes.show
      ? attributes.show.toLowerCase().split(",")
      : [];
    if (!existingValues.includes(newValue.toLowerCase())) {
      setAttributes({
        show: attributes.show ? `${attributes.show},${newValue}` : newValue,
      });
    }

    if (existingValues.includes(newValue.toLowerCase())) {
      let newValues = existingValues.filter(
        (value) => value !== newValue.toLowerCase()
      );
      setAttributes({ show: newValues.join(",") });
    }
  };

 
  /**
   * Renders the block
   */
  return (
    <div {...blockProps}>
      <InspectorControls>
        <PanelBody title={__("URL Settings", "rrze-video")} icon="format-video">
          <Text>
            Enter a video url from FAU Videoportal, YouTube, Vimeo, ARD, BR or
            Twitter.
          </Text>
          <Divider />
          <form onSubmit={handleSubmit}>
            <BaseControl
              label={__("Video URL", "rrze-video")}
              id="rrze-video-url"
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
        </PanelBody>
        <PanelBody
          title={__("Video Display Settings", "rrze-video")}
          icon="admin-appearance"
        >
          <CheckboxControl
            label={__("Show Title", "rrze-video")}
            checked={isTextInString("Title", attributes.show)}
            onChange={() => updateShowAttribute("title")}
          />
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
        </PanelBody>
        <PanelBody title={__("Video Library", "rrze-video")} icon="video-alt3">
          <Text>
            You can add videos to your video library by navigating to Dashboard
            | Video library | Add new.
          </Text>
          <Divider />
          <CategorySelector />
        </PanelBody>
      </InspectorControls>
      {id || url ? (
        <>
          <BlockControls>
            <ToolbarGroup>
              <ToolbarItem>
                {() => (
                  <Button
                    icon={reset}
                    label={__("Reset URL", "rrze-video")}
                    onClick={resetUrl}
                  />
                )}
              </ToolbarItem>
            </ToolbarGroup>
          </BlockControls>
          <ServerSideRender
            block="rrze/rrze-video"
            attributes={{ url: attributes.url, show: attributes.show }}
          />
        </>
      ) : (
        <Placeholder icon={more} label={__("Add your Video URL", "rrze-video")}>
          <p>
            {__(
              "Add your Video URL from FAU Videoportal, YouTube, ARD, ZDF or Vimeo.",
              "rrze-video"
            )}
          </p>
          <br />
          <form onSubmit={handleSubmit}>
            <input
              className="rrze-video-input-field"
              type="url"
              value={inputURL}
              onChange={(event) => setInputURL(event.target.value)}
              placeholder={__("Insert your Video URL", "rrze-video")}
              style={{ width: "100%" }}
            />
            <br />
            <Button isPrimary type="submit">
              {__("Embed Video from URL", "rrze-video")}
            </Button>
          </form>
        </Placeholder>
      )}
    </div>
  );
}
