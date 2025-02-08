<script setup lang="ts">
const props = withDefaults(
    defineProps<{
        second?: number;
        countdownSwitch?: boolean;
    }>(),
    {
        second: 59,
        countdownSwitch: false,
    }
);

const emits = defineEmits(['handleCode', 'update:countdownSwitch']);

// 获取验证码
function handleCode() {
    console.log('获取验证码');
    emits('handleCode');
}

// 倒计时结束
function timeup() {
    emits('update:countdownSwitch', false);
    console.log('倒计时结束');
}
</script>

<template>
    <view v-if="!countdownSwitch" pl-2 text-primary @click="handleCode"> 获取验证码 </view>

    <view v-else mr-4 center>
        <uni-countdown
            :second="second"
            :start="countdownSwitch"
            :show-day="false"
            :show-hour="false"
            :show-minute="false"
            @timeup="timeup"
        />
        <view text-primary> 秒 </view>
    </view>
</template>

<style lang="scss" scoped></style>
