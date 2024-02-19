//Imports for necessary WordPress libraries
import { __ } from "@wordpress/i18n";
import {
  Button,
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
import { useState } from "@wordpress/element";
import { InspectorControls } from "@wordpress/block-editor"; // eslint-disable-line import/no-unresolved

//Imports for custom components
import CategorySelector from "./CategorySelector";
import VideoIDSelector from "./VideoIDSelector";
import ImageSelectorEdit from "./ImageSelector";
import ShowSelector from "./ShowSelector";
import { whichProviderIsUsed } from "../HelperFunctions/utils";

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
              "rrze-video"
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
                  "rrze-video"
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
                "rrze-video"
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
                  "rrze-video"
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
                      "rrze-video"
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
      <PanelBody
        title={__("SSO secured video embed", "rrze-video")}
        initialOpen={false}
      >
        <Text>
          {__(
            `This feature only works with a FAU Videoportal API key. You can add a FAU Videoportal API key in the settings of this plugin.`,
            "rrze-video"
          )}
        </Text>
        <Divider />
        <Spacer>
          <Heading level={3}>{__("Secure video embed", "rrze-video")}</Heading>
          <Text>
            {__(
              `Enter the ID of your SSO-secured video here. You need a working FAU Video API key for this feature.`,
              "rrze-video"
            )}
          </Text>
        </Spacer>
        <InputControl
          label={__("Secure Video ID", "rrze-video")}
          value={attributes.secureclipid}
          onChange={(secureclipid) => setSecureId(secureclipid)}
        />
        <Button
          isPrimary
          disabled={attributes.secureclipid === secureId}
          onClick={() => setAttributes({ secureclipid: secureId })}
        >
          {__("Embed secure video", "rrze-video")}
        </Button>
      </PanelBody>
      {attributes.provider === "fauvideo" && (
        <PanelBody
          title={__("Player controls", "rrze-video")}
          initialOpen={false}
        >
          <Spacer>
            <Heading level={3}>{__("Loop mode", "rrze-video")}</Heading>
            <Text>
              {__(
                `Activates the loop feature. The video will be played in a loop.`,
                "rrze-video"
              )}
            </Text>
          </Spacer>
          <ToggleControl
            checked={attributes.loop}
            onChange={(loop) => setAttributes({ loop: loop })}
            label={__("Activate looping", "rrze-video")}
          />
          {attributes.loop && (
            <Spacer>
              <Text>
                {__(
                  `The loop mode is activated. The video will be played in a loop. If your video contains branding, the default settings should be sufficient. Else you can control the position in the clip where the loop should get triggered (clipend) and the position where the looped video should start (clipstart) with the following settings:`,
                  "rrze-video"
                )}
              </Text>
              <NumberControl
                label={__("Start of the looping section", "rrze-video")}
                value={tempClipStart}
                onChange={(clipstart) => setTempClipStart(clipstart)}
                min={0}
              />
              <NumberControl
                label={__("End of the looping section", "rrze-video")}
                value={tempClipEnd}
                onChange={(clipend) => setTempClipEnd(clipend)}
                min={0}
              />

              <Button
                isPrimary
                disabled={
                  tempClipStart === attributes.clipstart &&
                  tempClipEnd === attributes.clipend
                }
                onClick={() =>
                  setAttributes({
                    clipstart: tempClipStart,
                    clipend: tempClipEnd,
                  })
                }
              >
                {__("Update loop settings", "rrze-video")}
              </Button>
            </Spacer>
          )}
          <Heading level={3}>
            {__("Start position on first play", "rrze-video")}
          </Heading>
          <Spacer>
            <Text>
              {__(
                `The first time the video plays, start it at the following position in seconds:`,
                "rrze-video"
              )}
            </Text>
            <NumberControl
              label={__("Start of the video", "rrze-video")}
              value={tempStart}
              onChange={(start) => setTempStart(start)}
              min={0}
            />
            <Button
              isPrimary
              disabled={tempStart === attributes.start}
              onClick={() => setAttributes({ start: tempStart })}
            >
              {__("Update start position", "rrze-video")}
            </Button>
          </Spacer>
        </PanelBody>
      )}
    </InspectorControls>
  );
};

export default CustomInspectorControls;
