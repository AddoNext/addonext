import React, { useMemo, useState, useCallback } from "react";
import { useSelect, useDispatch } from "@wordpress/data";
import WidgetsToolbar from "./components/WidgetsToolbar/WidgetsToolbar";
import WidgetsCategorySection from "./components/WidgetsCategorySection/WidgetsCategorySection";
import { STORE_NAME } from "../../../store/admin";
import type { Widget } from "../../../store/types";
import type { WidgetFilter } from "./types";
import styles from "./WidgetsPage.module.scss";

const WidgetsPage: React.FC = () => {
  const [searchQuery, setSearchQuery] = useState("");
  const [filter, setFilter] = useState<WidgetFilter>("all");

  const { widgets, categories } = useSelect((select) => {
    const store = select(STORE_NAME) as {
      getWidgets: () => Widget[];
      getCategories: () => Record<string, { title: string; icon: string }>;
    };
    return {
      widgets: store.getWidgets() ?? [],
      categories: store.getCategories() ?? {},
    };
  }, []);

  const { updateWidgets } = useDispatch(STORE_NAME) as {
    updateWidgets: (ids: string[]) => void;
  };

  const filteredWidgets = useMemo(() => {
    let list = widgets;
    const q = searchQuery.trim().toLowerCase();
    if (q) {
      list = list.filter((w) => w.title.toLowerCase().includes(q));
    }
    if (filter === "active") {
      list = list.filter((w) => w.active);
    } else if (filter === "inactive") {
      list = list.filter((w) => !w.active);
    }
    return list;
  }, [widgets, searchQuery, filter]);

  const widgetsByCategory = useMemo(() => {
    const map: Record<string, Widget[]> = {};
    for (const slug of Object.keys(categories)) {
      map[slug] = filteredWidgets.filter((w) => w.admin_category === slug);
    }
    return map;
  }, [categories, filteredWidgets]);

  const activeCount = widgets.filter((w) => w.active).length;
  const totalCount = widgets.length;

  const toggleWidget = useCallback(
    (id: string, checked: boolean) => {
      const currentActive = widgets.filter((w) => w.active).map((w) => w.id);
      const next = checked
        ? [...currentActive, id].filter(Boolean)
        : currentActive.filter((x) => x !== id);
      updateWidgets(next);
    },
    [widgets, updateWidgets]
  );

  const toggleCategory = useCallback(
    (slug: string, enable: boolean) => {
      const inCategory = (widgetsByCategory[slug] ?? []).map((w) => w.id);
      const rest = widgets.filter((w) => w.active && !inCategory.includes(w.id)).map((w) => w.id);
      const next = enable ? [...new Set([...rest, ...inCategory])] : rest;
      updateWidgets(next);
    },
    [widgetsByCategory, widgets, updateWidgets]
  );

  const enableAll = useCallback(() => {
    updateWidgets(widgets.map((w) => w.id));
  }, [widgets, updateWidgets]);

  return (
    <div className={styles.page}>
      <WidgetsToolbar
        searchValue={searchQuery}
        onSearchChange={setSearchQuery}
        filter={filter}
        onFilterChange={setFilter}
        activeCount={activeCount}
        totalCount={totalCount}
        onEnableAll={enableAll}
      />

      <div className={styles.content}>
        {Object.keys(categories).map((slug) => (
          <WidgetsCategorySection
            key={slug}
            category={{ slug, ...categories[slug] }}
            widgets={widgetsByCategory[slug] ?? []}
            onToggle={toggleWidget}
            onToggleAll={toggleCategory}
          />
        ))}
        {filteredWidgets.length === 0 && (
          <p className={styles.placeholder}>
            {searchQuery || filter !== "all" ? "No widgets match your filters" : "No widgets found"}
          </p>
        )}
      </div>
    </div>
  );
};

export default WidgetsPage;
