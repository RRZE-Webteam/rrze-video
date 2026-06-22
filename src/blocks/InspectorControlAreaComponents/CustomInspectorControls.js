//Imports for necessary WordPress libraries
import { __ } from "@wordpress/i18n";
import {
  Button,
  Notice,
  PanelBody,
  BaseControl,
  ToggleControl,
  __experimentalInputControl as InputControl,
  __experimentalNumberControl as NumberControl,
  __experimentalText as Text,
  __experimentalDivider as Divider,
  __experimentalHeading as Heading,
  __experimentalSpacer as Spacer,
  __experimentalToggleGroupControl as ToggleGroupControl,
  __experimentalToggleGroupControlOption as ToggleGroupControlOption,
} from "@wordpress/components";
import { InspectorControls } from "@wordpress/block-editor"; // eslint-disable-line import/no-unresolved
import { useState, useEffect } from "@wordpress/element";

//Imports for custom components
import CategorySelector from "./CategorySelector";
import VideoIDSelector from "./VideoIDSelector";
import ImageSelectorEdit from "./ImageSelector";
import ShowSelector from "./ShowSelector";
import { whichProviderIsUsed } from "../Utils/utils";

/**
 * Controls the sidebar for videoblock in the blockeditor
 * @param {*} params
 * @returns JSX element
 */
const CustomInspectorControls = ({ attributes, setAttributes }) => {
  const [inputURL, setInputURL] = useState(attributes.url);
  const [secureId, setSecureId] = useState(attributes.secureclipid);
  const [tempClipStart, setTempClipStart] = useState(attributes.clipstart);
  const [tempClipEnd, setTempClipEnd] = useState(attributes.clipend);
  const [tempStart, setTempStart] = useState(attributes.start);
  const { textAlign } = attributes;

  /**
   * Handles the submit event of the form for the video url
   * @param {*} event
   */
  const handleToggleSubmit = (event) => {
    event.preventDefault();
    const url = inputURL;

    // Strip tracking parameters (e.g. ?si=...)
    const urlObj = new URL(url);
    urlObj.searchParams.delete("si");
    const cleanUrl = urlObj.toString();

    const shortsRegex = /((?:www\.)?youtube\.com\/)shorts\//;
    const youtubeRegex = /((?:www\.)?youtube\.com\/)watch\?v=|youtu\.be\//;
    const embedRegex = /((?:www\.)?youtube\.com\/)embed\//;

    let newAttributes = {};

    if (shortsRegex.test(cleanUrl)) {
      newAttributes = {
        aspectratio: "9/16",
        provider: "youtube",
        orientation: "vertical",
        url: cleanUrl.replace(shortsRegex, "$1embed/"),
      };
    } else if (youtubeRegex.test(cleanUrl)) {
      newAttributes = {
        aspectratio: "16/9",
        provider: "youtube",
        orientation: "landscape",
        url: /((www\.)youtube\.com\/)watch\?v=/.test(cleanUrl)
          ? cleanUrl.replace(/((www\.)youtube\.com\/)watch\?v=/, "$1embed/")
          : cleanUrl,
      };
    } else if (embedRegex.test(cleanUrl)) {
      newAttributes = {
        aspectratio: "16/9",
        provider: "youtube",
        orientation: "landscape",
        url: cleanUrl, // no need to replace the url, since it's already an embed link
      };
    } else {
      let providername = whichProviderIsUsed(cleanUrl);
      newAttributes = {
        aspectratio: "16/9",
        provider: providername,
        orientation: "landscape",
        url: cleanUrl,
      };
    }

    setAttributes(newAttributes);
  };

  useEffect(() => {
    setInputURL(attributes.url);
  }, [attributes.url]);

  const handleToggleAspectRatio = (newAspectratio) => {
    setAttributes({ aspectratio: newAspectratio });
  };

  const onChangeOrientation = (value) => {
    if (value === "landscape") {
      setAttributes({ orientation: value, aspectratio: "16/9" });
    } else {
      setAttributes({ orientation: value, aspectratio: "9/16" });
    }
  };

  const onChangeURL = (event) => {
    const url = event.target.value;
    setInputURL(url);
  };

  const clipStartInvalid = tempClipEnd > 0 && tempClipStart >= tempClipEnd;


  return (
    <InspectorControls>
      <PanelBody
        title={__("URL Settings", "rrze-video")}
        icon="format-video"
        initialOpen={false}
      >
        <Spacer>
          <Text>
            {__(
              "Enter a video url from FAU Videoportal, YouTube, Vimeo, ARD, BR or Twitter.",
              "rrze-video",
            )}
          </Text>
        </Spacer>

        <form onSubmit={handleToggleSubmit}>
          <BaseControl
            label={__("Video URL", "rrze-video")}
            id="rrze-video-url"
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
      </PanelBody>
      <PanelBody
        title={__("Video Display Settings", "rrze-video")}
        icon="admin-appearance"
        initialOpen={true}
      >
        {attributes.provider === "fauvideo" && (
          <>
            <ShowSelector
              attributes={attributes}
              setAttributes={setAttributes}
            />
            <Divider />

            <Spacer>
              <Heading level={3}>
                {__("Individual Thumbnail", "rrze-video")}
              </Heading>
              <Text>
                {__(
                  `Replaces the Thumbnail with the image you selected.`,
                  "rrze-video",
                )}
              </Text>
            </Spacer>
            <ImageSelectorEdit
              attributes={attributes}
              setAttributes={setAttributes}
            />
            <Divider />
          </>
        )}

        <Spacer>
          <Heading level={3}>{__("Aspect Ratio", "rrze-video")}</Heading>
          {attributes.provider === "fauvideo" && (
            <Text>
              {__(
                "In rare cases it can be useful to select an aspect ratio to prevent black borders. Only affects FAU Video embeds.",
                "rrze-video",
              )}
            </Text>
          )}
        </Spacer>

        {attributes.provider === "youtube" && (
          <>
            <Spacer>
              <Text>
                {__(
                  "Controls the video orientation. Vertical videos are displayed in portrait mode.",
                  "rrze-video",
                )}
              </Text>
            </Spacer>
            <ToggleGroupControl
              label={__("Orientation", "rrze-video")}
              value={attributes.orientation}
              onChange={onChangeOrientation}
              isBlock
            >
              <ToggleGroupControlOption
                value="landscape"
                label={__("Landscape mode", "rrze-video")}
              />
              <ToggleGroupControlOption
                value="vertical"
                label={__("Vertical video", "rrze-video")}
              />
            </ToggleGroupControl>
            {attributes.orientation === "vertical" && (
              <>
                <Spacer>
                  <Text>
                    {__(
                      "Controls the video alignment for vertical Videos.",
                      "rrze-video",
                    )}
                  </Text>
                </Spacer>
                <ToggleGroupControl
                  label={__("Alignment", "rrze-video")}
                  value={textAlign}
                  onChange={(value) => {
                    setAttributes({ textAlign: value });
                  }}
                  isBlock
                >
                  <ToggleGroupControlOption
                    value=""
                    label={__("Left", "rrze-video")}
                  />
                  <ToggleGroupControlOption
                    value="has-text-align-center"
                    label={__("Center", "rrze-video")}
                  />
                  <ToggleGroupControlOption
                    value="has-text-align-right"
                    label={__("Right", "rrze-video")}
                  />
                </ToggleGroupControl>
              </>
            )}
          </>
        )}

        {attributes.provider === "fauvideo" && (
          <ToggleGroupControl
            label={__("Aspect ratio", "rrze-video")}
            value={attributes.aspectratio}
            onChange={handleToggleAspectRatio}
            isBlock
          >
            <ToggleGroupControlOption value="16/9" label="16:9" />
            <ToggleGroupControlOption value="9/16" label="9:16" />
            <ToggleGroupControlOption value="4/3" label="4:3" />
            <ToggleGroupControlOption value="1/1" label="1:1" />
            <ToggleGroupControlOption value="2.35/1" label="2.35:1" />
            <ToggleGroupControlOption value="2.40/1" label="2.40:1" />
          </ToggleGroupControl>
        )}
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
            "rrze-video",
          )}
        </Text>
        <Divider />
        <Spacer>
          <Heading level={3}>{__("Random Output", "rrze-video")}</Heading>
          <Text>
            {__(
              `You can select a Video library category and a random video will be displayed from this category.`,
              "rrze-video",
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
              "rrze-video",
            )}
          </Text>
        </Spacer>
        <VideoIDSelector
          attributes={attributes ?? {}}
          setAttributes={setAttributes}
        />
      </PanelBody>
      {attributes.provider === "fauvideo" &&
        (() => {
          const hasClip =
            (attributes.clipstart ?? 0) !== 0 ||
            (attributes.clipend ?? 0) !== 0;
          const hasLoop = !!attributes.loop;

          return (
            <PanelBody
              title={__("Playback Settings", "rrze-video")}
              initialOpen={false}
            >
              {/* Loop section */}
              <Spacer>
                <Heading level={3}>{__("Loop mode", "rrze-video")}</Heading>
              </Spacer>
              {hasClip ? (
                <Notice status="warning" isDismissible={false}>
                  {__(
                    "Loop is not available when clip times are set. Remove clip times first.",
                    "rrze-video",
                  )}
                </Notice>
              ) : (
                <ToggleControl
                  checked={attributes.loop}
                  onChange={(loop) => setAttributes({ loop: loop })}
                  label={__("Activate looping", "rrze-video")}
                />
              )}

              <Divider />

              {/* Clip section */}
              <Spacer>
                <Heading level={3}>{__("Clip mode", "rrze-video")}</Heading>
                <Text style={{ marginBottom: "0.75rem", display: "block" }}>
                  {__("Crops the video to a specific section.", "rrze-video")}
                </Text>
              </Spacer>
              {hasLoop ? (
                <Notice status="warning" isDismissible={false}>
                  {__(
                    "Clip settings are not available when loop mode is active. Deactivate loop first.",
                    "rrze-video",
                  )}
                </Notice>
              ) : (
                <>
                  <NumberControl
                    label={__("Clip start time (seconds)", "rrze-video")}
                    value={tempClipStart}
                    onChange={(clipstart) =>
                      setTempClipStart(parseInt(clipstart, 10) || 0)
                    }
                    min={0}
                  />
                  <NumberControl
                    label={__("Clip end time (seconds)", "rrze-video")}
                    value={tempClipEnd}
                    onChange={(clipend) =>
                      setTempClipEnd(parseInt(clipend, 10) || 0)
                    }
                    min={0}
                  />

                  {clipStartInvalid && (
                    <Notice status="error" isDismissible={false}>
                      {__("Start time must be less than end time.", "rrze-video",)}
                    </Notice>
                  )}

                  <Button
                    isPrimary
                    disabled={
                    clipStartInvalid ||
                      (tempClipStart === attributes.clipstart &&
                      tempClipEnd === attributes.clipend)
                    }
                    onClick={() =>
                      setAttributes({
                        clipstart: tempClipStart,
                        clipend: tempClipEnd,
                      })
                    }
                  >
                    {__("Apply clip settings", "rrze-video")}
                  </Button>
                </>
              )}
            </PanelBody>
          );
        })()}
    </InspectorControls>
  );
};

export default CustomInspectorControls;
