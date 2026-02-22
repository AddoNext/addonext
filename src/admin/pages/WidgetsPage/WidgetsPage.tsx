import React, { useCallback, useMemo, useState } from "react";
import { useSelect } from "@wordpress/data";
import WidgetsToolbar from "./components/WidgetsToolbar/WidgetsToolbar";
import WidgetsCategorySection from "./components/WidgetsCategorySection/WidgetsCategorySection";
import { STORE_NAME } from "../../../store/admin";
import { useWidgetsState } from "../../contexts/WidgetsStateContext/WidgetsStateContext";
import { useScreenSave } from "../../contexts/ScreenSaveContext/ScreenSaveContext";
import type { Widget } from "../../../store/types";
import type { WidgetFilter } from "./types";
import styles from "./WidgetsPage.module.scss";

const WidgetsPage: React.FC = () => {
  const [searchQuery, setSearchQuery] = useState("");
  const [filter, setFilter] = useState<WidgetFilter>("all");

  const widgetsState = useWidgetsState();
  const getSaveHandler = useCallback(
    () => ({
      save: widgetsState.save,
      isDirty: widgetsState.isDirty,
      isSaving: widgetsState.isSaving,
    }),
    [widgetsState.save, widgetsState.isDirty, widgetsState.isSaving]
  );
  useScreenSave("widgets", getSaveHandler);

  const { widgets, categories } = useSelect(
    (select) => {
      const store = select(STORE_NAME) as {
        getWidgets: () => Widget[];
        getCategories: () => Record<string, { title: string; icon: string }>;
      };
      return {
        widgets: store.getWidgets() ?? [],
        categories: store.getCategories() ?? {},
      };
    },
    []
  );

  const { activeIds, toggleWidget, toggleCategory, enableAll } = widgetsState;

  const effectiveWidgets = useMemo(
    () =>
      widgets.map((w) => ({
        ...w,
        active: activeIds.includes(w.id),
      })),
    [widgets, activeIds]
  );

  const filteredWidgets = useMemo(() => {
    let list = effectiveWidgets;
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
  }, [effectiveWidgets, searchQuery, filter]);

  const widgetsByCategory = useMemo(() => {
    const map: Record<string, Widget[]> = {};
    for (const slug of Object.keys(categories)) {
      map[slug] = filteredWidgets.filter((w) => w.admin_category === slug);
    }
    return map;
  }, [categories, filteredWidgets]);

  const activeCount = activeIds.length;
  const totalCount = widgets.length;

  const handleToggleCategory = (slug: string, enable: boolean) => {
    const categoryWidgetIds = (widgetsByCategory[slug] ?? []).map((w) => w.id);
    toggleCategory(slug, enable, categoryWidgetIds);
  };

  return (
    <div className={styles.page}>
      <WidgetsToolbar
        searchValue={searchQuery}
        onSearchChange={setSearchQuery}
        filter={filter}
        onFilterChange={setFilter}
        activeCount={activeCount}
        totalCount={totalCount}
        onEnableAll={() => enableAll(widgets.map((w) => w.id))}
      />

      <div className={styles.content}>
        {Object.keys(categories).map((slug) => (
          <WidgetsCategorySection
            key={slug}
            category={{ slug, ...categories[slug] }}
            widgets={widgetsByCategory[slug] ?? []}
            onToggle={toggleWidget}
            onToggleAll={handleToggleCategory}
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
