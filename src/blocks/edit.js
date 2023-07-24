//Imports for necessary WordPress libraries
import { __ } from "@wordpress/i18n";
import { ToolbarGroup, ToolbarItem, ToolbarButton } from "@wordpress/components";
import { reset } from "@wordpress/icons";
import { useBlockProps, BlockControls } from "@wordpress/block-editor"; // eslint-disable-line import/no-unresolved
import { ServerSideRender } from "@wordpress/editor"; // eslint-disable-line import/no-unresolved
import { useState, useEffect, useRef } from "@wordpress/element";

//Imports for custom components
import {HeadingSelector} from "./CustomComponents/HeadingSelector";
import CustomInspectorControls from "./InspectorControlAreaComponents/CustomInspectorControls";
import CustomPlaceholder from "./CustomComponents/CustomPlaceholder";

//Imports for helper functions
import { isTextInString } from "./HelperFunctions/utils";

//Import the Editor Styles for the blockeditor
import "./editor.scss"; //Only active in the editor

/**
 * 
 * @param {*} props 
 * @returns 
 */
export default function Edit(props) {
  const uniqueId = Math.random().toString(36).substring(2, 15);

  // Create a ref to the container div
  const containerRef = useRef();
  const blockProps = useBlockProps();
  const { attributes, setAttributes } = props;
  const { id, url, rand, aspectratio } = attributes;
  const [setInputURL] = useState(attributes.url);

  /**
   * This useEffect hook is needed to set the aspect ratio 
   * of the video immediately within the Blockeditor view.
   * If the Video-Object inside the virtual dom is changed,
   * the aspect ratio is set again.
   */
  useEffect(() => {
    try {
      const observer = new MutationObserver(() => {

        const video = containerRef.current.querySelector("video");
        if (video) {
          video.style.aspectRatio = aspectratio;
          video.style.backgroundColor = "#000000";
        }
      });
  
      if (containerRef.current) {
        observer.observe(containerRef.current, {
          childList: true,
          subtree: true,
        });
      }
  
      return () => observer.disconnect();
    } catch (error) {
      console.log(error);
    }
  });

  /**
   * Resets the VideoURL Parameter. Activated by the reset Button.
   */
  const resetUrl = () => {
    setAttributes({ url: "", rand: "", id: "" });
    setInputURL("");
  };

  /**
   * Renders the Videoblock
   */
  return (
    <div {...blockProps}>
      <CustomInspectorControls attributes={attributes} setAttributes={setAttributes} />
      {id || url || rand ? (
        <>
          <BlockControls>
            <ToolbarGroup>
              {isTextInString("Title", attributes.show) && (
                <HeadingSelector attributes={attributes} setAttributes={setAttributes} />
              )}
              <ToolbarItem>
                {() => (
                  <ToolbarButton
                    icon={reset}
                    label={__("Reset Video block", "rrze-video")}
                    onClick={resetUrl}
                  />
                )}
              </ToolbarItem>
            </ToolbarGroup>
          </BlockControls>
          <div
            className={`rrze-video-container-${uniqueId}`}
            ref={containerRef}
          >
          {/* Renders dynamic Shortcode from includes/Gutenberg.php */}
            <ServerSideRender
              block="rrze/rrze-video"
              attributes={{
                url: attributes.url,
                show: attributes.show,
                rand: attributes.rand,
                id: attributes.id,
                titletag: attributes.titletag,
                poster: attributes.poster,
                aspectratio: attributes.aspectratio,
                class: attributes.class,
              }}
            />
          </div>
        </>
      ) : (
        <CustomPlaceholder attributes={attributes} setAttributes={setAttributes} />
      )}
    </div>
  );
}
