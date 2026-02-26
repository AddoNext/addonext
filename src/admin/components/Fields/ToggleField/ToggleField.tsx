import React from "react";
import ToggleSwitch from "../../ToggleSwitch/ToggleSwitch";
import type { ToggleFieldProps } from "./types";
import styles from "./ToggleField.module.scss";

const ToggleField: React.FC<ToggleFieldProps> = ({
  label,
  description,
  value,
  onChange,
}) => (
  <div className={styles.row}>
    <div className={styles.content}>
      <h3 className={styles.label}>{label}</h3>
      {description && <p className={styles.description}>{description}</p>}
    </div>
    <div className={styles.control}>
      <ToggleSwitch checked={value} onCheckedChange={onChange} />
    </div>
  </div>
);

export default ToggleField;
export type { ToggleFieldProps } from "./types";
