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

export default function Edit(props) {
  const blockProps = useBlockProps();
  const { attributes, setAttributes } = props;
  const { id, url } = attributes;

  const [inputURL, setInputURL] = useState(url);

  const handleSubmit = (event) => {
    event.preventDefault();
    setAttributes({ url: inputURL });
  };

  const resetUrl = () => {
    setAttributes({ url: "" });
    setInputURL("");
  };

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
                <Button isPrimary type="submit">
                  {__("Title", "rrze-video")}
                </Button>
                <Button isPrimary type="submit">
                  {__("Videolink", "rrze-video")}
                </Button>
                <Button isSecondary type="submit">
                  {__("Metadata", "rrze-video")}
                </Button>
                <Button isSecondary type="submit">
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
            attributes={{ url: attributes.url }}
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
