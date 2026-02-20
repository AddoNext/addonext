import apiFetch from '@wordpress/api-fetch';
import { createReduxStore, register } from '@wordpress/data';
import type { AdminState, WidgetsGetResponse, WidgetsUpdateResponse } from './types';

const STORE_NAME = 'elementrify/admin';

const DEFAULT_STATE: AdminState = {
    widgets: {},
    activeWidgets: [],
    isResolving: false,
    error: null,
};

const GET_WIDGETS = 'GET_WIDGETS';
const SET_ACTIVE_WIDGETS = 'SET_ACTIVE_WIDGETS';
const SET_RESOLVING = 'SET_RESOLVING';
const SET_ERROR = 'SET_ERROR';

const store = createReduxStore(STORE_NAME, {
    initialState: DEFAULT_STATE,

    reducer(state: AdminState = DEFAULT_STATE, action: { type: string; payload?: unknown }) {
        switch (action.type) {
            case GET_WIDGETS:
                return {
                    ...state,
                    widgets: (action.payload as Record<string, string>) ?? {},
                    isResolving: false,
                    error: null,
                };
            case SET_ACTIVE_WIDGETS:
                return {
                    ...state,
                    activeWidgets: (action.payload as string[]) ?? [],
                };
            case SET_RESOLVING:
                return { ...state, isResolving: action.payload as boolean };
            case SET_ERROR:
                return { ...state, error: action.payload as string | null, isResolving: false };
            default:
                return state;
        }
    },

    actions: {
        setWidgets(widgets: Record<string, string>) {
            return { type: GET_WIDGETS, payload: widgets };
        },
        setActiveWidgets(activeWidgets: string[]) {
            return { type: SET_ACTIVE_WIDGETS, payload: activeWidgets };
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
                            dispatch({ type: SET_ACTIVE_WIDGETS, payload: widgets });
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
        getActiveWidgets(state: AdminState) {
            return state.activeWidgets;
        },
        getIsResolving(state: AdminState) {
            return state.isResolving;
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
    },
});

register(store);

export { store, STORE_NAME };
