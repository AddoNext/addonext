import React from "react";
import { ToggleSwitchProps } from "./types";
import styles from "./ToggleSwitch.module.scss";

const ToggleSwitch: React.FC<ToggleSwitchProps> = ({
  checked = false,
  onCheckedChange,
  onChange,
  accent = false,
  ...rest
}) => {
  const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    onCheckedChange?.(e.target.checked);
    onChange?.(e);
  };
  return (
    <label className={`${styles.switch} ${accent ? styles.accent : ""}`}>
      <input
        type="checkbox"
        className={styles.input}
        checked={checked}
        onChange={handleChange}
        {...rest}
      />
      <span className={styles.track}>
        <span className={styles.thumb} />
      </span>
    </label>
  );
};

export default ToggleSwitch;
export type { ToggleSwitchProps } from "./types";
