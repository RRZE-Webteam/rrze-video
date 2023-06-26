import { __ } from "@wordpress/i18n";
import { useBlockProps } from "@wordpress/block-editor";
import { ServerSideRender } from '@wordpress/editor';
import "./editor.scss";

export default function Edit(props) {
  const blockProps = useBlockProps();

  return (
    <div {...blockProps}>
        <ServerSideRender
            block="rrze/rrze-video"
            attributes={ blockProps.attributes }
        />
    </div>
  );
}
