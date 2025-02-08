import { type Config, driver, type DriveStep } from 'driver.js';

export const useDriver = (
    step: DriveStep[],
    opts?: {
        cache?: {
            enable: boolean;
            key: string;
        };
        config?: Config;
    }
) => {
    const state = reactive<{
        step: number;
        status: boolean;
    }>({
        step: 1,
        status: false,
    });

    const driverObj = driver({
        showProgress: true,
        nextBtnText: '下一步',
        prevBtnText: '上一步',
        doneBtnText: '我知道了',
        popoverClass: 'driver__class',
        steps: step,
        ...opts?.config,
    });

    const start = () => {
        if (opts?.cache && opts.cache.enable && isClient()) {
            if (localStorage.getItem(opts.cache.key) === '1') {
                return;
            } else {
                localStorage.setItem(opts.cache.key, '1');
            }
        }
        state.status = true;
        driverObj.drive();
        state.step = 1;
    };

    const stop = () => {
        state.status = false;
        driverObj.destroy();
        state.step = 1;
    };

    const next = () => {
        driverObj.moveNext();
        state.step += 1;
    };

    const prev = () => {
        driverObj.movePrevious();
        state.step -= 1;
    };

    return {
        state,
        start,
        stop,
        next,
        prev,
    };
};
