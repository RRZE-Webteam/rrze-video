/**
 * ImageSelectorEdit component.
 *
 * This is an adapted copy of the PostFeaturedImage component.
 * Source: https://github.com/WordPress/gutenberg/blob/master/packages/editor/src/components/post-featured-image/index.js
 *
 * This is an adapted copy of the ImageSelector component here:
 * https://github.com/liip/image-selector-example-wp-plugin/blob/master/assets/blocks/image-selector-example/edit.js
 */
import { __ } from "@wordpress/i18n";
import { withSelect } from "@wordpress/data";

const {
  MediaUpload,
  MediaUploadCheck,
// eslint-disable-next-line no-undef
} = wp.blockEditor;
// eslint-disable-next-line no-undef
const { Button, ResponsiveWrapper, Spinner } = wp.components;
// eslint-disable-next-line no-undef
const { compose } = wp.compose;
const ALLOWED_MEDIA_TYPES = ["image"];

const ImageSelectorEdit = (props) => {
  const { attributes, setAttributes, bgImage } = props;
  const { bgImageId } = attributes;
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
    // eslint-disable-next-line no-unused-vars
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
                <Button onClick={open} variant="secondary">
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
