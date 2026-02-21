import React, { useMemo } from "react";
import Header from "./components/Header/Header";
import Tab from "./components/Tab/Tab";
import WidgetsPage from "./pages/WidgetsPage/WidgetsPage";
import ExtensionsPage from "./pages/ExtensionsPage/ExtensionsPage";
import TemplatesPage from "./pages/TemplatesPage/TemplatesPage";
import SettingsPage from "./pages/SettingsPage/SettingsPage";
import LicensePage from "./pages/LicensePage/LicensePage";
import styles from "./App.module.scss";

export const App = () => {
  const tabs = useMemo(
    () => [
      { label: "Widgets", slug: "widgets", selected: true, screen: <WidgetsPage /> },
      { label: "Extensions", slug: "extensions", screen: <ExtensionsPage /> },
      { label: "Templates", slug: "templates", screen: <TemplatesPage /> },
      { label: "Settings", slug: "settings", screen: <SettingsPage /> },
      { label: "License", slug: "license", screen: <LicensePage /> },
    ],
    []
  );

  const handleThemeToggle = () => { };
  const handleExportSettings = () => { };
  const handleSaveChanges = () => { };

  return (
    <div className={styles.app}>
      <Header
        onThemeToggle={handleThemeToggle}
        onExportSettings={handleExportSettings}
        onSaveChanges={handleSaveChanges}
      />
      <Tab tabs={tabs} />
    </div>
  );
};
