/**
 * 创建一个轮询任务
 * @param task 任务方法
 * @param options 其他配置
 */
export const usePollingTask = (task: (stopFn: () => void) => any, options?: PollingOptions) => {
    let intervalId: NodeJS.Timeout | null = null;
    let attempt = 0; // 当前尝试次数
    const interval = options?.interval || 3000;
    const maxAttempts = options?.maxAttempts || 0;
    const stopCondition = options?.stopCondition || false;
    const maxWaitTime = options?.maxWaitTime || 0;
    const startTime = Date.now();

    // 启动轮询
    const startPolling = () => {
        if (intervalId) {
            clearPolling(); // 清除已有的轮询任务
        }

        intervalId = setInterval(async () => {
            // 检查停止条件
            const shouldStop =
                typeof stopCondition === 'function' ? stopCondition() : stopCondition;

            const isTimeout = maxWaitTime > 0 && Date.now() - startTime > maxWaitTime;
            const isExceedCount = maxAttempts > 0 && attempt >= maxAttempts;

            if (shouldStop || isExceedCount || isTimeout) {
                clearPolling();
                return;
            }

            attempt++;

            // 执行传入的任务函数
            try {
                await task(clearPolling);
            } catch (error) {
                clearPolling();
                throw new Error(`轮询任务执行失败，错误信息：${error}`);
            }
        }, interval);
    };

    // 停止轮询
    const clearPolling = (callback?: () => void): void => {
        if (intervalId) {
            clearInterval(intervalId);
            intervalId = null;
            attempt = 0;
            options?.onEnded?.();
        }
        callback?.();
    };

    // 开始轮询并返回停止函数
    const start = (callback?: () => void) => {
        startPolling();
        callback?.();
    };

    // 监听组件卸载
    const router = useRouter();
    router.beforeEach(() => {
        clearPolling();
    });
    return {
        start,
        clear: clearPolling,
    };
};
