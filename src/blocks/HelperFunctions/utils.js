/**
 * Checks if a text is part of a comma separated string
 * @param {*} text The text to check
 * @param {*} commaSeparatedString The comma separated string to check against
 * @returns boolean
 */
export const isTextInString = (text, commaSeparatedString) => {
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
