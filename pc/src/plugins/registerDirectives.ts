import type { DirectiveBinding } from 'vue';

export default defineNuxtPlugin((nuxtApp) => {
    nuxtApp.vueApp.directive('auth', {
        mounted(el) {
            el.addEventListener(
                'click',
                async (event: Event) => {
                    const userStore = useUserStore();
                    const controlsStore = useControlsStore();
                    const isLogged = userStore.userInfo !== null && userStore.isLogin;

                    if (!isLogged) {
                        event.preventDefault();
                        event.stopImmediatePropagation();
                        controlsStore.setLoginModal(true);
                    }
                },
                true
            );
        },
    });

    interface parentElement extends HTMLElement {
        _loadingOverlay: HTMLElement;
    }

    interface loadingBindOpts {
        status: boolean;
        loadText?: string;
    }

    nuxtApp.vueApp.directive('load', {
        mounted(el: parentElement, binding: DirectiveBinding<boolean | loadingBindOpts>) {
            if (el) {
                const roundedClassName = Array.from(el.classList).filter((className) =>
                    /rounded/.test(className)
                );

                const status =
                    binding.value === undefined
                        ? true
                        : typeof binding.value === 'boolean'
                          ? binding.value
                          : binding.value.status;

                const loadText =
                    binding.value === undefined
                        ? ''
                        : typeof binding.value === 'boolean'
                          ? ''
                          : binding.value.loadText;

                // 创建蒙层元素
                const overlay = document.createElement('div');

                overlay.className =
                    'center absolute inset-0 size-full backdrop-blur-lg bg-background/50 z-[50] flex-col';
                if (roundedClassName.length > 0) {
                    overlay.classList.add(...roundedClassName);
                } else {
                    overlay.classList.add('rounded-lg', 'md:rounded-xl');
                }

                const spinner = document.createElement('div');
                spinner.className =
                    'size-8 flex-shrink-0 rounded-full animate-spin border-[3px] border-[var(--theme)] !border-t-transparent';

                // 加入到蒙层
                overlay.appendChild(spinner);
                el.style.position = 'relative';

                const loadTextElm = document.createElement('span');
                loadTextElm.className =
                    'text-sm text-forground/60 mt-2 text-primary dark:text-white';
                loadTextElm.innerText = loadText || '';

                if (loadText) {
                    overlay.appendChild(loadTextElm);
                }

                el._loadingOverlay = overlay; // 保存蒙层元素用于后续销毁
            }
        },
        updated(el: parentElement, binding: DirectiveBinding<boolean | loadingBindOpts>) {
            if (el) {
                const status =
                    binding.value === undefined
                        ? true
                        : typeof binding.value === 'boolean'
                          ? binding.value
                          : binding.value.status;

                if (el._loadingOverlay) {
                    if (status && !el.contains(el._loadingOverlay)) {
                        el.appendChild(el._loadingOverlay);
                    }
                    if (!status && el.contains(el._loadingOverlay)) {
                        el._loadingOverlay.remove();
                    }
                }
            }
        },
        unmounted(el: parentElement) {
            if (el) {
                // 清理蒙层元素
                if (el._loadingOverlay && el.contains(el._loadingOverlay)) {
                    el._loadingOverlay.remove();
                }
            }
        },
    });
});
