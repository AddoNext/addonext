import React, { useEffect, useState, useCallback } from "react";
import { useDispatch, useSelect } from "@wordpress/data";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
  faShieldHalved,
  faCrown,
  faCalendar,
  faHeadset,
  faKey,
  faStar,
  faCopy,
  faArrowsRotate,
  faXmark,
  faCircleInfo,
  faLightbulb,
  faBook,
  faArrowUpRightFromSquare,
  faCheck,
  faCircleNotch,
} from "@fortawesome/free-solid-svg-icons";
import { STORE_NAME } from "../../../store/admin";
import type { LicenseData } from "../../../store/types";
import LoadingSpinner from "../../components/LoadingSpinner/LoadingSpinner";
import styles from "./LicensePage.module.scss";

const PREMIUM_FEATURES = [
  {
    title: "50+ Premium Widgets",
    description: "Advanced form builders, sliders, galleries and more",
  },
  {
    title: "Premium Templates",
    description: "200+ professionally designed page templates",
  },
  {
    title: "Priority Support",
    description: "24/7 dedicated support with faster response times",
  },
  {
    title: "Regular Updates",
    description: "Automatic updates with new features and improvements",
  },
  {
    title: "Unlimited Sites",
    description: "Use on unlimited websites with a single license",
  },
  {
    title: "White Label Option",
    description: "Remove branding and customize for your clients",
  },
];

const LicensePage: React.FC = () => {
  const [activationKey, setActivationKey] = useState("");

  const licenseData = useSelect((select) => (select(STORE_NAME) as { getLicenseData: () => LicenseData | null }).getLicenseData(), []);
  const licenseResolving = useSelect((select) => (select(STORE_NAME) as { getLicenseResolving: () => boolean }).getLicenseResolving(), []);
  const licenseError = useSelect((select) => (select(STORE_NAME) as { getLicenseError: () => string | null }).getLicenseError(), []);
  const licenseActivating = useSelect((select) => (select(STORE_NAME) as { getLicenseActivating: () => boolean }).getLicenseActivating(), []);
  const licenseDeactivating = useSelect((select) => (select(STORE_NAME) as { getLicenseDeactivating: () => boolean }).getLicenseDeactivating(), []);
  const licenseChecking = useSelect((select) => (select(STORE_NAME) as { getLicenseChecking: () => boolean }).getLicenseChecking(), []);

  const { fetchLicense, activateLicense, deactivateLicense, checkLicenseStatus } = useDispatch(STORE_NAME) as {
    fetchLicense: () => Promise<void>;
    activateLicense: (key: string) => Promise<{ success: boolean; message?: string }>;
    deactivateLicense: () => Promise<{ success: boolean; message?: string }>;
    checkLicenseStatus: () => Promise<void>;
  };

  useEffect(() => {
    if (licenseData === null && !licenseResolving) {
      fetchLicense();
    }
  }, [licenseData, licenseResolving, fetchLicense]);

  const handleCopyKey = useCallback(() => {
    if (!licenseData?.license_key_masked) return;
    navigator.clipboard.writeText(licenseData.license_key_masked);
  }, [licenseData?.license_key_masked]);

  const handleActivate = useCallback(async () => {
    const key = activationKey.trim();
    if (!key) return;
    const result = await activateLicense(key);
    if (result?.success) setActivationKey("");
  }, [activationKey, activateLicense]);

  const handleDeactivate = useCallback(async () => {
    await deactivateLicense();
  }, [deactivateLicense]);

  const handleCheckStatus = useCallback(() => {
    checkLicenseStatus();
  }, [checkLicenseStatus]);

  const statusPillClass =
    licenseData?.status_label === "Expired"
      ? styles.statusPillExpired
      : licenseData?.is_active
        ? styles.statusPill
        : styles.statusPillInactive;

  if (licenseResolving && licenseData === null) {
    return (
      <div className={styles.page}>
        <div className={styles.loadingWrap}>
          <LoadingSpinner screenReaderLabel="Loading license" />
        </div>
      </div>
    );
  }

  const data = licenseData ?? ({} as LicenseData);

  return (
    <div className={styles.page}>
      {licenseError && (
        <div className={styles.errorBanner} role="alert">
          {licenseError}
        </div>
      )}

      <section className={styles.section}>
        <div className={styles.sectionHeader}>
          <div>
            <h2 className={`${styles.sectionTitleLarge} ${styles.sectionTitleRow}`}>
              <FontAwesomeIcon icon={faShieldHalved} className={styles.iconGreen} />
              License Status
            </h2>
            <p className={styles.sectionSubtitle}>Manage your AddoNext license key and subscription</p>
          </div>
          <div className={statusPillClass}>
            {data.is_active && <span className={styles.statusDot} />}
            <span>{data.status_label ?? "Not activated"}</span>
          </div>
        </div>

        {(data.plan_title || data.expires_label || data.support_label) && (
          <div className={styles.cardsGrid}>
            <div className={styles.cardGreen}>
              <div className={styles.cardTitleRow}>
                <span className={styles.cardTitle}>License Type</span>
                <FontAwesomeIcon icon={faCrown} className={styles.iconGreen} />
              </div>
              <p className={styles.cardValue}>{data.plan_title || "—"}</p>
              <p className={styles.cardSublabel}>{data.sites_label || "—"}</p>
            </div>
            <div className={styles.cardBlue}>
              <div className={styles.cardTitleRow}>
                <span className={styles.cardTitleBlue}>Expires</span>
                <FontAwesomeIcon icon={faCalendar} className={styles.iconBlue} />
              </div>
              <p className={styles.cardValueBlue}>{data.expires_label || "—"}</p>
              <p className={styles.cardSublabelBlue}>{data.time_left_label || "—"}</p>
            </div>
            <div className={styles.cardPurple}>
              <div className={styles.cardTitleRow}>
                <span className={styles.cardTitlePurple}>Support</span>
                <FontAwesomeIcon icon={faHeadset} className={styles.iconPurple} />
              </div>
              <p className={styles.cardValuePurple}>{data.support_label || "—"}</p>
              <p className={styles.cardSublabelPurple}>{data.support_sublabel || "—"}</p>
            </div>
          </div>
        )}

        {(data.license_key_masked || data.is_active) && (
          <div className={styles.licenseKeyBlock}>
            <div className={styles.licenseKeyRow}>
              <div>
                <h4 className={styles.licenseKeyLabel}>License Key</h4>
                <div className={styles.licenseKeyInputGroup}>
                  <code className={styles.licenseKeyCode}>{data.license_key_masked ? data.license_key_masked : "—"}</code>
                  <button type="button" className={styles.btnCopy} onClick={handleCopyKey} aria-label="Copy license key">
                    <FontAwesomeIcon icon={faCopy} />
                  </button>
                </div>
              </div>
              <div className={styles.licenseKeyActions}>
                <button
                  type="button"
                  className={styles.btnSecondary}
                  onClick={handleCheckStatus}
                  disabled={licenseChecking}
                >
                  {licenseChecking ? (
                    <FontAwesomeIcon icon={faCircleNotch} spin />
                  ) : (
                    <FontAwesomeIcon icon={faArrowsRotate} />
                  )}{" "}
                  Check Status
                </button>
                {data.is_active && (
                  <button
                    type="button"
                    className={styles.btnDanger}
                    onClick={handleDeactivate}
                    disabled={licenseDeactivating}
                  >
                    {licenseDeactivating ? (
                      <FontAwesomeIcon icon={faCircleNotch} spin />
                    ) : (
                      <FontAwesomeIcon icon={faXmark} />
                    )}{" "}
                    Deactivate
                  </button>
                )}
              </div>
            </div>
          </div>
        )}
      </section>

      {!data.is_active && (
        <section className={`${styles.section} ${styles.activationSection}`}>
          <div className={`${styles.sectionTitleRow} ${styles.sectionTitleBlock}`}>
            <FontAwesomeIcon icon={faKey} className={styles.activationIcon} />
            <div>
              <h2 className={styles.sectionTitle}>License Activation</h2>
              <p className={styles.sectionSubtitle}>Enter your license key to activate premium features</p>
            </div>
          </div>
          <div className={styles.activationForm}>
            <label className={styles.activationLabel} htmlFor="license-key-input">
              License Key
            </label>
            <div className={styles.activationRow}>
              <input
                id="license-key-input"
                type="text"
                className={styles.activationInput}
                placeholder="ANXT-XXXX-XXXX-XXXX-XXXX"
                value={activationKey}
                onChange={(e) => setActivationKey(e.target.value)}
              />
              <button
                type="button"
                className={styles.btnActivate}
                onClick={handleActivate}
                disabled={licenseActivating || !activationKey.trim()}
              >
                {licenseActivating ? (
                  <FontAwesomeIcon icon={faCircleNotch} spin />
                ) : (
                  <FontAwesomeIcon icon={faKey} />
                )}{" "}
                Activate
              </button>
            </div>
            <p className={styles.activationHint}>
              You can find your license key in your purchase confirmation email or account dashboard
            </p>
          </div>
        </section>
      )}

      {!data.is_active && (
        <>
          <section className={styles.section}>
            <div className={`${styles.sectionTitleRow} ${styles.sectionTitleBlock}`}>
              <FontAwesomeIcon icon={faStar} className={styles.iconYellow} />
              <div>
                <h2 className={styles.sectionTitle}>Premium Features</h2>
                <p className={styles.sectionSubtitle}>Unlock powerful widgets and advanced functionality</p>
              </div>
            </div>
            <div className={styles.featuresGrid}>
              {PREMIUM_FEATURES.map((feature) => (
                <div key={feature.title} className={styles.featureCard}>
                  <div className={styles.featureCheck}>
                    <FontAwesomeIcon icon={faCheck} />
                  </div>
                  <div>
                    <h3 className={styles.featureTitle}>{feature.title}</h3>
                    <p className={styles.featureDesc}>{feature.description}</p>
                  </div>
                </div>
              ))}
            </div>
          </section>

          <section className={`${styles.section} ${styles.upgradeSection}`}>
            <div className={styles.upgradeInner}>
              <div className={styles.upgradeContent}>
                <h2 className={styles.upgradeTitle}>Upgrade Your License</h2>
                <p className={styles.upgradeSubtitle}>Get access to even more features with our Enterprise plan</p>
                <div className={styles.upgradePills}>
                  <span className={styles.upgradePill}>Advanced Analytics</span>
                  <span className={styles.upgradePill}>Custom Integrations</span>
                  <span className={styles.upgradePill}>API Access</span>
                </div>
              </div>
              <div className={styles.upgradeActions}>
                {data.upgrade_url && (
                  <a
                    href={data.upgrade_url}
                    target="_blank"
                    rel="noopener noreferrer"
                    className={styles.btnUpgradeWhite}
                  >
                    <FontAwesomeIcon icon={faArrowUpRightFromSquare} /> View Plans
                  </a>
                )}
                <a
                  href={data.upgrade_url || "#"}
                  target="_blank"
                  rel="noopener noreferrer"
                  className={styles.btnUpgradeYellow}
                >
                  <FontAwesomeIcon icon={faCrown} /> Upgrade Now
                </a>
              </div>
            </div>
          </section>
        </>
      )}

      {data.is_active && (
        <section className={styles.section}>
          <div className={`${styles.sectionTitleRow} ${styles.sectionTitleBlock}`}>
            <FontAwesomeIcon icon={faCircleInfo} className={styles.iconBlue} />
            <div>
              <h2 className={styles.sectionTitle}>License Information</h2>
              <p className={styles.sectionSubtitle}>Important details about your license</p>
            </div>
          </div>
          <div className={styles.infoGrid}>
            <div className={styles.infoColumn}>
              <h3 className={styles.infoColumnTitle}>License Details</h3>
              <div className={styles.infoRow}>
                <span className={styles.infoLabel}>License ID:</span>
                <span className={styles.infoValue}>{data.license_id || "—"}</span>
              </div>
              <div className={styles.infoRow}>
                <span className={styles.infoLabel}>Activation Date:</span>
                <span className={styles.infoValue}>{data.activation_date || "—"}</span>
              </div>
              <div className={styles.infoRow}>
                <span className={styles.infoLabel}>Sites Used:</span>
                <span className={styles.infoValue}>{data.sites_label || "—"}</span>
              </div>
              <div className={styles.infoRow}>
                <span className={styles.infoLabel}>Version:</span>
                <span className={styles.infoValue}>{data.version || "—"}</span>
              </div>
            </div>
            <div className={styles.infoColumn}>
              <h3 className={styles.infoColumnTitle}>Account Information</h3>
              <div className={styles.infoRow}>
                <span className={styles.infoLabel}>Email:</span>
                <span className={styles.infoValue}>{data.email || "—"}</span>
              </div>
              <div className={styles.infoRow}>
                <span className={styles.infoLabel}>Customer ID:</span>
                <span className={styles.infoValue}>{data.customer_id || "—"}</span>
              </div>
              <div className={styles.infoRow}>
                <span className={styles.infoLabel}>Purchase Date:</span>
                <span className={styles.infoValue}>{data.purchase_date || "—"}</span>
              </div>
              <div className={styles.infoRow}>
                <span className={styles.infoLabel}>Auto Renewal:</span>
                <span className={data.auto_renewal ? styles.infoValueSuccess : styles.infoValue}>
                  {data.auto_renewal ? "Enabled" : "Disabled"}
                </span>
              </div>
            </div>
          </div>
          <div className={styles.needHelpBlock}>
            <div className={styles.needHelpInner}>
              <FontAwesomeIcon icon={faLightbulb} className={styles.iconBlue} />
              <div>
                <h4 className={styles.needHelpTitle}>Need Help?</h4>
                <p className={styles.needHelpText}>
                  If you&apos;re experiencing issues with your license or need assistance, our support team is here to
                  help.
                </p>
                <div className={styles.needHelpActions}>
                  {data.support_url && (
                    <a
                      href={data.support_url}
                      target="_blank"
                      rel="noopener noreferrer"
                      className={styles.btnSupport}
                    >
                      <FontAwesomeIcon icon={faHeadset} /> Contact Support
                    </a>
                  )}
                  {data.docs_url ? (
                    <a href={data.docs_url} target="_blank" rel="noopener noreferrer" className={styles.btnDocs}>
                      <FontAwesomeIcon icon={faBook} /> Documentation
                    </a>
                  ) : (
                    <span className={styles.btnDocs}>
                      <FontAwesomeIcon icon={faBook} /> Documentation
                    </span>
                  )}
                </div>
              </div>
            </div>
          </div>
        </section>
      )}
    </div>
  );
};

export default LicensePage;
