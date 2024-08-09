

import { router } from "@inertiajs/vue3";
import { Component } from "vue";
type ColumnAlign = "left" | "right" | "center";
interface ToastOption {
    title: string;
    message: string;
    type: "success" | "error" | "warning" | "info";
}
interface DataTableItemActionOptions {
    item: any;
    route_list: any;
    router: typeof router;
    showToast: (option: ToastOption) => void;
    showConfirmation: {
        title: string;
        body: string;
        cancel_button_label: string;
        confirm_button_label: string;
        has_password: boolean;
        onConfirm: (password: string) => void;
        onCancel: () => void;
    };
}
interface DataTableSelectedItemsActionOptions {
    selected_items: any;
    route_list: any;
    router: typeof router;
    showToast: (option: ToastOption) => void;
    showConfirmation: {
        title: string;
        body: string;
        cancel_button_label: string;
        confirm_button_label: string;
        onConfirm: () => void;
        onCancel: () => void;
    };
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
    }[];
    icon: string;
    style: string;
    type: string;
    show_reset: boolean;
    reset_button_label: string;
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

interface ActionsList {
    [key: string]: (item: DataTableItemActionOptions) => void
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
            item?: { [key: string]: (options: DataTableItemActionOptions) => any };
            selected_items?: { [key: string]: (options: DataTableSelectedItemsActionOptions) => any };
        },
        page?: { [key: string]: (options: DataTableSelectedItemsActionOptions) => any };
        resource_detail_page?: { [key: string]: (options: DataTableSelectedItemsActionOptions) => any };
        resource_create_page?: { [key: string]: (options: DataTableSelectedItemsActionOptions) => any };
        resource_edit_page?: { [key: string]: (options: DataTableSelectedItemsActionOptions) => any };

    };
    svg_icons?: { [key: string]: string };
    widgets?: { [key: string]: Component };
}


export type { LVPPluginOptions, DataTableItemActionOptions, DataTableSelectedItemsActionOptions, TableColumn, TableData, ActionsList, ActionMenu, FileInfo, PageProps, FolderInterface, TableFilter }
