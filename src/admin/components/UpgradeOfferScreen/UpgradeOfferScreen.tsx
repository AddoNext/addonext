import React from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faCrown, faCheck } from "@fortawesome/free-solid-svg-icons";
import { DEFAULT_PRO_UPGRADE_FEATURES } from "../../constants/proUpgradeFeatures";
import type { UpgradeOfferScreenProps } from "./types";
import styles from "./UpgradeOfferScreen.module.scss";

const UpgradeOfferScreen: React.FC<UpgradeOfferScreenProps> = ({
  title,
  description,
  upgradeUrl,
  features = DEFAULT_PRO_UPGRADE_FEATURES,
}) => {
  const url = upgradeUrl.trim();

  return (
    <div className={styles.wrap}>
      <section className={styles.card} aria-labelledby="upgrade-offer-title">
        <div className={styles.header}>
          <div className={styles.iconBubble} aria-hidden>
            <FontAwesomeIcon icon={faCrown} />
          </div>
          <h2 id="upgrade-offer-title" className={styles.heading}>
            {title}
          </h2>
          <p className={styles.subheading}>{description}</p>
        </div>
        <div className={styles.body}>
          <ul className={styles.list}>
            {features.map((text) => (
              <li key={text} className={styles.listItem}>
                <FontAwesomeIcon icon={faCheck} className={styles.check} aria-hidden />
                <span>{text}</span>
              </li>
            ))}
          </ul>
        </div>
        <div className={styles.footer}>
          {url ? (
            <a className={styles.upgradeBtn} href={url} target="_blank" rel="noopener noreferrer">
              Upgrade to Pro
            </a>
          ) : (
            <button type="button" className={styles.upgradeBtn} disabled>
              Upgrade to Pro
            </button>
          )}
        </div>
      </section>
    </div>
  );
};

export default UpgradeOfferScreen;
