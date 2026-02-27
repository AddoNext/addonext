import React, { createContext, useCallback, useContext, useEffect, useRef, useState } from "react";
import { useSelect, useDispatch } from "@wordpress/data";
import { STORE_NAME } from "../../../store/admin";
import type { Widget } from "../../../store/types";
import { useToast } from "../ToastContext/ToastContext";
import type { WidgetsStateContextValue } from "./types";

const WidgetsStateContext = createContext<WidgetsStateContextValue | null>(null);

export function WidgetsStateProvider({ children }: { children: React.ReactNode }) {
  const { widgets, isResolving } = useSelect(
    (select) => {
      const store = select(STORE_NAME) as {
        getWidgets: () => Widget[];
        getIsResolving: () => boolean;
      };
      return {
        widgets: store.getWidgets() ?? [],
        isResolving: store.getIsResolving?.() ?? false,
      };
    },
    []
  );

  const { addToast } = useToast();
  const { updateWidgets } = useDispatch(STORE_NAME) as {
    updateWidgets: (ids: string[]) => Promise<unknown>;
  };
  const [activeIds, setActiveIds] = useState<string[]>([]);
  const initialized = useRef(false);

  useEffect(() => {
    if (widgets.length > 0 && !initialized.current) {
      setActiveIds(widgets.filter((w) => w.active).map((w) => w.id));
      initialized.current = true;
    }
  }, [widgets]);

  const storeActiveIds = widgets.filter((w) => w.active).map((w) => w.id);
  const activeSet = new Set(activeIds);
  const storeSet = new Set(storeActiveIds);
  const isDirty =
    activeSet.size !== storeSet.size ||
    activeIds.some((id) => !storeSet.has(id)) ||
    storeActiveIds.some((id) => !activeSet.has(id));

  const toggleWidget = useCallback((id: string, active: boolean) => {
    setActiveIds((prev) =>
      active ? [...prev.filter((x) => x !== id), id] : prev.filter((x) => x !== id)
    );
  }, []);

  const toggleCategory = useCallback(
    (_slug: string, enable: boolean, categoryWidgetIds: string[]) => {
      setActiveIds((prev) => {
        const rest = prev.filter((id) => !categoryWidgetIds.includes(id));
        if (enable) {
          return [...new Set([...rest, ...categoryWidgetIds])];
        }
        return rest;
      });
    },
    []
  );

  const enableAll = useCallback((allWidgetIds: string[]) => {
    setActiveIds([...allWidgetIds]);
  }, []);

  const save = useCallback(async () => {
    try {
      await updateWidgets(activeIds);
      addToast({
        type: "success",
        title: "Widgets Saved Successfully!",
        message: "Your widget settings have been applied.",
      });
    } catch {
      addToast({
        type: "error",
        title: "Error Saving Widgets",
        message: "Please try again.",
      });
    }
  }, [activeIds, updateWidgets, addToast]);

  const value: WidgetsStateContextValue = {
    activeIds,
    isDirty,
    isSaving: isResolving,
    toggleWidget,
    toggleCategory,
    enableAll,
    save,
  };

  return (
    <WidgetsStateContext.Provider value={value}>
      {children}
    </WidgetsStateContext.Provider>
  );
}

export function useWidgetsState(): WidgetsStateContextValue {
  const ctx = useContext(WidgetsStateContext);
  if (!ctx) {
    throw new Error("useWidgetsState must be used within WidgetsStateProvider");
  }
  return ctx;
}
