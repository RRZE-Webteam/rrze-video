import { __ } from "@wordpress/i18n";
import {
  RadioControl,
  __experimentalDivider as Divider,
  __experimentalSpacer as Spacer,
  Notice,
} from "@wordpress/components";
import { useState, useEffect } from "@wordpress/element";

const VideoIDSelector = (props) => {
  const [videoInformation, setVideoInformation] = useState([]);
  const { attributes, setAttributes } = props;

  const handleOnChangeVideoCat = (videoID) => {
    setAttributes({
      id: videoID,
    });
  };

  /**
   * Uses RestAPI to retrieve all available video category IDs
   * @returns Array of VideoIDs
   */
  const retrieveAvailableVideoId = () => {
    // eslint-disable-next-line no-undef
    return wp
      .apiFetch({
        path: "/wp/v2/rrze-video/",
      })
      .then((data) => {
        let buffer = [];
        data.forEach((element) => {
          buffer.push({ id: element.id, title: element.title.rendered });
        });
        let flatBuffer = buffer.flat();
        let uniqueBuffer = [...new Set(flatBuffer)];
        return uniqueBuffer;
      })
      .catch((error) => {
        console.log(error);
        return [];
      });
  };

  /**
   * @Example [{id:38858, title:"Video"}]
   */
  useEffect(() => {
    retrieveAvailableVideoId().then((videoInformation) => {
      Promise.all(videoInformation)
        .then((videoInformation) => {
          setVideoInformation(videoInformation);
        })
        .catch((error) => {
          console.log(error);
        });
    });
  }, []);

  return (
    <>
      {videoInformation.length !== 0 && (
        <RadioControl
          label="Video ID"
          selected={attributes.id}
          options={[
            { label: __("No video selected", "rrze-video"), value: "" },
            ...videoInformation.map((video) => ({
              label: `${video.title} (${video.id})`,
              value: video.id.toString(),
            })),
          ]}
          onChange={(value) => handleOnChangeVideoCat(value)}
        />
      )}
      {videoInformation.length === 0 && (
        <>
          <Divider />
          <Spacer>
            <Notice status="info" isDismissible={false}>
              {__(
                "Add Videos via Dashboard > Video Library to use this feature.",
                "rrze-video"
              )}
            </Notice>
          </Spacer>
        </>
      )}
    </>
  );
};

export default VideoIDSelector;
