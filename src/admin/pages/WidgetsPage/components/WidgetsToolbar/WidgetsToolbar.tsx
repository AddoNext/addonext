import React from "react";
import WidgetsSearch from "../WidgetsSearch/WidgetsSearch";
import WidgetsFilterTabs from "../WidgetsFilterTabs/WidgetsFilterTabs";
import type { WidgetFilter } from "../../types";
import styles from "./WidgetsToolbar.module.scss";

interface WidgetsToolbarProps {
  searchValue: string;
  onSearchChange: (value: string) => void;
  filter: WidgetFilter;
  onFilterChange: (value: WidgetFilter) => void;
  activeCount: number;
  totalCount: number;
  onEnableAll: () => void;
}

const WidgetsToolbar: React.FC<WidgetsToolbarProps> = ({
  searchValue,
  onSearchChange,
  filter,
  onFilterChange,
  activeCount,
  totalCount,
  onEnableAll,
}) => (
  <div className={styles.toolbar}>
    <div className={styles.row}>
      <div className={styles.searchFilterRow}>
        <WidgetsSearch value={searchValue} onChange={onSearchChange} />
        <WidgetsFilterTabs value={filter} onChange={onFilterChange} />
      </div>
      <div className={styles.statsRow}>
        <div className={styles.stats}>
          <span className={styles.statsActive}>{activeCount} Active</span>
          <span className={styles.statsDivider}>|</span>
          <span>{totalCount} Total Widgets</span>
        </div>
        <button type="button" className={styles.enableAllBtn} onClick={onEnableAll}>
          Enable All
        </button>
      </div>
    </div>
  </div>
);

export default WidgetsToolbar;
