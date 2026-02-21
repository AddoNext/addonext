import React from "react";
import { SelectProps } from "./types";
import styles from "./Select.module.scss";
import Icon from "../Icon/Icon";

const Select: React.FC<SelectProps> = ({
  options,
  label,
  placeholder,
  id,
  ...rest
}) => {
  const selectId = id || `select-${React.useId()}`;
  return (
    <div className={styles.wrapper}>
      {label && (
        <label htmlFor={selectId} className={styles.label}>
          {label}
        </label>
      )}
      <div className={styles.selectWrapper}>
        <select id={selectId} className={styles.select} {...rest}>
          {placeholder && (
            <option value="" disabled>
              {placeholder}
            </option>
          )}
          {options.map((opt) => (
            <option key={opt.value} value={opt.value}>
              {opt.label}
            </option>
          ))}
        </select>
        <span className={styles.icon}>
          <Icon name="chevron-down" size={12} />
        </span>
      </div>
    </div>
  );
};

export default Select;
export type { SelectProps, SelectOption } from "./types";
