export type ExtensionBadge = "popular" | "updated" | "new";

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
