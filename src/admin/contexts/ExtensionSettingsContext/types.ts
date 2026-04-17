import type { ExtensionItem } from "../../pages/ExtensionsPage/types";

export interface ExtensionSettingsContextValue {
  openSettingsModal: (extensionId: string) => void;
  closeSettingsModal: () => void;
  saveSettingsModal: () => Promise<void>;
  setSettingsField: (fieldId: string, value: unknown) => void;
  modalExtensionId: string | null;
  modalDraft: Record<string, unknown>;
  modalSaving: boolean;
  modalExtension: ExtensionItem | null;
}

export interface ExtensionSettingsProviderProps {
  children: React.ReactNode;
  extensions: ExtensionItem[];
  onSettingsSaved: (extensionId: string, settings: Record<string, unknown>) => void;
}
