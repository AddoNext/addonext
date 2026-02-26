export interface Widget {
    id: string;
    title: string;
    icon: string;
    category: string;
    active: boolean;
    description: string;
    brand_color: string;
}

export interface CategoryItem {
    title: string;
    icon: string;
}

export interface Category extends CategoryItem {
    slug: string;
}

export interface WidgetsGetResponse {
    success: boolean;
    widgets: Widget[];
}

export interface CategoriesGetResponse {
    success: boolean;
    categories: Record<string, CategoryItem>;
}

export interface WidgetsUpdateResponse {
    success: boolean;
    message: string;
}

export type SettingsFieldType =
    | 'toggle'
    | 'text'
    | 'select'
    | 'radio'
    | 'password'
    | 'textarea';

export interface SettingsFieldOption {
    value: string;
    label: string;
}

export interface SettingsField {
    id: string;
    type: SettingsFieldType;
    optionKey: string;
    label: string;
    description?: string;
    default?: unknown;
    options?: SettingsFieldOption[];
    placeholder?: string;
}

export interface SettingsSection {
    id: string;
    title: string;
    description?: string;
    icon: string;
    fields: SettingsField[];
}

export interface SettingsGetResponse {
    success: boolean;
    sections: SettingsSection[];
    values: Record<string, unknown>;
}

export interface AdminState {
    widgets: Widget[];
    categories: Record<string, CategoryItem>;
    isResolving: boolean;
    categoriesResolving: boolean;
    error: string | null;
    settingsSections: SettingsSection[] | null;
    settingsValues: Record<string, unknown>;
    settingsInitialValues: Record<string, unknown>;
    settingsResolving: boolean;
    settingsSaving: boolean;
    settingsError: string | null;
}
