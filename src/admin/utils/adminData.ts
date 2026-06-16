declare global {
  interface Window {
    veloriaAdminData?: {
      isElementorActive?: boolean | string | number;
      hasProAccountApi?: boolean | string | number;
    };
  }
}

export function readLocalizedBoolean(value: unknown): boolean {
  return value === true || value === 1 || value === "1" || value === "true";
}

export function readHasProAccountApi(): boolean {
  if (typeof window === "undefined") {
    return false;
  }

  return readLocalizedBoolean(window.veloriaAdminData?.hasProAccountApi);
}

export function readElementorActive(): boolean {
  if (typeof window === "undefined" || typeof document === "undefined") {
    return true;
  }

  const localized = window.veloriaAdminData?.isElementorActive;
  if (typeof localized === "boolean") {
    return localized;
  }
  if (readLocalizedBoolean(localized)) {
    return true;
  }

  const root = document.getElementById("veloria-admin");
  const attr = root?.getAttribute("data-elementor-active");
  if (attr === "1") {
    return true;
  }
  if (attr === "0") {
    return false;
  }

  return false;
}
