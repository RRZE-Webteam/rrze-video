import { __ } from "@wordpress/i18n";
import {
  Placeholder,
  Button,
  ToolbarGroup,
  ToolbarItem,
  ToolbarButton,
  PanelBody,
  BaseControl,
  CheckboxControl,
  __experimentalText as Text,
  __experimentalDivider as Divider,
  __experimentalHeading as Heading,
  __experimentalSpacer as Spacer,
  __experimentalToggleGroupControl as ToggleGroupControl,
  __experimentalToggleGroupControlOption as ToggleGroupControlOption,
} from "@wordpress/components";
import {
  reset,
  video,
} from "@wordpress/icons";
import {
  useBlockProps,
  BlockControls,
  InspectorControls,
} from "@wordpress/block-editor"; // eslint-disable-line import/no-unresolved
import { ServerSideRender } from "@wordpress/editor"; // eslint-disable-line import/no-unresolved
import { useState, useEffect, useRef } from "@wordpress/element";

import CategorySelector from "./CategorySelector";
import VideoIDSelector from "./VideoIDSelector";
import ImageSelectorEdit from "./ImageSelector";
import {HeadingSelector, HeadingSelectorInspector} from "./HeadingSelector";
import { isTextInString } from "./utils";
import "./editor.scss";

export default function Edit(props) {
  const uniqueId = Math.random().toString(36).substring(2, 15);

  // Create a ref to the container div
  const containerRef = useRef();

  const blockProps = useBlockProps();
  const { attributes, setAttributes } = props;
  const { id, url, rand, aspectratio } = attributes;

  const [inputURL, setInputURL] = useState(url);

  /**
   * Handles the submit event of the form
   * @param {*} event
   */
  const handleSubmit = (event) => {
    event.preventDefault();
    setAttributes({ url: inputURL });
  };


  const handleToggleAspectRatio = (aspectratio) => {
    setAttributes({ aspectratio: aspectratio });
  };

  useEffect(() => {
    try {
      const observer = new MutationObserver(() => {

        const video = containerRef.current.querySelector("video");
        if (video) {
          video.style.aspectRatio = aspectratio;
          video.style.backgroundColor = "#000000";
        }
      });
  
      if (containerRef.current) {
        observer.observe(containerRef.current, {
          childList: true,
          subtree: true,
        });
      }
  
      return () => observer.disconnect();
    } catch (error) {
      console.log(error);
    }
  });

  /**
   * Resets the VideoURL Parameter
   */
  const resetUrl = () => {
    setAttributes({ url: "", rand: "", id: "" });
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
    } else {
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
        <PanelBody 
        title={__("URL Settings", "rrze-video")} 
        icon="format-video" 
        initialOpen={false}
        >
          <Spacer>
            <Text>
              {__("Enter a video url from FAU Videoportal, YouTube, Vimeo, ARD, BR or Twitter.", "rrze-video")}
            </Text>
          </Spacer>

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
          initialOpen={true}
        >
          <CheckboxControl
            label={__("Show Title", "rrze-video")}
            checked={isTextInString("Title", attributes.show)}
            onChange={() => updateShowAttribute("title")}
          />
          {isTextInString("Title", attributes.show) && (
            <HeadingSelectorInspector attributes={attributes} setAttributes={setAttributes} />
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
          <Divider />
          <Spacer>
            <Heading level={3}>
              {__("Individual Thumbnail", "rrze-video")}
            </Heading>
            <Text>
              {__(
                `Replaces the Thumbnail with the image you selected.`,
                "rrze-video"
              )}
            </Text>
          </Spacer>
          <ImageSelectorEdit
            attributes={attributes}
            setAttributes={setAttributes}
          />
          <Divider />
          <Spacer>
            <Heading level={3}>{__("Aspect Ratio", "rrze-video")}</Heading>
            <Text>
              {__("In rare cases it can be useful to select an aspect ratio to prevent black borders. Only affects FAU Video embeds.", "rrze-video")}
            </Text>
          </Spacer>
          <ToggleGroupControl
            label={__("Aspect ratio", "rrze-video")}
            value={attributes.aspectratio}
            onChange={handleToggleAspectRatio}
            isBlock
          >
            <ToggleGroupControlOption value="16/9" label="16:9" />
            <ToggleGroupControlOption value="4/3" label="4:3" />
            <ToggleGroupControlOption value="1/1" label="1:1" />
            <ToggleGroupControlOption value="2.35/1" label="2.35:1" />
            <ToggleGroupControlOption value="2.40/1" label="2.40:1" />
          </ToggleGroupControl>
        </PanelBody>
        <PanelBody 
        title={__("Video Library", "rrze-video")} 
        icon="video-alt3"
        initialOpen={false}
        >
          <Text>
            {__(
              `You can add videos to your video library by navigating to Dashboard
            | Video library | Add new.`,
              "rrze-video"
            )}
          </Text>
          <Divider />
          <Spacer>
            <Heading level={3}>{__("Random Output", "rrze-video")}</Heading>
            <Text>
              {__(
                `You can select a Video library category and a random video will be displayed from this category.`,
                "rrze-video"
              )}
            </Text>
          </Spacer>
          <CategorySelector
            attributes={attributes ?? {}}
            setAttributes={setAttributes}
          />
          <Divider />
          <Spacer>
            <Heading level={3}>{__("Individual Videos", "rrze-video")}</Heading>
            <Text>
              {__(
                `You can select a Video from within your Video library.`,
                "rrze-video"
              )}
            </Text>
          </Spacer>
          <VideoIDSelector
            attributes={attributes ?? {}}
            setAttributes={setAttributes}
          />
        </PanelBody>
      </InspectorControls>
      {id || url || rand ? (
        <>
          <BlockControls>
            <ToolbarGroup>
              {isTextInString("Title", attributes.show) && (
                <HeadingSelector attributes={attributes} setAttributes={setAttributes} />
              )}
              <ToolbarItem>
                {() => (
                  <ToolbarButton
                    icon={reset}
                    label={__("Reset Video block", "rrze-video")}
                    onClick={resetUrl}
                  />
                )}
              </ToolbarItem>
            </ToolbarGroup>
          </BlockControls>
          <div
            className={`rrze-video-container-${uniqueId}`}
            ref={containerRef}
          >
            <ServerSideRender
              block="rrze/rrze-video"
              attributes={{
                url: attributes.url,
                show: attributes.show,
                rand: attributes.rand,
                id: attributes.id,
                titletag: attributes.titletag,
                poster: attributes.poster,
                aspectratio: attributes.aspectratio,
                class: attributes.class,
              }}
            />
          </div>
        </>
      ) : (
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
              style={{width: "100%"}}
              className="rrze-video-editor-input"
            >
              <input
                className="rrze-video-input-field"
                type="url"
                value={inputURL}
                onChange={(event) => setInputURL(event.target.value)}
                placeholder={__("Update the Video URL", "rrze-video")}
                style={{ width: "100%"}}
              />
            </BaseControl>
            <Button isPrimary type="submit">
              {__("Embed Video from URL", "rrze-video")}
            </Button>
          </form>
        </Placeholder>
      )}
    </div>
  );
}
