import { App, Plugin } from 'vue';
import Toast from './Toast.vue'
interface ToastWidget {
    message: string;
    title?: string;
    type: "success" | "error" | "warning" | "info";
}

type ToastPostion = "top-left" | "top-right" | "bottom-left" | "bottom-right" | "top-center" | "bottom-center"
const useToast = ($data: ToastWidget, $position: ToastPostion = "top-right", $duration: number = 3000) => {
    const event = new CustomEvent('lvp-toast', {
        detail: {
            data: $data,
            position: $position,
            duration: $duration
        }
    });
    document.dispatchEvent(event);
}

interface ToastPluginOptions {
    position: ToastPostion
    duration: number
}
const plugin: Plugin = {
    install(app: App, options: ToastPluginOptions = { position: 'top-right', duration: 500 }) {
        // Inject your plugin here
        // app.config.globalProperties.$myPlugin = new MyPlugin(options);
        console.log('Toast options', options);
        const _useToast = ($data: ToastWidget, $position: ToastPostion = options.position, $duration: number = options.duration) => {
            const event = new CustomEvent('lvp-toast', {
                detail: {
                    data: $data,
                    position: $position,
                    duration: $duration
                }
            });
            document.dispatchEvent(event);
        }
        app.component('lvp-toast', Toast);
        //@ts-ignore
        app.config.globalProperties.toast = _useToast;
        app.provide('toast', _useToast);
    },
};

export default plugin;
export type { ToastPostion, ToastWidget }
export { useToast }
