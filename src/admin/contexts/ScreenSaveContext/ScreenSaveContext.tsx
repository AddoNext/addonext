import React, { createContext, useCallback, useContext, useRef, useState } from "react";
import type { ScreenSaveContextValue, ScreenSaveHandlerGetter } from "./types";

const ScreenSaveContext = createContext<ScreenSaveContextValue | null>(null);

export function ScreenSaveProvider({
  children,
  defaultSlug,
}: {
  children: React.ReactNode;
  defaultSlug: string;
}) {
  const [currentScreenSlug, setCurrentScreenSlug] = useState(defaultSlug);
  const handlersRef = useRef<Record<string, ScreenSaveHandlerGetter>>({});
  const [, forceUpdate] = useState(0);

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
