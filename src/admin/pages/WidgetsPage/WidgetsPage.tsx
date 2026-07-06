import React, { useCallback, useEffect, useMemo, useState } from "react";
import { useDispatch, useSelect } from "@wordpress/data";
import LoadingSpinner from "../../components/LoadingSpinner/LoadingSpinner";
import WidgetsToolbar from "./components/WidgetsToolbar/WidgetsToolbar";
import WidgetsCategorySection from "./components/WidgetsCategorySection/WidgetsCategorySection";
import UpgradeToProModal from "../../components/UpgradeToProModal/UpgradeToProModal";
import { STORE_NAME } from "../../../store/admin";
import { useWidgetsState } from "../../contexts/WidgetsStateContext/WidgetsStateContext";
import { useScreenSave } from "../../contexts/ScreenSaveContext/ScreenSaveContext";
import type { LicenseData, Widget } from "../../../store/types";
import type { WidgetFilter } from "./types";
import { readHasProAccountApi } from "../../utils/adminData";
import styles from "./WidgetsPage.module.scss";

const WidgetsPage: React.FC = () => {
  const hasProAccountApi = readHasProAccountApi();
  const [searchQuery, setSearchQuery] = useState("");
  const [filter, setFilter] = useState<WidgetFilter>("all");
  const [upgradeOpen, setUpgradeOpen] = useState(false);
  const [previewLockedWidgetId, setPreviewLockedWidgetId] = useState<string | null>(null);

  const { fetchLicense } = useDispatch(STORE_NAME) as { fetchLicense: () => Promise<void> };

  useEffect(() => {
    if (!hasProAccountApi) {
      return;
    }

    void fetchLicense();
  }, [fetchLicense, hasProAccountApi]);

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

  const { widgets, categories, widgetsResolving, categoriesResolving, licenseData } = useSelect(
    (select) => {
      const store = select(STORE_NAME) as {
        getWidgets: () => Widget[];
        getCategories: () => Record<string, { title: string; icon: string }>;
        getIsResolving: () => boolean;
        getCategoriesResolving: () => boolean;
        getLicenseData: () => LicenseData | null;
      };
      return {
        widgets: store.getWidgets() ?? [],
        categories: store.getCategories() ?? {},
        widgetsResolving: store.getIsResolving(),
        categoriesResolving: store.getCategoriesResolving(),
        licenseData: store.getLicenseData(),
      };
    },
    []
  );

  const awaitingInitialData =
    (widgetsResolving && widgets.length === 0) ||
    (categoriesResolving && Object.keys(categories).length === 0);

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
      map[slug] = filteredWidgets.filter((w) => w.category === slug);
    }
    return map;
  }, [categories, filteredWidgets]);

  const activeCount = activeIds.length;
  const totalCount = widgets.length;

  const handleToggleCategory = (slug: string, enable: boolean) => {
    const list = widgetsByCategory[slug] ?? [];
    const categoryWidgetIds = enable ? list.filter((w) => !w.is_locked).map((w) => w.id) : list.map((w) => w.id);
    toggleCategory(slug, enable, categoryWidgetIds);
  };

  const handleLockedToggleAttempt = useCallback(
    (widgetId: string) => {
      if (!hasProAccountApi) {
        return;
      }

      setPreviewLockedWidgetId(widgetId);
      setUpgradeOpen(true);
    },
    [hasProAccountApi]
  );

  const handleLockedUpgrade = useCallback(() => {
    if (!hasProAccountApi) {
      return;
    }

    setUpgradeOpen(true);
  }, [hasProAccountApi]);

  const handleUpgradeModalClose = useCallback(() => {
    setUpgradeOpen(false);
    setPreviewLockedWidgetId(null);
  }, []);

  return (
    <div className={styles.page}>
      {awaitingInitialData ? (
        <div className={styles.loadingWrap}>
          <LoadingSpinner screenReaderLabel="Loading widgets" />
        </div>
      ) : (
        <>
          <WidgetsToolbar
            searchValue={searchQuery}
            onSearchChange={setSearchQuery}
            filter={filter}
            onFilterChange={setFilter}
            activeCount={activeCount}
            totalCount={totalCount}
            onEnableAll={() => enableAll(widgets.filter((w) => !w.is_locked).map((w) => w.id))}
          />

          <div className={styles.content}>
            {Object.keys(categories).map((slug) => (
              <WidgetsCategorySection
                key={slug}
                category={{ slug, ...categories[slug] }}
                widgets={widgetsByCategory[slug] ?? []}
                onToggle={toggleWidget}
                onToggleAll={handleToggleCategory}
                onLockedUpgrade={hasProAccountApi ? handleLockedUpgrade : undefined}
                onLockedToggleAttempt={hasProAccountApi ? handleLockedToggleAttempt : undefined}
                previewLockedWidgetId={previewLockedWidgetId}
              />
            ))}
            {filteredWidgets.length === 0 && (
              <p className={styles.placeholder}>
                {searchQuery || filter !== "all" ? "No widgets match your filters" : "No widgets found"}
              </p>
            )}
          </div>
        </>
      )}

      {hasProAccountApi && (
        <UpgradeToProModal
          open={upgradeOpen}
          onClose={handleUpgradeModalClose}
          upgradeUrl={licenseData?.upgrade_url?.trim() ?? ""}
        />
      )}
    </div>
  );
};

export default WidgetsPage;
