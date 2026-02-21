export type InputTheme = 'light' | 'dark';

export interface InputProps extends Omit<React.InputHTMLAttributes<HTMLInputElement>, 'size'> {
  theme?: InputTheme;
  label?: string;
  error?: string;
}
