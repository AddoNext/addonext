export interface WidgetsStateContextValue {
  activeIds: string[];
  isDirty: boolean;
  isSaving: boolean;
  toggleWidget: (id: string, active: boolean) => void;
  toggleCategory: (slug: string, enable: boolean, categoryWidgetIds: string[]) => void;
  enableAll: (allWidgetIds: string[]) => void;
  save: () => Promise<void>;
}
