<script setup lang="ts">
import { useAppStore } from '@/stores/app';
import { useUserStore } from '@/stores/user';

const appStore = useAppStore();
const userStore = useUserStore();
onLaunch(() => {
    appStore.getConfig();
    // #ifdef MP-WEIXIN
    userStore.getUser();
    // #endif

    // const appStore = useAppStore()
    // const { inital } = useTheme(appStore.theme)
    // inital()

    // #ifdef MP-WEIXIN
    // 解决IOS播放没声音
    uni.setInnerAudioOption({
        obeyMuteSwitch: false,
    });
    // #endif
});
</script>

<style lang="scss">
// @import url("./styles/reset.scss");
</style>
