import React from "react";
import { HeaderProps } from "./types";
import styles from "./Header.module.scss";
import Button from "../Button/Button";
import Icon from "../Icon/Icon";

const Header: React.FC<HeaderProps> = ({
  title = "Elementrify",
  subtitle = "Premium Elementor Widgets Collection",
  onThemeToggle,
  onExportSettings,
  onSaveChanges,
}) => {
  return (
    <header className={styles.header} id="plugin-header">
      <div className={styles.inner}>
        <div className={styles.brand}>
          <div className={styles.logo}>
            <Icon name="cube" size={20} className={styles.logoIcon} />
          </div>
          <div>
            <h1 className={styles.title}>{title}</h1>
            <p className={styles.subtitle}>{subtitle}</p>
          </div>
        </div>
        <div className={styles.actions}>
          {onThemeToggle && (
            <button
              type="button"
              className={styles.themeBtn}
              onClick={onThemeToggle}
              aria-label="Toggle theme"
            >
              <Icon name="droplet" size={18} />
            </button>
          )}
          <div className={styles.actionsGroup}>
            {onExportSettings && (
              <Button variant="secondary" onClick={onExportSettings} icon={<Icon name="download" size={14} />}>
                Export Settings
              </Button>
            )}
            {onSaveChanges && (
              <Button variant="primary" onClick={onSaveChanges} icon={<Icon name="save" size={14} />}>
                Save Changes
              </Button>
            )}
          </div>
        </div>
      </div>
    </header>
  );
};

export default Header;
export type { HeaderProps } from "./types";
