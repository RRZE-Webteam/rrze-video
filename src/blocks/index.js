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
    src: (
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
        {/*!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.*/}
        <path
          d="M0 128C0 92.7 28.7 64 64 64l256 0c35.3 0 64 28.7 64 64l0 256c0 35.3-28.7 64-64 64L64 448c-35.3 0-64-28.7-64-64L0 128zM559.1 99.8c10.4 5.6 16.9 16.4 16.9 28.2l0 256c0 11.8-6.5 22.6-16.9 28.2s-23 5-32.9-1.6l-96-64L416 337.1l0-17.1 0-128 0-17.1 14.2-9.5 96-64c9.8-6.5 22.4-7.2 32.9-1.6z"
          fillRule="evenodd"
        />
      </svg>
    ),
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
