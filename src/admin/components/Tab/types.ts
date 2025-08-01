export interface Tab {
  label: string;
  slug: string;
  selected?: boolean;
  screen: React.ReactNode;
} 