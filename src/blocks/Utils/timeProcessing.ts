// Function to parse time strings into seconds
const parseTimeString = (timeString: string): number | null => {
  const parts = timeString.split(":").map((part) => part.trim());
  if (parts.length === 0) {
    return null;
  }

  const numbers = parts.map((part) => parseInt(part, 10));
  if (numbers.some((num) => isNaN(num) || num < 0)) {
    return null;
  }

  let seconds = 0;

  if (numbers.length === 1) {
    // Only seconds
    seconds = numbers[0];
  } else if (numbers.length === 2) {
    // minutes:seconds
    seconds = numbers[0] * 60 + numbers[1];
  } else if (numbers.length === 3) {
    // hours:minutes:seconds
    seconds = numbers[0] * 3600 + numbers[1] * 60 + numbers[2];
  } else {
    // More than 3 parts, invalid
    return null;
  }

  return seconds;
};

// Function to format seconds into time strings
const formatSecondsToTimeString = (totalSeconds: number): string => {
  const hours = Math.floor(totalSeconds / 3600);
  const minutes = Math.floor((totalSeconds % 3600) / 60);
  const seconds = totalSeconds % 60;

  const hoursString = hours > 0 ? `${hours}:` : "";
  const minutesString = `${hours > 0 ? String(minutes).padStart(2, "0") : minutes
    }:`;
  const secondsString = String(seconds).padStart(2, "0");

  return `${hoursString}${minutesString}${secondsString}`;
}

export { parseTimeString, formatSecondsToTimeString };