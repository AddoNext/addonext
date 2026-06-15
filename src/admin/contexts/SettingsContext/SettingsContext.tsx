import React, { createContext, useCallback, useContext, useEffect, useState } from "react";
import apiFetch from "@wordpress/api-fetch";
import type { SettingsContextValue } from "./types";

const SettingsContext = createContext<SettingsContextValue | null>(null);

export function SettingsProvider({ children }: { children: React.ReactNode }) {
  const [values, setValues] = useState<Record<string, unknown>>({});
  const [initialValues, setInitialValues] = useState<Record<string, unknown>>({});
  const [isLoading, setIsLoading] = useState(true);
  const [isSaving, setIsSaving] = useState(false);

  useEffect(() => {
    apiFetch({ path: "/veloria/v1/settings" })
      .then((res: { success?: boolean; settings?: Record<string, unknown> }) => {
        if (res?.success && res.settings) {
          setValues(res.settings);
          setInitialValues(res.settings);
        }
      })
      .finally(() => setIsLoading(false));
  }, []);

  const setValue = useCallback((key: string, value: unknown) => {
    setValues((prev) => ({ ...prev, [key]: value }));
  }, []);

  const isDirty = Object.keys(values).some(
    (key) => JSON.stringify(values[key]) !== JSON.stringify(initialValues[key])
  );

  const save = useCallback(async () => {
    setIsSaving(true);
    try {
      const res = await apiFetch({
        path: "/veloria/v1/settings",
        method: "POST",
        data: { settings: values },
      }) as { success?: boolean };
      if (res?.success) {
        setInitialValues(values);
      }
    } finally {
      setIsSaving(false);
    }
  }, [values]);

  const value: SettingsContextValue = {
    values,
    initialValues,
    isLoading,
    isSaving,
    isDirty,
    setValue,
    save,
  };

  return (
    <SettingsContext.Provider value={value}>
      {children}
    </SettingsContext.Provider>
  );
}

export function useSettingsContext(): SettingsContextValue {
  const ctx = useContext(SettingsContext);
  if (!ctx) {
    throw new Error("useSettingsContext must be used within SettingsProvider");
  }
  return ctx;
}
