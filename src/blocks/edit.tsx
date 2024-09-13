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
  defaultLayoutIcons,
  DefaultVideoLayout,
} from "@vidstack/react/player/layouts/default";
// import {
//   PlyrLayout,
//   plyrLayoutIcons,
// } from "@vidstack/react/player/layouts/plyr";

// Import the Editor Styles for the block editor
import "./editor.scss"; // Only active in the editor
import "./player.scss"; // Only active in the editor
interface video {
  alternative_Audio: string;
  alternative_Video_size_large: string;
  alternative_Video_size_large_height: number;
  alternative_Video_size_large_url: string;
  alternative_Video_size_large_width: number;
  alternative_Video_size_small: string;
  alternative_Video_size_small_height: string;
  alternative_Video_size_small_url: string;
  alternative_Video_size_small_width: string;
  author_name: string;
  author_url_0: string;
  description: string;
  duration: string;
  file: string;
  height: number;
  html: string;
  inLanguage: string;
  preview_image: string;
  provider_name: string;
  provider_url: string;
  provider_videoindex_url: string;
  thumbnail_height: number;
  thumbnail_url: string;
  thumbnail_width: number;
  title: string;
  transcript: string;
  transcript_de: string;
  transcript_en: string;
  type: string;
  upload_date: string;
  version: string;
  width: number;
}

interface ApiResponse {
  video?: video;
  oembed_api_url?: string;
  oembed_api_error?: string;
  message?: string;
  error?: string;
}

interface ApiResponseId {
  id: number;
  url?: string;
  poster?: string;
  message?: string;
}

interface oEmbedData {
  oembed_api_error: string;
  oembed_api_url: string;
  video: video;
}

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

function isFauVideoUrl(url: string): boolean {
  const urlDomain = new URL(url).hostname;
  return fauDomains.includes(urlDomain);
}

function isYouTubeUrl(url: string): boolean {
  const youtubeDomains = ["youtube.com", "www.youtube.com", "youtu.be"];
  const urlDomain = new URL(url).hostname;
  return youtubeDomains.includes(urlDomain);
}

interface EditProps {
  attributes: BlockAttributes;
  setAttributes: (attributes: Partial<BlockAttributes>) => void;
}

interface DynamicHeadingProps {
  tag: string;
  title: string;
}

const DynamicHeading: React.FC<DynamicHeadingProps> = ({ tag, title }) => {
  const Tag = tag as keyof JSX.IntrinsicElements;
  return <Tag>{title}</Tag>;
};

export default function Edit(props: EditProps): JSX.Element {
  const uniqueId = Math.random().toString(36).substring(2, 15);
  const containerRef = useRef<HTMLDivElement | null>(null);
  const blockProps = useBlockProps();
  const { attributes, setAttributes } = props;
  const { id, url, rand, aspectratio, secureclipid, mediaurl } = attributes;
  const [inputURL, setInputURL] = useState<string>(attributes.url);
  const [responseMessage, setResponseMessage] = useState("");
  const [oEmbedData, setOEmbedData] = useState(null);
  const [title, setTitle] = useState<string>("");
  const [description, setDescription] = useState<string>("");
  const [providerURL, setProviderURL] = useState<string>("");
  const [providerAudioURL, setProviderAudioURL] = useState<string>("");
  const [author, setAuthor] = useState<string>("");

  useEffect(() => {
    if (url && isFauVideoUrl(url)) {
      sendUrlToApi(url);
    } else if (url && isYouTubeUrl(url)) {
      setAttributes({ mediaurl: url, url: url });
    }
  }, [url]);

  useEffect(() => {
    if (id && !url) {
      idToUrlViaApi(parseInt(id));
    }
  }, [id])

  function onProviderChange(provider: MediaProviderAdapter | null) {
    if (isYouTubeProvider(provider)) {
      provider.cookies = true;
    }
  }

  const updateAttributesFromOEmbedData = (data: oEmbedData) => {
    console.log(data);
    if (!data || typeof data !== "object") {
      console.error("Invalid oEmbed data structure:", data);
      return;
    }

    const gcd = (a: number, b: number): number => (b === 0 ? a : gcd(b, a % b));
    let aspectRatio = "16/9";

    if (data.video.width && data.video.height) {
      const gcdValue = gcd(data.video.width, data.video.height);
      aspectRatio = `${data.video.width / gcdValue}/${
        data.video.height / gcdValue
      }`;
    }

    setTitle(data.video.title || "");
    setDescription(data.video.description || "");
    setAuthor(data.video.author_name || "");
    setProviderURL(data.video.provider_videoindex_url || "");
    setProviderAudioURL(data.video.alternative_Audio || "");

    setAttributes({
      aspectratio: aspectRatio,
      mediaurl: data.video.file || "",
      textAlign: "has-text-align-left",
      orientation:
        data.video.width > data.video.height ? "landscape" : "portrait",
      poster: attributes.poster || data.video.preview_image || "",
    });
  };

  useEffect(() => {
    const url = inputURL;

    switch (whichProviderIsUsed(url)) {
      case "youtube":
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
      secureclipid: "",
      start: 0,
      clipstart: 0,
      clipend: 0,
      mediaurl: "",
      show: "",
      titletag: "",
    });

    setInputURL("");
    setTitle("");
    setDescription("");
    setProviderURL("");
    setProviderAudioURL("");
    setOEmbedData(null);
    setAuthor("");
  };

  const sendUrlToApi = (url: string) => {
    apiFetch<ApiResponse>({
      path: "/rrze-video/v1/process-url",
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        url: url,
      }),
    })
      .then((response) => {
        // Handle the response with TypeScript type checking
        setResponseMessage(response.message || "Erfolgreich verarbeitet!");
        setOEmbedData(response);
        updateAttributesFromOEmbedData({
          oembed_api_error: response.oembed_api_error || "",
          oembed_api_url: response.oembed_api_url || "",
          video: response.video!,
        });
      })
      .catch((error: unknown) => {
        console.error("Fehler bei der API-Anfrage:", error);
        setResponseMessage(
          "Fehler: Sie müssen angemeldet sein, um diese Funktion zu nutzen."
        );
      });
  };

  const idToUrlViaApi = (id: number) => {
     apiFetch<ApiResponse>({
      path: "/rrze-video/v1/get-url-by-id",
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        id: id
      }),
    })
      .then((response) => {
        // Handle the response with TypeScript type checking
        setResponseMessage(response.message || "Erfolgreich verarbeitet!");
        setOEmbedData(response);
        console.log(response);
        setAttributes({
          url: response.video!.file,
          mediaurl: response.video!.file,

        })
        setInputURL(response.video!.file);
        updateAttributesFromOEmbedData({
          oembed_api_error: response.oembed_api_error || "",
          oembed_api_url: response.oembed_api_url || "",
          video: response.video!,
        });
      })
      .catch((error: unknown) => {
        console.error("Fehler bei der API-Anfrage:", error);
        setResponseMessage(
          "Fehler: Sie müssen angemeldet sein, um diese Funktion zu nutzen."
        );
      });
  };

  console.log("attributes", attributes);

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
              <p>
                {__(
                  "Eine Vorschau zugriffsgeschützter Videos im Editor ist nicht möglich.",
                  "rrze-video"
                )}
              </p>
            ) : (
              <>
                {!id && url && isFauVideoUrl(url) ? (
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
                    <DefaultVideoLayout
                      thumbnails={attributes.poster}
                      icons={defaultLayoutIcons}
                    />
                  </MediaPlayer>
                ) : (
                  <ServerSideRender
                    block="rrze/rrze-video"
                    attributes={{
                      url: attributes.url,
                      show: attributes.show,
                      rand: attributes.rand,
                      id: attributes.id || '',
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
                  {__(
                    "Videoportal der FAU (Friedrich-Alexander-Universität Erlangen-Nürnberg)",
                    "rrze-video"
                  )}
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
