<script setup lang="ts">
import DefaultAvatar from '@/static/images/default_avatar.png';
import UserService from './components/UserService.vue';

const userStore = useUserStore();

const toPage = (url: string) => {
    uni.navigateTo({
        url,
    });
};

const toSwitchPage = (url: string) => {
    uni.switchTab({
        url,
    });
};

onShow(() => {
    userStore.getUser();
});
</script>

<template>
    <view class="user">
        <!-- 透明导航栏 -->
        <uni-nav-bar
            title=""
            :status-bar="true"
            :border="false"
            :fixed="false"
            :background-color="`rgba(256,256, 256, ${0})`"
        />

        <!-- 用户信息 -->
        <navigator url="/bundle/pages/user_set/index">
            <view start px-4.5 pt-1.5>
                <image
                    h-11.5
                    w-11.5
                    flex-none
                    rounded-full
                    :src="userStore.userInfo?.avatar || DefaultAvatar"
                    mode="scaleToFill"
                />
                <view text="36rpx" ml-3 center font-700>
                    <view v-if="userStore.isLogin" line-clamp-1>
                        {{ userStore.userInfo?.nickname }}
                    </view>
                    <view v-else line-clamp-1> 请先登录 </view>
                    <view i-tabler:chevron-right ml-1 flex-none />
                </view>
            </view>
        </navigator>

        <view mb-5 mt-6 flex="~ justify-around">
            <!-- <view v-for="item in userStore.userInfo?.static" :key="item.label" text-center>
                <view text="xl font-500" mb-0.5>
                    {{ item.value }}
                </view>
                <view text="sm">
                    {{ item.label }}
                </view>
            </view> -->

            <view text-center @click="toPage('/bundle/pages/profile/index?type=0')">
                <view text="xl font-500" mb-0.5>
                    {{ userStore.userInfo?.image_num || 0 }}
                </view>
                <view text="sm"> 数字形象 </view>
            </view>

            <view text-center @click="toSwitchPage('/pages/product/index')">
                <view text="xl font-500" mb-0.5>
                    {{ userStore.userInfo?.avatar_num || 0 }}
                </view>
                <view text="sm"> 数字分身 </view>
            </view>
            <view text-center @click="toPage('/bundle/pages/profile/index?type=2')">
                <view text="xl font-500" mb-0.5>
                    {{ userStore.userInfo?.timbre_num || 0 }}
                </view>
                <view text="sm"> 定制音色 </view>
            </view>
            <view text-center @click="toPage('/bundle/pages/profile/index?type=1')">
                <view text="xl font-500" mb-0.5>
                    {{ userStore.userInfo?.voice_num || 0 }}
                </view>
                <view text="sm"> 克隆声音 </view>
            </view>
        </view>

        <!-- 算力卡片 -->
        <view
            class="user-hashrate-card"
            position-relative
            mx-3
            mb-5
            h-216rpx
            w-702rpx
            overflow-hidden
            rounded-28rpx
            pl-6.5
            @click.stop="toPage('/bundle/pages/hashrate/index')"
        >
            <view mt-5.5 text-36rpx font-700> 算力充值 </view>
            <view text="xs foreground-muted" mt-2.5 start>
                <view>我的算力:</view>
                <image src="@/static/icons/icon-lightning.png" h-36rpx w-36rpx mode="scaleToFill" />
                <view>{{ userStore.userInfo?.user_money || 0 }}</view>
            </view>
            <view text="xs foreground/60" mt-1> 尊享8+数字权益，每日赠送算力 </view>

            <!-- 右上角 -->
            <view
                position="absolute top-1 right-3"
                center
                @click.stop="toPage('/bundle/pages/hashrate_log/index')"
            >
                <view text="xs primary-muted"> 算力明细 </view>
                <view i-tabler:chevron-right text="xs primary-muted" />
            </view>
        </view>

        <!-- 服务菜单 -->
        <user-service :type="1" />
    </view>

    <!-- tabbar -->
    <tabbar :current="2" />
</template>

<style lang="scss" scoped>
.user {
    background-repeat: no-repeat;
    background-size: 750rpx 300rpx;
    background-position: -100rpx -130rpx;

    .user-hashrate-card {
        background: url('@/static/images/bg_user_hashrate.png') no-repeat;
        background-size: 702rpx 216rpx;
    }
}
</style>

<route lang="json">
{
    "layout": "auth"
}
</route>
