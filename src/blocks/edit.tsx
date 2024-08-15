// Imports for necessary WordPress libraries
import { __ } from "@wordpress/i18n";
import {
  ToolbarGroup,
  ToolbarItem,
  ToolbarButton,
} from "@wordpress/components";
import { trash } from "@wordpress/icons";
import { useBlockProps, BlockControls } from "@wordpress/block-editor";
// @ts-ignore
import { ServerSideRender } from "@wordpress/server-side-render";
import { useState, useEffect, useRef } from "@wordpress/element";

import { isHLSProvider, type TextTrackInit } from "vidstack";

// Imports for custom components
// @ts-ignore
import { HeadingSelector } from "./CustomComponents/HeadingSelector";
// @ts-ignore
import CustomInspectorControls from "./InspectorControlAreaComponents/CustomInspectorControls";
// @ts-ignore
import CustomPlaceholder from "./CustomComponents/CustomPlaceholder";

import apiFetch from "@wordpress/api-fetch";

// Imports for helper functions
// @ts-ignore
import { isTextInString, whichProviderIsUsed } from "./HelperFunctions/utils";
import {
  MediaPlayer,
  MediaProvider,
  isYouTubeProvider,
  type MediaProviderAdapter,
} from "@vidstack/react";
import {
  PlyrLayout,
  plyrLayoutIcons,
} from "@vidstack/react/player/layouts/plyr";

// Import the Editor Styles for the block editor
import "./editor.scss"; // Only active in the editor
import "./player.scss"; // Only active in the editor

// Define the attributes type
interface BlockAttributes {
  id: string;
  title: string;
  url: string;
  rand: string;
  aspectratio: string;
  secureclipid: string;
  show?: string;
  titletag?: string;
  poster?: string;
  textAlign?: string;
  loop?: boolean;
  start?: number;
  clipstart?: number;
  clipend?: number;
  provider?: string;
  orientation?: string;
  mediaurl: string;
}

// Known FAU domains
const fauDomains = [
  "video.uni-erlangen.de",
  "video.fau.de",
  "www.video.uni-erlangen.de",
  "www.video.fau.de",
  "fau.tv",
  "www.fau.tv",
];

// FAU oEmbed API endpoint
const fauApiEndpoint = "https://www.fau.tv/services/oembed";
const youtubeOEmbedEndpoint = "https://www.youtube.com/oembed";

function isFauVideoUrl(url: string): boolean {
  const urlDomain = new URL(url).hostname;
  return fauDomains.includes(urlDomain);
}

function isYouTubeUrl(url: string): boolean {
  const youtubeDomains = ["youtube.com", "www.youtube.com", "youtu.be"];
  const urlDomain = new URL(url).hostname;
  return youtubeDomains.includes(urlDomain);
}

// Define the props type for the Edit component
interface EditProps {
  attributes: BlockAttributes;
  setAttributes: (attributes: Partial<BlockAttributes>) => void;
}

interface DynamicHeadingProps {
  tag: string;
  title: string;
}

const DynamicHeading: React.FC<DynamicHeadingProps> = ({ tag, title }) => {
  const Tag = tag as keyof JSX.IntrinsicElements; // Dynamically determine the tag
  return <Tag>{title}</Tag>;
};

/**
 * The Edit component for the block editor
 *
 * @param props - The props for the component
 * @returns JSX.Element
 */
export default function Edit(props: EditProps): JSX.Element {
  const uniqueId = Math.random().toString(36).substring(2, 15);

  // Create a ref to the container div
  const containerRef = useRef<HTMLDivElement | null>(null);
  const blockProps = useBlockProps();
  const { attributes, setAttributes } = props;
  const { id, url, rand, aspectratio, secureclipid, title, mediaurl } =
    attributes;
  const [inputURL, setInputURL] = useState<string>(attributes.url);
  const [oEmbedData, setOEmbedData] = useState(null);

  useEffect(() => {
    if (url && isFauVideoUrl(url)) {
      fetchFauOEmbedData(url);
    } else if (url && isYouTubeUrl(url)) {
      setAttributes({ mediaurl: url, url: url });
    }
  }, [url]);

  function onProviderChange(provider: MediaProviderAdapter | null) {
    if (isYouTubeProvider(provider)) {
      provider.cookies = true;
    }
  }

  const fetchFauOEmbedData = (videoUrl: string) => {
    const apiUrl = `${fauApiEndpoint}?url=${encodeURIComponent(
      videoUrl
    )}&format=json`;

    // Using the browser's native fetch API instead of WordPress's apiFetch
    fetch(apiUrl)
      .then((response) => {
        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
      })
      .then((data) => {
        setOEmbedData(data);
        updateAttributesFromOEmbedData(data);
        console.log("FAU oEmbed data:", data);
      })
      .catch((error) => {
        console.error("Failed to fetch FAU oEmbed data:", error);
        setOEmbedData(null);
      });
  };

  const updateAttributesFromOEmbedData = (data: any) => {
    setAttributes({
      title: data.title,
      poster: data.preview_image,
      aspectratio: `${data.width}/${data.height}`,
      mediaurl: data.file,
      textAlign: "has-text-align-left",
      orientation: data.width > data.height ? "landscape" : "portrait",
    });
  };

  // /**
  //  * This useEffect hook is needed to set the aspect ratio
  //  * of the video immediately within the Blockeditor view.
  //  * If the Video-Object inside the virtual dom is changed,
  //  * the aspect ratio is set again.
  //  */
  // useEffect(() => {
  //   try {
  //     const observer = new MutationObserver(() => {
  //       const video = containerRef.current?.querySelector("video");
  //       if (video) {
  //         video.style.aspectRatio = aspectratio;
  //         video.style.backgroundColor = "#000000";
  //       }
  //     });

  //     if (containerRef.current) {
  //       observer.observe(containerRef.current, {
  //         childList: true,
  //         subtree: true,
  //       });
  //     }

  //     return () => observer.disconnect();
  //   } catch (error) {
  //     console.error(error);
  //   }
  // }, [aspectratio]);

  useEffect(() => {
    const url = inputURL;

    switch (whichProviderIsUsed(url)) {
      case "youtube":
        setAttributes({ provider: "youtube" });
        break;
      case "youtubeShorts":
        setAttributes({ provider: "youtube" });
        break;
      case "vimeo":
        setAttributes({ provider: "vimeo" });
        break;
      case "fauvideo":
        setAttributes({ provider: "fauvideo" });
        break;
      case "br":
        setAttributes({ provider: "br" });
        break;
      case "ard":
        setAttributes({ provider: "ard" });
        break;
      default:
        setAttributes({ provider: "fauvideo" });
        break;
    }
  }, [inputURL, setAttributes]);

  /**
   * Resets the VideoURL Parameter. Activated by the reset Button.
   */
  const resetUrl = () => {
    setAttributes({
      url: "",
      rand: "",
      id: "",
      provider: "fauvideo",
      aspectratio: "16/9",
      orientation: "landscape",
      textAlign: "has-text-align-left",
      poster: "",
    });
    setInputURL("");
  };

  console.log(attributes);

  /**
   * Renders the Videoblock
   */
  return (
    <div {...blockProps}>
      <CustomInspectorControls
        attributes={attributes}
        setAttributes={setAttributes}
      />
      {id || url || rand || secureclipid ? (
        <>
          <BlockControls>
            <ToolbarGroup>
              {isTextInString("Title", attributes.show) && (
                <HeadingSelector
                  attributes={attributes}
                  setAttributes={setAttributes}
                />
              )}
              <ToolbarItem>
                {() => (
                  <ToolbarButton
                    icon={trash}
                    label={__("Reset Video block", "rrze-video")}
                    onClick={resetUrl}
                  />
                )}
              </ToolbarItem>
            </ToolbarGroup>
          </BlockControls>
          <div
            className={`rrze-video-container-${uniqueId}${
              secureclipid ? " securedVideo" : ""
            }`}
            ref={containerRef}
          >
            {isTextInString("Title", attributes.show) && (
              <DynamicHeading
                tag={attributes.titletag || "h2"}
                title={attributes.title}
              />
            )}
            <MediaPlayer
              title={attributes.title}
              src={
                mediaurl}
              aspectRatio={attributes.aspectratio}
              poster={attributes.poster}
              onProviderChange={onProviderChange}
            >
              <MediaProvider />
              <PlyrLayout
                thumbnails={attributes.poster}
                icons={plyrLayoutIcons}
              />
            </MediaPlayer>
            {isTextInString("link", attributes.show) && (
              <p className="rrze-video-link">
                <a href={attributes.url} target="_blank" rel="noreferrer">
                  {attributes.url}
                </a>
              </p>
            )}
            {/* <ServerSideRender
              block="rrze/rrze-video"
              attributes={{
                url: attributes.url,
                show: attributes.show,
                rand: attributes.rand,
                id: attributes.id,
                titletag: attributes.titletag,
                textAlign: attributes.textAlign,
                secureclipid: attributes.secureclipid,
                loop: attributes.loop,
                start: attributes.start,
                clipstart: attributes.clipstart,
                clipend: attributes.clipend,
              }}
            /> */}
          </div>
        </>
      ) : (
        <CustomPlaceholder
          attributes={attributes}
          setAttributes={setAttributes}
        />
      )}
    </div>
  );
}
