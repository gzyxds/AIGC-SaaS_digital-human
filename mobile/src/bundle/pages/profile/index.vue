<script setup lang="ts">
import { ProfileTabEnum } from '@/enums/variableEnum';
import { ref } from 'vue';
import ProfileList from './components/ProfileList.vue';
import SoundList from './components/SoundList.vue';

const tabsList = ref(['我的形象', '我的声音', '声音合成']);
const currentTab = ref(0);

const changeTab = (e: any) => {
    console.log('选中tabs => ', e);
    currentTab.value = e.index;
};

onLoad((options: any) => {
    console.log('options => ', options);
    if (options.type) {
        currentTab.value = Number(options.type);
    }
});
</script>

<template>
    <view class="profile">
        <!-- tabs标签 -->
        <fui-tabs
            :current="currentTab"
            :tabs="tabsList"
            background="#0d0d0d"
            color="#ffffffb3"
            selected-color="#ffffff"
            slider-background="#1453e9"
            :is-fixed="true"
            @change="changeTab"
        />

        <!-- 形象-内容卡片 -->
        <view
            v-if="currentTab === ProfileTabEnum.MyProfile"
            class="profile-list mt-[102rpx] px-[24rpx]"
        >
            <profile-list />
        </view>

        <!-- 我的声音-内容卡片 -->
        <view
            v-if="currentTab === ProfileTabEnum.MySound"
            class="profile-list mt-[102rpx] px-[24rpx]"
        >
            <sound-list :type="currentTab" />
        </view>

        <!-- 声音合成-内容卡片 -->
        <view
            v-if="currentTab === ProfileTabEnum.SoundSynthesis"
            class="profile-list mt-[102rpx] px-[24rpx]"
        >
            <sound-list :type="currentTab" />
        </view>
    </view>
</template>

<style lang="scss" scoped>
.profile-list {
    height: calc(100vh - 102rpx - env(safe-area-inset-bottom));
}
</style>
