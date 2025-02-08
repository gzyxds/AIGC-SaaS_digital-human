import VConsole from 'vconsole';

export default defineNuxtPlugin((nuxtApp) => {
    isDev(() => {
        if (isMobile()) new VConsole();
    });
});
