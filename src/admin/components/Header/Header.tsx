import React from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faCube, faSun, faMoon, faDownload, faSave } from "@fortawesome/free-solid-svg-icons";
import { HeaderProps } from "./types";
import styles from "./Header.module.scss";
import Button from "../Button/Button";
import { useScreenSaveContext } from "../../contexts/ScreenSaveContext/ScreenSaveContext";

const Header: React.FC<HeaderProps> = ({
  title = "Elementrify",
  subtitle = "Premium Elementor Widgets Collection",
  theme = "light",
  onThemeToggle,
  onExportSettings,
}) => {
  const { currentScreenSlug, getSaveState } = useScreenSaveContext();
  const saveState = getSaveState(currentScreenSlug);

  return (
    <header className={styles.header} id="plugin-header">
      <div className={styles.inner}>
        <div className={styles.brand}>
          <div className={styles.logo}>
            <FontAwesomeIcon icon={faCube} className={styles.logoIcon} />
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
              aria-label={theme === "dark" ? "Switch to light mode" : "Switch to dark mode"}
            >
              <FontAwesomeIcon icon={theme === "dark" ? faMoon : faSun} size="lg" />
            </button>
          )}
          <div className={styles.actionsGroup}>
            {onExportSettings && (
              <Button variant="secondary" onClick={onExportSettings} icon={<FontAwesomeIcon icon={faDownload} />}>
                Export Settings
              </Button>
            )}
            <Button
              variant="primary"
              onClick={saveState?.save ?? (() => Promise.resolve())}
              icon={<FontAwesomeIcon icon={faSave} />}
              disabled={!saveState || !saveState.isDirty || saveState.isSaving}
            >
              {saveState?.isSaving ? "Saving…" : "Save Changes"}
            </Button>
          </div>
        </div>
      </div>
    </header>
  );
};

export default Header;
export type { HeaderProps } from "./types";
