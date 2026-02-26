import React from "react";
import Input from "../../Input/Input";
import type { TextFieldProps } from "./types";
import styles from "./TextField.module.scss";

const TextField: React.FC<TextFieldProps> = ({
  label,
  description,
  value,
  onChange,
  placeholder,
}) => (
  <div className={styles.row}>
    <label className={styles.label}>{label}</label>
    <Input
      theme="light"
      value={value}
      onChange={(e) => onChange(e.target.value)}
      placeholder={placeholder}
    />
    {description && <p className={styles.description}>{description}</p>}
  </div>
);

export default TextField;
export type { TextFieldProps } from "./types";
