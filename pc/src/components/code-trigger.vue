<template>
    <slot :state="state" @click="sendCode"></slot>
</template>

<script lang="ts" setup>
import { apiPostSendCode, type CodeSence } from '~/api/user';
const props = withDefaults(
    defineProps<{
        mobile: string;
        scene: CodeSence;
    }>(),
    {}
);

const state = ref<{
    isCounting: boolean;
    text: string;
    disabled: boolean;
}>({
    isCounting: false,
    text: '获取验证码',
    disabled: false,
});

const sendCode = async () => {
    if (state.value.isCounting === true) return;
    state.value.text = '正在发送中';
    state.value.disabled = true;
    try {
        await apiPostSendCode({
            mobile: props.mobile,
            scene: props.scene,
        });
        useMessage().success('验证码已发送，注意查收');
        state.value.isCounting = true;
        state.value.disabled = false;
        let count = 60;
        state.value.text = count + 's';
        const interval = setInterval(() => {
            count--;
            state.value.text = count + 's';
            if (count === 0) {
                clearInterval(interval);
                state.value.isCounting = false;
                state.value.text = '重新发送';
            }
        }, 1000);
    } catch (error) {
        state.value.isCounting = false;
        state.value.text = '重新发送';
    }
};
</script>

<style lang="scss" scoped></style>
