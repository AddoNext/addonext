import React from "react";
import type { TextareaFieldProps } from "./types";
import styles from "./TextareaField.module.scss";

const TextareaField: React.FC<TextareaFieldProps> = ({
  label,
  description,
  value,
  onChange,
  placeholder,
  rows = 6,
}) => (
  <div className={styles.row}>
    <label className={styles.label}>{label}</label>
    <textarea
      className={styles.textarea}
      value={value}
      onChange={(e) => onChange(e.target.value)}
      placeholder={placeholder}
      rows={rows}
    />
    {description && <p className={styles.description}>{description}</p>}
  </div>
);

export default TextareaField;
export type { TextareaFieldProps } from "./types";
