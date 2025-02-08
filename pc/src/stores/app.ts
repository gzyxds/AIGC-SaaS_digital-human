import { apiGetSiteConfig } from '~/api/common';

export const useAppStore = defineStore('app', () => {
    const showNavbarTitle = ref<boolean>(false);

    const siteConfig = ref<SiteConfig | null>(null);

    const setShowNavbarTitle = (state?: boolean) => {
        showNavbarTitle.value = state === undefined ? !showNavbarTitle.value : state;
    };

    const getSiteConfig = async () => {
        siteConfig.value = await apiGetSiteConfig();
    };

    return {
        showNavbarTitle,
        siteConfig,
        setShowNavbarTitle,
        getSiteConfig,
    };
});
