export interface ScreenSaveHandler {
  save: () => Promise<void>;
  isDirty: boolean;
  isSaving: boolean;
}

export type ScreenSaveHandlerGetter = () => ScreenSaveHandler;

export interface ScreenSaveContextValue {
  currentScreenSlug: string;
  setCurrentScreenSlug: (slug: string) => void;
  registerSaveHandler: (slug: string, getHandler: ScreenSaveHandlerGetter) => void;
  unregisterSaveHandler: (slug: string) => void;
  getSaveState: (slug: string) => ScreenSaveHandler | null;
}
