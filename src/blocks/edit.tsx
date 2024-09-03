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
import ServerSideRender from "@wordpress/server-side-render";
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
  const { id, url, rand, aspectratio, secureclipid, mediaurl } = attributes;
  const [inputURL, setInputURL] = useState<string>(attributes.url);
  const [oEmbedData, setOEmbedData] = useState(null);
  const [title, setTitle] = useState<string>("");
  const [description, setDescription] = useState<string>("");
  const [providerURL, setProviderURL] = useState<string>("");
  const [providerAudioURL, setProviderAudioURL] = useState<string>("");
  const [author, setAuthor] = useState<string>("");

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
      })
      .catch((error) => {
        console.error("Failed to fetch FAU oEmbed data:", error);
        setOEmbedData(null);
      });
  };

  const updateAttributesFromOEmbedData = (data: any) => {
    //calculate the Aspect ratio based on width and height

    const gcd = (a: number, b: number): number => {
      return b === 0 ? a : gcd(b, a % b);
    };

    let aspectRatio = "16/9";
    if (data.width && data.height) {
      const gcdValue = gcd(data.width, data.height);
      let aspectRatio = `${data.width / gcdValue}/${data.height / gcdValue}`;
    }
    setTitle(data.title);
    setDescription(data.description);
    setAuthor(data.author_name);
    setProviderURL(data.provider_videoindex_url);
    setProviderAudioURL(data.alternative_Audio);

    setAttributes({
      aspectratio: aspectRatio,
      mediaurl: data.file,
      textAlign: "has-text-align-left",
      orientation: data.width > data.height ? "landscape" : "portrait",
    });

    if (attributes.poster === "") {
      setAttributes({ poster: data.preview_image });
    }
  };

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
    // Clear all attributes explicitly
    setAttributes({
      url: "",
      rand: "",
      id: "",
      provider: "fauvideo",
      aspectratio: "16/9",
      orientation: "landscape",
      textAlign: "has-text-align-left",
      poster: "",
      secureclipid: "",
      start: 0,
      clipstart: 0,
      clipend: 0,
      mediaurl: "",
      show: "",
      titletag: "",
      // Add any other attributes that need to be reset here
    });
  
    // Clear state values
    setInputURL("");
    setTitle("");
    setDescription("");
    setProviderURL("");
    setProviderAudioURL("");
    setOEmbedData(null);
    setAuthor("");
  };

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
              <DynamicHeading tag={attributes.titletag || "h2"} title={title} />
            )}
            {attributes.secureclipid ? (
              <p>{__("Eine Vorschau zugriffsgeschützter Videos im Editor ist nicht möglich.", "rrze-video")}</p>
            ): (
              <>
            {url && isFauVideoUrl(url) ? (
              <MediaPlayer
                title={title}
                src={mediaurl}
                aspectRatio={attributes.aspectratio}
                poster={attributes.poster}
                onProviderChange={onProviderChange}
                clipEndTime={attributes.clipend}
                clipStartTime={attributes.clipstart}
                loop={attributes.loop}
              >
                <MediaProvider />
                <PlyrLayout
                  thumbnails={attributes.poster}
                  icons={plyrLayoutIcons}
                />
              </MediaPlayer>
            ) : (
              <ServerSideRender
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
              />
            )}
            </>
            )}
            {isTextInString("link", attributes.show) && (
              <p className="rrze-video-link">
                <a href={attributes.url} target="_blank" rel="noreferrer">
                  {attributes.url}
                </a>
              </p>
            )}
            {isTextInString("desc", attributes.show) && (
              <p className="rrze-video-desc">{description}</p>
            )}
            {isTextInString("meta", attributes.show) && (
              <dl className="meta">
                <dt>{__("Autor", "rrze-video")}</dt>
                <dd>{author}</dd>

                <dt>{__("Quelle", "rrze-video")}</dt>
                <dd>
                  <a href={providerURL}>
                    {providerURL?.replace("https://", "")}
                  </a>
                </dd>

                <dt>{__("Audioformat", "rrze-video")}</dt>
                <dd>
                  <a href={providerAudioURL}>
                    {providerAudioURL?.replace("https://", "")}
                  </a>
                </dd>

                <dt>{__("Provider", "rrze-video")}</dt>
                <dd>
                  {__("Videoportal der FAU (Friedrich-Alexander-Universität Erlangen-Nürnberg)", "rrze-video")}
                </dd>
              </dl>
            )}
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
