import React, { useEffect } from "react";
import { createPortal } from "react-dom";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faXmark, faFloppyDisk } from "@fortawesome/free-solid-svg-icons";
import { useExtensionSettings } from "../../../contexts/ExtensionSettingsContext/ExtensionSettingsContext";
import ExtensionSettingsFields from "../ExtensionSettingsFields/ExtensionSettingsFields";
import Button from "../../../components/Button/Button";
import styles from "./ExtensionSettingsModal.module.scss";

const ExtensionSettingsModal: React.FC = () => {
  const {
    modalExtension,
    modalExtensionId,
    closeSettingsModal,
    saveSettingsModal,
    modalSaving,
    modalDraft,
    setSettingsField,
  } = useExtensionSettings();

  useEffect(() => {
    if (!modalExtensionId) {
      return;
    }
    const onKey = (e: KeyboardEvent) => {
      if (e.key === "Escape") {
        closeSettingsModal();
      }
    };
    document.addEventListener("keydown", onKey);
    const prev = document.body.style.overflow;
    document.body.style.overflow = "hidden";
    return () => {
      document.removeEventListener("keydown", onKey);
      document.body.style.overflow = prev;
    };
  }, [closeSettingsModal, modalExtensionId]);

  if (!modalExtensionId || !modalExtension) {
    return null;
  }

  const node = (
    <div
      className={styles.overlay}
      role="dialog"
      aria-modal="true"
      aria-labelledby="extension-settings-title"
      onClick={closeSettingsModal}
    >
      <div className={styles.modal} onClick={(e) => e.stopPropagation()}>
        <div className={styles.header}>
          <h2 id="extension-settings-title" className={styles.title}>
            {modalExtension.title}
          </h2>
          <button
            type="button"
            className={styles.closeBtn}
            onClick={closeSettingsModal}
            aria-label="Close"
          >
            <FontAwesomeIcon icon={faXmark} />
          </button>
        </div>
        <div className={styles.body}>
          <ExtensionSettingsFields
            fields={modalExtension.fields}
            values={modalDraft}
            onChange={setSettingsField}
          />
        </div>
        <div className={styles.footer}>
          <Button variant="secondary" onClick={closeSettingsModal} disabled={modalSaving}>
            Cancel
          </Button>
          <Button
            variant="primary"
            onClick={() => void saveSettingsModal()}
            loading={modalSaving}
            disabled={modalSaving}
            icon={<FontAwesomeIcon icon={faFloppyDisk} />}
          >
            Save
          </Button>
        </div>
      </div>
    </div>
  );

  return typeof document !== "undefined" ? createPortal(node, document.body) : null;
};

export default ExtensionSettingsModal;
