export default defineNuxtPlugin((nuxtApp) => {
    nuxtApp.vueApp.config.errorHandler = (error, instance, info) => {};

    nuxtApp.hook('vue:error', (error, instance, info) => {
        console.error('vueError', error);
    });

    nuxtApp.hook('app:error', (err) => {
        console.log('appError', err);
    });
});
