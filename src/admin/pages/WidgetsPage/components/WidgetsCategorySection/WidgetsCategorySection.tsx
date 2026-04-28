import React from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faLayerGroup, faPalette, faBullhorn, faChartPie, faCartShopping } from "@fortawesome/free-solid-svg-icons";
import classNames from "classnames";
import WidgetCard from "../WidgetCard/WidgetCard";
import type { Widget, Category } from "../../../../../store/types";
import styles from "./WidgetsCategorySection.module.scss";

const CATEGORY_ICONS: Record<string, typeof faLayerGroup> = {
  content: faLayerGroup,
  creative: faPalette,
  marketing: faBullhorn,
  data: faChartPie,
  woocommerce: faCartShopping,
};

interface WidgetsCategorySectionProps {
  category: Category;
  widgets: Widget[];
  onToggle: (id: string, checked: boolean) => void;
  onToggleAll: (slug: string, enable: boolean) => void;
  onLockedUpgrade?: () => void;
  onLockedToggleAttempt?: (id: string) => void;
  previewLockedWidgetId?: string | null;
}

const WidgetsCategorySection: React.FC<WidgetsCategorySectionProps> = ({
  category,
  widgets,
  onToggle,
  onToggleAll,
  onLockedUpgrade,
  onLockedToggleAttempt,
  previewLockedWidgetId,
}) => {
  if (widgets.length === 0) return null;

  const icon = CATEGORY_ICONS[category.slug];
  const unlocked = widgets.filter((w) => !w.is_locked);
  const allActive = unlocked.length > 0 && unlocked.every((w) => w.active);

  return (
    <section className={styles.section}>
      <div className={styles.header}>
        <h2
          className={classNames(
            styles.title,
            category.slug === "content" && styles.titleContent,
            category.slug === "creative" && styles.titleCreative,
            category.slug === "marketing" && styles.titleMarketing,
            category.slug === "data" && styles.titleData,
            category.slug === "woocommerce" && styles.titleWooCommerce
          )}
        >
          {icon && <FontAwesomeIcon icon={icon} className={styles.icon} />}
          {category.title}
        </h2>
        <button
          type="button"
          className={styles.toggleAll}
          onClick={() => onToggleAll(category.slug, !allActive)}
        >
          {allActive ? "Deactivate All" : "Activate All"}
        </button>
      </div>
      <div className={styles.grid}>
        {widgets.map((widget) => (
          <WidgetCard
            key={widget.id}
            widget={widget}
            onToggle={onToggle}
            onLockedUpgrade={onLockedUpgrade}
            onLockedToggleAttempt={onLockedToggleAttempt}
            lockedTogglePreview={previewLockedWidgetId === widget.id}
          />
        ))}
      </div>
    </section>
  );
};

export default WidgetsCategorySection;
