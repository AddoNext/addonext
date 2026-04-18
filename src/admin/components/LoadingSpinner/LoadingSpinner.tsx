import React from "react";
import type { LoadingSpinnerProps } from "./types";
import styles from "./LoadingSpinner.module.scss";

const sizeClass: Record<NonNullable<LoadingSpinnerProps["size"]>, string> = {
  md: styles.sizeMd,
  sm: styles.sizeSm,
};

const LoadingSpinner: React.FC<LoadingSpinnerProps> = ({
  className,
  size = "md",
  screenReaderLabel = "Loading",
}) => {
  return (
    <div
      className={`${styles.root} ${sizeClass[size]} ${className ?? ""}`}
      role="status"
      aria-live="polite"
      aria-busy="true"
    >
      <span className={styles.srOnly}>{screenReaderLabel}</span>
      <span className={styles.ring} aria-hidden />
    </div>
  );
};

export default LoadingSpinner;
