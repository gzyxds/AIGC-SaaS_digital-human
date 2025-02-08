import iconify from './libs/iconify.json';

console.log(
    'ℹ 当前环境：',
    process.env.NUXT_BUILD_ENV === 'development' ? 'development' : 'production'
);
console.log('ℹ 是否SSR：', process.env.NUXT_BUILD_SSR !== '0' ? '是' : '否');

export default defineNuxtConfig({
    compatibilityDate: '2024-12-02',
    devtools: { enabled: false },
    nitro: {
        prerender: {
            failOnError: false,
        },
        externals: {
            traceInclude: [],
        },
    },
    modules: [
        '@nuxt/icon',
        '@nuxt/ui',
        '@nuxt/image',
        '@nuxt/eslint',
        '@vueuse/nuxt',
        [
            '@pinia/nuxt',
            {
                autoImports: ['defineStore', ['defineStore', 'definePiniaStore']],
            },
        ],
        '@nuxtjs/device',
    ],
    ssr: process.env.NUXT_BUILD_SSR !== '0',
    icon: {
        provider:
            process.env.NUXT_BUILD_ENV === 'development' || process.env.NUXT_BUILD_SSR !== '0'
                ? 'server'
                : 'iconify',
        clientBundle:
            process.env.NUXT_BUILD_ENV === 'development' || process.env.NUXT_BUILD_SSR !== '0'
                ? {}
                : {
                      sizeLimitKb: 10 * 1024,
                      scan: true,
                      icons: iconify,
                  },
    },
    srcDir: 'src/',
    devServer: {
        port: 3333,
        host: ['localhost', '0.0.0.0'],
    },
    app: {
        pageTransition: { name: 'page', mode: 'out-in' },
        layoutTransition: { name: 'layout', mode: 'out-in' },
    },
    css: ['~/styles/global.scss', 'driver.js/dist/driver.css'],
    postcss: {
        plugins: {
            tailwindcss: {},
            autoprefixer: {},
        },
    },
    runtimeConfig: {
        public: {
            /** 接口请求域名 */
            apiBase: '',
            /** 接口请求前缀 */
            apiPrefix: '',
            /** 接口请求超时时间 */
            apiTimeout: 60000,
        },
    },
    vite: {
        css: {
            preprocessorOptions: {
                scss: {
                    api: 'modern-compiler',
                },
            },
        },
    },
});
