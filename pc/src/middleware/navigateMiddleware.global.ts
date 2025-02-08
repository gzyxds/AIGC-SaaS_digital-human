import { apiGetArticleList } from '~/api/article';
import { PageEnum } from '~/enums/pageEnum';
import { VariableEnum } from '~/enums/variableEnum';

export default defineNuxtRouteMiddleware(async (to, from) => {
    const userStore = useUserStore();
    const appStore = useAppStore();
    const controlsStore = useControlsStore();

    // 每次跳转页面时获取最新配置信息，首次进入时先等待获取
    if (appStore.siteConfig === null) {
        await appStore.getSiteConfig();
    } else {
        appStore.getSiteConfig();
    }

    if (userStore.isLogin && userStore.userInfo === null) {
        try {
            await userStore.getUser();
        } catch (error) {
            return userStore.logout(true, from);
        }
    } else {
        if (!userStore.isLogin) {
            if (to.meta.auth !== false) {
                isClient(async () => {
                    useMessage().warn('请先登录');
                    controlsStore.setLoginModal(true);
                });
                return navigateTo(PageEnum.HOME);
            }
        } else {
            userStore.refreshToken();
        }
    }
});
