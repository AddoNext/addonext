export interface SettingsContextValue {
  values: Record<string, unknown>;
  initialValues: Record<string, unknown>;
  isLoading: boolean;
  isSaving: boolean;
  isDirty: boolean;
  setValue: (key: string, value: unknown) => void;
  save: () => Promise<void>;
}
