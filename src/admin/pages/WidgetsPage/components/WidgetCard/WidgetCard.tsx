import React from "react";
import classNames from "classnames";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faLock } from "@fortawesome/free-solid-svg-icons";
import ToggleSwitch from "../../../../components/ToggleSwitch/ToggleSwitch";
import type { Widget } from "../../../../../store/types";
import styles from "./WidgetCard.module.scss";
import { hexToRgba } from "../../../../utils/colors";
import { getWidgetIcon } from "../../../../utils/widgetIcons";

interface WidgetCardProps {
  widget: Widget;
  onToggle: (id: string, checked: boolean) => void;
  onLockedUpgrade?: () => void;
}

const WidgetCard: React.FC<WidgetCardProps> = ({ widget, onToggle, onLockedUpgrade }) => {
  const locked = Boolean(widget.is_locked);

  const handleCardClick = (e: React.MouseEvent<HTMLDivElement>) => {
    if (!locked || !onLockedUpgrade) {
      return;
    }
    const target = e.target as HTMLElement;
    if (target.closest("label") || target.closest("a") || target.closest("button")) {
      return;
    }
    onLockedUpgrade();
  };

  return (
    <div
      className={classNames(styles.card, locked && styles.cardLocked)}
      onClick={handleCardClick}
      role={locked ? "button" : undefined}
      tabIndex={locked ? 0 : undefined}
      onKeyDown={
        locked && onLockedUpgrade
          ? (ev) => {
              if (ev.key === "Enter" || ev.key === " ") {
                ev.preventDefault();
                onLockedUpgrade();
              }
            }
          : undefined
      }
    >
      <div className={styles.header}>
        <div
          className={styles.iconWrap}
          style={{ backgroundColor: hexToRgba(widget.brand_color, 0.2) }}
        >
          <FontAwesomeIcon
            icon={getWidgetIcon(widget.id, widget.icon)}
            className={styles.widgetIcon}
            style={{ color: widget.brand_color }}
          />
        </div>
        <div className={styles.toggleRow}>
          {locked && (
            <span className={styles.lock} title="Pro plan required" aria-hidden>
              <FontAwesomeIcon icon={faLock} className={styles.lockIcon} />
            </span>
          )}
          <ToggleSwitch
            checked={widget.active}
            disabled={locked}
            onCheckedChange={(checked) => onToggle(widget.id, checked)}
            aria-label={locked ? `${widget.title} (Pro)` : widget.title}
          />
        </div>
      </div>
      <h3 className={styles.title}>{widget.title}</h3>
      <p className={styles.desc}>{widget.description}</p>
    </div>
  );
};

export default WidgetCard;
