////////////////////////////////////////
// Imports for necessary WordPress libraries
import { __ } from "@wordpress/i18n";
import {
  ToolbarGroup,
  ToolbarItem,
  ToolbarButton,
  __experimentalConfirmDialog as ConfirmDialog,
} from "@wordpress/components";
import { trash, plus, reset, edit } from "@wordpress/icons";
import { useBlockProps, BlockControls } from "@wordpress/block-editor";
import ServerSideRender from "@wordpress/server-side-render";
import {
  useState,
  useEffect,
  useRef,
  useMemo,
  useCallback,
} from "@wordpress/element";

////////////////////////////////////////
// Imports for custom components
import { type ChapterMarker } from "./CustomComponents/ChapterMarkerCreator";
import ChapterMarkerCreator from "./CustomComponents/ChapterMarkerCreator";
import { RRZEVidstackPlayer } from "./CustomComponents/Vidstack";
import { HeadingSelector } from "./CustomComponents/HeadingSelector";
// @ts-ignore
import CustomInspectorControls from "./InspectorControlAreaComponents/CustomInspectorControls";
// @ts-ignore
import CustomPlaceholder from "./CustomComponents/CustomPlaceholder";

// Imports for Utils
import {
  isTextInString,
  whichProviderIsUsed,
  isFauVideoUrl,
  isYouTubeUrl,
} from "./Utils/utils";
import { Video, ApiResponse, OEmbedData } from "./Utils/types";
import { sendUrlToApi } from "./Utils/apiService";

// Import the Editor Styles for the block editor
import "./editor.scss";
import "./player.scss";

////////////////////////////////////////

////////////////////////////////////////
// Types and Interfaces
interface BlockAttributes {
  id: string | number;
  url: string;
  rand: string;
  aspectratio: string;
  secureclipid: string;
  show?: string;
  titletag: string;
  poster?: string;
  textAlign?: string;
  loop?: boolean;
  start?: number;
  clipstart?: number;
  clipend?: number;
  provider?: string;
  orientation?: string;
  mediaurl: string;
  chapterMarkers?: string;
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

////////////////////////////////////////
// Main Edit Component
export default function Edit(props: EditProps): JSX.Element {
  const blockProps = useBlockProps();
  // Destructure the attributes and setAttributes from the props
  const { attributes, setAttributes } = props;
  const { id, url, rand, aspectratio, secureclipid, mediaurl } = attributes;

  // Generate a unique ID for the video container
  const uniqueId = Math.random().toString(36).substring(2, 15);

  // Create a reference to the container div
  const containerRef = useRef<HTMLDivElement | null>(null);

  ////////////////////////////////////////
  // State variables
  const [inputURL, setInputURL] = useState<string>(attributes.url);
  const [title, setTitle] = useState<string>("");
  const [author, setAuthor] = useState<string>("");

  const [responseMessage, setResponseMessage] = useState("");
  const [oEmbedData, setOEmbedData] = useState(null);

  const [description, setDescription] = useState<string>("");

  // Player state
  const [playerCurrentTime, setPlayerCurrentTime] = useState<number>(0);
  const [playerClipStart, setPlayerClipStart] = useState<number>(0);
  const [playerClipEnd, setPlayerClipEnd] = useState<number>(0);
  const [playerDuration, setPlayerDuration] = useState<number>(0);

  const [providerURL, setProviderURL] = useState<string>("");
  const [providerAudioURL, setProviderAudioURL] = useState<string>("");
  const [providerName, setProviderName] = useState<string>("");

  // Popup and Confirmation state
  const [isChapterMarkerModalOpen, setIsChapterMarkerModalOpen] =
    useState(false);
  const [isOpen, setIsOpen] = useState(false);
  const [confirmVal, setConfirmVal] = useState("");

  // Define markers at the top level of the component
  const markers: ChapterMarker[] = useMemo(() => {
    return attributes.chapterMarkers
      ? JSON.parse(attributes.chapterMarkers as string)
      : [];
  }, [attributes.chapterMarkers]);

  ////////////////////////////////////////
  // Event Handlers

  // Handle the confirmation dialog
  const handleConfirm = () => {
    setConfirmVal("Confirmed");
    setIsOpen(false);
    resetUrl();
  };

  const handleCancel = () => {
    setConfirmVal("Cancelled");
    setIsOpen(false);
  };

  const handleDeleteCurrentMarker = () => {
    const newMarkers = markers.filter(
      (marker: ChapterMarker) =>
        playerCurrentTime < marker.startTime ||
        playerCurrentTime > marker.endTime
    );

    setAttributes({ chapterMarkers: JSON.stringify(newMarkers) });
  };

  const resetUrl = () => {
    setAttributes({
      id: "",
      url: "",
      titletag: "h2",
      poster: "",
      rand: "",
      show: "",
      provider: "fauvideo",
      textAlign: "",
      secureclipid: undefined,
      loop: false,
      start: 0,
      clipstart: 0,
      clipend: 0,
      mediaurl: "",
      chapterMarkers: "",
    });

    // Reset internal state variables
    setInputURL("");
    setTitle("");
    setDescription("");
    setProviderURL("");
    setProviderAudioURL("");
    setOEmbedData(null);
    setAuthor("");
  };

  // Send the URL to the API
  const handleSendUrlToApi = async (
    url?: string,
    id?: number,
    rand?: string
  ) => {
    try {
      const response = await sendUrlToApi(url, id, rand);
      setResponseMessage(response.message || "Erfolgreich verarbeitet!");
      setOEmbedData(response);

      updateAttributesFromOEmbedData({
        oembed_api_error: response.oembed_api_error || "",
        oembed_api_url: response.oembed_api_url || "",
        video: response.video!,
      });
    } catch (error) {
      console.error("Fehler bei der API-Anfrage:", error);
      setResponseMessage(
        "Fehler: Sie müssen angemeldet sein, um diese Funktion zu nutzen."
      );
    }
  };

  ////////////////////////////////////////
  // Use Effects

  useEffect(() => {
    if (url && isFauVideoUrl(url)) {
      handleSendUrlToApi(url);
    } else if (url && isYouTubeUrl(url)) {
      setAttributes({ mediaurl: url, url: url });
    }
  }, [url]);

  useEffect(() => {
    if (!url && id) {
      let tempId;
      if (id && typeof id === "string") {
        tempId = parseInt(id);
      }
      handleSendUrlToApi(undefined, tempId);
    }
    if (!url && rand) {
      handleSendUrlToApi(undefined, undefined, rand);
    }
  }, [id, rand]);

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

  const onTimeUpdate = useCallback(
    ({
      currentPlayerTime,
      playerClipStart,
      playerClipEnd,
      playerDuration,
    }: {
      currentPlayerTime: number;
      playerClipStart: number;
      playerClipEnd: number;
      playerDuration: number;
    }) => {
      setPlayerCurrentTime(currentPlayerTime);
      setPlayerClipStart(playerClipStart);
      setPlayerClipEnd(playerClipEnd);
      setPlayerDuration(playerDuration);
    },
    []
  );

  ////////////////////////////////////////
  // Functions

  const updateAttributesFromOEmbedData = (data: OEmbedData) => {
    if (!data || typeof data !== "object") {
      console.error("Invalid oEmbed data structure:", data);
      return;
    }

    // if (data.video.provider_name contains FAU
    if (data.video.provider_name && data.video.provider_name.includes("FAU")) {
      setProviderName("FAU");
      const gcd = (a: number, b: number): number =>
        b === 0 ? a : gcd(b, a % b);
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
    } else {
      setProviderName(data.video.provider_name || "");
    }
  };

  ////////////////////////////////////////
  // Render the Edit Component

  return (
    <div {...blockProps}>
      <CustomInspectorControls
        attributes={attributes}
        setAttributes={setAttributes}
      />
      {id || url || rand || secureclipid ? (
        <>
          <ConfirmDialog
            isOpen={isOpen}
            onCancel={handleCancel}
            onConfirm={handleConfirm}
          >
            {__(
              "Are you sure you want to reset the video block to factory settings?",
              "rrze-video"
            )}
          </ConfirmDialog>
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
                    onClick={() => setIsOpen(true)}
                  />
                )}
              </ToolbarItem>
            </ToolbarGroup>
            {(url && isFauVideoUrl(url)) || providerName === "FAU" ? (
              <ToolbarGroup>
                <ToolbarItem>
                  {() => (
                    <>
                      <ToolbarButton
                        icon={plus}
                        label={__("Add Chapter Markers", "rrze-video")}
                        onClick={() => setIsChapterMarkerModalOpen(true)}
                      />
                      <ToolbarButton
                        icon={reset}
                        label={__("Delete Current Marker", "rrze-video")}
                        onClick={handleDeleteCurrentMarker}
                        disabled={
                          !markers.some(
                            (marker: ChapterMarker) =>
                              playerCurrentTime >= marker.startTime &&
                              playerCurrentTime <= marker.endTime
                          )
                        }
                      />
                      <ToolbarButton
                        icon={edit}
                        label={__("Edit Markers", "rrze-video")}
                        onClick={() => setIsChapterMarkerModalOpen(true)}
                      />
                    </>
                  )}
                </ToolbarItem>
              </ToolbarGroup>
            ) : null}
          </BlockControls>
          {isChapterMarkerModalOpen && (
            <ChapterMarkerCreator
              attributes={attributes}
              setAttributes={setAttributes}
              onClose={() => setIsChapterMarkerModalOpen(false)}
              times={{
                playerCurrentTime: playerCurrentTime,
                playerClipStart: playerClipStart,
                playerClipEnd: playerClipEnd,
                playerDuration: playerDuration,
              }}
            />
          )}
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
                {(url && isFauVideoUrl(url)) || providerName === "FAU" ? (
                  <RRZEVidstackPlayer
                    title={title}
                    mediaurl={mediaurl}
                    aspectratio={aspectratio}
                    poster={attributes.poster}
                    clipend={attributes.clipend}
                    clipstart={attributes.clipstart}
                    loop={attributes.loop}
                    onTimeUpdate={onTimeUpdate}
                    markers={markers}
                  />
                ) : (
                  <ServerSideRender
                    block="rrze/rrze-video"
                    attributes={{
                      url: attributes.url,
                      show: attributes.show,
                      rand: attributes.rand,
                      id: attributes.id || "",
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
