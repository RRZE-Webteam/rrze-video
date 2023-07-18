/**
 * Registers a new block provided a unique name and an object defining its behavior.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
import { registerBlockType } from "@wordpress/blocks";

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * All files containing `style` keyword are bundled together. The code used
 * gets applied both to the front of your site and to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */

/**
 * Internal dependencies
 */
import Edit from "./edit";
import save from "./save";
import metadata from "./block.json";

/**
 * Every block starts by registering a new block type definition.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
registerBlockType(metadata.name, {
  icon: {
		src: "format-video",
		background: "#00458c"
	},
  transforms: {
    from: [
      {
        type: "shortcode",
        tag: "fauvideo",
        attributes: {
          id: {
            type: "string",
            shortcode: (attrs) => attrs.named.id,
          },
          url: {
            type: "string",
            shortcode: (attrs) => attrs.named.url,
          },
          poster: {
            type: "string",
            shortcode: (attrs) => attrs.named.poster,
          },
          show: {
            type: "string",
            shortcode: (attrs) => attrs.named.show,
          },
          rand: {
            type: "string",
            shortcode: (attrs) => attrs.named.rand,
          },
          titletag: {
            type: "string",
            shortcode: (attrs) => {
              if (
                ["h2", "h3", "h4", "h5", "h6"].includes(attrs.named.titletag)
              ) {
                return attrs.named.titletag;
              } else {
                return "h2";
              }
            },
          },
          aspectratio: {
            type: "string",
            shortcode: (attrs) => {
              if (!attrs.named.aspectratio) {
                return "16/9";
              } else {
                return attrs.named.aspectratio;
              }
            },
          },
          class: {
            type: "string",
            shortcode: (attrs) => attrs.named.class,
          },
        },
      },
    ],
  },
  /**
   * @see ./edit.js
   */
  edit: Edit,

  /**
   * @see ./save.js
   */
  save,
});
