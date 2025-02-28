import { __ } from "@wordpress/i18n";
import {
  Modal,
  Button,
  TextControl,
  Notice,
  __experimentalConfirmDialog as ConfirmDialog,
} from "@wordpress/components";
import { useState, useEffect } from "@wordpress/element";
import { trash, justifyRight, justifyCenter } from "@wordpress/icons";
import { BlockAttributes } from "@wordpress/blocks";
import {
  formatSecondsToTimeString,
  parseTimeString,
} from "../Utils/timeProcessing";

// For generation of unique ID's
import { v4 as uuidv4 } from "uuid";
const generateUniqueId = () => uuidv4();

/////////////////////////////////////////////
// Types and Interfaces

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

  /////////////////////////////////////////////
  // States

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
  const [newMarkerStartTimeInput, setNewMarkerStartTimeInput] =
    useState<string>(formatSecondsToTimeString(newMarkerStartTime));
  const [newMarkerEndTimeInput, setNewMarkerEndTimeInput] = useState<string>(
    formatSecondsToTimeString(newMarkerEndTime)
  );

  const [errorMessage, setErrorMessage] = useState<string>("");

  // State for confirm dialog
  const [showOverlapConfirm, setShowOverlapConfirm] = useState<boolean>(false);
  const [pendingNewMarker, setPendingNewMarker] =
    useState<ChapterMarker | null>(null);

  // State for editing marker
  const [editingMarker, setEditingMarker] = useState<ChapterMarker | null>(
    null
  );
  const [editingStartTimeInput, setEditingStartTimeInput] =
    useState<string>("");
  const [editingEndTimeInput, setEditingEndTimeInput] = useState<string>("");

  /////////////////////////////////////////////
  // Effects

  useEffect(() => {
    setAttributes({ chapterMarkers: JSON.stringify(markers) });
  }, [markers]);

  /////////////////////////////////////////////
  // Functions

  // Function to find overlapping markers
  const findOverlappingMarkers = (
    startTime: number,
    endTime: number,
    excludeId?: string
  ) => {
    return markers.filter((marker) => {
      if (excludeId && marker.id === excludeId) {
        return false;
      }
      return marker.startTime < endTime && marker.endTime > startTime;
    });
  };

  // Function to adjust existing markers based on overlaps
  const adjustMarkers = (newMarker: ChapterMarker) => {
    let adjustedMarkers: ChapterMarker[] = [];

    markers.forEach((marker) => {
      // No overlap
      if (
        marker.endTime <= newMarker.startTime ||
        marker.startTime >= newMarker.endTime
      ) {
        adjustedMarkers.push(marker);
      } else {
        // Handle overlaps
        if (
          marker.startTime < newMarker.startTime &&
          marker.endTime > newMarker.startTime &&
          marker.endTime <= newMarker.endTime
        ) {
          // Adjust existing marker to end at newMarker.startTime
          adjustedMarkers.push({
            ...marker,
            endTime: newMarker.startTime,
          });
        } else if (
          marker.startTime >= newMarker.startTime &&
          marker.startTime < newMarker.endTime &&
          marker.endTime > newMarker.endTime
        ) {
          // Adjust existing marker to start at newMarker.endTime
          adjustedMarkers.push({
            ...marker,
            startTime: newMarker.endTime,
          });
        } else if (
          marker.startTime < newMarker.startTime &&
          marker.endTime > newMarker.endTime
        ) {
          // Split existing marker into two
          adjustedMarkers.push({
            ...marker,
            endTime: newMarker.startTime,
          });
          adjustedMarkers.push({
            ...marker,
            startTime: newMarker.endTime,
            id: generateUniqueId(),
          });
        }
        // Fully overlapped markers are not added
      }
    });

    return adjustedMarkers;
  };

  // Function to handle adding a new marker
  const handleAddMarker = () => {
    // Validate start and end times
    if (newMarkerEndTime <= newMarkerStartTime) {
      setErrorMessage(
        __("End time must be greater than start time.", "rrze-video")
      );
      return;
    }

    // Find overlapping markers
    const overlappingMarkers = findOverlappingMarkers(
      newMarkerStartTime,
      newMarkerEndTime
    );

    const newMarker: ChapterMarker = {
      id: generateUniqueId(),
      startTime: newMarkerStartTime,
      endTime: newMarkerEndTime,
      text: newMarkerLabel,
    };

    if (overlappingMarkers.length > 0) {
      // Show confirmation dialog
      setPendingNewMarker(newMarker);
      setShowOverlapConfirm(true);
    } else {
      // No overlaps, proceed to add marker
      addMarker(newMarker);
    }
  };

  // Function to add marker after confirmation
  const addMarker = (newMarker: ChapterMarker) => {
    let adjustedMarkers = adjustMarkers(newMarker);

    // Add new marker
    adjustedMarkers.push(newMarker);

    // Sort markers by startTime
    adjustedMarkers.sort((a, b) => a.startTime - b.startTime);

    setMarkers(adjustedMarkers);
    setNewMarkerLabel("");
    setNewMarkerStartTime(Math.round(times.playerCurrentTime));
    setNewMarkerEndTime(Math.round(times.playerCurrentTime) + 10);
    setNewMarkerStartTimeInput(newMarkerStartTime.toString());
    setNewMarkerEndTimeInput(newMarkerEndTime.toString());
    setErrorMessage("");
  };

  // Function to start editing a marker
  const startEditingMarker = (marker: ChapterMarker) => {
    setEditingMarker({ ...marker }); // Create a copy to edit
    setEditingStartTimeInput(marker.startTime.toString());
    setEditingEndTimeInput(marker.endTime.toString());
  };

  // Function to cancel editing
  const cancelEditing = () => {
    setEditingMarker(null);
    setErrorMessage("");
  };

  // Function to save edited marker
  const saveEditedMarker = () => {
    if (editingMarker) {
      // Validate start and end times
      if (editingMarker.endTime <= editingMarker.startTime) {
        setErrorMessage(
          __("End time must be greater than start time.", "rrze-video")
        );
        return;
      }

      // Find overlapping markers
      const overlappingMarkers = findOverlappingMarkers(
        editingMarker.startTime,
        editingMarker.endTime,
        editingMarker.id
      );

      if (overlappingMarkers.length > 0) {
        // Show confirmation dialog
        setPendingNewMarker(editingMarker);
        setShowOverlapConfirm(true);
      } else {
        // No overlaps, proceed to update marker
        proceedToUpdateMarker(editingMarker);
        setEditingMarker(null);
      }
    }
  };

  // Function to update marker after confirmation
  const proceedToUpdateMarker = (updatedMarker: ChapterMarker) => {
    let adjustedMarkers = adjustMarkers(updatedMarker);

    // Add updated marker
    adjustedMarkers.push(updatedMarker);

    // Sort markers by startTime
    adjustedMarkers.sort((a, b) => a.startTime - b.startTime);

    setMarkers(adjustedMarkers);
    setErrorMessage("");
  };

  // Function to remove a marker
  const removeMarker = (id: string) => {
    const newMarkers = markers.filter((marker) => marker.id !== id);
    setMarkers(newMarkers);
  };

  // Function to delete marker at current position
  const deleteMarkerAtPosition = () => {
    const position = times.playerCurrentTime;

    // Find markers at the current position
    const overlappingMarkers = markers.filter(
      (marker) => marker.startTime <= position && marker.endTime >= position
    );

    if (overlappingMarkers.length === 0) {
      setErrorMessage(
        __("No marker at the current position to delete.", "rrze-video")
      );
      return;
    }

    // Choose the marker whose startTime is closest to the current position
    const markerToDelete = overlappingMarkers.reduce((prev, curr) => {
      const prevDistance = Math.abs(prev.startTime - position);
      const currDistance = Math.abs(curr.startTime - position);
      return currDistance < prevDistance ? curr : prev;
    });

    // Remove the marker
    const newMarkers = markers.filter(
      (marker) => marker.id !== markerToDelete.id
    );

    setMarkers(newMarkers);
    setErrorMessage("");
  };

  /////////////////////////////////////////////
  // Event Handlers

  // ConfirmDialog handlers
  const handleConfirm = () => {
    if (pendingNewMarker) {
      if (markers.find((marker) => marker.id === pendingNewMarker.id)) {
        // Updating an existing marker
        proceedToUpdateMarker(pendingNewMarker);
        setEditingMarker(null);
      } else {
        // Adding a new marker
        addMarker(pendingNewMarker);
      }
    }
    setPendingNewMarker(null);
    setShowOverlapConfirm(false);
  };

  const handleCancel = () => {
    setPendingNewMarker(null);
    setShowOverlapConfirm(false);
  };

  return (
    <Modal
      title={__("Edit Chapter Markers", "rrze-video")}
      onRequestClose={onClose}
      className="chapter-marker-modal"
      size="large"
    >
      {errorMessage && (
        <Notice status="error" isDismissible={false}>
          {errorMessage}
        </Notice>
      )}
      <div style={{ marginTop: "20px" }}>
        <TextControl
          label={__("Marker Label", "rrze-video")}
          value={newMarkerLabel}
          onChange={(value) => setNewMarkerLabel(value)}
        />
        <div style={{ display: "flex", alignItems: "end" }}>
          <TextControl
            label={__("Start Time", "rrze-video")}
            type="text"
            value={newMarkerStartTimeInput}
            onChange={(value) => {
              setNewMarkerStartTimeInput(value);
              const parsedSeconds = parseTimeString(value);
              if (parsedSeconds !== null) {
                setNewMarkerStartTime(parsedSeconds);
                setErrorMessage("");
              } else {
                setErrorMessage(__("Invalid time format.", "rrze-video"));
              }
            }}
            onBlur={() => {
              if (newMarkerEndTime <= newMarkerStartTime) {
                setErrorMessage(
                  __("End time must be greater than start time.", "rrze-video")
                );
              } else {
                setErrorMessage("");
              }
            }}
          />
          <Button
            variant="secondary"
            icon={justifyCenter}
            onClick={() => {
              const currentTime = Math.round(times.playerCurrentTime);
              setNewMarkerStartTime(currentTime);
              setNewMarkerStartTimeInput(
                formatSecondsToTimeString(currentTime)
              );
            }}
            style={{ marginLeft: "10px", marginTop: "22px" }}
          >
            {__("Set to Current Time", "rrze-video")}
          </Button>
        </div>
        <div style={{ display: "flex", alignItems: "end" }}>
          <TextControl
            label={__("End Time", "rrze-video")}
            type="text"
            value={newMarkerEndTimeInput}
            onChange={(value) => {
              setNewMarkerEndTimeInput(value);
              const parsedSeconds = parseTimeString(value);
              if (parsedSeconds !== null) {
                setNewMarkerEndTime(parsedSeconds);
                setErrorMessage("");
              } else {
                setErrorMessage(__("Invalid time format.", "rrze-video"));
              }
            }}
            onBlur={() => {
              if (newMarkerEndTime <= newMarkerStartTime) {
                setErrorMessage(
                  __("End time must be greater than start time.", "rrze-video")
                );
              } else {
                setErrorMessage("");
              }
            }}
          />
          <Button
            variant="secondary"
            icon={justifyCenter}
            onClick={() => {
              const currentTime = Math.round(times.playerCurrentTime);
              setNewMarkerEndTime(currentTime);
              setNewMarkerEndTimeInput(
                formatSecondsToTimeString(currentTime)
              );
            }}
            style={{ marginLeft: "10px", marginTop: "22px" }}
          >
            {__("Set to Current Time", "rrze-video")}
          </Button>
          <Button
            icon={justifyRight}
            variant="secondary"
            label={__("Set to End of Video", "rrze-video")}
            onClick={() => {
              const duration = Math.round(times.playerDuration);
              setNewMarkerEndTime(duration);
              setNewMarkerEndTimeInput(formatSecondsToTimeString(duration));
            }}
            style={{ marginLeft: "10px", marginTop: "22px" }}
          />
        </div>
        <Button
          icon="plus"
          onClick={handleAddMarker}
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
          <table className="markers-table">
            <thead>
              <tr>
                <th>{__("Label", "rrze-video")}</th>
                <th>{__("Start Time", "rrze-video")}</th>
                <th>{__("End Time", "rrze-video")}</th>
                <th>{__("Actions", "rrze-video")}</th>
              </tr>
            </thead>
            <tbody>
              {markers.map((marker) => (
                <tr key={marker.id}>
                  <td>{marker.text}</td>
                  <td>{formatSecondsToTimeString(marker.startTime)}</td>
                  <td>{formatSecondsToTimeString(marker.endTime)}</td>
                  <td>
                    <Button
                      icon="edit"
                      label={__("Edit Marker", "rrze-video")}
                      onClick={() => startEditingMarker(marker)}
                    />
                    <Button
                      icon={trash}
                      label={__("Delete Marker", "rrze-video")}
                      onClick={() => removeMarker(marker.id)}
                      isDestructive
                      style={{ marginLeft: "5px" }}
                    />
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      )}
      <div style={{ marginTop: "20px" }}>
        <Button onClick={deleteMarkerAtPosition} variant="secondary">
          {__("Delete Marker at Current Position", "rrze-video")}
        </Button>
        <Button
          onClick={onClose}
          style={{ marginLeft: "10px" }}
          variant="primary"
        >
          {__("Close", "rrze-video")}
        </Button>
      </div>

      {/* Editing Modal */}
      {editingMarker && (
        <Modal
          title={__("Edit Marker", "rrze-video")}
          onRequestClose={cancelEditing}
          className="edit-marker-modal"
          size="medium"
        >
          {errorMessage && (
            <Notice status="error" isDismissible={false}>
              {errorMessage}
            </Notice>
          )}
          <TextControl
            label={__("Marker Label", "rrze-video")}
            value={editingMarker.text}
            onChange={(value) =>
              setEditingMarker({ ...editingMarker, text: value })
            }
          />
          <div style={{ display: "flex", alignItems: "end" }}>
            <TextControl
              label={__("Start Time", "rrze-video")}
              type="text"
              value={editingStartTimeInput}
              onChange={(value) => {
                setEditingStartTimeInput(value);
                const parsedSeconds = parseTimeString(value);
                if (parsedSeconds !== null) {
                  setEditingMarker({
                    ...editingMarker,
                    startTime: parsedSeconds,
                  });
                  setErrorMessage("");
                } else {
                  setErrorMessage(__("Invalid time format.", "rrze-video"));
                }
              }}
              onBlur={() => {
                if (editingMarker.endTime <= editingMarker.startTime) {
                  setErrorMessage(
                    __(
                      "End time must be greater than start time.",
                      "rrze-video"
                    )
                  );
                } else {
                  setErrorMessage("");
                }
              }}
            />
            <Button
              variant="secondary"
              icon={justifyCenter}
              label={__("Set to Current Time", "rrze-video")}
              onClick={() => {
                const currentTime = Math.round(times.playerCurrentTime);
                setEditingMarker({
                  ...editingMarker,
                  startTime: currentTime,
                });
                setEditingStartTimeInput(
                  formatSecondsToTimeString(currentTime)
                );
              }}
              style={{ marginLeft: "10px", marginTop: "22px" }}
            />
          </div>
          <div style={{ display: "flex", alignItems: "end" }}>
            <TextControl
              label={__("End Time", "rrze-video")}
              type="text"
              value={editingEndTimeInput}
              onChange={(value) => {
                setEditingEndTimeInput(value);
                const parsedSeconds = parseTimeString(value);
                if (parsedSeconds !== null) {
                  setEditingMarker({
                    ...editingMarker,
                    endTime: parsedSeconds,
                  });
                  setErrorMessage("");
                } else {
                  setErrorMessage(__("Invalid time format.", "rrze-video"));
                }
              }}
              onBlur={() => {
                if (editingMarker.endTime <= editingMarker.startTime) {
                  setErrorMessage(
                    __(
                      "End time must be greater than start time.",
                      "rrze-video"
                    )
                  );
                } else {
                  setErrorMessage("");
                }
              }}
            />
            <Button
              variant="secondary"
              icon={justifyCenter}
              label={__("Set to Current Time", "rrze-video")}
              onClick={() => {
                const currentTime = Math.round(times.playerCurrentTime);
                setEditingMarker({
                  ...editingMarker,
                  endTime: currentTime,
                });
                setEditingEndTimeInput(formatSecondsToTimeString(currentTime));
              }}
              style={{ marginLeft: "10px", marginTop: "22px" }}
            />
            <Button
              icon={justifyRight}
              variant="secondary"
              label={__("Set to End of Video", "rrze-video")}
              onClick={() => {
                const duration = Math.round(times.playerDuration);
                setEditingMarker({
                  ...editingMarker,
                  endTime: duration,
                });
                setEditingEndTimeInput(formatSecondsToTimeString(duration));
              }}
              style={{ marginLeft: "10px", marginTop: "22px" }}
            />
          </div>
          <div style={{ marginTop: "20px" }}>
            <Button onClick={saveEditedMarker} variant="primary">
              {__("Save", "rrze-video")}
            </Button>
            <Button
              onClick={cancelEditing}
              variant="secondary"
              style={{ marginLeft: "10px" }}
            >
              {__("Cancel", "rrze-video")}
            </Button>
          </div>
        </Modal>
      )}

      {/* ConfirmDialog for overlaps */}
      {showOverlapConfirm && (
        <ConfirmDialog
          title={__("Marker Overlap", "rrze-video")}
          onConfirm={handleConfirm}
          onCancel={handleCancel}
        >
          {__(
            "The new marker overlaps with existing markers. Overlapping markers will be adjusted or removed. Do you want to proceed?",
            "rrze-video"
          )}
        </ConfirmDialog>
      )}
    </Modal>
  );
};

export default ChapterMarkerCreator;
