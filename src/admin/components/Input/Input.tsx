import React from "react";
import classNames from "classnames";
import { InputProps } from "./types";
import styles from "./Input.module.scss";

const Input = React.forwardRef<HTMLInputElement, InputProps>(
  ({ theme = "light", label, error, className, id, ...rest }, ref) => {
    const inputId = id || `input-${React.useId()}`;
    return (
      <div className={styles.wrapper}>
        {label && (
          <label htmlFor={inputId} className={styles.label}>
            {label}
          </label>
        )}
        <input
          ref={ref}
          id={inputId}
          className={classNames(styles.input, styles[theme], className)}
          {...rest}
        />
        {error && <span className={styles.error}>{error}</span>}
      </div>
    );
  }
);

Input.displayName = "Input";
export default Input;
export type { InputProps, InputTheme } from "./types";
