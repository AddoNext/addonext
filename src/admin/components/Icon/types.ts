export type IconName =
  | 'chevron-down'
  | 'save'
  | 'download'
  | 'palette'
  | 'layer-group'
  | 'cube'
  | 'bullhorn'
  | 'chart-pie'
  | 'wand-magic-sparkles'
  | 'heading'
  | 'font'
  | 'ruler'
  | 'droplet'
  | 'fill-drip';

export interface IconProps {
  name: IconName;
  size?: number;
  className?: string;
  ariaHidden?: boolean;
}
