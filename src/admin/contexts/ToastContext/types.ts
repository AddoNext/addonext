import type { ToastType } from "../../components/Toast/types";

export interface ToastItem {
  id: string;
  type: ToastType;
  title: string;
  message: string;
  duration?: number;
}

export interface ToastContextValue {
  toasts: ToastItem[];
  addToast: (options: {
    type: ToastType;
    title: string;
    message: string;
    duration?: number;
  }) => void;
  removeToast: (id: string) => void;
}
