export interface Widget {
    id: string;
    title: string;
    icon: string;
    category: string;
    active: boolean;
    description: string;
    brand_color: string;
    required_plan: string;
    is_locked: boolean;
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

export interface SettingsFieldDependency {
    optionKey: string;
    value: unknown;
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
    dependsOn?: SettingsFieldDependency;
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

export interface BackupItem {
    name: string;
    date: string;
    timestamp: number;
}

export interface BackupsListResponse {
    success: boolean;
    backups: BackupItem[];
}

export interface BackupsExportResponse {
    success: boolean;
    backup_name: string;
    backups: BackupItem[];
    download: string;
}

export interface BackupsImportResponse {
    success: boolean;
    values: Record<string, unknown>;
}

export interface BackupsDownloadResponse {
    success: boolean;
    file: string;
    content: string;
}

export interface LicenseData {
    is_active: boolean;
    has_pro_plan: boolean;
    status_label: string;
    plan_name: string;
    plan_title: string;
    sites_label: string;
    expires_at: string | null;
    expires_label: string;
    time_left_label: string;
    support_label: string;
    support_sublabel: string;
    license_key_masked: string;
    license_id: string;
    activation_date: string;
    sites_used: number;
    sites_quota: number | null;
    version: string;
    email: string;
    customer_id: string;
    purchase_date: string;
    auto_renewal: boolean;
    upgrade_url: string;
    account_url: string;
    support_url: string;
    docs_url: string;
}

export interface LicenseGetResponse {
    success: boolean;
    data?: LicenseData;
}

export interface LicenseActivateResponse {
    success: boolean;
    message?: string;
    data?: LicenseData;
}

export interface LicenseDeactivateResponse {
    success: boolean;
    message?: string;
    data?: LicenseData;
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
    backups: BackupItem[];
    backupsResolving: boolean;
    backupsExporting: boolean;
    backupsImporting: boolean;
    backupsError: string | null;
    restoringBackupFile: string | null;
    licenseData: LicenseData | null;
    licenseResolving: boolean;
    licenseError: string | null;
    licenseActivating: boolean;
    licenseDeactivating: boolean;
    licenseChecking: boolean;
}
