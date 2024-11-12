/**
 * Checks if a text is part of a comma separated string
 * @param {*} text The text to check
 * @param {*} commaSeparatedString The comma separated string to check against
 * @returns boolean
 */
export const isTextInString = (text: string, commaSeparatedString: string) => {
  if (commaSeparatedString === undefined) {
    console.log(`commaSeparatedString is undefined: ${commaSeparatedString} ${text}`);
    return false;
  } else {
    let commaSeparatedStringLowerCase = commaSeparatedString.toLowerCase();
    let array = commaSeparatedStringLowerCase.split(",");

    if (array.includes(text.toLowerCase())) {
      return true;
    } else {
      return false;
    }
  }
};

/**
 * Evaluates which Provider is used for the video
 * @param {String} url 
 * @returns String with the provider name
 */
export const whichProviderIsUsed = (url: string) => {
  const regexYoutubeShorts = /(www\.youtube\.com\/)shorts\//;
  const regexYoutube = /(www\.youtube\.com\/embed\/)|(www\.youtube\.com\/)/;
  const regexVimeo = /(www\.vimeo\.com\/)/;
  const regexFau = /(www\.fau\.de\/)/;
  const regexBr = /(www\.br\.de\/)/;
  const regexArd = /(www\.ard\.de\/)/;
  
  if (regexYoutubeShorts.test(url)) {
    return "youtubeShorts";
  } else if (regexYoutube.test(url)) {
    return "youtube";
  } else if (regexVimeo.test(url)) {
    return "vimeo";
  } else if (regexFau.test(url)) {
    return "fauvideo";
  } else if (regexBr.test(url)) {
    return "br";
  } else if (regexArd.test(url)) {
    return "ard";
  } else {
    return "fauvideo"; // default
  }
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

export const isFauVideoUrl = (url: string): boolean => {
  const urlDomain = new URL(url).hostname;
  return fauDomains.includes(urlDomain);
}

export const isYouTubeUrl = (url: string): boolean => {
  const youtubeDomains = ["youtube.com", "www.youtube.com", "youtu.be"];
  const urlDomain = new URL(url).hostname;
  return youtubeDomains.includes(urlDomain);
}