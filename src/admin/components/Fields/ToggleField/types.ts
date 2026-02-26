export interface ToggleFieldProps {
  label: string;
  description?: string;
  value: boolean;
  onChange: (value: boolean) => void;
}
