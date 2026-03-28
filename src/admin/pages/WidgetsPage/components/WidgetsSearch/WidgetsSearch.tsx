import React, { useRef } from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faSearch, faCircleXmark } from "@fortawesome/free-solid-svg-icons";
import styles from "./WidgetsSearch.module.scss";

interface WidgetsSearchProps {
  value: string;
  onChange: (value: string) => void;
  placeholder?: string;
}

const WidgetsSearch: React.FC<WidgetsSearchProps> = ({
  value,
  onChange,
  placeholder = "Search widgets...",
}) => {
  const inputRef = useRef<HTMLInputElement>(null);

  const handleClearMouseDown = (e: React.MouseEvent) => {
    e.preventDefault();
  };

  const handleClearClick = () => {
    onChange("");
    window.setTimeout(() => {
      const el = inputRef.current;
      if (el) {
        el.focus({ preventScroll: true });
      }
    }, 0);
  };

  return (
    <div className={styles.wrap}>
      <FontAwesomeIcon icon={faSearch} className={styles.icon} aria-hidden />
      <input
        ref={inputRef}
        type="text"
        placeholder={placeholder}
        value={value}
        onChange={(e) => onChange(e.target.value)}
        className={styles.input}
      />
      {value.length > 0 && (
        <button
          type="button"
          className={styles.clear}
          onMouseDown={handleClearMouseDown}
          onClick={handleClearClick}
          aria-label="Clear search"
        >
          <FontAwesomeIcon icon={faCircleXmark} className={styles.clearIcon} />
        </button>
      )}
    </div>
  );
};

export default WidgetsSearch;
