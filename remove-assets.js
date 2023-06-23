/* A simple Node script to remove the not required asset files from the build folder after the build process. */
const fs = require('fs');
const path = require('path');

const filesToRemove = ['admin.asset.php', 'front.asset.php', 'style-blocks.css', 'blocks.css'];

filesToRemove.forEach((file) => {
    fs.unlink(path.join(__dirname, 'build', file), (err) => {
        if (err) {
            console.error(`Failed to remove ${file}:`, err);
        } else {
            console.log(`Successfully removed ${file}`);
        }
    });
});