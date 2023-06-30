import { __ } from "@wordpress/i18n";
import {
  Placeholder,
  Button,
  ButtonGroup,
  ToolbarGroup,
  ToolbarDropdownMenu,
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
  check,
  headingLevel2,
  headingLevel3,
  headingLevel4,
  headingLevel5,
  headingLevel6,
  more,
  reset,
  video,
} from "@wordpress/icons";
import {
  useBlockProps,
  BlockControls,
  InspectorControls,
} from "@wordpress/block-editor";
import { ServerSideRender } from "@wordpress/editor";
import { useState, useEffect, useRef } from "@wordpress/element";
import apiFetch from "@wordpress/api-fetch";

import CategorySelector from "./CategorySelector";
import VideoIDSelector from "./VideoIDSelector";
import ImageSelectorEdit from "./ImageSelector";
import { isTextInString } from "./utils";
import "./editor.scss";

export default function Edit(props) {
  const uniqueId = Math.random().toString(36).substring(2, 15);

  // Create a ref to the container div
  const containerRef = useRef();

  const blockProps = useBlockProps();
  const { attributes, setAttributes } = props;
  const { id, url, rand, titletag, poster, aspectratio } = attributes;

  const [inputURL, setInputURL] = useState(url);

  /**
   * Handles the submit event of the form
   * @param {*} event
   */
  const handleSubmit = (event) => {
    event.preventDefault();
    setAttributes({ url: inputURL });
  };

  const handleToggleHeadingGroup = (newValue) => {
    setAttributes({ titletag: newValue });
  };

  const handleToggleAspectRatio = (aspectratio) => {
    setAttributes({ aspectratio: aspectratio });
  };

  useEffect(() => {
    try {
      // Create an observer instance linked to the callback function
      const observer = new MutationObserver(() => {
        // Use the ref to find the video element and apply the style
        const video = containerRef.current.querySelector("video");
        if (video) {
          video.style.aspectRatio = aspectratio;
          video.style.backgroundColor = "#000000";
        }
      });
  
      // Check if containerRef.current exists before observing
      if (containerRef.current) {
        // Start observing the container with configuration
        observer.observe(containerRef.current, {
          childList: true,
          subtree: true,
        });
      }
  
      // Cleanup: disconnect the observer when the component is unmounted
      return () => observer.disconnect();
    } catch (error) {
      console.log(error);
    }
  });

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
    } else {
      let newValues = existingValues.filter(
        (value) => value !== newValue.toLowerCase()
      );
      setAttributes({ show: newValues.join(",") });
    }
  };

  const checkHeadingLevelIcon = (headinglevel) => {
    switch (attributes.titletag) {
      case "h2":
        return headingLevel2;
      case "h3":
        return headingLevel3;
      case "h4":
        return headingLevel4;
      case "h5":
        return headingLevel5;
      case "h6":
        return headingLevel6;
      default:
        return headingLevel2; // default icon if none matches
    }
  };

  /**
   * Renders the block
   */
  return (
    <div {...blockProps}>
      <InspectorControls>
        <PanelBody title={__("URL Settings", "rrze-video")} icon="format-video">
          <Spacer>
            <Text>
              Enter a video url from FAU Videoportal, YouTube, Vimeo, ARD, BR or
              Twitter.
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
        >
          <CheckboxControl
            label={__("Show Title", "rrze-video")}
            checked={isTextInString("Title", attributes.show)}
            onChange={() => updateShowAttribute("title")}
          />
          {isTextInString("Title", attributes.show) && (
            <>
              <ToggleGroupControl
                label={__("Heading level", "rrze-video")}
                value={attributes.titletag}
                onChange={handleToggleHeadingGroup}
                isBlock
              >
                <ToggleGroupControlOption value="h2" label="H2" />
                <ToggleGroupControlOption value="h3" label="H3" />
                <ToggleGroupControlOption value="h4" label="H4" />
                <ToggleGroupControlOption value="h5" label="H5" />
                <ToggleGroupControlOption value="h6" label="H6" />
              </ToggleGroupControl>
              <Divider />
            </>
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
              {__(
                "In rare cases it can be useful to select an aspect ratio to prevent black borders. Only affects FAU Video embeds."
              )}
            </Text>
          </Spacer>
          <ToggleGroupControl
            label={__("Heading level", "rrze-video")}
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
        <PanelBody title={__("Video Library", "rrze-video")} icon="video-alt3">
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
                <ToolbarDropdownMenu
                  icon={checkHeadingLevelIcon()}
                  label="Select heading level"
                  value={attributes.titletag}
                  controls={[
                    {
                      title: "H2",
                      isDisabled: attributes.titletag === "h2",
                      onClick: () => handleToggleHeadingGroup("h2"),
                    },
                    {
                      title: "H3",
                      isDisabled: attributes.titletag === "h3",
                      onClick: () => handleToggleHeadingGroup("h3"),
                    },
                    {
                      title: "H4",
                      isDisabled: attributes.titletag === "h4",
                      onClick: () => handleToggleHeadingGroup("h4"),
                    },
                    {
                      title: "H5",
                      isDisabled: attributes.titletag === "h5",
                      onClick: () => handleToggleHeadingGroup("h5"),
                    },
                    {
                      title: "H6",
                      isDisabled: attributes.titletag === "h6",
                      onClick: () => handleToggleHeadingGroup("h6"),
                    },
                  ]}
                />
              )}
              <ToolbarItem>
                {() => (
                  <ToolbarButton
                    icon={reset}
                    label={__("Reset URL", "rrze-video")}
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
              }}
            />
          </div>
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
