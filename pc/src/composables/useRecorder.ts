import Recorder from 'recorder-core/recorder.mp3.min';
import { boolean } from 'yup';

import { RecorderStatusEnum } from '~/enums/variableEnum';

export const useRecorder = (opt?: {
    sampleRate?: number;
    bitRate?: number;
    onProcess: (duration: number) => void;
}) => {
    /** 0-初始，1-录音中，2-暂停，3-结束 */
    const statusCode = ref<0 | 1 | 2 | 3>(0);

    const duration = ref<number>(0);

    const status = computed(() => {
        return {
            isInit: statusCode.value === RecorderStatusEnum.INIT,
            isRecording: statusCode.value === RecorderStatusEnum.RECORDING,
            isPause: statusCode.value === RecorderStatusEnum.PAUSE,
            isEnd: statusCode.value === RecorderStatusEnum.END,
        };
    });

    const result = reactive<{
        url: string;
        blob: Blob | null;
        duration: number;
    }>({
        url: '',
        blob: null,
        duration: 0,
    });

    let recorderInstance: any = null;

    /**
     * 开始录音
     * @param success 成功回调
     * @param fail 失败回调
     */
    const start = (success?: () => void, fail?: () => void) => {
        if (status.value.isRecording) return useMessage().warn('正在录音，请勿重复操作');
        let startTime: number = 0;
        recorderInstance = Recorder({
            type: 'mp3',
            sampleRate: opt?.sampleRate || 48000,
            bitRate: opt?.bitRate || 128,
            onProcess: () => {
                if (startTime === 0) startTime = Date.now();
                duration.value = Date.now() - startTime;
                opt?.onProcess(duration.value);
            },
        });

        recorderInstance.open(
            () => {
                recorderInstance.start();
                statusCode.value = RecorderStatusEnum.RECORDING;
                success?.();
            },
            (msg: string, isUserNotAllow: boolean) => {
                //用户拒绝未授权或不支持
                if (isUserNotAllow) useMessage().error('用户未授权录音或浏览器不支持');
                fail?.();
            }
        );
    };

    /**
     * 暂停录制
     */
    const pause = () => {
        statusCode.value = RecorderStatusEnum.PAUSE;
        recorderInstance.pause();
        return useMessage().info('录音已暂停');
    };

    /**
     * 恢复录制
     */
    const resume = () => {
        statusCode.value = RecorderStatusEnum.RECORDING;
        recorderInstance.resume();
        return useMessage().info('录音已恢复');
    };

    /**
     * 结束录音
     */
    const stop = (callback?: (blob: Blob, duration: number) => void) => {
        recorderInstance.stop(
            (blob: Blob, _duration: number) => {
                result.blob = blob;
                result.url = (window.URL || webkitURL).createObjectURL(blob);
                duration.value = _duration;
                result.duration = _duration;
                _close();
                callback?.(blob, _duration);
            },
            (msg: string) => {
                _close();
                console.log('录音失败:' + msg);
                throw new Error('录音失败:' + msg);
            }
            // true 自动关闭释放资源
        );
    };

    /**
     * 重置录音器
     */
    const reset = () => {
        if (status.value.isRecording && recorderInstance) {
            stop(() => {
                duration.value = 0;
                statusCode.value = RecorderStatusEnum.INIT;

                result.url = '';
                result.blob = null;
            });
        }
        duration.value = 0;
        statusCode.value = RecorderStatusEnum.INIT;

        result.url = '';
        result.blob = null;
    };

    /**
     * 关闭录制，释放资源
     * @param callback 释放资源后执行的回调
     */
    const _close = (callback?: () => void) => {
        recorderInstance.close();
        recorderInstance = null;
        statusCode.value = RecorderStatusEnum.END;
        callback?.();
    };

    return {
        start,
        pause,
        resume,
        stop,
        result,
        reset,
        status,
        statusCode,
        duration,
    };
};
