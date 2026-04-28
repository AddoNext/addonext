import React from "react";
import type { SettingsField } from "./fields";
import ToggleField from "../../components/Fields/ToggleField/ToggleField";
import TextField from "../../components/Fields/TextField/TextField";
import SelectField from "../../components/Fields/SelectField/SelectField";
import RadioField from "../../components/Fields/RadioField/RadioField";
import PasswordField from "../../components/Fields/PasswordField/PasswordField";
import TextareaField from "../../components/Fields/TextareaField/TextareaField";

function toBoolean(value: unknown): boolean {
  if (typeof value === "boolean") {
    return value;
  }
  if (typeof value === "number") {
    return value === 1;
  }
  if (typeof value === "string") {
    const normalized = value.trim().toLowerCase();
    if (normalized === "1" || normalized === "true" || normalized === "yes" || normalized === "on") {
      return true;
    }
    if (normalized === "0" || normalized === "false" || normalized === "no" || normalized === "off" || normalized === "") {
      return false;
    }
  }
  return Boolean(value);
}

export function getFieldValue(
  field: SettingsField,
  values: Record<string, unknown>
): unknown {
  const v = values[field.optionKey];
  return v !== undefined ? v : field.default;
}

export function shouldRenderField(
  field: SettingsField,
  values: Record<string, unknown>
): boolean {
  if (!field.dependsOn) {
    return true;
  }

  const dependencyValue = values[field.dependsOn.optionKey];
  if (typeof field.dependsOn.value === "boolean") {
    return toBoolean(dependencyValue) === field.dependsOn.value;
  }
  return dependencyValue === field.dependsOn.value;
}

export function renderField(
  field: SettingsField,
  values: Record<string, unknown>,
  setValue: (key: string, value: unknown) => void
): React.ReactNode {
  const value = getFieldValue(field, values);

  switch (field.type) {
    case "toggle":
      return (
        <ToggleField
          key={field.id}
          label={field.label}
          description={field.description}
          value={toBoolean(value)}
          onChange={(v) => setValue(field.optionKey, v)}
        />
      );
    case "text":
      return (
        <TextField
          key={field.id}
          label={field.label}
          description={field.description}
          value={String(value ?? "")}
          onChange={(v) => setValue(field.optionKey, v)}
          placeholder={field.placeholder}
        />
      );
    case "select":
      return (
        <SelectField
          key={field.id}
          label={field.label}
          value={String(value ?? "")}
          onChange={(v) => setValue(field.optionKey, v)}
          options={field.options ?? []}
        />
      );
    case "radio":
      return (
        <RadioField
          key={field.id}
          label={field.label}
          name={field.id}
          value={String(value ?? "")}
          onChange={(v) => setValue(field.optionKey, v)}
          options={field.options ?? []}
        />
      );
    case "password":
      return (
        <PasswordField
          key={field.id}
          label={field.label}
          value={String(value ?? "")}
          onChange={(v) => setValue(field.optionKey, v)}
        />
      );
    case "textarea":
      return (
        <TextareaField
          key={field.id}
          label={field.label}
          value={String(value ?? "")}
          onChange={(v) => setValue(field.optionKey, v)}
          placeholder={field.placeholder}
        />
      );
    default:
      return null;
  }
}
