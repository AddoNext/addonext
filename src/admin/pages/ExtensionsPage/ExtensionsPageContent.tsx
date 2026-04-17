import React from "react";
import ToggleSwitch from "../../components/ToggleSwitch/ToggleSwitch";
import { useExtensionSettings } from "../../contexts/ExtensionSettingsContext/ExtensionSettingsContext";
import ExtensionCard from "./ExtensionCard/ExtensionCard";
import ExtensionSettingsModal from "./ExtensionSettingsModal/ExtensionSettingsModal";
import type { ExtensionItem } from "./types";
import styles from "./ExtensionsPage.module.scss";

interface ExtensionsPageContentProps {
  loading: boolean;
  saving: boolean;
  extensionsCount: number;
  effectiveExtensions: ExtensionItem[];
  allUnlockedEnabled: boolean;
  unlockedLength: number;
  onToggle: (id: string, enabled: boolean) => void;
  onEnableAll: (enable: boolean) => void;
}

const ExtensionsPageContent: React.FC<ExtensionsPageContentProps> = ({
  loading,
  saving,
  extensionsCount,
  effectiveExtensions,
  allUnlockedEnabled,
  unlockedLength,
  onToggle,
  onEnableAll,
}) => {
  const { openSettingsModal } = useExtensionSettings();

  return (
    <div className={styles.page}>
      <header className={styles.header}>
        <div className={styles.headerText}>
          <h1 className={styles.title}>Features</h1>
          <p className={styles.subtitle}>Enable powerful features to improve your Elementor workflow.</p>
        </div>
        <div className={styles.enableAll}>
          <span className={styles.enableAllLabel}>Enable All</span>
          <ToggleSwitch
            accent
            checked={allUnlockedEnabled}
            disabled={unlockedLength === 0 || saving || loading}
            onCheckedChange={onEnableAll}
          />
        </div>
      </header>

      {loading ? (
        <p className={styles.stateMessage}>Loading…</p>
      ) : extensionsCount === 0 ? (
        <p className={styles.stateMessage}>No extensions are available yet.</p>
      ) : (
        <div className={styles.grid}>
          {effectiveExtensions.map((ext) => (
            <ExtensionCard
              key={ext.id}
              extension={ext}
              onToggle={onToggle}
              onSettingsClick={ext.hasSettings ? (id) => openSettingsModal(id) : undefined}
            />
          ))}
        </div>
      )}
      <ExtensionSettingsModal />
    </div>
  );
};

export default ExtensionsPageContent;
