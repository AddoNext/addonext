import React from "react";
import Select from "../../Select/Select";
import type { SelectFieldProps } from "./types";
import styles from "./SelectField.module.scss";

const SelectField: React.FC<SelectFieldProps> = ({
  label,
  value,
  onChange,
  options,
}) => (
  <div className={styles.row}>
    <label className={styles.label}>{label}</label>
    <Select
      options={options}
      value={value}
      onChange={(e) => onChange(e.target.value)}
    />
  </div>
);

export default SelectField;
export type { SelectFieldProps, SelectFieldOption } from "./types";
