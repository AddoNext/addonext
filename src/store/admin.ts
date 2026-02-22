import apiFetch from '@wordpress/api-fetch';
import { createReduxStore, register } from '@wordpress/data';
import type { AdminState, Widget, CategoryItem, WidgetsGetResponse, CategoriesGetResponse, WidgetsUpdateResponse } from './types';

const STORE_NAME = 'elementrify/admin';

const DEFAULT_STATE: AdminState = {
    widgets: [],
    categories: {},
    isResolving: false,
    categoriesResolving: false,
    error: null,
};

const GET_WIDGETS = 'GET_WIDGETS';
const GET_CATEGORIES = 'GET_CATEGORIES';
const MERGE_WIDGETS_ACTIVE = 'MERGE_WIDGETS_ACTIVE';
const SET_RESOLVING = 'SET_RESOLVING';
const SET_CATEGORIES_RESOLVING = 'SET_CATEGORIES_RESOLVING';
const SET_ERROR = 'SET_ERROR';

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
