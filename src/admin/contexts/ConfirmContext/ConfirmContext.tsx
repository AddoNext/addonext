import React, { createContext, useCallback, useContext, useState } from "react";
import { createPortal } from "react-dom";
import ConfirmModal from "../../components/ConfirmModal/ConfirmModal";
import type { ConfirmContextValue, ConfirmOptions } from "./types";

const ConfirmContext = createContext<ConfirmContextValue | null>(null);

export function ConfirmProvider({ children }: { children: React.ReactNode }) {
  const [state, setState] = useState<ConfirmOptions | null>(null);

  const confirm = useCallback((options: ConfirmOptions) => {
    setState(options);
  }, []);

  const handleConfirm = useCallback(() => {
    state?.onConfirm();
    setState(null);
  }, [state]);

  const handleCancel = useCallback(() => {
    setState(null);
  }, []);

  const modal =
    typeof document !== "undefined" && state ? (
      createPortal(
        <ConfirmModal
          open
          title={state.title}
          message={state.message}
          confirmLabel={state.confirmLabel}
          cancelLabel={state.cancelLabel}
          onConfirm={handleConfirm}
          onCancel={handleCancel}
        />,
        document.body
      )
    ) : null;

  const value: ConfirmContextValue = { confirm };

  return (
    <ConfirmContext.Provider value={value}>
      {children}
      {modal}
    </ConfirmContext.Provider>
  );
}

export function useConfirm(): ConfirmContextValue {
  const ctx = useContext(ConfirmContext);
  if (!ctx) {
    throw new Error("useConfirm must be used within ConfirmProvider");
  }
  return ctx;
}
