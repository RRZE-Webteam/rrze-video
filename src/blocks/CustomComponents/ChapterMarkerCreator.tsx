import { __ } from "@wordpress/i18n";
import { Modal, Button, TextControl } from "@wordpress/components";
import { useState, useEffect } from "react";
import { trash, justifyRight, justifyCenter, arrowDown } from "@wordpress/icons";
import { BlockAttributes } from "@wordpress/blocks";
import { v4 as uuidv4 } from "uuid";

// Utility function to generate unique IDs
const generateUniqueId = () => uuidv4();

export interface ChapterMarker {
  id: string;
  startTime: number;
  endTime: number;
  text: string;
}

interface ChapterMarkerCreatorProps {
  attributes: BlockAttributes;
  setAttributes: (attributes: Partial<BlockAttributes>) => void;
  times: {
    playerCurrentTime: number;
    playerClipStart: number;
    playerClipEnd: number;
    playerDuration: number;
  };
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
    return storedMarkers.map((marker: ChapterMarker) => {
      if (!marker.id) {
        return { ...marker, id: generateUniqueId() };
      } else {
        return marker;
      }
    });
  });

  const [newMarkerLabel, setNewMarkerLabel] = useState<string>("");
  const [newMarkerStartTime, setNewMarkerStartTime] = useState<number>(
    Math.round(times.playerCurrentTime)
  );
  const [newMarkerEndTime, setNewMarkerEndTime] = useState<number>(
    Math.round(times.playerCurrentTime) + 10
  );

  useEffect(() => {
    console.log(times);
  }, [times.playerClipEnd, times.playerClipStart, times.playerCurrentTime]);

  useEffect(() => {
    setAttributes({ chapterMarkers: JSON.stringify(markers) });
  }, [markers]);

  useEffect(() => {
    // Find overlapping markers
    const overlappingMarkers = markers.filter(
      (marker) =>
        marker.startTime <= newMarkerStartTime &&
        marker.endTime > newMarkerStartTime
    );

    if (overlappingMarkers.length > 0) {
      // If overlapping marker found, set endTime to its endTime
      setNewMarkerEndTime(overlappingMarkers[0].endTime);
    } else {
      // If no overlapping marker, default to clip end time or startTime + 10
      setNewMarkerEndTime(
        Math.min(newMarkerStartTime + 10, times.playerClipEnd)
      );
    }
  }, [newMarkerStartTime]);

  const addMarker = () => {
    const newMarker: ChapterMarker = {
      id: generateUniqueId(),
      startTime: newMarkerStartTime,
      endTime: newMarkerEndTime,
      text: newMarkerLabel,
    };

    sortMarker(newMarker);
    setNewMarkerLabel("");
    setNewMarkerStartTime(Math.round(times.playerCurrentTime));
    setNewMarkerEndTime(Math.round(times.playerCurrentTime) + 10);
  };

  const sortMarker = (newMarker: ChapterMarker) => {
    let newMarkers: ChapterMarker[] = [];

    markers.forEach((marker) => {
      // No overlap
      if (
        marker.endTime <= newMarker.startTime ||
        marker.startTime >= newMarker.endTime
      ) {
        newMarkers.push(marker);
      }
      // Existing marker completely within new marker
      else if (
        marker.startTime >= newMarker.startTime &&
        marker.endTime <= newMarker.endTime
      ) {
        // Do not add the existing marker; it's completely overlapped
      }
      // Existing marker overlaps at the start
      else if (
        marker.startTime < newMarker.startTime &&
        marker.endTime > newMarker.startTime &&
        marker.endTime <= newMarker.endTime
      ) {
        // Adjust existing marker to end at newMarker.startTime
        newMarkers.push({ ...marker, endTime: newMarker.startTime });
      }
      // Existing marker overlaps at the end
      else if (
        marker.startTime >= newMarker.startTime &&
        marker.startTime < newMarker.endTime &&
        marker.endTime > newMarker.endTime
      ) {
        // Adjust existing marker to start at newMarker.endTime
        newMarkers.push({ ...marker, startTime: newMarker.endTime });
      }
      // Existing marker completely encompasses new marker
      else if (
        marker.startTime < newMarker.startTime &&
        marker.endTime > newMarker.endTime
      ) {
        // Split the existing marker into two parts
        newMarkers.push({ ...marker, endTime: newMarker.startTime });
        newMarkers.push({
          ...marker,
          startTime: newMarker.endTime,
          id: generateUniqueId(),
        });
      }
    });

    // Insert the new marker
    newMarkers.push(newMarker);

    // Sort the markers by startTime
    newMarkers.sort((a, b) => a.startTime - b.startTime);

    setMarkers(newMarkers);
  };

  const updateMarker = (id: string, updatedMarker: ChapterMarker) => {
    const newMarkers = markers.map((marker) =>
      marker.id === id ? updatedMarker : marker
    );
    setMarkers(newMarkers);
  };

  const removeMarker = (id: string) => {
    const newMarkers = markers.filter((marker) => marker.id !== id);
    setMarkers(newMarkers);
  };

  return (
    <Modal
      title={__("Edit Chapter Markers", "rrze-video")}
      onRequestClose={onClose}
      className="chapter-marker-modal"
      size="large"
    >
      <div style={{ marginTop: "20px" }}>
        <TextControl
          label={__("Marker Label", "rrze-video")}
          value={newMarkerLabel}
          onChange={(value) => setNewMarkerLabel(value)}
        />
        <div style={{ display: "flex", alignItems: "end" }}>
          <TextControl
            label={__("Start Time (seconds)", "rrze-video")}
            type="number"
            value={newMarkerStartTime.toString()}
            onChange={(value) => setNewMarkerStartTime(parseFloat(value))}
          />
          <Button
            variant="secondary"
            icon={justifyCenter}
            onClick={() =>
              setNewMarkerStartTime(Math.round(times.playerCurrentTime))
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
            value={newMarkerEndTime.toString()}
            onChange={(value) => setNewMarkerEndTime(parseFloat(value))}
          />
          <Button
            variant="secondary"
            icon={justifyCenter}
            onClick={() =>
              setNewMarkerEndTime(Math.round(times.playerCurrentTime))
            }
            style={{ marginLeft: "10px", marginTop: "22px" }}
          >
            {__("Set to Current Time", "rrze-video")}
          </Button>
          <Button
            icon={justifyRight}
            variant="secondary"
            label={__("Set to End of Video", "rrze-video")}
            onClick={() =>
              setNewMarkerEndTime(Math.round(times.playerDuration))
            }
          />
        </div>
        <Button
          icon="plus"
          onClick={addMarker}
          variant="secondary"
          style={{ marginTop: "10px" }}
        >
          {__("Add Marker", "rrze-video")}
        </Button>
      </div>
      {/* Display list of markers */}
      {markers.length > 0 && (
        <div style={{ marginTop: "20px" }}>
          <h3>{__("Markers", "rrze-video")}</h3>
          {markers.map((marker) => (
            <div key={marker.id} className="marker-item">
              <TextControl
                label={__("Marker Label", "rrze-video")}
                value={marker.text}
                onChange={(value) =>
                  updateMarker(marker.id, { ...marker, text: value })
                }
              />
              <div style={{ display: "flex", alignItems: "end" }}>
                <TextControl
                  label={__("Start Time (seconds)", "rrze-video")}
                  type="number"
                  value={marker.startTime.toString()}
                  onChange={(value) =>
                    updateMarker(marker.id, {
                      ...marker,
                      startTime: parseFloat(value),
                    })
                  }
                />
                <Button
                  variant="secondary"
                  icon={justifyCenter}
                  label={__("Set to Current Time", "rrze-video")}
                  onClick={() =>
                    updateMarker(marker.id, {
                      ...marker,
                      startTime: Math.round(times.playerCurrentTime),
                    })
                  }
                  style={{ marginLeft: "10px", marginTop: "22px" }}
                />
              </div>
              <div style={{ display: "flex", alignItems: "end" }}>
                <TextControl
                  label={__("End Time (seconds)", "rrze-video")}
                  type="number"
                  value={marker.endTime.toString()}
                  onChange={(value) =>
                    updateMarker(marker.id, {
                      ...marker,
                      endTime: parseFloat(value),
                    })
                  }
                />
                <Button
                  variant="secondary"
                  icon={justifyCenter}
                  label={__("Set to Current Time", "rrze-video")}
                  onClick={() =>
                    updateMarker(marker.id, {
                      ...marker,
                      endTime: Math.round(times.playerCurrentTime),
                    })
                  }
                  style={{ marginLeft: "10px", marginTop: "22px" }}
                />
                <Button
                icon={justifyRight}
                variant="secondary"
                label={__("Set to End of Video", "rrze-video")}
                onClick={() =>
                  updateMarker(marker.id, {
                    ...marker,
                    endTime: Math.round(times.playerDuration),
                  })
                }
              />
              </div>
              <Button
                icon={trash}
                label={__("Delete Marker", "rrze-video")}
                onClick={() => removeMarker(marker.id)}
                isDestructive
              />
            </div>
          ))}
        </div>
      )}
      <Button onClick={onClose} style={{ marginTop: "20px" }} variant="primary" >
        {__("Close", "rrze-video")}
      </Button>
    </Modal>
  );
};

export default ChapterMarkerCreator;
