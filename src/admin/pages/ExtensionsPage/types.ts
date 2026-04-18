export type ExtensionBadge = "popular" | "updated" | "new";

export interface ExtensionFieldOption {
  value: string;
  label: string;
}

export interface ExtensionFieldShowWhen {
  field: string;
  value: unknown;
}

export interface ExtensionFieldShowWhenIn {
  field: string;
  values: unknown[];
}

export interface ExtensionField {
  id: string;
  type: "toggle" | "select" | "checkbox_group" | "text" | "color" | "range";
  label: string;
  description?: string;
  default?: unknown;
  options?: ExtensionFieldOption[];
  placeholder?: string;
  min?: number;
  max?: number;
  step?: number;
  unit?: string;
  show_when?: ExtensionFieldShowWhen;
  show_when_in?: ExtensionFieldShowWhenIn;
}

export interface ExtensionItem {
  id: string;
  title: string;
  description: string;
  isEnabled: boolean;
  isLocked: boolean;
  badge: ExtensionBadge | null;
  docUrl: string;
  demoUrl: string;
  hasSettings: boolean;
  fields: ExtensionField[];
  settings: Record<string, unknown>;
}

export interface ExtensionsGetResponse {
  success: boolean;
  extensions: ExtensionItem[];
}

export interface ExtensionsUpdateResponse {
  success: boolean;
  message?: string;
  extensions?: ExtensionItem[];
}

export interface ExtensionSettingsSaveResponse {
  success: boolean;
  message?: string;
  id?: string;
  settings?: Record<string, unknown>;
}
