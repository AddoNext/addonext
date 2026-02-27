import React, { createContext, useCallback, useContext, useState } from "react";
import { createPortal } from "react-dom";
import Toast from "../../components/Toast/Toast";
import type { ToastContextValue, ToastItem } from "./types";
import styles from "./ToastContext.module.scss";

const ToastContext = createContext<ToastContextValue | null>(null);

function generateId(): string {
  return `toast-${Date.now()}-${Math.random().toString(36).slice(2, 9)}`;
}

export function ToastProvider({ children }: { children: React.ReactNode }) {
  const [toasts, setToasts] = useState<ToastItem[]>([]);

  const addToast = useCallback(
    (options: {
      type: ToastItem["type"];
      title: string;
      message: string;
      duration?: number;
    }) => {
      setToasts((prev) => [...prev, { ...options, id: generateId() }]);
    },
    []
  );

  const removeToast = useCallback((id: string) => {
    setToasts((prev) => prev.filter((t) => t.id !== id));
  }, []);

  const value: ToastContextValue = { toasts, addToast, removeToast };

  const container =
    typeof document !== "undefined" ? (
      createPortal(
        <div className={styles.container} role="region" aria-label="Notifications">
          {toasts.map((t) => (
            <Toast
              key={t.id}
              id={t.id}
              type={t.type}
              title={t.title}
              message={t.message}
              duration={t.duration}
              onClose={removeToast}
            />
          ))}
        </div>,
        document.body
      )
    ) : null;

  return (
    <ToastContext.Provider value={value}>
      {children}
      {container}
    </ToastContext.Provider>
  );
}

export function useToast(): ToastContextValue {
  const ctx = useContext(ToastContext);
  if (!ctx) {
    throw new Error("useToast must be used within ToastProvider");
  }
  return ctx;
}
