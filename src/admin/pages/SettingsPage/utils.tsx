import React from "react";
import type { SettingsField } from "./fields";
import ToggleField from "../../components/Fields/ToggleField/ToggleField";
import TextField from "../../components/Fields/TextField/TextField";
import SelectField from "../../components/Fields/SelectField/SelectField";
import RadioField from "../../components/Fields/RadioField/RadioField";
import PasswordField from "../../components/Fields/PasswordField/PasswordField";
import TextareaField from "../../components/Fields/TextareaField/TextareaField";

export function getFieldValue(
  field: SettingsField,
  values: Record<string, unknown>
): unknown {
  const v = values[field.optionKey];
  return v !== undefined ? v : field.default;
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
          value={Boolean(value)}
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
