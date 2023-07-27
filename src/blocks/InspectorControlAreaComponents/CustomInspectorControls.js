//Imports for necessary WordPress libraries
import { __ } from "@wordpress/i18n";
import {
  Button,
  PanelBody,
  BaseControl,
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

/**
 * Controls the sidebar for videoblock in the blockeditor
 * @param {*} params 
 * @returns JSX element
 */
const CustomInspectorControls = ({ attributes, setAttributes }) => {
  const [inputURL, setInputURL] = useState(attributes.url);

  /**
   * Handles the submit event of the form for the video url
   * @param {*} event
   */
  const handleToggleSubmit = (event) => {
    event.preventDefault();
    setAttributes({ url: inputURL });
  };

  const handleToggleAspectRatio = (newAspectratio) => {
    setAttributes({ aspectratio: newAspectratio });
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
        <ShowSelector attributes={attributes} setAttributes={setAttributes} />
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
              "In rare cases it can be useful to select an aspect ratio to prevent black borders. Only affects FAU Video embeds.",
              "rrze-video"
            )}
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
  );
};

export default CustomInspectorControls;