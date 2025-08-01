import { useCallback, useMemo } from "react";

export interface Route {
  slug: string;
  screen: React.ReactNode;
}

function getPageParam(): string | null {
  if (typeof window === "undefined") return null;
  const params = new URLSearchParams(window.location.search);
  return params.get("page");
}

export const useRoute = (routes: Route[]) => {
  const pageSlug = getPageParam();
  const currentRoute = useMemo(() => {
    return routes.find(route => route.slug === pageSlug) || routes[0];
  }, [routes, pageSlug]);

  const setRoute = useCallback((slug: string) => {
    if (typeof window === "undefined") return;
    const params = new URLSearchParams(window.location.search);
    params.set("page", slug);
    const newUrl = `${window.location.pathname}?${params.toString()}`;
    window.history.pushState({}, '', newUrl);
    // Optionally, you can trigger a re-render here if needed
  }, []);

  return {
    currentRoute,
    setRoute,
    routes,
  };
};
