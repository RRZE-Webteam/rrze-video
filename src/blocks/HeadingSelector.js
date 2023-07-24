import { __ } from "@wordpress/i18n";
import {
  ToolbarDropdownMenu,
  __experimentalDivider as Divider,
  __experimentalToggleGroupControl as ToggleGroupControl,
  __experimentalToggleGroupControlOption as ToggleGroupControlOption,
  } from "@wordpress/components";
import {
  headingLevel2,
  headingLevel3,
  headingLevel4,
  headingLevel5,
  headingLevel6
} from "@wordpress/icons";

const checkHeadingLevelIcon = (titletag) => {
  switch (titletag) {
    case "h2":
      return headingLevel2;
    case "h3":
      return headingLevel3;
    case "h4":
      return headingLevel4;
    case "h5":
      return headingLevel5;
    case "h6":
      return headingLevel6;
    default:
      return headingLevel2; // default icon if none matches
  }
};

const HeadingSelector = ({ attributes, setAttributes }) => {
  const handleToggleHeadingGroup = (newValue) => {
    setAttributes({ titletag: newValue });
  };

return(
<ToolbarDropdownMenu
  icon={checkHeadingLevelIcon(attributes.titletag)}
  label="Select heading level"
  value={attributes.titletag}
  controls={[
    {
      title: "H2",
      isDisabled: attributes.titletag === "h2",
      onClick: () => handleToggleHeadingGroup("h2"),
    },
    {
      title: "H3",
      isDisabled: attributes.titletag === "h3",
      onClick: () => handleToggleHeadingGroup("h3"),
    },
    {
      title: "H4",
      isDisabled: attributes.titletag === "h4",
      onClick: () => handleToggleHeadingGroup("h4"),
    },
    {
      title: "H5",
      isDisabled: attributes.titletag === "h5",
      onClick: () => handleToggleHeadingGroup("h5"),
    },
    {
      title: "H6",
      isDisabled: attributes.titletag === "h6",
      onClick: () => handleToggleHeadingGroup("h6"),
    },
  ]}
/>
);
}

const HeadingSelectorInspector = ({ attributes, setAttributes }) => {
  const handleToggleHeadingGroup = (newValue) => {
    setAttributes({ titletag: newValue });
  };

  return (
  <>
  <ToggleGroupControl
    label={__("Heading level", "rrze-video")}
    value={attributes.titletag}
    onChange={handleToggleHeadingGroup}
    isBlock
  >
    <ToggleGroupControlOption value="h2" label="H2" />
    <ToggleGroupControlOption value="h3" label="H3" />
    <ToggleGroupControlOption value="h4" label="H4" />
    <ToggleGroupControlOption value="h5" label="H5" />
    <ToggleGroupControlOption value="h6" label="H6" />
  </ToggleGroupControl>
  <Divider />
</>
)}


export {HeadingSelector, HeadingSelectorInspector};