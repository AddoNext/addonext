import React from "react";
import classNames from "classnames";
import type { WidgetFilter } from "../../types";
import styles from "./WidgetsFilterTabs.module.scss";

interface WidgetsFilterTabsProps {
  value: WidgetFilter;
  onChange: (value: WidgetFilter) => void;
}

const TABS: { value: WidgetFilter; label: string }[] = [
  { value: "all", label: "All" },
  { value: "active", label: "Active" },
  { value: "inactive", label: "Inactive" },
];

const WidgetsFilterTabs: React.FC<WidgetsFilterTabsProps> = ({ value, onChange }) => (
  <div className={styles.tabs}>
    {TABS.map((tab) => (
      <button
        key={tab.value}
        type="button"
        className={classNames(styles.tab, value === tab.value && styles.tabActive)}
        onClick={() => onChange(tab.value)}
      >
        {tab.label}
      </button>
    ))}
  </div>
);

export default WidgetsFilterTabs;
