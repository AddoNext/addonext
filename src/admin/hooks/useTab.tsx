import { useState } from "react";
import type { Tab } from "../components/Tab/Tab";

/**
 * useTab hook for navigating between tabs.
 * @param tabsArray Array of tab objects with label, slug, selected, and screen.
 * @returns { currentTab, setTab, tabs }
 */
function useTab(tabsArray: Tab[]) {
  // Find the first tab with selected: true, or default to the first tab
  const getDefaultTab = () => {
    return tabsArray.find(tab => tab.selected) || tabsArray[0];
  };

  const [currentSlug, setCurrentSlug] = useState(() => getDefaultTab()?.slug);

  const setTab = (slug: string) => {
    if (tabsArray.some(tab => tab.slug === slug)) {
      setCurrentSlug(slug);
    }
  };

  const currentTab = tabsArray.find(tab => tab.slug === currentSlug) || tabsArray[0];

  return {
    currentTab,
    setTab,
    tabs: tabsArray,
  };
}

export default useTab;
