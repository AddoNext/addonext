export interface ConfirmOptions {
  title: string;
  message: string;
  confirmLabel?: string;
  cancelLabel?: string;
  onConfirm: () => void;
}

export interface ConfirmContextValue {
  confirm: (options: ConfirmOptions) => void;
}
