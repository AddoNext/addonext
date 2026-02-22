export interface Widget {
    id: string;
    title: string;
    icon: string;
    category: string[];
    admin_category: string;
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

export interface AdminState {
    widgets: Widget[];
    categories: Record<string, CategoryItem>;
    isResolving: boolean;
    categoriesResolving: boolean;
    error: string | null;
}
