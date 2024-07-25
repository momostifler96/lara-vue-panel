<template>
    <div class="relative w-[600px] overflow-y-auto h-full p-5">
        <div class="w-full my-auto flex-center cropper-container"></div>
        <div class="flex justify-center gap-2 mt-5">
            <CropperActionButton
                @click="actions.validate"
                id="cropper-action-validate"
            >
                <ValidateIcon class="w-4 h-4" />
            </CropperActionButton>
            <CropperActionButton
                @click="actions.zoomPlus"
                id="cropper-action-zoom-plus"
            >
                <ZoomPlusIcon class="w-5 h-5" />
            </CropperActionButton>
            <CropperActionButton
                @click="actions.zoomMinus"
                id="cropper-action-zoom-minus"
            >
                <ZoomMinusIcon class="w-5 h-5" />
            </CropperActionButton>
            <CropperActionButton
                @click="actions.center"
                id="cropper-action-zoom-minus"
            >
                <CenterIcon class="w-5 h-5" />
            </CropperActionButton>
            <CropperActionButton @click="actions.rotateLeft">
                <RotateLeft class="!w-5 !h-5" />
            </CropperActionButton>
            <CropperActionButton @click="actions.rotateRight">
                <RotateRight class="!w-5 !h-5" />
            </CropperActionButton>
            <CropperActionButton @click="actions.undo" id="cropper-action-undo">
                <UndoIcon class="w-4 h-4" />
            </CropperActionButton>
            <CropperActionButton @click="actions.redo" id="cropper-action-redo">
                <RedoIcon class="w-4 h-4" />
            </CropperActionButton>
            <CropperActionButton
                @click="actions.reset"
                id="cropper-action-reset"
            >
                <CloseIcon class="w-4 h-4" />
            </CropperActionButton>
        </div>
    </div>
</template>
<script setup lang="ts">
import { defineProps, defineEmits, ref, onMounted } from "vue";
import CloseIcon from "@heroicons/vue/24/outline/XMarkIcon";
import ValidateIcon from "@heroicons/vue/24/outline/CheckIcon";
// import ValidateIcon from "@heroicons/vue/24/outline/CheckIcon";
import UndoIcon from "@heroicons/vue/24/outline/ArrowUturnLeftIcon";
import RedoIcon from "@heroicons/vue/24/outline/ArrowUturnRightIcon";
import ZoomPlusIcon from "@heroicons/vue/24/outline/MagnifyingGlassPlusIcon";
import ZoomMinusIcon from "@heroicons/vue/24/outline/MagnifyingGlassMinusIcon";
import CenterIcon from "@heroicons/vue/24/outline/ArrowsPointingInIcon";
import CropperActionButton from "./CropperActionButton.vue";
import RotateLeft from "lvp/Components/Icons/RotateLeft.vue";
import RotateRight from "lvp/Components/Icons/RotateRight.vue";

import Cropper from "cropperjs";
const props = defineProps({
    image: String,
    imageName: String,
});

const emit = defineEmits(["onCrop"]);

const cropper = ref();
const copperImage = ref();

const actions = {
    reset: () => {
        cropper.value.getCropperSelection()?.$reset();
        cropper.value.getCropperImage()?.$center("contain");
        cropper.value.getCropperImage()?.$rotate(0);
        cropper.value.getCropperImage()?.$zoom(0);
    },
    validate: () => {
        // emit("update:cropper", cropper.value.getData());
        cropper.value
            .getCropperSelection()
            ?.$toCanvas()
            .then((canvas: any) => {
                canvas?.toBlob((blob: Blob) =>
                    emit("onCrop", blobToFile(blob, "jpg", "image-cropped"))
                );
            });
    },
    zoomPlus: () => {
        cropper.value.getCropperImage()?.$zoom(0.1);
    },
    zoomMinus: () => {
        cropper.value.getCropperImage()?.$zoom(-0.1);
    },
    rotateLeft: () => {
        cropper.value.getCropperImage()?.$rotate(-90);
    },
    rotateRight: () => {
        cropper.value.getCropperImage()?.$rotate(90);
    },
    undo: () => {
        // cropper.value.undo();
    },
    redo: () => {
        // cropper.value.redo();
    },
    center: () => {
        cropper.value.getCropperImage()?.$center("contain");
    },
};
function blobToFile(blob: Blob, fileExtension = "png", namePrefix = "") {
    // Génère un nom de fichier aléatoire
    const randomFileName =
        namePrefix +
        "-" +
        `${Math.random().toString(36).substring(2, 15)}.${fileExtension}`;

    // Crée un objet File à partir du blob
    const file = new File([blob], randomFileName, { type: blob.type });

    return file;
}
function base64ToFile(base64: string, fileName: string) {
    // Décoder la chaîne base64 en un tableau d'octets
    const byteString = atob(base64.split(",")[1]);
    //@ts-ignore
    const mimeType = base64.match(/data:([^;]+);/)[1];

    // Créer un tableau d'octets
    const byteNumbers = new Array(byteString.length);
    for (let i = 0; i < byteString.length; i++) {
        byteNumbers[i] = byteString.charCodeAt(i);
    }

    // Créer un `Uint8Array` à partir du tableau d'octets
    const byteArray = new Uint8Array(byteNumbers);

    // Créer un `Blob` à partir du tableau d'octets
    const blob = new Blob([byteArray], { type: mimeType });

    // Créer un objet `File` à partir du `Blob`
    const file = new File([blob], fileName, { type: mimeType });

    return file;
}
onMounted(() => {
    const image = new Image();
    image.src = props.image;
    image.alt = "Picture";
    cropper.value = new Cropper(image, {
        container: ".cropper-container",
    });

    // const test_cropper = new Cropper(image, {
    //     container: ".cropper-containesr",
    // });
    // test_cropper.getCropperImage().$center("contain");
});
</script>
<style lang="scss" scoped>
.cropper-container {
    border: 1px solid var(--vp-c-divider);
    border-radius: 0.375rem;
    // margin-bottom: 1rem;
    // margin-top: 1rem;
    height: calc(100% - 4rem);
    :deep(cropper-canvas) {
        height: 100%;
        width: 600px;
    }
}
</style>
