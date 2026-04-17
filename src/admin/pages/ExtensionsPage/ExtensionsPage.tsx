import React, { useCallback, useEffect, useMemo, useState } from "react";
import apiFetch from "@wordpress/api-fetch";
import ToggleSwitch from "../../components/ToggleSwitch/ToggleSwitch";
import { useToast } from "../../contexts/ToastContext/ToastContext";
import { useScreenSave, useScreenSaveContext } from "../../contexts/ScreenSaveContext/ScreenSaveContext";
import ExtensionCard from "./ExtensionCard/ExtensionCard";
import type { ExtensionItem, ExtensionsGetResponse, ExtensionsUpdateResponse } from "./types";
import styles from "./ExtensionsPage.module.scss";

function isExtensionBadge(value: unknown): value is ExtensionItem["badge"] {
  return value === "popular" || value === "updated" || value === "new" || value === null;
}

function normalizeExtension(raw: unknown): ExtensionItem | null {
  if (!raw || typeof raw !== "object") return null;
  const row = raw as Record<string, unknown>;
  const id = typeof row.id === "string" ? row.id : "";
  const title = typeof row.title === "string" ? row.title : "";
  if (!id || !title) return null;
  const badge = isExtensionBadge(row.badge) ? row.badge : null;
  return {
    id,
    title,
    description: typeof row.description === "string" ? row.description : "",
    isEnabled: Boolean(row.isEnabled),
    isLocked: Boolean(row.isLocked),
    badge,
    docUrl: typeof row.docUrl === "string" ? row.docUrl : "",
    demoUrl: typeof row.demoUrl === "string" ? row.demoUrl : "",
    hasSettings: Boolean(row.hasSettings),
  };
}

const ExtensionsPage: React.FC = () => {
  const { addToast } = useToast();
  const { setCurrentScreenSlug } = useScreenSaveContext();
  const [extensions, setExtensions] = useState<ExtensionItem[]>([]);
  const [draftActiveIds, setDraftActiveIds] = useState<string[]>([]);
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);

  const applyExtensionsFromServer = useCallback((list: ExtensionItem[]) => {
    setExtensions(list);
    setDraftActiveIds(list.filter((e) => e.isEnabled).map((e) => e.id));
  }, []);

  const loadExtensions = useCallback(async () => {
    setLoading(true);
    try {
      const res = (await apiFetch({ path: "/elementrify/v1/extensions" })) as ExtensionsGetResponse;
      const list = Array.isArray(res.extensions)
        ? res.extensions.map(normalizeExtension).filter((x): x is ExtensionItem => x !== null)
        : [];
      applyExtensionsFromServer(list);
    } catch {
      addToast({
        type: "error",
        title: "Could not load extensions",
        message: "Check your connection and try again.",
      });
      applyExtensionsFromServer([]);
    } finally {
      setLoading(false);
    }
  }, [addToast, applyExtensionsFromServer]);

  useEffect(() => {
    void loadExtensions();
  }, [loadExtensions]);

  const savedActiveIds = useMemo(
    () => extensions.filter((e) => e.isEnabled).map((e) => e.id),
    [extensions]
  );

  const draftSet = useMemo(() => new Set(draftActiveIds), [draftActiveIds]);
  const savedSet = useMemo(() => new Set(savedActiveIds), [savedActiveIds]);
  const isDirty =
    draftActiveIds.length !== savedActiveIds.length ||
    draftActiveIds.some((id) => !savedSet.has(id)) ||
    savedActiveIds.some((id) => !draftSet.has(id));

  const save = useCallback(async () => {
    setSaving(true);
    try {
      const res = (await apiFetch({
        path: "/elementrify/v1/extensions",
        method: "POST",
        data: { extensions: draftActiveIds },
      })) as ExtensionsUpdateResponse;
      if (res.success && Array.isArray(res.extensions)) {
        const list = res.extensions.map(normalizeExtension).filter((x): x is ExtensionItem => x !== null);
        applyExtensionsFromServer(list);
        addToast({
          type: "success",
          title: "Extensions saved",
          message: "Your feature toggles have been applied.",
        });
      } else {
        await loadExtensions();
      }
    } catch {
      addToast({
        type: "error",
        title: "Could not save extensions",
        message: "Your changes were not saved.",
      });
      await loadExtensions();
    } finally {
      setSaving(false);
    }
  }, [addToast, applyExtensionsFromServer, draftActiveIds, loadExtensions]);

  const getSaveHandler = useCallback(
    () => ({
      save,
      isDirty,
      isSaving: saving,
    }),
    [save, isDirty, saving]
  );
  useScreenSave("extensions", getSaveHandler);

  const effectiveExtensions = useMemo(
    () =>
      extensions.map((e) => ({
        ...e,
        isEnabled: draftSet.has(e.id),
      })),
    [extensions, draftSet]
  );

  const unlocked = useMemo(() => extensions.filter((e) => !e.isLocked), [extensions]);

  const allUnlockedEnabled = useMemo(
    () => unlocked.length > 0 && unlocked.every((e) => draftSet.has(e.id)),
    [draftSet, unlocked]
  );

  const handleToggle = useCallback((id: string, enabled: boolean) => {
    setDraftActiveIds((prev) => {
      const next = new Set(prev);
      if (enabled) {
        next.add(id);
      } else {
        next.delete(id);
      }
      return [...next];
    });
  }, []);

  const handleEnableAll = useCallback(
    (enable: boolean) => {
      if (enable) {
        setDraftActiveIds((prev) => {
          const next = new Set(prev);
          unlocked.forEach((e) => next.add(e.id));
          return [...next];
        });
        return;
      }
      setDraftActiveIds([]);
    },
    [unlocked]
  );

  const handleExtensionSettings = useCallback(
    (_id: string) => {
      setCurrentScreenSlug("settings");
    },
    [setCurrentScreenSlug]
  );

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
            disabled={unlocked.length === 0 || saving || loading}
            onCheckedChange={handleEnableAll}
          />
        </div>
      </header>

      {loading ? (
        <p className={styles.stateMessage}>Loading…</p>
      ) : extensions.length === 0 ? (
        <p className={styles.stateMessage}>No extensions are available yet.</p>
      ) : (
        <div className={styles.grid}>
          {effectiveExtensions.map((ext) => (
            <ExtensionCard
              key={ext.id}
              extension={ext}
              onToggle={handleToggle}
              onSettingsClick={handleExtensionSettings}
            />
          ))}
        </div>
      )}
    </div>
  );
};

export default ExtensionsPage;
