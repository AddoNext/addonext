import React, { useCallback } from "react";
import ToggleField from "../../../components/Fields/ToggleField/ToggleField";
import SelectField from "../../../components/Fields/SelectField/SelectField";
import TextField from "../../../components/Fields/TextField/TextField";
import type { ExtensionField } from "../types";
import styles from "./ExtensionSettingsFields.module.scss";

interface ExtensionSettingsFieldsProps {
  fields: ExtensionField[];
  values: Record<string, unknown>;
  onChange: (fieldId: string, value: unknown) => void;
}

function isFieldVisible(field: ExtensionField, values: Record<string, unknown>): boolean {
  if (field.show_when_in) {
    const { field: dep, values: allowed } = field.show_when_in;
    return allowed.includes(values[dep]);
  }
  if (field.show_when) {
    const { field: dep, value: expected } = field.show_when;
    return values[dep] === expected;
  }
  return true;
}

const ExtensionSettingsFields: React.FC<ExtensionSettingsFieldsProps> = ({ fields, values, onChange }) => {
  const toggleCheckbox = useCallback(
    (fieldId: string, optionValue: string, checked: boolean, current: string[]) => {
      const set = new Set(current);
      if (checked) {
        set.add(optionValue);
      } else {
        set.delete(optionValue);
      }
      onChange(fieldId, [...set]);
    },
    [onChange]
  );

  return (
    <div className={styles.stack}>
      {fields.map((field) => {
        const visible = isFieldVisible(field, values);

        if (field.type === "toggle") {
          const v = Boolean(values[field.id]);
          return (
            <div key={field.id} hidden={!visible}>
              {visible ? (
                <ToggleField
                  label={field.label}
                  description={field.description}
                  value={v}
                  onChange={(next) => onChange(field.id, next)}
                />
              ) : null}
            </div>
          );
        }

        if (field.type === "select") {
          const raw = values[field.id];
          const v = typeof raw === "string" ? raw : "";
          const options = field.options ?? [];
          return (
            <div key={field.id} hidden={!visible}>
              {visible ? (
                <SelectField label={field.label} value={v} options={options} onChange={(next) => onChange(field.id, next)} />
              ) : null}
            </div>
          );
        }

        if (field.type === "text") {
          const raw = values[field.id];
          const v = typeof raw === "string" ? raw : raw != null ? String(raw) : "";
          return (
            <div key={field.id} hidden={!visible}>
              {visible ? (
                <TextField
                  label={field.label}
                  description={field.description}
                  value={v}
                  placeholder={field.placeholder}
                  onChange={(next) => onChange(field.id, next)}
                />
              ) : null}
            </div>
          );
        }

        if (field.type === "color") {
          const raw = values[field.id];
          const current = typeof raw === "string" && raw.trim() ? raw : "#4f46e5";
          return (
            <div key={field.id} hidden={!visible}>
              {visible ? (
                <div className={styles.colorGroup}>
                  <label className={styles.groupLabel}>{field.label}</label>
                  <div className={styles.colorControl}>
                    <input
                      type="color"
                      className={styles.colorPicker}
                      value={current}
                      onChange={(e) => onChange(field.id, e.target.value)}
                    />
                    <span className={styles.colorValue}>{current}</span>
                  </div>
                  {field.description ? <p className={styles.groupDescription}>{field.description}</p> : null}
                </div>
              ) : null}
            </div>
          );
        }

        if (field.type === "range") {
          const raw = values[field.id];
          const num = Number(raw);
          const min = typeof field.min === "number" ? field.min : 0;
          const max = typeof field.max === "number" ? field.max : 100;
          const step = typeof field.step === "number" ? field.step : 1;
          const unit = field.unit ?? "";
          const current = Number.isFinite(num) ? num : Number(field.default ?? min);
          return (
            <div key={field.id} hidden={!visible}>
              {visible ? (
                <div className={styles.rangeGroup}>
                  <div className={styles.rangeHeader}>
                    <label className={styles.groupLabel}>{field.label}</label>
                    <span className={styles.rangeValue}>
                      {current}
                      {unit}
                    </span>
                  </div>
                  <input
                    type="range"
                    className={styles.rangeInput}
                    min={min}
                    max={max}
                    step={step}
                    value={current}
                    onChange={(e) => onChange(field.id, Number(e.target.value))}
                  />
                  {field.description ? <p className={styles.groupDescription}>{field.description}</p> : null}
                </div>
              ) : null}
            </div>
          );
        }

        if (field.type === "checkbox_group") {
          const raw = values[field.id];
          const list = Array.isArray(raw) ? raw.filter((x): x is string => typeof x === "string") : [];
          const options = field.options ?? [];
          return (
            <div key={field.id} className={styles.checkboxGroup} hidden={!visible}>
              <div>
                <h3 className={styles.groupLabel}>{field.label}</h3>
                {field.description ? <p className={styles.groupDescription}>{field.description}</p> : null}
              </div>
              {visible
                ? options.map((opt) => (
                    <div key={opt.value} className={styles.checkboxRow}>
                      <input
                        id={`ext-set-${field.id}-${opt.value}`}
                        type="checkbox"
                        className={styles.checkbox}
                        checked={list.includes(opt.value)}
                        onChange={(e) =>
                          toggleCheckbox(field.id, opt.value, e.target.checked, list)
                        }
                      />
                      <label className={styles.checkboxLabel} htmlFor={`ext-set-${field.id}-${opt.value}`}>
                        {opt.label}
                      </label>
                    </div>
                  ))
                : null}
            </div>
          );
        }

        return null;
      })}
    </div>
  );
};

export default ExtensionSettingsFields;
