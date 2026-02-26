import React, { useState } from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faEye, faEyeSlash } from "@fortawesome/free-solid-svg-icons";
import Input from "../../Input/Input";
import type { PasswordFieldProps } from "./types";
import styles from "./PasswordField.module.scss";

const PasswordField: React.FC<PasswordFieldProps> = ({
  label,
  value,
  onChange,
  placeholder,
}) => {
  const [visible, setVisible] = useState(false);

  return (
    <div className={styles.row}>
      <label className={styles.label}>{label}</label>
      <div className={styles.inputRow}>
        <div className={styles.input}>
          <Input
            theme="light"
            type={visible ? "text" : "password"}
            value={value}
            onChange={(e) => onChange(e.target.value)}
            placeholder={placeholder}
          />
        </div>
        <button
          type="button"
          className={styles.toggle}
          onClick={() => setVisible((v) => !v)}
          aria-label={visible ? "Hide password" : "Show password"}
        >
          <FontAwesomeIcon icon={visible ? faEyeSlash : faEye} />
        </button>
      </div>
    </div>
  );
};

export default PasswordField;
export type { PasswordFieldProps } from "./types";
