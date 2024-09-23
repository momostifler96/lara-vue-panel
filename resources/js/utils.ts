const formatFileSize = (bytes: number) => {
    if (bytes === 0) {
        return "0 Bytes";
    }
    const decimals = 2;
    const k = 1024;
    const dm = decimals < 0 ? 0 : decimals;
    const sizes = ["Bytes", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB"];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + " " + sizes[i];
};


interface ConfirmationModalOption {
    title: string,
    body: string,
    cancel_button_label?: string,
    confirm_button_label?: string,
    has_password?: string,
    icon?: string,
    onCancel?: () => void,
    onConfirm: (data: { password: string }) => void,
}
const showConfirmation = (option: ConfirmationModalOption) => {
    const event = new CustomEvent('show-lvp-confirmation', {
        detail: option
    });
    document.dispatchEvent(event);
}

export { formatFileSize, showConfirmation, }