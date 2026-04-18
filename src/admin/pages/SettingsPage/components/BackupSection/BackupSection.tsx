import React, { useEffect, useRef } from "react";
import { useDispatch, useSelect } from "@wordpress/data";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faDownload, faUpload, faFileArchive, faTrash, faRotateLeft } from "@fortawesome/free-solid-svg-icons";
import LoadingSpinner from "../../../../components/LoadingSpinner/LoadingSpinner";
import { useConfirm } from "../../../../contexts/ConfirmContext/ConfirmContext";
import { STORE_NAME } from "../../../../../store/admin";
import styles from "./BackupSection.module.scss";

type StoreSelect = {
  getBackups: () => { name: string; date: string; timestamp: number }[];
  getBackupsResolving: () => boolean;
  getBackupsExporting: () => boolean;
  getBackupsImporting: () => boolean;
  getBackupsError: () => string | null;
  getRestoringBackupFile: () => string | null;
  getSettingsValues: () => Record<string, unknown>;
};

const BackupSection: React.FC = () => {
  const fileInputRef = useRef<HTMLInputElement>(null);

  const backups = useSelect((select) => (select(STORE_NAME) as StoreSelect).getBackups(), []);
  const resolving = useSelect((select) => (select(STORE_NAME) as StoreSelect).getBackupsResolving(), []);
  const exporting = useSelect((select) => (select(STORE_NAME) as StoreSelect).getBackupsExporting(), []);
  const importing = useSelect((select) => (select(STORE_NAME) as StoreSelect).getBackupsImporting(), []);
  const error = useSelect((select) => (select(STORE_NAME) as StoreSelect).getBackupsError(), []);
  const restoringFile = useSelect((select) => (select(STORE_NAME) as StoreSelect).getRestoringBackupFile(), []);
  const values = useSelect((select) => (select(STORE_NAME) as StoreSelect).getSettingsValues(), []);

  const { confirm } = useConfirm();
  const {
    fetchBackups,
    exportBackup,
    importBackup,
    deleteBackup,
    downloadBackup,
    restoreBackup,
  } = useDispatch(STORE_NAME) as {
    fetchBackups: () => Promise<void>;
    exportBackup: (p: { values: Record<string, unknown>; saveToServer: boolean }) => Promise<{ success: boolean; download?: string; backup_name?: string }>;
    importBackup: (p: { file: File } | { values: Record<string, unknown> }) => Promise<{ success: boolean }>;
    deleteBackup: (file: string) => Promise<void>;
    downloadBackup: (file: string) => Promise<{ success: boolean }>;
    restoreBackup: (file: string) => Promise<void>;
  };

  useEffect(() => {
    fetchBackups();
  }, [fetchBackups]);

  const handleExport = async (saveToServer: boolean) => {
    try {
      const result = await exportBackup({ values, saveToServer });
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
      // error handled in store
    }
  };

  const handleImportClick = () => {
    fileInputRef.current?.click();
  };

  const handleImportFile = (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    if (!file) return;
    importBackup({ file }).finally(() => {
      e.target.value = "";
    });
  };

  return (
    <div className={styles.root}>
      <div className={styles.cards}>
        <div className={styles.card}>
          <div className={styles.cardIconExport}>
            <FontAwesomeIcon icon={faDownload} className={styles.cardIconSvg} />
          </div>
          <h3 className={styles.cardTitle}>Export Settings</h3>
          <p className={styles.cardDesc}>Download all your plugin settings as a JSON file</p>
          <button
            type="button"
            className={styles.btnExport}
            disabled={exporting}
            onClick={() => handleExport(true)}
          >
            <FontAwesomeIcon icon={faDownload} className={styles.btnIcon} />
            Export Now
          </button>
        </div>
        <div className={styles.card}>
          <div className={styles.cardIconImport}>
            <FontAwesomeIcon icon={faUpload} className={styles.cardIconSvg} />
          </div>
          <h3 className={styles.cardTitle}>Import Settings</h3>
          <p className={styles.cardDesc}>Restore settings from a previously exported file</p>
          <input
            ref={fileInputRef}
            type="file"
            accept=".json,application/json"
            className={styles.fileInput}
            onChange={handleImportFile}
          />
          <button
            type="button"
            className={styles.btnImport}
            disabled={importing}
            onClick={handleImportClick}
          >
            <FontAwesomeIcon icon={faUpload} className={styles.btnIcon} />
            Import File
          </button>
        </div>
      </div>

      {error && <p className={styles.error}>{error}</p>}

      <div className={styles.recent}>
        <h4 className={styles.recentTitle}>Recent Backups</h4>
        {resolving && backups.length === 0 ? (
          <div className={styles.recentLoading}>
            <LoadingSpinner size="sm" screenReaderLabel="Loading backups" />
          </div>
        ) : backups.length === 0 ? (
          <p className={styles.recentEmpty}>No backups yet. Export settings to create one.</p>
        ) : (
          <ul className={styles.recentList}>
            {backups.map((b) => (
              <li key={b.name} className={styles.recentItem}>
                <div className={styles.recentItemInfo}>
                  <FontAwesomeIcon icon={faFileArchive} className={styles.recentItemIcon} />
                  <div>
                    <p className={styles.recentItemName}>{b.name}</p>
                    <p className={styles.recentItemDate}>{b.date}</p>
                  </div>
                </div>
                <div className={styles.recentItemActions}>
                  <button
                    type="button"
                    className={styles.recentBtnRestore}
                    onClick={() => restoreBackup(b.name)}
                    disabled={restoringFile !== null}
                    aria-label="Restore"
                    title="Restore this backup"
                  >
                    {restoringFile === b.name ? (
                      <span className={styles.spinner} />
                    ) : (
                      <FontAwesomeIcon icon={faRotateLeft} />
                    )}
                  </button>
                  <button
                    type="button"
                    className={styles.recentBtnDownload}
                    onClick={() => downloadBackup(b.name)}
                    aria-label="Download"
                  >
                    <FontAwesomeIcon icon={faDownload} />
                  </button>
                  <button
                    type="button"
                    className={styles.recentBtnDelete}
                    onClick={() =>
                      confirm({
                        title: "Delete Backup?",
                        message:
                          "This action will permanently delete the selected backup. This cannot be undone.",
                        confirmLabel: "Delete",
                        onConfirm: () => deleteBackup(b.name),
                      })
                    }
                    aria-label="Delete"
                  >
                    <FontAwesomeIcon icon={faTrash} />
                  </button>
                </div>
              </li>
            ))}
          </ul>
        )}
      </div>
    </div>
  );
};

export default BackupSection;
