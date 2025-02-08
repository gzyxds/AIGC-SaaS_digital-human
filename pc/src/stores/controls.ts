import { apiGetCustomerConfig } from '~/api/common';

/**
 * @description 全局控件状态管理
 */
export const useControlsStore = defineStore('controls', () => {
    /** 登录弹窗 */
    const loginModal = ref<boolean>(false);

    /** 客服弹窗 */
    const customerModal = ref<boolean>(false);

    /** 客服配置 */
    const customerConfig = ref<CustomerConfig>();

    /** 登录弹窗状态 */
    const setLoginModal = (state?: boolean) => {
        loginModal.value = state === undefined ? !loginModal.value : state;
    };

    /** 客服弹窗状态 */
    const setCustomerModal = async (state?: boolean) => {
        customerModal.value = state === undefined ? !customerModal.value : state;
        customerConfig.value = await apiGetCustomerConfig();
    };

    return {
        loginModal,
        customerModal,
        customerConfig,
        setLoginModal,
        setCustomerModal,
    };
});
