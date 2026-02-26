export interface RadioFieldOption {
  value: string;
  label: string;
}

export interface RadioFieldProps {
  label: string;
  name: string;
  value: string;
  onChange: (value: string) => void;
  options: RadioFieldOption[];
}
