import React, { createContext, useCallback, useContext, useMemo, useState } from "react";
import apiFetch from "@wordpress/api-fetch";
import { useToast } from "../ToastContext/ToastContext";
import type { ExtensionSettingsContextValue, ExtensionSettingsProviderProps } from "./types";
import type { ExtensionSettingsSaveResponse } from "../../pages/ExtensionsPage/types";

const ExtensionSettingsContext = createContext<ExtensionSettingsContextValue | null>(null);

function cloneSettings(source: Record<string, unknown>): Record<string, unknown> {
  const out: Record<string, unknown> = {};
  for (const key of Object.keys(source)) {
    const v = source[key];
    out[key] = Array.isArray(v) ? [...v] : v;
  }
  return out;
}

export function ExtensionSettingsProvider({
  children,
  extensions,
  onSettingsSaved,
}: ExtensionSettingsProviderProps) {
  const { addToast } = useToast();
  const [modalExtensionId, setModalExtensionId] = useState<string | null>(null);
  const [modalDraft, setModalDraft] = useState<Record<string, unknown>>({});
  const [modalSaving, setModalSaving] = useState(false);

  const modalExtension = useMemo(
    () => (modalExtensionId ? extensions.find((e) => e.id === modalExtensionId) ?? null : null),
    [extensions, modalExtensionId]
  );

  const openSettingsModal = useCallback(
    (extensionId: string) => {
      const ext = extensions.find((e) => e.id === extensionId);
      if (!ext?.hasSettings) {
        return;
      }
      setModalExtensionId(extensionId);
      setModalDraft(cloneSettings(ext.settings ?? {}));
    },
    [extensions]
  );

  const closeSettingsModal = useCallback(() => {
    setModalExtensionId(null);
    setModalDraft({});
    setModalSaving(false);
  }, []);

  const setSettingsField = useCallback((fieldId: string, value: unknown) => {
    setModalDraft((prev) => ({ ...prev, [fieldId]: value }));
  }, []);

  const saveSettingsModal = useCallback(async () => {
    if (!modalExtensionId) {
      return;
    }
    setModalSaving(true);
    try {
      const res = (await apiFetch({
        path: `/addonext/v1/extensions/${encodeURIComponent(modalExtensionId)}/settings`,
        method: "POST",
        data: { settings: modalDraft },
      })) as ExtensionSettingsSaveResponse;
      if (res.success && res.settings) {
        onSettingsSaved(modalExtensionId, res.settings);
        addToast({
          type: "success",
          title: "Settings saved",
          message: "Extension settings were updated.",
        });
        closeSettingsModal();
      } else {
        addToast({
          type: "error",
          title: "Save failed",
          message: res.message ?? "Could not save settings.",
        });
      }
    } catch {
      addToast({
        type: "error",
        title: "Save failed",
        message: "Could not save settings.",
      });
    } finally {
      setModalSaving(false);
    }
  }, [addToast, closeSettingsModal, modalDraft, modalExtensionId, onSettingsSaved]);

  const value = useMemo<ExtensionSettingsContextValue>(
    () => ({
      openSettingsModal,
      closeSettingsModal,
      saveSettingsModal,
      setSettingsField,
      modalExtensionId,
      modalDraft,
      modalSaving,
      modalExtension,
    }),
    [
      closeSettingsModal,
      modalDraft,
      modalExtension,
      modalExtensionId,
      modalSaving,
      openSettingsModal,
      saveSettingsModal,
      setSettingsField,
    ]
  );

  return <ExtensionSettingsContext.Provider value={value}>{children}</ExtensionSettingsContext.Provider>;
}

export function useExtensionSettings(): ExtensionSettingsContextValue {
  const ctx = useContext(ExtensionSettingsContext);
  if (!ctx) {
    throw new Error("useExtensionSettings must be used within ExtensionSettingsProvider");
  }
  return ctx;
}
