import React, { useCallback } from "react";
import { useScreenSaveContext } from "../../contexts/ScreenSaveContext/ScreenSaveContext";
import type { Tab as TabItem } from "./types";
import styles from "./Tab.module.scss";
import classNames from "classnames";

interface TabProps {
  tabs: TabItem[];
}

const Tab: React.FC<TabProps> = ({ tabs }) => {
  const { currentScreenSlug, setCurrentScreenSlug } = useScreenSaveContext();
  const currentTab = tabs.find((t) => t.slug === currentScreenSlug) ?? tabs[0];
  const handleTabChange = useCallback(
    (slug: string) => {
      if (slug === currentScreenSlug) {
        return;
      }

      setCurrentScreenSlug(slug);
      window.scrollTo({ top: 0, left: 0, behavior: "auto" });
      document.documentElement.scrollTop = 0;
      document.body.scrollTop = 0;
    },
    [currentScreenSlug, setCurrentScreenSlug]
  );

  return (
    <div className={styles.tabContainer}>
      <div className={styles.tabHeader}>
        {tabs.map((tab) => (
          <button
            key={tab.slug}
            onClick={() => handleTabChange(tab.slug)}
            className={classNames(styles.tabIndex, {
              [styles.active]: currentTab.slug === tab.slug,
            })}
          >
            {tab.label}
          </button>
        ))}
      </div>
      <div className={styles.tabBody}>
        {currentTab.screen}
      </div>
    </div>
  );
};

export default Tab;
export type { Tab } from "./types";
