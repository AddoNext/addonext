export interface ToggleSwitchProps extends Omit<React.InputHTMLAttributes<HTMLInputElement>, 'size' | 'type'> {
  checked?: boolean;
  onCheckedChange?: (checked: boolean) => void;
}
