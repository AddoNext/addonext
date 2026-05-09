import React, { useMemo, useState, useEffect, useCallback } from "react";
import { useDispatch, useSelect } from "@wordpress/data";
import Header from "./components/Header/Header";
import Tab from "./components/Tab/Tab";
import WidgetsPage from "./pages/WidgetsPage/WidgetsPage";
import ExtensionsPage from "./pages/ExtensionsPage/ExtensionsPage";
import TemplatesPage from "./pages/TemplatesPage/TemplatesPage";
import SettingsPage from "./pages/SettingsPage/SettingsPage";
import LicensePage from "./pages/LicensePage/LicensePage";
import { WidgetsStateProvider } from "./contexts/WidgetsStateContext/WidgetsStateContext";
import { ScreenSaveProvider } from "./contexts/ScreenSaveContext/ScreenSaveContext";
import { ToastProvider } from "./contexts/ToastContext/ToastContext";
import { ConfirmProvider } from "./contexts/ConfirmContext/ConfirmContext";
import { STORE_NAME } from "../store/admin";
import styles from "./App.module.scss";

const STORAGE_KEY = "elementrify-theme";
const DEFAULT_TAB_SLUG = "widgets";
const WPBODY_DARK_CLASS = "elementrify-dark-mode";

declare global {
  interface Window {
    elementrifyAdminData?: {
      isElementorActive?: boolean;
    };
  }
}

function readElementorActive(): boolean {
  if (typeof window === "undefined" || typeof document === "undefined") {
    return true;
  }
  const localized = window.elementrifyAdminData?.isElementorActive;
  if (typeof localized === "boolean") {
    return localized;
  }
  const root = document.getElementById("elementrify-admin");
  const attr = root?.getAttribute("data-elementor-active");
  if (attr === "1") {
    return true;
  }
  if (attr === "0") {
    return false;
  }
  return false;
}

export const App = () => {
  const [theme, setTheme] = useState<"light" | "dark">(() => {
    if (typeof window === "undefined") return "light";
    const stored = localStorage.getItem(STORAGE_KEY);
    return (stored === "dark" || stored === "light") ? stored : "light";
  });
  const isElementorActive = readElementorActive();

  useEffect(() => {
    localStorage.setItem(STORAGE_KEY, theme);

    const wpBodyElement = document.getElementById("wpbody");
    if (!wpBodyElement) {
      return;
    }

    wpBodyElement.classList.toggle(WPBODY_DARK_CLASS, theme === "dark");

    return () => {
      wpBodyElement.classList.remove(WPBODY_DARK_CLASS);
    };
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

  const values = useSelect((select) => (select(STORE_NAME) as { getSettingsValues: () => Record<string, unknown> }).getSettingsValues(), []);
  const { exportBackup } = useDispatch(STORE_NAME) as {
    exportBackup: (p: { values: Record<string, unknown>; saveToServer: boolean }) => Promise<{ success: boolean; download?: string; backup_name?: string }>;
  };

  const handleThemeToggle = () => setTheme((prev) => (prev === "light" ? "dark" : "light"));
  const handleExportSettings = useCallback(async () => {
    try {
      const result = await exportBackup({ values, saveToServer: true });
      if (result?.success && result.download) {
        const blob = new Blob([result.download], { type: "application/json" });
        const url = URL.createObjectURL(blob);
        const a = document.createElement("a");
        a.href = url;
        a.download = result.backup_name ?? "elementrify-backup.json";
        a.click();
        URL.revokeObjectURL(url);
      }
    } catch {
      // error in store
    }
  }, [exportBackup, values]);

  return (
    <div className={styles.app} data-theme={theme}>
      <ToastProvider>
        <ConfirmProvider>
          <ScreenSaveProvider defaultSlug={DEFAULT_TAB_SLUG}>
            <WidgetsStateProvider>
              <Header
                theme={theme}
                onThemeToggle={handleThemeToggle}
                onExportSettings={handleExportSettings}
              />
              {isElementorActive ? (
                <Tab tabs={tabs} />
              ) : (
                <section className={styles.elementorRequired}>
                  <div className={styles.elementorRequiredIcon} aria-hidden>
                    E
                  </div>
                  <h2 className={styles.elementorRequiredTitle}>Elementor Plugin Required</h2>
                  <p className={styles.elementorRequiredText}>
                    Elementrify needs Elementor to be active before it can load widgets, extensions, and settings.
                    Please activate the Elementor plugin, then refresh this page.
                  </p>
                </section>
              )}
            </WidgetsStateProvider>
          </ScreenSaveProvider>
        </ConfirmProvider>
      </ToastProvider>
    </div>
  );
};
