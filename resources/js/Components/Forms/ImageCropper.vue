<template>
  <div class="relative w-[600px] overflow-y-auto h-full p-5">
    <div class="cropper-container">
      <cropper-canvas background="red" ref="cropperCanvas">
        <cropper-image :src="image" ref="cropperImage" alt="Picture" rotatable :scalable="true" :skewable="true"
          :translatable="true" initialCenterSize="contain" />
        <cropper-shade />

        <cropper-selection ref="cropperSelection" initial-coverage="0.8" :resizable="true" outlined movable
          :aspectRatio="_aspectRatio">
          <cropper-grid role="grid" covered hidden />
          <cropper-crosshair centered />
          <!-- <cropper-handle action="move" theme-color="rgba(255, 255, 255, 0.35)" /> -->
          <cropper-handle action="move" theme-color="transparent" />
          <cropper-handle action="n-resize" />
          <cropper-handle action="e-resize" />
          <cropper-handle action="s-resize" />
          <cropper-handle action="w-resize" />
          <cropper-handle action="ne-resize" />
          <cropper-handle action="nw-resize" />
          <cropper-handle action="se-resize" />
          <cropper-handle action="sw-resize" />
        </cropper-selection>
      </cropper-canvas>
    </div>
    <div class="flex justify-center gap-2 mt-5">
      <CropperActionButton @click="actions.validate" id="cropper-action-validate">
        <span v-html="ValidateIcon" class="w-4 h-4" />
      </CropperActionButton>
      <CropperActionButton @click="actions.zoomPlus" id="cropper-action-zoom-plus">
        <span v-html="ZoomInIcon" class="w-5 h-5" />
      </CropperActionButton>
      <CropperActionButton @click="actions.zoomMinus" id="cropper-action-zoom-minus">
        <span v-html="ZoomOutIcon" class="w-5 h-5" />
      </CropperActionButton>
      <CropperActionButton @click="actions.center" id="cropper-action-zoom-minus">
        <span v-html="CenterIcon" class="w-5 h-5" />
      </CropperActionButton>
      <CropperActionButton @click="actions.rotateLeft">
        <RotateLeft class="!w-5 !h-5" />
      </CropperActionButton>
      <CropperActionButton @click="actions.rotateRight">
        <RotateRight class="!w-5 !h-5" />
      </CropperActionButton>
      <CropperActionButton @click="actions.flipHorizontal">
        <span v-html="flipHorizontalIcon" class="w-4 h-4" />

      </CropperActionButton>
      <CropperActionButton @click="actions.flipVerticale">
        <span v-html="flipVerticaleIcon" class="w-4 h-4" />
      </CropperActionButton>
      <select v-model="_aspectRatio" v-if="canChangeRatio"
        class="min-w-8 h-8 text-white transition-colors border rounded border-gray-500/30 flex-center bg-transparent hover:bg-gray-800 p-0 px-2 w-24 focus:ring-0 focus:outline-none ring-0">
        <option :value="false">Free</option>
        <option :value="1">1</option>
        <option :value="16 / 9">16/9</option>
        <option :value="4 / 3">4/3</option>
        <option :value="3 / 2">3/2</option>
      </select>

      <CropperActionButton @click="actions.reset" id="cropper-action-reset">
        <span v-html="CloseIcon" class="w-4 h-4" />
      </CropperActionButton>
    </div>
  </div>
</template>
<script setup lang="ts">
import { ref, onMounted } from "vue";

import {
  CloseIcon,
  ValidateIcon,
  UndoIcon,
  RedoIcon,
  ZoomInIcon,
  ZoomOutIcon,
  CenterIcon,
  EyeIcon,
  EditIcon,
  flipHorizontalIcon,
  flipVerticaleIcon,
} from "lvp/helpers/lvp_icons";

import CropperActionButton from "./CropperActionButton.vue";
import RotateLeft from "lvp/Components/Icons/RotateLeft.vue";
import RotateRight from "lvp/Components/Icons/RotateRight.vue";

import "cropperjs";
const props = defineProps({
  image: {
    type: String,
    required: true,
  },
  imageName: {
    type: String,
    default: null,
  },
  aspectRatio: {
    type: Number,
    default: 16 / 9,
  },
  canChangeRatio: {
    type: Boolean,
    default: true,
  },
});

const emit = defineEmits(["onCrop"]);

const cropper = ref();
const _aspectRatio = ref(props.aspectRatio);
const cropperImage = ref();
const cropperSelection = ref();
const cropperCanvas = ref();

const actions = {
  reset: () => {
    cropperSelection.value.$reset();
    cropperImage.value.$center("contain");
    cropperImage.value.$rotate(0);
    cropperImage.value.$zoom(0);
  },
  validate: () => {
    // emit("update:cropper", cropper.value.getData());
    cropperSelection.value.$toCanvas()
      .then((canvas: any) => {
        canvas?.toBlob((blob: Blob) =>
          emit("onCrop", blobToFile(blob, "png", "image-cropped"))
        );
      });
  },
  zoomPlus: () => {
    cropperImage.value.$zoom(0.1);
  },
  zoomMinus: () => {
    cropperImage.value.$zoom(-0.1);
  },
  rotateLeft: () => {
    cropperImage.value.$rotate('-90deg');
  },
  rotateRight: () => {
    cropperImage.value.$rotate('90deg');
  },
  flipHorizontal: () => {
    cropperImage.value.$scale(-1, 1);
  },
  flipVerticale: () => {
    cropperImage.value.$scale(1, -1);
  },
  undo: () => {
    // cropper.value.undo();
  },
  redo: () => {
    // cropper.value.redo();
  },
  center: () => {
    cropperImage.value.$center("contain");
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
</script>
<style lang="scss" scoped>
.cropper-container {
  border: 1px solid var(--vp-c-divider);
  border-radius: 0.375rem;
  height: calc(100% - 4rem);

  cropper-canvas {
    height: 100%;
  }

}
</style>
