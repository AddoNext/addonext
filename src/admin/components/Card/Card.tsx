import React from "react";
import classNames from "classnames";
import { CardProps } from "./types";
import styles from "./Card.module.scss";

const Card: React.FC<CardProps> = ({
  theme = "light",
  title,
  description,
  icon,
  action,
  children,
  className,
}) => {
  return (
    <div className={classNames(styles.card, styles[theme], className)}>
      {(icon || action) && (
        <div className={styles.header}>
          {icon && <div className={styles.iconWrap}>{icon}</div>}
          {action}
        </div>
      )}
      {title && <h4 className={styles.title}>{title}</h4>}
      {description && <p className={styles.description}>{description}</p>}
      {children && <div className={styles.body}>{children}</div>}
    </div>
  );
};

export default Card;
export type { CardProps, CardTheme } from "./types";
