import React from "react";
import useTab from "../../hooks/useTab";
import { Tab } from "./types";
import styles from "./Tab.module.scss";
import classNames from "classnames";

interface TabProps {
  tabs: Tab[];
}

const Tab: React.FC<TabProps> = ({ tabs }) => {
  const { currentTab, setTab } = useTab(tabs);

  return (
    <div className={styles.tabContainer}>
      <div className={styles.tabHeader}>
        {tabs.map(tab => (
          <button
            key={tab.slug}
            onClick={() => setTab(tab.slug)}
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
