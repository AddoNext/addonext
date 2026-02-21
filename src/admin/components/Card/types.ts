export type CardTheme = 'light' | 'dark';

export interface CardProps {
  theme?: CardTheme;
  title?: string;
  description?: string;
  icon?: React.ReactNode;
  action?: React.ReactNode;
  children?: React.ReactNode;
  className?: string;
}
