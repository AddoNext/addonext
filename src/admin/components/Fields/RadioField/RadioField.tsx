import React from "react";
import type { RadioFieldProps } from "./types";
import styles from "./RadioField.module.scss";

const RadioField: React.FC<RadioFieldProps> = ({
  label,
  name,
  value,
  onChange,
  options,
}) => (
  <div className={styles.row}>
    <h3 className={styles.label}>{label}</h3>
    <div className={styles.options}>
      {options.map((opt) => (
        <label key={opt.value} className={styles.option}>
          <input
            type="radio"
            name={name}
            value={opt.value}
            checked={value === opt.value}
            onChange={() => onChange(opt.value)}
          />
          <span className={styles.optionLabel}>{opt.label}</span>
        </label>
      ))}
    </div>
  </div>
);

export default RadioField;
export type { RadioFieldProps, RadioFieldOption } from "./types";
