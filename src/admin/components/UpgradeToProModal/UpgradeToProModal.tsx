import React, { useEffect } from "react";
import { createPortal } from "react-dom";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faXmark, faCrown, faCheck } from "@fortawesome/free-solid-svg-icons";
import type { UpgradeToProModalProps } from "./types";
import styles from "./UpgradeToProModal.module.scss";

const FEATURES = [
  "Advanced Widget Library with 21+ widgets",
  "Access to advanced extensions",
  "Premium Templates",
  "Priority Support",
  "Early Access to New Features",
  'Priority Feature Requests'
];

const UpgradeToProModal: React.FC<UpgradeToProModalProps> = ({ open, onClose, upgradeUrl }) => {
  useEffect(() => {
    if (!open) {
      return;
    }
    const onKey = (e: KeyboardEvent) => {
      if (e.key === "Escape") {
        onClose();
      }
    };
    document.addEventListener("keydown", onKey);
    const prev = document.body.style.overflow;
    document.body.style.overflow = "hidden";
    return () => {
      document.removeEventListener("keydown", onKey);
      document.body.style.overflow = prev;
    };
  }, [open, onClose]);

  if (!open || typeof document === "undefined") {
    return null;
  }

  const node = (
    <div className={styles.overlay} role="dialog" aria-modal="true" aria-labelledby="upgrade-pro-title" onClick={onClose}>
      <div className={styles.modal} onClick={(e) => e.stopPropagation()}>
        <div className={styles.header}>
          <button type="button" className={styles.closeBtn} onClick={onClose} aria-label="Close">
            <FontAwesomeIcon icon={faXmark} />
          </button>
          <div className={styles.iconBubble} aria-hidden>
            <FontAwesomeIcon icon={faCrown} />
          </div>
          <h2 id="upgrade-pro-title" className={styles.heading}>
            Upgrade to Pro
          </h2>
          <p className={styles.subheading}>Unlock premium features and advanced settings</p>
        </div>
        <div className={styles.body}>
          <ul className={styles.list}>
            {FEATURES.map((text) => (
              <li key={text} className={styles.listItem}>
                <FontAwesomeIcon icon={faCheck} className={styles.check} aria-hidden />
                <span>{text}</span>
              </li>
            ))}
          </ul>
        </div>
        <div className={styles.footer}>
          {upgradeUrl ? (
            <a className={styles.upgradeBtn} href={upgradeUrl} target="_blank" rel="noopener noreferrer">
              Upgrade Now
            </a>
          ) : (
            <button type="button" className={styles.upgradeBtn} disabled>
              Upgrade Now
            </button>
          )}
          <button type="button" className={styles.laterBtn} onClick={onClose}>
            Maybe Later
          </button>
        </div>
      </div>
    </div>
  );

  return createPortal(node, document.body);
};

export default UpgradeToProModal;
