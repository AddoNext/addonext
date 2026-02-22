import React from "react";
import classNames from "classnames";
import { ButtonProps } from "./types";
import styles from "./Button.module.scss";

const Button: React.FC<ButtonProps> = ({
  variant = "primary",
  size = "sm",
  icon,
  iconPosition = "left",
  loading = false,
  children,
  className,
  disabled,
  ...rest
}) => {
  return (
    <button
      type="button"
      className={classNames(
        styles.btn,
        styles[variant],
        size === "sm" ? styles.sizeSm : styles.sizeMd,
        className
      )}
      disabled={disabled ?? loading}
      {...rest}
    >
      {icon && (
        <span className={iconPosition === "left" ? styles.iconLeft : styles.iconRight}>
          {loading ? <span className={styles.spinner} aria-hidden /> : icon}
        </span>
      )}
      {children}
    </button>
  );
};

export default Button;
export type { ButtonProps, ButtonVariant, ButtonSize } from "./types";
