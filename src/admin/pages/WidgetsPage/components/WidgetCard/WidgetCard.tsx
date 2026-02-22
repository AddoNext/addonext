import React from "react";
import classNames from "classnames";
import ToggleSwitch from "../../../../components/ToggleSwitch/ToggleSwitch";
import type { Widget } from "../../../../../store/types";
import styles from "./WidgetCard.module.scss";
import { hexToRgba } from "../../../../utils/colors";

interface WidgetCardProps {
  widget: Widget;
  categorySlug: string;
  onToggle: (id: string, checked: boolean) => void;
}

const WidgetCard: React.FC<WidgetCardProps> = ({ widget, categorySlug, onToggle }) => (
  <div className={styles.card}>
    <div className={styles.header}>
      <div
        className={classNames(
          styles.iconWrap,
        )}
        style={{ backgroundColor: hexToRgba(widget.brand_color, 0.2) }}
      >
        <i className={widget.icon} aria-hidden style={{ color: widget.brand_color }} />
      </div>
      <ToggleSwitch
        checked={widget.active}
        onCheckedChange={(checked) => onToggle(widget.id, checked)}
      />
    </div>
    <h3 className={styles.title}>{widget.title}</h3>
    <p className={styles.desc}>{widget.description}</p>
  </div>
);

export default WidgetCard;
