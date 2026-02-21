import React from "react";
import { IconProps, IconName } from "./types";
import styles from "./Icon.module.scss";
import chevronDown from "../../../img/icons/chevron-down.svg";
import saveIcon from "../../../img/icons/save.svg";
import downloadIcon from "../../../img/icons/download.svg";
import paletteIcon from "../../../img/icons/palette.svg";
import layerGroupIcon from "../../../img/icons/layer-group.svg";
import cubeIcon from "../../../img/icons/cube.svg";
import bullhornIcon from "../../../img/icons/bullhorn.svg";
import chartPieIcon from "../../../img/icons/chart-pie.svg";
import wandMagicSparklesIcon from "../../../img/icons/wand-magic-sparkles.svg";
import headingIcon from "../../../img/icons/heading.svg";
import fontIcon from "../../../img/icons/font.svg";
import rulerIcon from "../../../img/icons/ruler.svg";
import dropletIcon from "../../../img/icons/droplet.svg";
import fillDripIcon from "../../../img/icons/fill-drip.svg";

const iconModules: Record<IconName, string> = {
  "chevron-down": chevronDown,
  save: saveIcon,
  download: downloadIcon,
  palette: paletteIcon,
  "layer-group": layerGroupIcon,
  cube: cubeIcon,
  bullhorn: bullhornIcon,
  "chart-pie": chartPieIcon,
  "wand-magic-sparkles": wandMagicSparklesIcon,
  heading: headingIcon,
  font: fontIcon,
  ruler: rulerIcon,
  droplet: dropletIcon,
  "fill-drip": fillDripIcon,
};

const Icon: React.FC<IconProps> = ({
  name,
  size = 16,
  className,
  ariaHidden = true,
}) => {
  const src = iconModules[name];
  if (!src) return null;
  return (
    <img
      src={src}
      alt=""
      width={size}
      height={size}
      className={className ?? styles.icon}
      aria-hidden={ariaHidden}
    />
  );
};

export default Icon;
export type { IconProps, IconName } from "./types";
