import React, { useEffect } from "react";
import { useDispatch, useSelect } from "@wordpress/data";
import LoadingSpinner from "../../components/LoadingSpinner/LoadingSpinner";
import UpgradeOfferScreen from "../../components/UpgradeOfferScreen/UpgradeOfferScreen";
import { STORE_NAME } from "../../../store/admin";
import type { LicenseData } from "../../../store/types";
import styles from "./TemplatesPage.module.scss";

const TemplatesPage: React.FC = () => {
  const { fetchLicense } = useDispatch(STORE_NAME) as { fetchLicense: () => Promise<void> };

  useEffect(() => {
    void fetchLicense();
  }, [fetchLicense]);

  const { licenseData, licenseResolving } = useSelect(
    (select) => {
      const store = select(STORE_NAME) as {
        getLicenseData: () => LicenseData | null;
        getLicenseResolving: () => boolean;
      };
      return {
        licenseData: store.getLicenseData(),
        licenseResolving: store.getLicenseResolving(),
      };
    },
    []
  );

  const awaitingLicense = licenseResolving && licenseData === null;
  const isPro = licenseData?.has_pro_plan === true;

  return (
    <div className={styles.page}>
      {awaitingLicense ? (
        <div className={styles.loadingWrap}>
          <LoadingSpinner screenReaderLabel="Loading license" />
        </div>
      ) : isPro ? (
        <p className={styles.placeholder}>Templates screen</p>
      ) : (
        <UpgradeOfferScreen
          title="Templates are a Pro feature"
          description="Upgrade to import and manage premium Elementor templates from Elementrify."
          upgradeUrl={licenseData?.upgrade_url?.trim() ?? ""}
        />
      )}
    </div>
  );
};

export default TemplatesPage;
