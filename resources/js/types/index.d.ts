export interface User {
    id: number;
    name: string;
    phone: string;
    email: string;
    email_verified_at: string;
}

export interface NavLink {
    label: string;
    path: string;
    icon: string;
}
export interface NavGroup {
    label: string;
    path: string;
    dismisable: boolean;
    children: NavLink[];
}

type NavMenuItem = NavLink | NavGroup

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    auth: {
        user: User;
    };
    notifications: number;
    admin_logo: string;
    currentPath: string;
    panel_data: any;
    user_menu: any;
    flash: {
        info: string | null;
        status: string | null;
        error: string | null;
        success: string | null;
        warning: string | null;
        alert: string | null;
    };
    nav_menu: NavMenuItem[];
};
