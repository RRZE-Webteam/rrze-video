import { __ } from "@wordpress/i18n";
import {
  Modal,
  Button,
  TextControl,
  CheckboxControl,
} from "@wordpress/components";
import { useState, useEffect } from "react";
import { trash } from "@wordpress/icons";
import { BlockAttributes } from "@wordpress/blocks";

export interface ChapterMarker {
  startTime: number;
  endTime: number;
  text: string;
}

interface ChapterMarkerCreatorProps {
  attributes: BlockAttributes;
  setAttributes: (attributes: Partial<BlockAttributes>) => void;
  times: { currentTime: number; clipStartTime: number; clipEndTime: number };
  onClose: () => void;
}

const ChapterMarkerCreator: React.FC<ChapterMarkerCreatorProps> = ({
  attributes,
  setAttributes,
  times,
  onClose,
}) => {
  // Initialize markers state from attributes.chapterMarkers
  const [markers, setMarkers] = useState<ChapterMarker[]>(() => {
    const storedMarkers = attributes.chapterMarkers
      ? JSON.parse(attributes.chapterMarkers as string)
      : [];
    return storedMarkers;
  });

  const [newMarkerLabel, setNewMarkerLabel] = useState<string>("");
  const [useClipStartForStartTime, setUseClipStartForStartTime] =
    useState<boolean>(true);

  // Update attributes.chapterMarkers whenever markers change
  useEffect(() => {
    setAttributes({ chapterMarkers: JSON.stringify(markers) });
  }, [markers]);

  const addMarker = () => {
    let startTime: number;
    let endTime: number;

    const currentTime = Math.round(times.currentTime);

    if (useClipStartForStartTime) {
      if (markers.length > 0) {
        startTime = markers[markers.length - 1].endTime + 1;
      } else {
        startTime = Math.round(times.clipStartTime);
      }
      endTime = currentTime;
    } else {
      startTime = currentTime;
      endTime = Math.round(times.clipEndTime);
    }

    const newMarker: ChapterMarker = {
      startTime: startTime,
      endTime: endTime,
      text: newMarkerLabel,
    };

    // Update markers
    setMarkers([...markers, newMarker]);
    setNewMarkerLabel("");
  };

  const updateMarker = (index: number, updatedMarker: ChapterMarker) => {
    const newMarkers = [...markers];
    newMarkers[index] = updatedMarker;
    setMarkers(newMarkers);
  };

  const removeMarker = (index: number) => {
    const newMarkers = markers.filter((_, i) => i !== index);
    setMarkers(newMarkers);
  };

  return (
    <Modal
      title={__("Edit Chapter Markers", "rrze-video")}
      onRequestClose={onClose}
      className="chapter-marker-modal"
      size="large"
    >
      <TextControl
        label={__("Marker Label", "rrze-video")}
        value={newMarkerLabel}
        onChange={(value) => setNewMarkerLabel(value)}
      />
      <CheckboxControl
        label={__(
          "Use Clip Start Time or Previous Marker End Time as Start Time",
          "rrze-video"
        )}
        checked={useClipStartForStartTime}
        onChange={(checked) => setUseClipStartForStartTime(checked)}
      />
      <Button
        icon="plus"
        onClick={addMarker}
        isPrimary
        style={{ marginTop: "10px" }}
      >
        {__("Add Marker", "rrze-video")}
      </Button>
      {/* Display list of markers */}
      {markers.length > 0 && (
        <div style={{ marginTop: "20px" }}>
          <h3>{__("Markers", "rrze-video")}</h3>
          {markers.map((marker, index) => (
            <div key={index} className="marker-item">
              <TextControl
                label={__("Marker Label", "rrze-video")}
                value={marker.text}
                onChange={(value) =>
                  updateMarker(index, { ...marker, text: value })
                }
              />
               <div style={{ display: "flex", alignItems: "end" }}>
                <TextControl
                  label={__("Start Time (seconds)", "rrze-video")}
                  type="number"
                  value={marker.startTime.toString()}
                  onChange={(value) =>
                    updateMarker(index, {
                      ...marker,
                      startTime: parseFloat(value),
                    })
                  }
                />
                <Button
                  isSecondary
                  onClick={() =>
                    updateMarker(index, {
                      ...marker,
                      startTime: Math.round(times.currentTime),
                    })
                  }
                  style={{ marginLeft: "10px", marginTop: "22px" }}
                >
                  {__("Set to Current Time", "rrze-video")}
                </Button>
              </div>
              <div style={{ display: "flex", alignItems: "end" }}>
                <TextControl
                  label={__("End Time (seconds)", "rrze-video")}
                  type="number"
                  value={marker.endTime.toString()}
                  onChange={(value) =>
                    updateMarker(index, {
                      ...marker,
                      endTime: parseFloat(value),
                    })
                  }
                />
                <Button
                  isSecondary
                  onClick={() =>
                    updateMarker(index, {
                      ...marker,
                      endTime: Math.round(times.currentTime),
                    })
                  }
                  style={{ marginLeft: "10px", marginTop: "22px" }}
                >
                  {__("Set to Current Time", "rrze-video")}
                </Button>
              </div>
              <Button
                icon={trash}
                label={__("Delete Marker", "rrze-video")}
                onClick={() => removeMarker(index)}
                isDestructive
              />
            </div>
          ))}
        </div>
      )}
      <Button onClick={onClose} style={{ marginTop: "20px" }}>
        {__("Close", "rrze-video")}
      </Button>
    </Modal>
  );
};

export default ChapterMarkerCreator;
