<template>
    <div class="toast-container top-left" id="toast-container-top-left"></div>
    <div class="toast-container top-right" id="toast-container-top-right"></div>
    <div
        class="toast-container top-center"
        id="toast-container-top-center"
    ></div>
    <div
        class="toast-container bottom-left"
        id="toast-container-bottom-left"
    ></div>
    <div
        class="toast-container bottom-right"
        id="toast-container-bottom-right"
    ></div>
    <div
        class="toast-container bottom-center"
        id="toast-container-bottom-center"
    ></div>
</template>
<script setup lang="ts">
import { onMounted, onUnmounted } from "vue";
import type { ToastPostion, ToastWidget } from "lvp/Plugins/toast";

const svg_icons = {
    success: `
    <svg viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
    <circle cx="9.5" cy="9.5" r="8.5" fill="#0AFF5E" fill-opacity="0.2"/>
    <path fill-rule="evenodd" clip-rule="evenodd" d="M6.90702 9.12185C6.47345 8.81097 5.86995 8.91042 5.55906 9.34399V9.34399C5.24818 9.77756 5.34763 10.3811 5.7812 10.6919L8.91062 12.9359C9.35945 13.2577 9.98419 13.1547 10.306 12.7059L14.3054 7.12827C14.5728 6.75529 14.4873 6.23612 14.1143 5.96868V5.96868C13.7413 5.70124 13.2222 5.7868 12.9547 6.15978L10.0812 10.1673C9.75933 10.6162 9.13459 10.7191 8.68576 10.3973L6.90702 9.12185Z" fill="#0AFF5E"/>
    </svg>
    `,
    error: `
    <svg viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
    <circle cx="9.5" cy="9.5" r="8.5" fill="#FF2063" fill-opacity="0.2"/>
    <path fill-rule="evenodd" clip-rule="evenodd" d="M11.4093 12.5C11.7105 12.8012 12.199 12.8012 12.5002 12.5C12.8015 12.1987 12.8015 11.7103 12.5002 11.4091L10.591 9.49989L12.5001 7.59082C12.8013 7.28957 12.8013 6.80114 12.5001 6.49989C12.1988 6.19864 11.7104 6.19864 11.4092 6.49989L9.5001 8.40896L7.59103 6.4999C7.28978 6.19864 6.80136 6.19864 6.5001 6.4999C6.19885 6.80115 6.19885 7.28957 6.50011 7.59082L8.40917 9.49989L6.49999 11.4091C6.19874 11.7103 6.19874 12.1987 6.49999 12.5C6.80124 12.8012 7.28967 12.8012 7.59092 12.5L9.5001 10.5908L11.4093 12.5Z" fill="#CE2525"/>
    <path fill-rule="evenodd" clip-rule="evenodd" d="M11.4093 12.5C11.7105 12.8012 12.199 12.8012 12.5002 12.5C12.8015 12.1987 12.8015 11.7103 12.5002 11.4091L10.591 9.49989L12.5001 7.59082C12.8013 7.28957 12.8013 6.80114 12.5001 6.49989C12.1988 6.19864 11.7104 6.19864 11.4092 6.49989L9.5001 8.40896L7.59103 6.4999C7.28978 6.19864 6.80136 6.19864 6.5001 6.4999C6.19885 6.80115 6.19885 7.28957 6.50011 7.59082L8.40917 9.49989L6.49999 11.4091C6.19874 11.7103 6.19874 12.1987 6.49999 12.5C6.80124 12.8012 7.28967 12.8012 7.59092 12.5L9.5001 10.5908L11.4093 12.5Z" fill="#FF2063"/>
    </svg>
    `,
    warning: `
    <svg viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
    <circle cx="9.5" cy="9.5" r="8.5" fill="#DEFF5C" fill-opacity="0.2"/>
    <path fill-rule="evenodd" clip-rule="evenodd" d="M9.50005 6C8.83731 6 8.30005 6.53726 8.30005 7.2V10.4C8.30005 11.0627 8.83731 11.6 9.50005 11.6C10.1628 11.6 10.7 11.0627 10.7 10.4V7.2C10.7 6.53726 10.1628 6 9.50005 6ZM9.10005 12.4C8.65822 12.4 8.30005 12.7582 8.30005 13.2C8.30005 13.6418 8.65822 14 9.10005 14H9.90005C10.3419 14 10.7 13.6418 10.7 13.2C10.7 12.7582 10.3419 12.4 9.90005 12.4H9.10005Z" fill="#D0CB5C"/>
    </svg>
    `,
    info: `
    <svg viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
    <circle cx="9.5" cy="9.5" r="8.5" fill="#5C76FF" fill-opacity="0.2"/>
    <path fill-rule="evenodd" clip-rule="evenodd" d="M9.50005 6C8.83731 6 8.30005 6.53726 8.30005 7.2V10.4C8.30005 11.0627 8.83731 11.6 9.50005 11.6C10.1628 11.6 10.7 11.0627 10.7 10.4V7.2C10.7 6.53726 10.1628 6 9.50005 6ZM9.10005 12.4C8.65822 12.4 8.30005 12.7582 8.30005 13.2C8.30005 13.6418 8.65822 14 9.10005 14H9.90005C10.3419 14 10.7 13.6418 10.7 13.2C10.7 12.7582 10.3419 12.4 9.90005 12.4H9.10005Z" fill="#5C76FF"/>
    </svg>
    `,
};

const toastWidget = ($data: ToastWidget) => {
    const html_text = `
     <div class="toast ${$data.type}">
            <span class="w-8 h-8">
                ${svg_icons[$data.type]}
            </span>
            <div class="">
                <h6>${$data.title}</h6>
                <p>${$data.message}</p>
            </div>
        </div>
    `;

    const container = document.createElement("div");
    container.classList.add("toast-box");
    container.innerHTML = html_text;
    return container;
};
const _showToast = (
    $data: ToastWidget,
    $position: ToastPostion,
    $duration: number = 500
) => {
    const toast = toastWidget({
        message: $data.message,
        title: $data.title,
        type: $data.type,
    });
    document.getElementById("toast-container-" + $position)?.appendChild(toast);

    setTimeout(() => {
        closeToast(toast);
    }, $duration);
};

const closeToast = (toast: HTMLDivElement) => {
    toast.classList.add("close");
    setTimeout(() => {
        toast.remove();
    }, 250);
};

onMounted(() => {
    const showToastEvent = (e: any) => {
        _showToast(
            e.detail.data as ToastWidget,
            e.detail.position,
            e.detail.duration
        );
    };
    document.addEventListener("lvp-toast", showToastEvent);
    onUnmounted(() => {
        document.removeEventListener("lvp-toast", showToastEvent);
    });
});
</script>
<style lang="scss">
.toast-container {
    position: fixed;
    z-index: 1000;
    --toast-anim-duration: 0.3s;
    --toast-anim: cubic-bezier(0.68, -0.55, 0.265, 1.55);
    &.top-left {
        top: 20px;
        left: 20px;
        .toast-box {
            animation: show-toast-from-left var(--toast-anim-duration)
                var(--toast-anim);
        }
        .toast-box.close {
            animation: close-toast-from-left var(--toast-anim-duration)
                var(--toast-anim);
        }
    }

    &.top-right {
        top: 20px;
        right: 20px;
        .toast-box {
            animation: show-toast-from-right var(--toast-anim-duration)
                var(--toast-anim);
        }
        .toast-box.close {
            animation: close-toast-from-right var(--toast-anim-duration)
                var(--toast-anim);
        }
    }

    &.bottom-left {
        bottom: 20px;
        left: 20px;
        .toast-box {
            animation: show-toast-from-left var(--toast-anim-duration)
                var(--toast-anim);
        }
        .toast-box.close {
            animation: close-toast-from-left var(--toast-anim-duration)
                var(--toast-anim);
        }
    }

    &.bottom-right {
        bottom: 20px;
        right: 20px;
        .toast-box {
            animation: show-toast-from-right var(--toast-anim-duration)
                var(--toast-anim);
        }
        .toast-box.close {
            animation: close-toast-from-right var(--toast-anim-duration)
                var(--toast-anim);
        }
    }
    .toast {
        width: 300px;
        display: flex;
        gap: 0.5rem;
        align-items: center;
        margin: 1rem 0rem;
        padding: 0.5rem;
        background: white;
        border-left: 5px solid #0aff5e;
        box-shadow: 0px 1px 20px 0px #c0c0c078;
        svg {
            color: #0aff5e;
        }
        h6 {
            font-weight: 600;
            font-size: 14px;
        }
        p {
            font-size: 12px;
        }
        &.success {
            border-color: #0aff5e;
            svg {
                color: #0aff5e;
            }
        }
        &.error {
            border-color: #ff3860;
            svg {
                color: #ff3860;
            }
        }
        &.warning {
            border-color: #ffdd57;
            svg {
                color: #ffdd57;
            }
        }
        &.info {
            border-color: #209cee;
            svg {
                color: #209cee;
            }
        }
    }
}

@keyframes show-toast-from-bottom {
    0% {
        transform: translateY(100%);
    }
    100% {
        transform: translateY(0);
    }
}

@keyframes close-toast-from-bottom {
    0% {
        transform: translateY(0%);
    }
    100% {
        transform: translateY(100%);
    }
}

@keyframes show-toast-from-top {
    0% {
        transform: translateY(-100%);
    }
    100% {
        transform: translateY(0);
    }
}

@keyframes close-toast-from-top {
    0% {
        transform: translateY(0%);
    }
    100% {
        transform: translateY(-100%);
    }
}

@keyframes show-toast-from-left {
    0% {
        transform: translateX(-100%);
    }
    100% {
        transform: translateX(0);
    }
}

@keyframes close-toast-from-left {
    0% {
        transform: translateX(0%);
    }
    100% {
        transform: translateX(-100%);
    }
}

@keyframes show-toast-from-right {
    0% {
        transform: translateX(100%);
    }
    100% {
        transform: translateX(0);
    }
}

@keyframes close-toast-from-right {
    0% {
        transform: translateX(0%);
    }
    100% {
        transform: translateX(100%);
    }
}
</style>
