/* A simple Node script to remove the not required asset files from the build folder after the build process. */
const fs = require("fs");
const path = require("path");

const filesToRemove = [
  "admin.asset.php",
  "front.asset.php",
  "style-blocks.css",
  "blocks.css",
];

filesToRemove.forEach((file) => {
  fs.unlink(path.join(__dirname, "build", file), (err) => {
    if (err) {
      console.error(`Failed to remove ${file}:`, err);
    } else {
      console.log(`Successfully removed ${file}`);
    }
  });
});

const filesToRename = {
  "style-blocks.css": "style-index.css",
  "blocks.css": "index.css",
};

for (const [oldName, newName] of Object.entries(filesToRename)) {
  fs.rename(
    path.join(__dirname, "build/blocks", oldName),
    path.join(__dirname, "build/blocks", newName),
    (err) => {
      if (err) {
        console.error(`Failed to rename ${oldName} to ${newName}:`, err);
      } else {
        console.log(`Successfully renamed ${oldName} to ${newName}`);
      }
    }
  );
}
