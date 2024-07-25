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
const getFileType = (file: File): string => {
    const fileType = file.type;

    if (fileType.startsWith("image")) {
        return "image";
    } else if (fileType.startsWith("application")) {
        // On distingue les documents des fichiers zip en vérifiant les sous-types spécifiques
        if (
            fileType.includes("pdf") ||
            fileType.includes("msword") ||
            fileType.includes("vnd.openxmlformats-officedocument")
        ) {
            return "document";
        } else if (
            fileType.includes("zip") ||
            fileType.includes("x-compressed-zip")
        ) {
            return "zip";
        } else {
            return "document"; // Par défaut, tous les autres types d'application sont considérés comme des documents
        }
    } else {
        return "over";
    }
};

export { formatFileSize, getFileType }
