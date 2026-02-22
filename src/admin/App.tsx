import React, { useMemo, useState, useEffect } from "react";
import Header from "./components/Header/Header";
import Tab from "./components/Tab/Tab";
import WidgetsPage from "./pages/WidgetsPage/WidgetsPage";
import ExtensionsPage from "./pages/ExtensionsPage/ExtensionsPage";
import TemplatesPage from "./pages/TemplatesPage/TemplatesPage";
import SettingsPage from "./pages/SettingsPage/SettingsPage";
import LicensePage from "./pages/LicensePage/LicensePage";
import { WidgetsStateProvider } from "./contexts/WidgetsStateContext/WidgetsStateContext";
import { ScreenSaveProvider } from "./contexts/ScreenSaveContext/ScreenSaveContext";
import styles from "./App.module.scss";

const STORAGE_KEY = "elementrify-theme";
const DEFAULT_TAB_SLUG = "widgets";

export const App = () => {
  const [theme, setTheme] = useState<"light" | "dark">(() => {
    if (typeof window === "undefined") return "light";
    const stored = localStorage.getItem(STORAGE_KEY);
    return (stored === "dark" || stored === "light") ? stored : "light";
  });

  useEffect(() => {
    localStorage.setItem(STORAGE_KEY, theme);
  }, [theme]);

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

  const handleThemeToggle = () => setTheme((prev) => (prev === "light" ? "dark" : "light"));
  const handleExportSettings = () => { };

  return (
    <div className={styles.app} data-theme={theme}>
      <ScreenSaveProvider defaultSlug={DEFAULT_TAB_SLUG}>
        <WidgetsStateProvider>
          <Header
            theme={theme}
            onThemeToggle={handleThemeToggle}
            onExportSettings={handleExportSettings}
          />
          <Tab tabs={tabs} />
        </WidgetsStateProvider>
      </ScreenSaveProvider>
    </div>
  );
};
