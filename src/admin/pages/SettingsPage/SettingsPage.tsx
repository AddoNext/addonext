import React, { useCallback } from "react";
import { useScreenSave } from "../../contexts/ScreenSaveContext/ScreenSaveContext";
import styles from "./SettingsPage.module.scss";

const SettingsPage: React.FC = () => {
  const getSaveHandler = useCallback(
    () => ({
      save: async () => {},
      isDirty: false,
      isSaving: false,
    }),
    []
  );
  useScreenSave("settings", getSaveHandler);

  return (
    <div className={styles.page}>
      <p className={styles.placeholder}>Settings screen</p>
    </div>
  );
};

export default SettingsPage;
