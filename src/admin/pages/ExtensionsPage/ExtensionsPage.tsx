import React, { useCallback, useEffect, useMemo, useState } from "react";
import apiFetch from "@wordpress/api-fetch";
import { useToast } from "../../contexts/ToastContext/ToastContext";
import { ExtensionSettingsProvider } from "../../contexts/ExtensionSettingsContext/ExtensionSettingsContext";
import { useScreenSave } from "../../contexts/ScreenSaveContext/ScreenSaveContext";
import ExtensionsPageContent from "./ExtensionsPageContent";
import type {
  ExtensionField,
  ExtensionItem,
  ExtensionsGetResponse,
  ExtensionsUpdateResponse,
} from "./types";

function isExtensionBadge(value: unknown): value is ExtensionItem["badge"] {
  return value === "popular" || value === "updated" || value === "new" || value === null;
}

function normalizeSettings(raw: unknown): Record<string, unknown> {
  if (!raw || typeof raw !== "object" || Array.isArray(raw)) {
    return {};
  }
  return { ...(raw as Record<string, unknown>) };
}

function normalizeField(raw: unknown): ExtensionField | null {
  if (!raw || typeof raw !== "object") {
    return null;
  }
  const row = raw as Record<string, unknown>;
  const id = typeof row.id === "string" ? row.id : "";
  const type = row.type;
  if (
    id === "" ||
    (type !== "toggle" && type !== "select" && type !== "checkbox_group" && type !== "text")
  ) {
    return null;
  }
  const optionsRaw = row.options;
  const options = Array.isArray(optionsRaw)
    ? optionsRaw
        .map((o) => {
          if (!o || typeof o !== "object") {
            return null;
          }
          const opt = o as Record<string, unknown>;
          const value = typeof opt.value === "string" ? opt.value : "";
          const label = typeof opt.label === "string" ? opt.label : "";
          if (!value || !label) {
            return null;
          }
          return { value, label };
        })
        .filter((x): x is { value: string; label: string } => x !== null)
    : undefined;

  const sw = row.show_when;
  let show_when: ExtensionField["show_when"];
  if (sw && typeof sw === "object" && typeof (sw as Record<string, unknown>).field === "string") {
    const o = sw as Record<string, unknown>;
    show_when = { field: o.field as string, value: o.value };
  }

  const swi = row.show_when_in;
  let show_when_in: ExtensionField["show_when_in"];
  if (swi && typeof swi === "object" && typeof (swi as Record<string, unknown>).field === "string") {
    const o = swi as Record<string, unknown>;
    const vals = o.values;
    if (Array.isArray(vals)) {
      show_when_in = { field: o.field as string, values: vals };
    }
  }

  return {
    id,
    type: type as ExtensionField["type"],
    label: typeof row.label === "string" ? row.label : id,
    description: typeof row.description === "string" ? row.description : undefined,
    default: row.default,
    options: options && options.length > 0 ? options : undefined,
    placeholder: typeof row.placeholder === "string" ? row.placeholder : undefined,
    show_when,
    show_when_in,
  };
}

function normalizeExtension(raw: unknown): ExtensionItem | null {
  if (!raw || typeof raw !== "object") {
    return null;
  }
  const row = raw as Record<string, unknown>;
  const id = typeof row.id === "string" ? row.id : "";
  const title = typeof row.title === "string" ? row.title : "";
  if (!id || !title) {
    return null;
  }
  const badge = isExtensionBadge(row.badge) ? row.badge : null;
  const fieldsRaw = row.fields;
  const fields = Array.isArray(fieldsRaw)
    ? fieldsRaw.map(normalizeField).filter((x): x is ExtensionField => x !== null)
    : [];
  const settings = normalizeSettings(row.settings);

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
    fields,
    settings,
  };
}

const ExtensionsPage: React.FC = () => {
  const { addToast } = useToast();
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

  const patchExtensionSettings = useCallback((extensionId: string, settings: Record<string, unknown>) => {
    setExtensions((prev) =>
      prev.map((e) => (e.id === extensionId ? { ...e, settings: { ...settings } } : e))
    );
  }, []);

  return (
    <ExtensionSettingsProvider extensions={extensions} onSettingsSaved={patchExtensionSettings}>
      <ExtensionsPageContent
        loading={loading}
        saving={saving}
        extensionsCount={extensions.length}
        effectiveExtensions={effectiveExtensions}
        allUnlockedEnabled={allUnlockedEnabled}
        unlockedLength={unlocked.length}
        onToggle={handleToggle}
        onEnableAll={handleEnableAll}
      />
    </ExtensionSettingsProvider>
  );
};

export default ExtensionsPage;
