

import { router } from "@inertiajs/vue3";
import { Component } from "vue";
type ColumnAlign = "left" | "right" | "center";
interface ToastOption {
    title: string;
    message: string;
    type: "success" | "error" | "warning" | "info";
}
type ShowConfirmation = {
    title: string;
    body: string;
    cancel_button_label?: string;
    confirm_button_label?: string;
    has_password?: boolean;
    onConfirm: (password: string) => void;
    onCancel?: () => void;
}

type ShowFormModal = {
    title: string;
    description: string;
    cancel_button_label?: string;
    submit_button_label?: string;
    fields: { type: string, props: { [k: string]: any } }[];
    has_password?: boolean;
    onSubmit: (option: { password: string, formData: { [k: string]: any } }) => void;
    onCancel?: () => void;
}

interface DataTableItemActionOptions {
    item: any;
    route_list: any;
    router: typeof router;
    showToast: (option: ToastOption) => void;
    showConfirmation: (opt: ShowConfirmation) => void;
    showFormModal: (opt: ShowFormModal) => void;
}
interface DataTableItemColActionOptions {
    data: any;
    route_list: any;
    router: typeof router;
    showToast: (option: ToastOption) => void;
    showConfirmation: {
        title: string;
        body: string;
        cancel_button_label?: string;
        confirm_button_label?: string;
        has_password?: boolean;
        onConfirm: (password: string) => void;
        onCancel?: () => void;
    };
    showFormModal: {
        title: string;
        description: string;
        cancel_button_label?: string;
        submit_button_label?: string;
        fields: { type: string, props: { [k: string]: any } }[];
        has_password?: boolean;
        onSubmit: (option: { password: string, formData: { [k: string]: any } }) => void;
        onCancel?: () => void;
    };
}
interface DataTableSelectedItemsActionOptions {
    selected_items_ids: string[];
    selected_items: any[];
    route_list: any;
    router: typeof router;
    showToast: (option: ToastOption) => void;
    showConfirmation: (option: ShowConfirmation) => void;
    showFormModal: (option: {
        title: string;
        description: string;
        cancel_button_label?: string;
        submit_button_label?: string;
        fields: { type: string, props: { [k: string]: any } }[];
        has_password?: boolean;
        onSubmit: (option: { password: string, formData: { [k: string]: any } }) => void;
        onCancel?: () => void;
    }) => void;
}

interface TableColumn {
    searchable: boolean;
    sortable: boolean;
    field: string;
    align: ColumnAlign;
    label: string;
    type: string;
    visible: boolean;
    data: any;
    file_path?: string;
    editable: string;

}

interface TableFilter {
    filters: {
        field: string;
        props: { [k: string]: any };
        component: string;
        col_span: string;
    }[];
    searchable: any[];
    icon: string;
    style: string;
    type: string;
    show_reset: boolean;
    auto_submit: boolean;
    reset_button_label: string;
    submit_button_label: string;
}
interface ActionMenu {
    type: "inline" | "dropdown";
    actions: {
        label: string;
        action: string;
        icon: string;
        color: string;
    }[];
}
interface TableData {
    columns: any[];
    filters: [];
    group_action: ActionMenu;
    // action: {
    //     type: "inline" | "dropdown";
    //     actions: {
    //         label: string;
    //         action: string;
    //         icon: string;
    //         color: string;
    //     }[];
    // };
    label: string;
    fixe_last_column: boolean;
    fixe_first_column: boolean;
    type: string;
    api_url: null;
    paginated: null;
    data: {
        items: [];
        pagination: {
            current_page: number;
            total_items: number;
            total: number;
            per_page: number;
            from: number;
            to: number;
            path: string;
        };
    };
}

interface SingleItemAction {
    [key: string]: (item: DataTableItemActionOptions) => void
}

interface ItemColAction {
    [key: string]: (item: DataTableItemActionOptions) => void
}

interface SelectedItemsActions {
    [key: string]: (item: DataTableSelectedItemsActionOptions) => void
}
interface FileInfo {
    imagePreview?: string;
    fileName: string;
    fileOriginalName: string;
    fileSize: string;
    fileFormatedSize: string;
    fileType: string;
    fileExtension: string;
    file?: File;
}

interface PageProps {
    props: {
        errors: [];
        auth: any;
        notifications: number;
        loading: boolean;
        user_menu: [];
        nav_menu: [];
        flash: {
            success: string;
            error: string;
            warn: string;
            info: string;
            alert: string;
        }
    }

}
interface FolderInterface {
    id: string;
    name: string;
    created_date: string;
    type: 'directory' | 'file' | 'other';
    items: number;
    file_info: {
        url: string;
        download_name: string;
        uuid: string;
        extension: string;
        mime_type: string;
        size: number;
    };
}
interface LVPPluginOptions {

    actions?: {
        datatable?: {
            item?: { [key: string]: (options: SingleItemAction) => any };
            bulk?: { [key: string]: (options: SelectedItemsActions) => any };
            item_col?: { [key: string]: (options: DataTableItemColActionOptions) => any };
        },
        page?: { [key: string]: (options: DataTableSelectedItemsActionOptions) => any };
        resource_detail_page?: { [key: string]: (options: DataTableSelectedItemsActionOptions) => any };
        resource_create_page?: { [key: string]: (options: DataTableSelectedItemsActionOptions) => any };
        resource_edit_page?: { [key: string]: (options: DataTableSelectedItemsActionOptions) => any };

    };
    svg_icons?: { [key: string]: string };
    widgets?: { [key: string]: Component };
    form_fields?: { [key: string]: Component };
    datatable_columns?: { [key: string]: Component };
    data_grid_cards?: { [key: string]: Component };
}

export interface User {
    id: number;
    name: string;
    phone: string;
    email: string;
    email_verified_at: string;
}


namespace NavMenu {
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
}


type NavMenuItem = NavMenu.NavLink | NavMenu.NavGroup

interface PageProps {
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


export type { LVPPluginOptions, TableColumn, TableData, SingleItemAction, SelectedItemsActions, ActionMenu, NavMenu, FileInfo, PageProps, FolderInterface, TableFilter, ShowConfirmation, DataTableItemActionOptions }
