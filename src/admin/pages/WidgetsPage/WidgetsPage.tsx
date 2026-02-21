import React from "react";
import Input from "../../components/Input/Input";
import styles from "./WidgetsPage.module.scss";

const WidgetsPage: React.FC = () => {
  return (
    <div className={styles.page}>
      <div className={styles.toolbar}>
        <div className={styles.searchRow}>
          <Input theme="light" placeholder="Search widgets..." />
        </div>
      </div>
      <div className={styles.content}>
        <p className={styles.placeholder}>Widgets screen content</p>
      </div>
    </div>
  );
};

export default WidgetsPage;
