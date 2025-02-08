/**
 * 等待钩子执行完后刷新用户信息
 * @param hook 触发钩子函数，必须返回一个 Promise
 * @returns hook 执行后的返回值（若有）
 */
export const useRefreshUser = async <T = any>(hook: (...args: any[]) => Promise<T>): Promise<T> => {
    const userStore = useUserStore();
    try {
        const result = await hook();
        // 刷新用户信息
        await userStore.getUser();
        return result;
    } catch (error) {
        throw new Error(error as string);
    }
};
