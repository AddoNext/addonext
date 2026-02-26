import React, { useCallback, useEffect, useRef, useState } from "react";
import { useDispatch, useSelect } from "@wordpress/data";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
  faSliders,
  faCog,
  faBolt,
  faPuzzlePiece,
  faKey,
  faCode,
  faDatabase,
} from "@fortawesome/free-solid-svg-icons";
import { useScreenSave } from "../../contexts/ScreenSaveContext/ScreenSaveContext";
import { STORE_NAME } from "../../../store/admin";
import type { SettingsSection } from "./fields";
import { renderField } from "./utils";
import BackupSection from "./components/BackupSection/BackupSection";
import styles from "./SettingsPage.module.scss";

type SettingsStoreSelect = {
  getSettingsSections: () => SettingsSection[] | null;
  getSettingsValues: () => Record<string, unknown>;
  getSettingsResolving: () => boolean;
  getSettingsSaving: () => boolean;
  getSettingsIsDirty: () => boolean;
};

const SECTION_ICONS: Record<string, typeof faCog> = {
  cog: faCog,
  bolt: faBolt,
  "puzzle-piece": faPuzzlePiece,
  key: faKey,
  code: faCode,
  database: faDatabase,
};

const SettingsPage: React.FC = () => {
  const mainRef = useRef<HTMLDivElement>(null);
  const [activeSectionId, setActiveSectionId] = useState<string | null>(null);

  const { sections, values, isLoading, isSaving, isDirty } = useSelect(
    (select) => {
      const store = select(STORE_NAME) as SettingsStoreSelect;
      return {
        sections: store.getSettingsSections(),
        values: store.getSettingsValues(),
        isLoading: store.getSettingsResolving(),
        isSaving: store.getSettingsSaving(),
        isDirty: store.getSettingsIsDirty(),
      };
    },
    []
  );

  const { fetchSettings, setSettingsValue, saveSettings } = useDispatch(STORE_NAME);

  useEffect(() => {
    if (sections === null) {
      fetchSettings();
    }
  }, [sections, fetchSettings]);

  const setValue = useCallback(
    (key: string, value: unknown) => {
      setSettingsValue(key, value);
    },
    [setSettingsValue]
  );

  const save = useCallback(async () => {
    if (sections) {
      await saveSettings({ values, sections });
    }
  }, [saveSettings, values, sections]);

  const getSaveHandler = useCallback(
    () => ({
      save,
      isDirty,
      isSaving,
    }),
    [save, isDirty, isSaving]
  );
  useScreenSave("settings", getSaveHandler);

  const getSectionIconClass = (sectionId: string) => {
    const map: Record<string, string> = {
      general: styles.sectionIcon,
      performance: styles.sectionIconPerformance,
      integrations: styles.sectionIconIntegrations,
      api: styles.sectionIconApi,
      advanced: styles.sectionIconAdvanced,
      backup: styles.sectionIconBackup,
    };
    return map[sectionId] ?? styles.sectionIcon;
  };

  if (isLoading) {
    return (
      <div className={styles.page}>
        <p className={styles.loading}>Loading settings…</p>
      </div>
    );
  }

  if (!sections || sections.length === 0) {
    return (
      <div className={styles.page}>
        <p className={styles.loading}>No settings sections.</p>
      </div>
    );
  }

  return (
    <div className={styles.page}>
      <div className={styles.grid}>
        <aside className={styles.sidebar}>
          <div className={styles.sidebarCard}>
            <div className={styles.sidebarHeader}>
              <h3 className={styles.sidebarTitle}>
                <FontAwesomeIcon icon={faSliders} />
                Settings Menu
              </h3>
            </div>
            <nav className={styles.sidebarNav}>
              {sections.map((section) => (
                <a
                  key={section.id}
                  href={`#${section.id}`}
                  className={`${styles.sidebarLink} ${activeSectionId === section.id ? styles.active : ""}`}
                  onClick={(e) => {
                    e.preventDefault();
                    setActiveSectionId(section.id);
                    document.getElementById(section.id)?.scrollIntoView({ behavior: "smooth" });
                  }}
                >
                  <FontAwesomeIcon icon={SECTION_ICONS[section.icon] ?? faCog} />
                  {section.title}
                </a>
              ))}
            </nav>
          </div>
        </aside>

        <div ref={mainRef} className={styles.main}>
          {sections.map((section) => (
            <section
              key={section.id}
              id={section.id}
              className={styles.sectionCard}
            >
              <div className={styles.sectionHeader}>
                <h2 className={styles.sectionTitle}>
                  <FontAwesomeIcon
                    icon={SECTION_ICONS[section.icon] ?? faCog}
                    className={getSectionIconClass(section.id)}
                  />
                  {section.title}
                </h2>
                {section.description && (
                  <p className={styles.sectionDescription}>{section.description}</p>
                )}
              </div>
              {section.id === "backup" ? (
                <BackupSection />
              ) : section.fields.length > 0 ? (
                <div className={styles.fieldsList}>
                  {section.fields.map((field) => (
                    <div key={field.id} className={styles.fieldItem}>
                      {renderField(field, values, setValue)}
                    </div>
                  ))}
                </div>
              ) : (
                <p className={styles.placeholder}>No settings in this section yet.</p>
              )}
            </section>
          ))}
        </div>
      </div>
    </div>
  );
};

export default SettingsPage;
