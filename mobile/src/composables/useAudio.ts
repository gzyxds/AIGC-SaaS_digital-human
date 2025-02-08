// 引入 Vue 的组合式 API
import { onMounted, onUnmounted, ref } from 'vue';

/**
 * @description 音频播放
 */
export function useAudio() {
    // 创建音频上下文
    const audioContext = uni.createInnerAudioContext();
    // #ifdef MP-WEIXIN
    // 解决IOS播放没声音
    audioContext.obeyMuteSwitch = false;
    // #endif
    // 定义一个响应式变量，用于存储音频播放状态
    const isPlaying = ref(false);

    // 在组件挂载时创建音频上下文并设置事件监听
    onMounted(() => {
        // 音频播放事件监听
        audioContext.onPlay(() => {
            isPlaying.value = true;
        });
        // 音频暂停事件监听
        audioContext.onPause(() => {
            isPlaying.value = false;
        });
        // 音频停止事件监听
        audioContext.onStop(() => {
            isPlaying.value = false;
        });
        // 音频播放结束事件监听
        audioContext.onEnded(() => {
            isPlaying.value = false;
        });
        // 音频播放错误事件监听
        audioContext.onError((res) => {
            isPlaying.value = false;
            console.log('音频播放错误', res.errMsg);
            useToast('音频播放错误', res.errMsg);
        });
    });

    // 在组件卸载时销毁音频上下文
    onUnmounted(() => {
        if (audioContext) {
            audioContext.destroy();
        }
    });

    // 播放音频的方法，接收音频源路径作为参数
    const play = (src: string) => {
        if (audioContext) {
            audioContext.src = src;
            audioContext.play();
        } else {
            console.log('音频上下文不存在');
        }
    };

    // 暂停音频的方法
    const pause = () => {
        if (audioContext && isPlaying.value) {
            audioContext.pause();
        }
    };

    // 停止音频的方法
    const stop = () => {
        if (audioContext) {
            audioContext.stop();
        }
    };

    // 返回音频控制方法和状态
    return {
        play,
        pause,
        stop,
        isPlaying,
    };
}
