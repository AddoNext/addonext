export type ToastType = "success" | "error";

export const DEFAULT_TOAST_DURATION = 3000;

export interface ToastProps {
  id: string;
  type: ToastType;
  title: string;
  message: string;
  duration?: number;
  onClose: (id: string) => void;
}

export interface ToastOptions {
  type: ToastType;
  title: string;
  message: string;
  duration?: number;
}
