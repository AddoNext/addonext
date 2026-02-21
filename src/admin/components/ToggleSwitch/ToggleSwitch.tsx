import React from "react";
import { ToggleSwitchProps } from "./types";
import styles from "./ToggleSwitch.module.scss";

const ToggleSwitch: React.FC<ToggleSwitchProps> = ({
  checked = false,
  onCheckedChange,
  onChange,
  ...rest
}) => {
  const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    onCheckedChange?.(e.target.checked);
    onChange?.(e);
  };
  return (
    <label className={styles.switch}>
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
