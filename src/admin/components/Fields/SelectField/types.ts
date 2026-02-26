export interface SelectFieldOption {
  value: string;
  label: string;
}

export interface SelectFieldProps {
  label: string;
  description?: string;
  value: string;
  onChange: (value: string) => void;
  options: SelectFieldOption[];
}
