export interface HeaderProps {
  title?: string;
  subtitle?: string;
  theme?: "light" | "dark";
  onThemeToggle?: () => void;
  onExportSettings?: () => void;
  onSaveChanges?: () => void;
}
