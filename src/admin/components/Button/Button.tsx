import React from "react";
import classNames from "classnames";
import { ButtonProps } from "./types";
import styles from "./Button.module.scss";

const Button: React.FC<ButtonProps> = ({
  variant = "primary",
  size = "sm",
  icon,
  iconPosition = "left",
  children,
  className,
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
      {...rest}
    >
      {icon && (
        <span className={iconPosition === "left" ? styles.iconLeft : styles.iconRight}>
          {icon}
        </span>
      )}
      {children}
    </button>
  );
};

export default Button;
export type { ButtonProps, ButtonVariant, ButtonSize } from "./types";
