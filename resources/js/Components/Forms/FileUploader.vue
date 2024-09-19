<template>
  <div>
    <label for="" class="flex">{{ label }}
      <span v-if="required" class="text-red-500">*</span>
    </label>
    <div ref="fileInput" @dragover="onOver" @dragleave="onLeave" @drop="onDrop"
      class="flex-col bg-gray-100 border-2 border-gray-300 border-dashed rounded cursor-pointer lvp-file-uploader flex-center">
      <label v-if="multiple || uploadedFilesInfos.length == 0" for="f-loader-1" :class="[height]"
        class="w-full cursor-pointer flex-center">
        <span class="flex items-center gap-3 text-gray-500 pointer-events-none">
          <span v-html="UploadIcon" class="w-10 h-10" />
          <span class="text-xl">Drop files here</span>
        </span>
        <form ref="fileForm" class="hidden">
          <input type="file" id="f-loader-1" :accept="accept" class="hidden" :multiple="multiple"
            @change="onFilePicked" />
        </form>
      </label>
      <div class="flex flex-col w-full">
        <TransitionGroup name="slide-fade">
          <template v-for="(file, i) in uploadedFilesInfos" :key="i">
            <div class="p-2">
              <div class="lvp-file-uploaded-container">
                <div class="lvp-file-container" :class="{
                  'lvp-file-image-container': file.fileType === 'image',
                  'bg-black/50 h-44': file.fileType != 'image',
                }">
                  <div class="grid grid-cols-2 gap-1">
                    <span>
                      <p class="line-clamp-1">
                        {{ file.fileName }}
                      </p>
                      <p class="text-xs text-gray-300">
                        {{ file.fileFormatedSize }}
                      </p>
                    </span>
                    <div class="flex items-center justify-end gap-3">
                      <span class="text-right" v-if="server">
                        <p>Upload finished</p>
                        <p class="text-xs text-gray-400">Tap to view</p>
                      </span>
                      <button type="button" v-if="file.fileType != 'image'" @click="removeFile(i)"
                        class="p-1 text-white transition bg-black rounded-full ring-1 ring-transparent hover:ring-white w-7 h-7 flex-center">
                        <span v-html="CloseIcon" class="w-4 h-4" />
                      </button>
                    </div>
                  </div>
                  <div class="w-full gap-2 flex-center" v-if="file.fileType === 'image'">
                    <button type="button" @click="
                      showCropper(
                        i,
                        file.imagePreview as string,
                        file.fileOriginalName
                      )
                      "
                      class="p-1 text-white transition bg-black rounded-full ring-1 ring-transparent hover:ring-white w-7 h-7 flex-center">
                      <span v-html="EditIcon" class="w-4 h-4" />
                    </button>
                    <button type="button" @click="removeFile(i)"
                      class="p-1 text-white transition bg-black rounded-full ring-1 ring-transparent hover:ring-white w-7 h-7 flex-center">
                      <span v-html="CloseIcon" class="w-4 h-4" />
                    </button>
                    <button type="button"
                      class="p-1 text-white transition bg-black rounded-full ring-1 ring-transparent hover:ring-white w-7 h-7 flex-center">
                      <span v-html="EyeIcon" class="w-4 h-4" />
                    </button>
                    <button type="button" v-if="server"
                      class="p-1 text-white transition bg-black rounded-full ring-1 ring-transparent hover:ring-white w-7 h-7 flex-center">
                      <span v-html="UploadIcon" class="w-4 h-4" />
                    </button>
                  </div>
                </div>
                <img v-if="file.fileType === 'image'" class="absolute top-0 left-0 z-10 object-cover w-full h-full"
                  :src="file.imagePreview" alt="" />
              </div>
              <small class="flex w-full text-red-500">
                {{ $page.props.errors[field + "." + i] }}
              </small>
            </div>
          </template>
        </TransitionGroup>
      </div>
    </div>
    <small v-if="helperText && helperText.length > 0" class="text-gray-300">{{
      helperText
    }}</small>
    <small v-if="errorText && errorText.length > 0" class="text-red-500">{{
      errorText
    }}</small>
    <CropperModal v-model:show="cropper.show" :image="cropper.image" :imageName="cropper.imageName"
      @onCrop="onCroppe" />
  </div>
</template>
<script setup lang="ts">
import { reactive, ref } from "vue";
import {
  CloseIcon,
  UploadIcon,
  EyeIcon,
  EditIcon,
} from "lvp/helpers/lvp_icons";
import { TransitionRoot, TransitionChild } from "@headlessui/vue";
import CropperModal from "./CropperModal.vue";
const props = defineProps({
  modelValue: {
    type: Object,
    default: null,
  },
  label: {
    type: String,
    default: null,
  },
  field: {
    type: String,
    default: null,
  },
  accept: {
    type: String,
    default: null,
  },
  height: {
    type: String,
    default: "h-32",
  },
  multiple: {
    type: Boolean,
    default: false,
  },
  required: {
    type: Boolean,
    default: false,
  },
  server: {
    type: String,
    default: null,
  },
  helperText: String as () => String | null | undefined,
  errorText: String as () => String | null | undefined,
  disabled: Boolean,
  readonly: Boolean,
});

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

const uploadedFilesInfos = ref<FileInfo[]>([]);
const cropper = reactive({
  show: false,
  currentFile: <number | null>null,
  image: <string | null>null,
  croppedImage: <File | null>null,
  imageName: <string | null>null,
});
const errorIsArray = (errors: any, field: string): string | null => {
  const error = errors[field];
  return error ? (Array.isArray(error) ? error[0] : error) : null;
};
const showCropper = (index: number, data: string, name: string) => {
  cropper.show = true;
  cropper.image = data;
  cropper.imageName = name;
  cropper.currentFile = index;
};
const emit = defineEmits(["update:modelValue"]);
const _modelValue = ref(props.modelValue);

const onCroppe = (data: File) => {
  cropper.show = false;
  cropper.croppedImage = data;
  if (cropper.currentFile !== null) {
    uploadedFilesInfos.value[cropper.currentFile].file = data;
    uploadedFilesInfos.value[cropper.currentFile].fileSize =
      data.size.toString();
    uploadedFilesInfos.value[cropper.currentFile].fileFormatedSize =
      formatFileSize(data.size);
    uploadedFilesInfos.value[cropper.currentFile].imagePreview =
      URL.createObjectURL(data);
    updateModelValue();
  }
};

const fileInput = ref<HTMLInputElement | null>(null);
const onOver = (event: Event) => {
  event.preventDefault();
  event.stopPropagation();
  if (!props.readonly) {
    fileInput.value?.classList.add("border-lvp-primary");
    emit("update:modelValue", (event.target as HTMLInputElement).value);
  }
};
const onLeave = (event: Event) => {
  event.preventDefault();
  if (!props.readonly) {
    fileInput.value?.classList.remove("border-lvp-primary");

    emit("update:modelValue", (event.target as HTMLInputElement).value);
  }
};
const loadFile = async (files: FileList) => {
  const promises = Array.from(files).map((file) => {
    return new Promise<void>((resolve, reject) => {
      const _file_info = {
        imagePreview: "",
        fileName: file.name,
        fileOriginalName: file.name,
        fileSize: file.size.toString(),
        fileFormatedSize: formatFileSize(file.size),
        fileType: getFileType(file),
        fileExtension: file.type.split("/")[1],
        file: file,
      };

      if (file.type.startsWith("image")) {
        const reader = new FileReader();
        reader.onload = (e) => {
          _file_info.imagePreview = e.target?.result as string;
          if (props.multiple) {
            uploadedFilesInfos.value.push(_file_info);
          } else {
            uploadedFilesInfos.value = [_file_info];
          }
          resolve();
        };
        reader.onerror = () => {
          reject(new Error("Failed to read file"));
        };
        reader.readAsDataURL(file);
      } else {
        if (props.multiple) {
          uploadedFilesInfos.value.push(_file_info);
        } else {
          uploadedFilesInfos.value = [_file_info];
        }
        resolve();
      }
    });
  });

  try {
    await Promise.all(promises);
    updateModelValue();
    console.log("All files have been loaded");
  } catch (error) {
    console.error("An error occurred while loading files", error);
  }
};

const removeFile = (index: number) => {
  uploadedFilesInfos.value.splice(index, 1);
};

const onDrop = (event: Event) => {
  event.preventDefault();
  if (!props.readonly) {
    fileInput.value?.classList.remove("border-lvp-primary");
    //@ts-ignore
    const files = event.dataTransfer?.files;
    console.log("file droped", files);
    if (props.multiple && files.length > 1) {
      alert("Only one file can be uploaded");
    } else {
      loadFile(files);
    }
  }

  // emit("update:modelValue", (event.target as HTMLInputElement).value);
};

const updateModelValue = () => {
  const files = uploadedFilesInfos.value.map((file) => file.file);
  emit("update:modelValue", files);
};
const fileForm = ref();
const onFilePicked = (event: Event) => {
  event.preventDefault();
  if (!props.readonly) {
    fileInput.value?.classList.remove("border-lvp-primary");
    //@ts-ignore
    const files = <FileList>event.target?.files;
    if (files.length > 0) {
      if (props.multiple && files.length > 1) {
        alert("Only one file can be uploaded");
      } else {
        loadFile(files);
      } // emit("update:modelValue", (event.target as HTMLInputElement).value);
      fileForm.value.reset();
    }
  }
};

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
</script>
<style>
.v-enter-active,
.v-leave-active {
  transition: opacity 0.5s ease;
}

.v-enter-from,
.v-leave-to {
  opacity: 0;
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.slide-fade-enter-active,
.slide-fade-leave-active {
  transition: all 0.3s ease-out;
}

.slide-fade-enter-from,
.slide-fade-leave-to {
  transform: translateY(20px);
  opacity: 0;
}
</style>
