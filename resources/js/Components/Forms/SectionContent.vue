<template>
    <div class="">
        <div class="grid grid-cols-1 gap-3">
            <component v-for="(section, i) in sections" :is="sections_components[section.type]" v-bind="section.props"
                :key="`section-${section.type}-${i}`" @edit="edit" />
        </div>
    </div>
</template>
<script setup lang="ts">
const props = defineProps({
    sections: {
        type: Object,
        default: null,
    },
    formData: {
        type: Object,
        default: null,
    },
    label: {
        type: String as () => string | null | undefined,
        default: null,
    }, header: {
        type: Object,
        required: false,
    }, footer: {
        type: Object,
        required: false,
    }, cols: {
        type: Number,
        default: 1,
    },
    gap: {
        type: Number,
        default: 1,
    }, filled: {
        type: Boolean,
        default: true,
    }
});
</script>