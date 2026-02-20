export interface WidgetsGetResponse {
    success: boolean;
    widgets: Record<string, string>;
}

export interface WidgetsUpdateResponse {
    success: boolean;
    message: string;
}

export interface AdminState {
    widgets: Record<string, string>;
    activeWidgets: string[];
    isResolving: boolean;
    error: string | null;
}
