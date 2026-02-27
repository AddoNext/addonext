import apiFetch from '@wordpress/api-fetch';
import { createReduxStore, register } from '@wordpress/data';
import type {
    AdminState,
    Widget,
    CategoryItem,
    WidgetsGetResponse,
    CategoriesGetResponse,
    WidgetsUpdateResponse,
    SettingsGetResponse,
    SettingsSection,
    BackupItem,
    BackupsListResponse,
    BackupsExportResponse,
    BackupsImportResponse,
    BackupsDownloadResponse,
    LicenseData,
    LicenseGetResponse,
    LicenseActivateResponse,
    LicenseDeactivateResponse,
} from './types';

const STORE_NAME = 'elementrify/admin';

const DEFAULT_STATE: AdminState = {
    widgets: [],
    categories: {},
    isResolving: false,
    categoriesResolving: false,
    error: null,
    settingsSections: null,
    settingsValues: {},
    settingsInitialValues: {},
    settingsResolving: false,
    settingsSaving: false,
    settingsError: null,
    backups: [],
    backupsResolving: false,
    backupsExporting: false,
    backupsImporting: false,
    backupsError: null,
    restoringBackupFile: null,
    licenseData: null,
    licenseResolving: false,
    licenseError: null,
    licenseActivating: false,
    licenseDeactivating: false,
    licenseChecking: false,
};

const GET_WIDGETS = 'GET_WIDGETS';
const GET_CATEGORIES = 'GET_CATEGORIES';
const MERGE_WIDGETS_ACTIVE = 'MERGE_WIDGETS_ACTIVE';
const SET_RESOLVING = 'SET_RESOLVING';
const SET_CATEGORIES_RESOLVING = 'SET_CATEGORIES_RESOLVING';
const SET_ERROR = 'SET_ERROR';
const SET_SETTINGS_DATA = 'SET_SETTINGS_DATA';
const SET_SETTINGS_VALUES = 'SET_SETTINGS_VALUES';
const SET_SETTINGS_VALUE = 'SET_SETTINGS_VALUE';
const SET_SETTINGS_RESOLVING = 'SET_SETTINGS_RESOLVING';
const SET_SETTINGS_SAVING = 'SET_SETTINGS_SAVING';
const SET_SETTINGS_ERROR = 'SET_SETTINGS_ERROR';
const SET_BACKUPS = 'SET_BACKUPS';
const SET_BACKUPS_RESOLVING = 'SET_BACKUPS_RESOLVING';
const SET_BACKUPS_EXPORTING = 'SET_BACKUPS_EXPORTING';
const SET_BACKUPS_IMPORTING = 'SET_BACKUPS_IMPORTING';
const SET_BACKUPS_ERROR = 'SET_BACKUPS_ERROR';
const SET_RESTORING_BACKUP_FILE = 'SET_RESTORING_BACKUP_FILE';
const SET_LICENSE_DATA = 'SET_LICENSE_DATA';
const SET_LICENSE_RESOLVING = 'SET_LICENSE_RESOLVING';
const SET_LICENSE_ERROR = 'SET_LICENSE_ERROR';
const SET_LICENSE_ACTIVATING = 'SET_LICENSE_ACTIVATING';
const SET_LICENSE_DEACTIVATING = 'SET_LICENSE_DEACTIVATING';
const SET_LICENSE_CHECKING = 'SET_LICENSE_CHECKING';

const store = createReduxStore(STORE_NAME, {
    initialState: DEFAULT_STATE,

    reducer(state: AdminState = DEFAULT_STATE, action: { type: string; payload?: unknown }) {
        switch (action.type) {
            case GET_WIDGETS:
                return {
                    ...state,
                    widgets: (action.payload as Widget[]) ?? [],
                    isResolving: false,
                    error: null,
                };
            case GET_CATEGORIES:
                return {
                    ...state,
                    categories: (action.payload as Record<string, CategoryItem>) ?? {},
                    categoriesResolving: false,
                };
            case MERGE_WIDGETS_ACTIVE: {
                const ids = (action.payload as string[]) ?? [];
                return {
                    ...state,
                    widgets: state.widgets.map((w) => ({ ...w, active: ids.includes(w.id) })),
                };
            }
            case SET_RESOLVING:
                return { ...state, isResolving: action.payload as boolean };
            case SET_CATEGORIES_RESOLVING:
                return { ...state, categoriesResolving: action.payload as boolean };
            case SET_ERROR:
                return { ...state, error: action.payload as string | null, isResolving: false };
            case SET_SETTINGS_DATA: {
                const { sections, values } = action.payload as { sections: SettingsSection[]; values: Record<string, unknown> };
                return {
                    ...state,
                    settingsSections: sections ?? null,
                    settingsValues: values ?? {},
                    settingsInitialValues: values ?? {},
                    settingsResolving: false,
                    settingsError: null,
                };
            }
            case SET_SETTINGS_VALUES: {
                const values = action.payload as Record<string, unknown>;
                return {
                    ...state,
                    settingsValues: values ?? {},
                    settingsInitialValues: values ?? {},
                };
            }
            case SET_SETTINGS_VALUE: {
                const { key, value } = action.payload as { key: string; value: unknown };
                return {
                    ...state,
                    settingsValues: { ...state.settingsValues, [key]: value },
                };
            }
            case SET_SETTINGS_RESOLVING:
                return { ...state, settingsResolving: action.payload as boolean };
            case SET_SETTINGS_SAVING:
                return { ...state, settingsSaving: action.payload as boolean };
            case SET_SETTINGS_ERROR:
                return { ...state, settingsError: action.payload as string | null };
            case SET_BACKUPS:
                return { ...state, backups: (action.payload as BackupItem[]) ?? [], backupsError: null };
            case SET_BACKUPS_RESOLVING:
                return { ...state, backupsResolving: action.payload as boolean };
            case SET_BACKUPS_EXPORTING:
                return { ...state, backupsExporting: action.payload as boolean };
            case SET_BACKUPS_IMPORTING:
                return { ...state, backupsImporting: action.payload as boolean };
            case SET_BACKUPS_ERROR:
                return { ...state, backupsError: action.payload as string | null };
            case SET_RESTORING_BACKUP_FILE:
                return { ...state, restoringBackupFile: action.payload as string | null };
            case SET_LICENSE_DATA:
                return {
                    ...state,
                    licenseData: action.payload as LicenseData | null,
                    licenseResolving: false,
                    licenseError: null,
                };
            case SET_LICENSE_RESOLVING:
                return { ...state, licenseResolving: action.payload as boolean };
            case SET_LICENSE_ERROR:
                return { ...state, licenseError: action.payload as string | null };
            case SET_LICENSE_ACTIVATING:
                return { ...state, licenseActivating: action.payload as boolean };
            case SET_LICENSE_DEACTIVATING:
                return { ...state, licenseDeactivating: action.payload as boolean };
            case SET_LICENSE_CHECKING:
                return { ...state, licenseChecking: action.payload as boolean };
            default:
                return state;
        }
    },

    actions: {
        setWidgets(widgets: Widget[]) {
            return { type: GET_WIDGETS, payload: widgets };
        },
        setCategories(categories: Record<string, CategoryItem>) {
            return { type: GET_CATEGORIES, payload: categories };
        },
        mergeWidgetsActive(ids: string[]) {
            return { type: MERGE_WIDGETS_ACTIVE, payload: ids };
        },
        setResolving(isResolving: boolean) {
            return { type: SET_RESOLVING, payload: isResolving };
        },
        setError(error: string | null) {
            return { type: SET_ERROR, payload: error };
        },
        setSettingsData(sections: SettingsSection[], values: Record<string, unknown>) {
            return { type: SET_SETTINGS_DATA, payload: { sections, values } };
        },
        setSettingsValue(key: string, value: unknown) {
            return { type: SET_SETTINGS_VALUE, payload: { key, value } };
        },
        setSettingsResolving(resolving: boolean) {
            return { type: SET_SETTINGS_RESOLVING, payload: resolving };
        },
        setSettingsSaving(saving: boolean) {
            return { type: SET_SETTINGS_SAVING, payload: saving };
        },
        setSettingsError(error: string | null) {
            return { type: SET_SETTINGS_ERROR, payload: error };
        },
        fetchSettings() {
            return async ({ dispatch }: { dispatch: (action: unknown) => void }) => {
                dispatch({ type: SET_SETTINGS_RESOLVING, payload: true });
                try {
                    const res = await apiFetch({ path: '/elementrify/v1/settings' }) as SettingsGetResponse;
                    if (res?.success && res.sections && res.values) {
                        dispatch({ type: SET_SETTINGS_DATA, payload: { sections: res.sections, values: res.values } });
                    }
                } catch (err) {
                    dispatch({
                        type: SET_SETTINGS_ERROR,
                        payload: (err as { message?: string })?.message ?? 'Failed to fetch settings',
                    });
                } finally {
                    dispatch({ type: SET_SETTINGS_RESOLVING, payload: false });
                }
            };
        },
        saveSettings(payload: { values: Record<string, unknown>; sections: SettingsSection[] | null }) {
            return async ({ dispatch }: { dispatch: (action: unknown) => void }) => {
                const { values: settingsValues, sections: settingsSections } = payload;
                dispatch({ type: SET_SETTINGS_SAVING, payload: true });
                try {
                    const res = await apiFetch({
                        path: '/elementrify/v1/settings',
                        method: 'POST',
                        data: { values: settingsValues },
                    }) as { success?: boolean };
                    if (res?.success) {
                        dispatch({
                            type: SET_SETTINGS_DATA,
                            payload: {
                                sections: settingsSections ?? [],
                                values: settingsValues,
                            },
                        });
                    }
                } catch (err) {
                    dispatch({
                        type: SET_SETTINGS_ERROR,
                        payload: (err as { message?: string })?.message ?? 'Failed to save settings',
                    });
                    throw err;
                } finally {
                    dispatch({ type: SET_SETTINGS_SAVING, payload: false });
                }
            };
        },
        setBackups(backups: BackupItem[]) {
            return { type: SET_BACKUPS, payload: backups };
        },
        setBackupsResolving(resolving: boolean) {
            return { type: SET_BACKUPS_RESOLVING, payload: resolving };
        },
        setBackupsExporting(exporting: boolean) {
            return { type: SET_BACKUPS_EXPORTING, payload: exporting };
        },
        setBackupsImporting(importing: boolean) {
            return { type: SET_BACKUPS_IMPORTING, payload: importing };
        },
        setBackupsError(error: string | null) {
            return { type: SET_BACKUPS_ERROR, payload: error };
        },
        fetchBackups() {
            return async ({ dispatch }: { dispatch: (action: unknown) => void }) => {
                dispatch({ type: SET_BACKUPS_RESOLVING, payload: true });
                try {
                    const res = await apiFetch({ path: '/elementrify/v1/backups' }) as BackupsListResponse;
                    if (res?.success && res.backups) {
                        dispatch({ type: SET_BACKUPS, payload: res.backups });
                    }
                } catch (err) {
                    dispatch({
                        type: SET_BACKUPS_ERROR,
                        payload: (err as { message?: string })?.message ?? 'Failed to fetch backups',
                    });
                } finally {
                    dispatch({ type: SET_BACKUPS_RESOLVING, payload: false });
                }
            };
        },
        exportBackup(payload: { values: Record<string, unknown>; saveToServer: boolean }) {
            return async ({ dispatch }: { dispatch: (action: unknown) => void }) => {
                dispatch({ type: SET_BACKUPS_EXPORTING, payload: true });
                try {
                    const res = await apiFetch({
                        path: '/elementrify/v1/backups/export',
                        method: 'POST',
                        data: { values: payload.values, save_to_server: payload.saveToServer },
                    }) as BackupsExportResponse;
                    if (res?.success) {
                        dispatch({ type: SET_BACKUPS, payload: res.backups ?? [] });
                        return { success: true as const, download: res.download, backup_name: res.backup_name };
                    }
                    return { success: false as const };
                } catch (err) {
                    dispatch({
                        type: SET_BACKUPS_ERROR,
                        payload: (err as { message?: string })?.message ?? 'Failed to export',
                    });
                    throw err;
                } finally {
                    dispatch({ type: SET_BACKUPS_EXPORTING, payload: false });
                }
            };
        },
        importBackup(payload: { values: Record<string, unknown> } | { file: File }) {
            return async ({ dispatch }: { dispatch: (action: unknown) => void }) => {
                dispatch({ type: SET_BACKUPS_IMPORTING, payload: true });
                try {
                    if ('file' in payload) {
                        const formData = new FormData();
                        formData.append('file', payload.file);
                        const res = await apiFetch({
                            path: '/elementrify/v1/backups/import',
                            method: 'POST',
                            body: formData,
                        }) as BackupsImportResponse;
                        if (res?.success && res.values) {
                            dispatch({ type: SET_SETTINGS_VALUES, payload: res.values });
                            return { success: true as const, values: res.values };
                        }
                    } else {
                        const res = await apiFetch({
                            path: '/elementrify/v1/backups/import',
                            method: 'POST',
                            data: { values: payload.values },
                        }) as BackupsImportResponse;
                        if (res?.success && res.values) {
                            dispatch({ type: SET_SETTINGS_VALUES, payload: res.values });
                            return { success: true as const, values: res.values };
                        }
                    }
                    return { success: false as const };
                } catch (err) {
                    dispatch({
                        type: SET_BACKUPS_ERROR,
                        payload: (err as { message?: string })?.message ?? 'Failed to import',
                    });
                    throw err;
                } finally {
                    dispatch({ type: SET_BACKUPS_IMPORTING, payload: false });
                }
            };
        },
        deleteBackup(file: string) {
            return async ({ dispatch }: { dispatch: (action: unknown) => void }) => {
                try {
                    const res = await apiFetch({
                        path: `/elementrify/v1/backups?file=${encodeURIComponent(file)}`,
                        method: 'DELETE',
                    }) as BackupsListResponse;
                    if (res?.success && res.backups) {
                        dispatch({ type: SET_BACKUPS, payload: res.backups });
                    }
                } catch (err) {
                    dispatch({
                        type: SET_BACKUPS_ERROR,
                        payload: (err as { message?: string })?.message ?? 'Failed to delete backup',
                    });
                    throw err;
                }
            };
        },
        downloadBackup(file: string) {
            return async () => {
                const res = await apiFetch({
                    path: `/elementrify/v1/backups/download?file=${encodeURIComponent(file)}`,
                }) as BackupsDownloadResponse;
                if (res?.success && res.content) {
                    const blob = new Blob([res.content], { type: 'application/json' });
                    const url = URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = res.file;
                    a.click();
                    URL.revokeObjectURL(url);
                    return { success: true as const };
                }
                return { success: false as const };
            };
        },
        restoreBackup(file: string) {
            return async ({ dispatch }: { dispatch: (action: unknown) => void }) => {
                dispatch({ type: SET_RESTORING_BACKUP_FILE, payload: file });
                dispatch({ type: SET_BACKUPS_ERROR, payload: null });
                try {
                    const downloadRes = await apiFetch({
                        path: `/elementrify/v1/backups/download?file=${encodeURIComponent(file)}`,
                    }) as BackupsDownloadResponse;
                    if (!downloadRes?.success || !downloadRes.content) {
                        dispatch({
                            type: SET_BACKUPS_ERROR,
                            payload: 'Failed to load backup file',
                        });
                        return;
                    }
                    const data = JSON.parse(downloadRes.content) as { values?: Record<string, unknown> };
                    const values = data?.values && typeof data.values === 'object' ? data.values : {};
                    const importRes = await apiFetch({
                        path: '/elementrify/v1/backups/import',
                        method: 'POST',
                        data: { values },
                    }) as BackupsImportResponse;
                    if (importRes?.success && importRes.values) {
                        dispatch({ type: SET_SETTINGS_VALUES, payload: importRes.values });
                    }
                } catch (err) {
                    dispatch({
                        type: SET_BACKUPS_ERROR,
                        payload: (err as { message?: string })?.message ?? 'Failed to restore backup',
                    });
                } finally {
                    dispatch({ type: SET_RESTORING_BACKUP_FILE, payload: null });
                }
            };
        },
        fetchLicense() {
            return async ({ dispatch }: { dispatch: (action: unknown) => void }) => {
                dispatch({ type: SET_LICENSE_RESOLVING, payload: true });
                try {
                    const res = await apiFetch({ path: '/elementrify/v1/license' }) as LicenseGetResponse;
                    if (res?.success && res.data) {
                        dispatch({ type: SET_LICENSE_DATA, payload: res.data });
                    }
                } catch (err) {
                    dispatch({
                        type: SET_LICENSE_ERROR,
                        payload: (err as { message?: string })?.message ?? 'Failed to fetch license',
                    });
                } finally {
                    dispatch({ type: SET_LICENSE_RESOLVING, payload: false });
                }
            };
        },
        activateLicense(licenseKey: string) {
            return async ({ dispatch }: { dispatch: (action: unknown) => void }) => {
                dispatch({ type: SET_LICENSE_ACTIVATING, payload: true });
                dispatch({ type: SET_LICENSE_ERROR, payload: null });
                try {
                    const res = await apiFetch({
                        path: '/elementrify/v1/license/activate',
                        method: 'POST',
                        data: { license_key: licenseKey },
                    }) as LicenseActivateResponse;
                    if (res?.success && res.data) {
                        dispatch({ type: SET_LICENSE_DATA, payload: res.data });
                        return { success: true as const };
                    }
                    const message = res?.message ?? 'Activation failed';
                    dispatch({ type: SET_LICENSE_ERROR, payload: message });
                    return { success: false as const, message };
                } catch (err) {
                    const message = (err as { message?: string })?.message ?? 'Failed to activate license';
                    dispatch({ type: SET_LICENSE_ERROR, payload: message });
                    throw err;
                } finally {
                    dispatch({ type: SET_LICENSE_ACTIVATING, payload: false });
                }
            };
        },
        deactivateLicense() {
            return async ({ dispatch }: { dispatch: (action: unknown) => void }) => {
                dispatch({ type: SET_LICENSE_DEACTIVATING, payload: true });
                dispatch({ type: SET_LICENSE_ERROR, payload: null });
                try {
                    const res = await apiFetch({
                        path: '/elementrify/v1/license/deactivate',
                        method: 'POST',
                    }) as LicenseDeactivateResponse;
                    if (res?.success && res.data) {
                        dispatch({ type: SET_LICENSE_DATA, payload: res.data });
                        return { success: true as const };
                    }
                    const message = res?.message ?? 'Deactivation failed';
                    dispatch({ type: SET_LICENSE_ERROR, payload: message });
                    return { success: false as const, message };
                } catch (err) {
                    const message = (err as { message?: string })?.message ?? 'Failed to deactivate license';
                    dispatch({ type: SET_LICENSE_ERROR, payload: message });
                    throw err;
                } finally {
                    dispatch({ type: SET_LICENSE_DEACTIVATING, payload: false });
                }
            };
        },
        checkLicenseStatus() {
            return async ({ dispatch }: { dispatch: (action: unknown) => void }) => {
                dispatch({ type: SET_LICENSE_CHECKING, payload: true });
                try {
                    const res = await apiFetch({
                        path: '/elementrify/v1/license/check',
                        method: 'POST',
                    }) as LicenseGetResponse;
                    if (res?.success && res.data) {
                        dispatch({ type: SET_LICENSE_DATA, payload: res.data });
                    }
                } catch (err) {
                    dispatch({
                        type: SET_LICENSE_ERROR,
                        payload: (err as { message?: string })?.message ?? 'Failed to check license',
                    });
                } finally {
                    dispatch({ type: SET_LICENSE_CHECKING, payload: false });
                }
            };
        },
        updateWidgets(widgets: string[]) {
            return ({ dispatch }: { dispatch: (action: unknown) => void }) => {
                dispatch({ type: SET_RESOLVING, payload: true });
                return apiFetch({
                    path: '/elementrify/v1/update-widgets',
                    method: 'POST',
                    data: { widgets },
                })
                    .then((res: unknown) => {
                        const data = res as WidgetsUpdateResponse;
                        if (data?.success) {
                            dispatch({ type: MERGE_WIDGETS_ACTIVE, payload: widgets });
                        }
                        dispatch({ type: SET_RESOLVING, payload: false });
                        return data;
                    })
                    .catch((err: { message?: string }) => {
                        dispatch({
                            type: SET_ERROR,
                            payload: err?.message ?? 'Failed to update widgets',
                        });
                        dispatch({ type: SET_RESOLVING, payload: false });
                        throw err;
                    });
            };
        },
    },

    selectors: {
        getWidgets(state: AdminState) {
            return state.widgets;
        },
        getCategories(state: AdminState) {
            return state.categories;
        },
        getIsResolving(state: AdminState) {
            return state.isResolving;
        },
        getCategoriesResolving(state: AdminState) {
            return state.categoriesResolving;
        },
        getError(state: AdminState) {
            return state.error;
        },
        getSettingsSections(state: AdminState) {
            return state.settingsSections;
        },
        getSettingsValues(state: AdminState) {
            return state.settingsValues;
        },
        getSettingsInitialValues(state: AdminState) {
            return state.settingsInitialValues;
        },
        getSettingsResolving(state: AdminState) {
            return state.settingsResolving;
        },
        getSettingsSaving(state: AdminState) {
            return state.settingsSaving;
        },
        getSettingsError(state: AdminState) {
            return state.settingsError;
        },
        getSettingsIsDirty(state: AdminState) {
            const a = state.settingsValues;
            const b = state.settingsInitialValues;
            const keys = new Set([...Object.keys(a), ...Object.keys(b)]);
            for (const key of keys) {
                if (JSON.stringify(a[key]) !== JSON.stringify(b[key])) return true;
            }
            return false;
        },
        getBackups(state: AdminState) {
            return state.backups;
        },
        getBackupsResolving(state: AdminState) {
            return state.backupsResolving;
        },
        getBackupsExporting(state: AdminState) {
            return state.backupsExporting;
        },
        getBackupsImporting(state: AdminState) {
            return state.backupsImporting;
        },
        getBackupsError(state: AdminState) {
            return state.backupsError;
        },
        getRestoringBackupFile(state: AdminState) {
            return state.restoringBackupFile;
        },
        getLicenseData(state: AdminState) {
            return state.licenseData;
        },
        getLicenseResolving(state: AdminState) {
            return state.licenseResolving;
        },
        getLicenseError(state: AdminState) {
            return state.licenseError;
        },
        getLicenseActivating(state: AdminState) {
            return state.licenseActivating;
        },
        getLicenseDeactivating(state: AdminState) {
            return state.licenseDeactivating;
        },
        getLicenseChecking(state: AdminState) {
            return state.licenseChecking;
        },
    },

    resolvers: {
        getWidgets:
            () =>
            async ({ dispatch }: { dispatch: (action: unknown) => void }) => {
                dispatch({ type: SET_RESOLVING, payload: true });
                try {
                    const res = await apiFetch({
                        path: '/elementrify/v1/widgets',
                    }) as WidgetsGetResponse;
                    if (res?.success && res.widgets) {
                        dispatch({ type: GET_WIDGETS, payload: res.widgets });
                    }
                } catch (err) {
                    dispatch({
                        type: SET_ERROR,
                        payload: (err as { message?: string })?.message ?? 'Failed to fetch widgets',
                    });
                } finally {
                    dispatch({ type: SET_RESOLVING, payload: false });
                }
            },
        getCategories:
            () =>
            async ({ dispatch }: { dispatch: (action: unknown) => void }) => {
                dispatch({ type: SET_CATEGORIES_RESOLVING, payload: true });
                try {
                    const res = await apiFetch({
                        path: '/elementrify/v1/categories',
                    }) as CategoriesGetResponse;
                    if (res?.success && res.categories) {
                        dispatch({ type: GET_CATEGORIES, payload: res.categories });
                    }
                } catch {
                    dispatch({ type: SET_CATEGORIES_RESOLVING, payload: false });
                }
            },
    },
});

register(store);

export { store, STORE_NAME };
