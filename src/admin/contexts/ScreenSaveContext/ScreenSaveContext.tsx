import React, { createContext, useCallback, useContext, useEffect, useRef, useState } from "react";
import type { ScreenSaveContextValue, ScreenSaveHandlerGetter } from "./types";

const TAB_PARAM = "tab";

function getTabFromUrl(): string | null {
  if (typeof window === "undefined") return null;
  const params = new URLSearchParams(window.location.search);
  return params.get(TAB_PARAM);
}

function pushTabToUrl(slug: string): void {
  if (typeof window === "undefined") return;
  const params = new URLSearchParams(window.location.search);
  params.set(TAB_PARAM, slug);
  const url = `${window.location.pathname}?${params.toString()}${window.location.hash ? window.location.hash : ""}`;
  window.history.pushState({ tab: slug }, "", url);
}

const ScreenSaveContext = createContext<ScreenSaveContextValue | null>(null);

export function ScreenSaveProvider({
  children,
  defaultSlug,
}: {
  children: React.ReactNode;
  defaultSlug: string;
}) {
  const [currentScreenSlug, setCurrentScreenSlugState] = useState(() => {
    const tab = getTabFromUrl();
    return tab ?? defaultSlug;
  });
  const handlersRef = useRef<Record<string, ScreenSaveHandlerGetter>>({});
  const [, forceUpdate] = useState(0);

  useEffect(() => {
    if (getTabFromUrl() === null) {
      const params = new URLSearchParams(window.location.search);
      params.set(TAB_PARAM, currentScreenSlug);
      const url = `${window.location.pathname}?${params.toString()}${window.location.hash ? window.location.hash : ""}`;
      window.history.replaceState({ tab: currentScreenSlug }, "", url);
    }
  }, []);

  useEffect(() => {
    const onPopState = () => {
      const tab = getTabFromUrl();
      if (tab) setCurrentScreenSlugState(tab);
    };
    window.addEventListener("popstate", onPopState);
    return () => window.removeEventListener("popstate", onPopState);
  }, []);

  const setCurrentScreenSlug = useCallback((slug: string) => {
    pushTabToUrl(slug);
    setCurrentScreenSlugState(slug);
  }, []);

  const registerSaveHandler = useCallback((slug: string, getHandler: ScreenSaveHandlerGetter) => {
    handlersRef.current[slug] = getHandler;
    forceUpdate((n) => n + 1);
  }, []);

  const unregisterSaveHandler = useCallback((slug: string) => {
    delete handlersRef.current[slug];
    forceUpdate((n) => n + 1);
  }, []);

  const getSaveState = useCallback((slug: string) => {
    const getter = handlersRef.current[slug];
    return getter ? getter() : null;
  }, []);

  const value: ScreenSaveContextValue = {
    currentScreenSlug,
    setCurrentScreenSlug,
    registerSaveHandler,
    unregisterSaveHandler,
    getSaveState,
  };

  return (
    <ScreenSaveContext.Provider value={value}>
      {children}
    </ScreenSaveContext.Provider>
  );
}

export function useScreenSaveContext(): ScreenSaveContextValue {
  const ctx = useContext(ScreenSaveContext);
  if (!ctx) {
    throw new Error("useScreenSaveContext must be used within ScreenSaveProvider");
  }
  return ctx;
}

export function useScreenSave(slug: string, getHandler: ScreenSaveHandlerGetter) {
  const { registerSaveHandler, unregisterSaveHandler } = useScreenSaveContext();

  React.useEffect(() => {
    registerSaveHandler(slug, getHandler);
    return () => unregisterSaveHandler(slug);
  }, [slug, registerSaveHandler, unregisterSaveHandler, getHandler]);
}
