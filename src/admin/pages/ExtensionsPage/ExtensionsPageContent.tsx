import React from "react";
import LoadingSpinner from "../../components/LoadingSpinner/LoadingSpinner";
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
  onLockedUpgrade?: () => void;
  onLockedToggleAttempt?: (id: string) => void;
  previewLockedExtensionId?: string | null;
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
  onLockedUpgrade,
  onLockedToggleAttempt,
  previewLockedExtensionId,
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
        <div className={styles.loadingRegion}>
          <LoadingSpinner screenReaderLabel="Loading extensions" />
        </div>
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
              onLockedUpgrade={onLockedUpgrade}
              onLockedToggleAttempt={onLockedToggleAttempt}
              lockedTogglePreview={previewLockedExtensionId === ext.id}
            />
          ))}
        </div>
      )}
      <ExtensionSettingsModal />
    </div>
  );
};

export default ExtensionsPageContent;
