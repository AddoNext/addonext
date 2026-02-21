export interface HeaderProps {
  title?: string;
  subtitle?: string;
  onThemeToggle?: () => void;
  onExportSettings?: () => void;
  onSaveChanges?: () => void;
}
