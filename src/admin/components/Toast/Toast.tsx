import React, { useEffect, useRef } from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faCheck, faCircleExclamation, faXmark } from "@fortawesome/free-solid-svg-icons";
import type { ToastProps } from "./types";
import { DEFAULT_TOAST_DURATION } from "./types";
import styles from "./Toast.module.scss";

const CIRCLE_R = 14;
const CIRCLE_CIRCUMFERENCE = 2 * Math.PI * CIRCLE_R;
const CIRCLE_SIZE = 32;

const Toast: React.FC<ToastProps> = ({
  id,
  type,
  title,
  message,
  duration = DEFAULT_TOAST_DURATION,
  onClose,
}) => {
  const timeoutRef = useRef<ReturnType<typeof setTimeout> | null>(null);

  useEffect(() => {
    timeoutRef.current = setTimeout(() => {
      onClose(id);
    }, duration);
    return () => {
      if (timeoutRef.current) clearTimeout(timeoutRef.current);
    };
  }, [id, duration, onClose]);

  const icon = type === "success" ? faCheck : faCircleExclamation;

  return (
    <div className={`${styles.toast} ${styles[type]}`} role="alert">
      <div className={styles.iconWrap}>
        <span className={styles.iconInner}>
          <FontAwesomeIcon icon={icon} className={styles.iconSvg} />
        </span>
      </div>
      <div className={styles.content}>
        <p className={styles.title}>{title}</p>
        <p className={styles.message}>{message}</p>
      </div>
      <div
        className={styles.progressWrap}
        style={{ ["--toast-duration" as string]: `${duration}ms` }}
      >
        <svg
          className={styles.progressSvg}
          viewBox={`0 0 ${CIRCLE_SIZE} ${CIRCLE_SIZE}`}
          aria-hidden
        >
          <circle
            className={styles.progressTrack}
            cx={CIRCLE_SIZE / 2}
            cy={CIRCLE_SIZE / 2}
            r={CIRCLE_R}
            fill="none"
          />
          <circle
            className={styles.progressFill}
            cx={CIRCLE_SIZE / 2}
            cy={CIRCLE_SIZE / 2}
            r={CIRCLE_R}
            fill="none"
            strokeDasharray={CIRCLE_CIRCUMFERENCE}
            strokeDashoffset={0}
          />
        </svg>
        <button
          type="button"
          className={styles.close}
          onClick={() => onClose(id)}
          aria-label="Dismiss"
        >
          <FontAwesomeIcon icon={faXmark} />
        </button>
      </div>
    </div>
  );
};

export default Toast;
export type { ToastProps, ToastOptions } from "./types";
