/**
 * ImageSelectorEdit component.
 *
 * This is an adapted copy of the PostFeaturedImage component.
 * Source: https://github.com/WordPress/gutenberg/blob/master/packages/editor/src/components/post-featured-image/index.js
 *
 * This is an adapted copy of the ImageSelector component here:
 * https://github.com/liip/image-selector-example-wp-plugin/blob/master/assets/blocks/image-selector-example/edit.js
 */

// Load dependencies
const { __ } = wp.i18n;
const { Component, Fragment } = wp.element;
const {
  InnerBlocks,
  MediaUpload,
  MediaUploadCheck,
  useBlockProps,
} = wp.blockEditor;
const { PanelBody, Button, ResponsiveWrapper, Spinner } = wp.components;
const { compose } = wp.compose;
import { withSelect } from "@wordpress/data";

const ALLOWED_MEDIA_TYPES = ["image"];

const ImageSelectorEdit = (props) => {
  const { attributes, setAttributes, bgImage, className } = props;
  const { bgImageId, poster } = attributes;
  const instructions = (
    <p>
      {__(
        "To edit the background image, you need permission to upload media.",
        "rrze-video"
      )}
    </p>
  );

  let styles = {};
  if (bgImage && bgImage.source_url) {
    styles = { backgroundImage: `url(${bgImage.source_url})` };
  }

  const onUpdateImage = (image) => {
    setAttributes({
      bgImageId: image.id,
      poster: image.url,
    });
  };

  const onRemoveImage = () => {
    setAttributes({
      bgImageId: undefined,
      poster: "",
    });
  };

  return (
      <div className="rrze-video-block-image">
        <MediaUploadCheck fallback={instructions}>
          <MediaUpload
            title={__("Background image", "rrze-video")}
            onSelect={onUpdateImage}
            allowedTypes={ALLOWED_MEDIA_TYPES}
            value={bgImageId}
            render={({ open }) => (
              <Button
                className={
                  !bgImageId
                    ? "editor-post-featured-image__toggle"
                    : "editor-post-featured-image__preview"
                }
                onClick={open}
              >
                {!bgImageId && __("Set Video Thumbnail", "rrze-video")}
                {!!bgImageId && !bgImage && <Spinner />}
                {!!bgImageId && bgImage && (
                  <ResponsiveWrapper
                    naturalWidth={bgImage.media_details.width}
                    naturalHeight={bgImage.media_details.height}
                  >
                    <img
                      src={bgImage.source_url}
                      alt={__("Video Thumbnail", "rrze-video")}
                    />
                  </ResponsiveWrapper>
                )}
              </Button>
            )}
          />
        </MediaUploadCheck>
        {!!bgImageId && bgImage && (
          <MediaUploadCheck>
            <MediaUpload
              title={__("Video Thumbnail", "rrze-video")}
              onSelect={onUpdateImage}
              allowedTypes={ALLOWED_MEDIA_TYPES}
              value={bgImageId}
              render={({ open }) => (
                <Button onClick={open} isDefault isLarge>
                  {__("Replace Video Thumbnail", "rrze-video")}
                </Button>
              )}
            />
          </MediaUploadCheck>
        )}
        {!!bgImageId && (
          <MediaUploadCheck>
            <Button onClick={onRemoveImage} isLink isDestructive>
              {__("Remove Video Thumbnail", "rrze-video")}
            </Button>
          </MediaUploadCheck>
        )}
      </div>
  );
};

export default compose([
  withSelect((select, props) => {
    const { getMedia } = select("core");
    const { bgImageId } = props.attributes;

    return {
      bgImage: bgImageId ? getMedia(bgImageId) : null,
    };
  }),
])(ImageSelectorEdit);
