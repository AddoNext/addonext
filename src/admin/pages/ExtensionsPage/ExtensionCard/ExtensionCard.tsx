import React from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
  faLock,
  faFileLines,
  faArrowUpRightFromSquare,
  faGear,
} from "@fortawesome/free-solid-svg-icons";
import ToggleSwitch from "../../../components/ToggleSwitch/ToggleSwitch";
import type { ExtensionItem } from "../types";
import styles from "./ExtensionCard.module.scss";

interface ExtensionCardProps {
  extension: ExtensionItem;
  onToggle: (id: string, enabled: boolean) => void;
  onSettingsClick?: (id: string) => void;
  onLockedUpgrade?: () => void;
}

const ExtensionCard: React.FC<ExtensionCardProps> = ({ extension, onToggle, onSettingsClick, onLockedUpgrade }) => {
  const { id, title, isEnabled, isLocked, badge, docUrl, demoUrl, hasSettings } = extension;
  const docHref = docUrl?.trim() ?? "";
  const demoHref = demoUrl?.trim() ?? "";

  const handleCardClick = (e: React.MouseEvent<HTMLElement>) => {
    if (!isLocked || !onLockedUpgrade) {
      return;
    }
    const target = e.target as HTMLElement;
    if (target.closest("a") || target.closest("button")) {
      return;
    }
    onLockedUpgrade();
  };

  return (
    <article
      className={`${styles.card}${isLocked ? ` ${styles.cardLocked}` : ""}`}
      onClick={handleCardClick}
      role={isLocked ? "button" : undefined}
      tabIndex={isLocked ? 0 : undefined}
      onKeyDown={
        isLocked && onLockedUpgrade
          ? (ev) => {
              if (ev.key === "Enter" || ev.key === " ") {
                ev.preventDefault();
                onLockedUpgrade();
              }
            }
          : undefined
      }
    >
      <div className={styles.topRow}>
        <h3 className={styles.title}>{title}</h3>
        <div
          className={styles.controls}
          onClick={isLocked ? (e) => e.stopPropagation() : undefined}
        >
          {isLocked && (
            <button
              type="button"
              className={styles.lock}
              title="Pro plan required"
              aria-label="Pro plan required"
              onClick={(e) => {
                e.stopPropagation();
                onLockedUpgrade?.();
              }}
            >
              <FontAwesomeIcon icon={faLock} className={styles.lockIcon} />
            </button>
          )}
          <ToggleSwitch
            accent
            checked={isEnabled}
            disabled={isLocked}
            onCheckedChange={(next) => onToggle(id, next)}
            aria-label={isLocked ? `${title} (premium)` : title}
          />
        </div>
      </div>
      <div className={styles.bottomRow}>
        <div className={styles.badges}>
          {badge ? (
            <span className={`${styles.badge} ${styles[`badge_${badge}`]}`}>{badge}</span>
          ) : (
            <span className={styles.badgePlaceholder} />
          )}
        </div>
        <div className={styles.actions}>
          {docHref ? (
            <a
              href={docHref}
              className={styles.iconBtn}
              target="_blank"
              rel="noopener noreferrer"
              aria-label={`Documentation for ${title}`}
            >
              <FontAwesomeIcon icon={faFileLines} />
            </a>
          ) : null}
          {demoHref ? (
            <a
              href={demoHref}
              className={styles.iconBtn}
              target="_blank"
              rel="noopener noreferrer"
              aria-label={`External link for ${title}`}
            >
              <FontAwesomeIcon icon={faArrowUpRightFromSquare} />
            </a>
          ) : null}
          {hasSettings && onSettingsClick ? (
            <button
              type="button"
              className={styles.iconBtn}
              aria-label={`Settings for ${title}`}
              onClick={() => onSettingsClick(id)}
            >
              <FontAwesomeIcon icon={faGear} />
            </button>
          ) : null}
        </div>
      </div>
    </article>
  );
};

export default ExtensionCard;
