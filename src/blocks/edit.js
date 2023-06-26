import { __ } from "@wordpress/i18n";
import {
  Placeholder,
  Button,
  ButtonGroup,
  ToolbarGroup,
  ToolbarItem,
  PanelBody,
} from "@wordpress/components";
import { more, reset, video } from "@wordpress/icons";
import {
  useBlockProps,
  BlockControls,
  InspectorControls,
} from "@wordpress/block-editor";
import { ServerSideRender } from "@wordpress/editor";
import { useState } from "@wordpress/element";
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
  const { id, url } = attributes;

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
    let existingValues = attributes.show ? attributes.show.toLowerCase().split(",") : [];
    if (!existingValues.includes(newValue.toLowerCase())) {
      setAttributes({ show: attributes.show ? `${attributes.show},${newValue}` : newValue });
    }

    if (existingValues.includes(newValue.toLowerCase())) {
      let newValues = existingValues.filter((value) => value !== newValue.toLowerCase());
      setAttributes({ show: newValues.join(",") });
    }

    console.log(attributes.show);
  }

  /**
   * Renders the block
   */
  return (
    <div {...blockProps}>
      {id || url ? (
        <>
          <InspectorControls>
            <PanelBody
              title={__("URL Settings", "rrze-video")}
              icon="format-video"
            >
              <form onSubmit={handleSubmit}>
                <input
                  className="rrze-video-input-field"
                  type="url"
                  value={inputURL}
                  onChange={(event) => setInputURL(event.target.value)}
                  placeholder={__("Update the Video URL", "rrze-video")}
                  style={{ width: "100%" }}
                />
                <br />
                <Button isPrimary type="submit">
                  {__("Embed Video from URL", "rrze-video")}
                </Button>
              </form>
            </PanelBody>
            <PanelBody
              title={__("Display Settings", "rrze-video")}
              icon="admin-appearance"
            >
              <ButtonGroup
                className="rrze-video-button-group"
                aria-label={__("Display Settings", "rrze-video")}
              >
                <Button
                  isPrimary={isTextInString("Title", attributes.show)}
                  isSecondary={!isTextInString("Title", attributes.show)}
                  type="submit"
                  onClick={() => updateShowAttribute("title")}
                >
                  {__("Title", "rrze-video")}
                </Button>
                <Button
                  isPrimary={isTextInString("link", attributes.show)}
                  isSecondary={!isTextInString("link", attributes.show)}
                  type="submit"
                  onClick={() => updateShowAttribute("link")}
                >
                  {__("Videolink", "rrze-video")}
                </Button>
                <Button
                  isPrimary={isTextInString("meta", attributes.show)}
                  isSecondary={!isTextInString("meta", attributes.show)}
                  type="submit"
                  onClick={() => updateShowAttribute("meta")}
                >
                  {__("Metadata", "rrze-video")}
                </Button>
                <Button
                  isPrimary={isTextInString("desc", attributes.show)}
                  isSecondary={!isTextInString("desc", attributes.show)}
                  type="submit"
                  onClick={() => updateShowAttribute("desc")}
                >
                  {__("Description", "rrze-video")}
                </Button>
              </ButtonGroup>
            </PanelBody>
          </InspectorControls>
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
