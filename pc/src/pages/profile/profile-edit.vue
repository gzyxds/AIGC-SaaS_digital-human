<template>
    <PageContainer
        class="flex-1"
        :scroll="true"
        breadcrumb="编辑资料"
        :padding="true"
        :background="true"
    >
        <UForm
            :state="formData"
            class="flex h-full flex-col space-y-4 sm:max-w-sm"
            :validate-on="['submit']"
        >
            <UFormGroup
                :ui="{
                    label: {
                        base: 'text-foreground/50 dark:text-foreground/50',
                    },
                }"
                label="头像"
                name="cover"
                size="lg"
                help="建议上传10MB以内 1:1 比例的.png、.jpg、.jpeg格式的图片"
            >
                <ProUploader
                    v-model="formData.avatar"
                    class="h-24 !w-24"
                    type="image"
                    text="请选择头像"
                    icon="tabler:user-circle"
                    square
                    :max-size="10"
                    :show-delete="false"
                    @change="avatarChange"
                />
            </UFormGroup>

            <UFormGroup
                :ui="{
                    label: {
                        base: 'text-foreground/50 dark:text-foreground/50',
                    },
                }"
                label="UID"
                name="mobile"
                size="lg"
            >
                <div class="flex items-center gap-1">
                    <span class="text-sm">{{ detail?.sn }}</span>
                    <UIcon
                        v-if="detail?.sn"
                        name="tabler:copy"
                        class="ml-0.5 cursor-pointer"
                        @click="useCopy(detail.sn, '已复制UID至剪切板')"
                    />
                </div>
            </UFormGroup>

            <UFormGroup
                :ui="{
                    label: {
                        base: 'text-foreground/50 dark:text-foreground/50',
                    },
                }"
                label="登录账号"
                name="mobile"
                size="lg"
            >
                <div class="flex items-center gap-1">
                    <span class="text-sm">{{ detail?.account }}</span>
                    <UIcon
                        v-if="detail?.account"
                        name="tabler:copy"
                        class="ml-0.5 cursor-pointer"
                        @click="useCopy(detail.account, '已复制账号至剪切板')"
                    />
                </div>
            </UFormGroup>

            <UFormGroup
                :ui="{
                    label: {
                        base: 'text-foreground/50 dark:text-foreground/50',
                    },
                }"
                label="昵称"
                name="nickname"
                size="lg"
            >
                <div v-if="!isEdit" class="flex items-center">
                    <span class="text-sm">{{ formData.nickname }}</span>
                    <UButton
                        variant="link"
                        icon="tabler:pencil-minus"
                        @click="
                            async () => {
                                isEdit = true;
                                await nextTick();
                                nicknameRef?.input.focus();
                            }
                        "
                    />
                </div>
                <UButtonGroup
                    v-else
                    size="lg"
                    orientation="horizontal"
                    :ui="{
                        wrapper: {
                            horizontal: 'flex -space-x-px',
                            vertical: 'inline-flex flex-col -space-y-px',
                        },
                    }"
                >
                    <UInput
                        ref="nicknameRef"
                        v-model="formData.nickname"
                        class="flex-1"
                        placeholder="请填写昵称"
                        color="gray"
                        :disabled="!isEdit"
                        @blur="updateNickname"
                    />

                    <UButton icon="tabler:check" color="primary" label="保存" />
                </UButtonGroup>
            </UFormGroup>

            <UFormGroup
                :ui="{
                    label: {
                        base: 'text-foreground/50 dark:text-foreground/50',
                    },
                }"
                label="手机号"
                name="mobile"
                size="lg"
            >
                <div v-if="detail?.mobile" class="flex items-center gap-1">
                    <span class="text-sm">{{ dataMasking(detail?.mobile, 6) }}</span>
                    <UButton variant="link" @click="BindMobileRef?.open(detail)">换绑</UButton>
                </div>
                <div v-else>
                    <UButton variant="soft" size="xs" @click="BindMobileRef?.open(detail)"
                        >绑定手机号</UButton
                    >
                </div>
            </UFormGroup>

            <UFormGroup
                :ui="{
                    label: {
                        base: 'text-foreground/50 dark:text-foreground/50',
                    },
                }"
                label="登录密码"
                name="mobile"
                size="lg"
            >
                <div v-if="detail?.has_password == true" class="flex items-center gap-1">
                    <span class="text-sm">已设置</span>
                    <UButton variant="link" @click="EditPasswordRef?.open(detail)">修改</UButton>
                </div>
                <div v-else>
                    <UButton variant="soft" size="xs" @click="SetPasswordRef?.open()"
                        >点击设置</UButton
                    >
                </div>
            </UFormGroup>

            <UFormGroup
                :ui="{
                    label: {
                        base: 'text-foreground/50 dark:text-foreground/50',
                    },
                }"
                label="微信"
                name="mobile"
                size="lg"
            >
                <div v-if="isEnable(detail?.is_auth)" class="flex items-center gap-1">
                    <span class="text-sm">已绑定</span>
                    <!-- <UButton variant="link" @click="BindWechatRef?.open()">换绑</UButton> -->
                </div>
                <div v-else>
                    <UButton variant="soft" size="xs" @click="BindWechatRef?.open()"
                        >点击绑定</UButton
                    >
                </div>
            </UFormGroup>
        </UForm>

        <BindWechat ref="BindWechatRef" @refresh="getUserDetail" />
        <BindMobile ref="BindMobileRef" @refresh="getUserDetail" />
        <EditPassword ref="EditPasswordRef" @refresh="getUserDetail" />
        <SetPassword ref="SetPasswordRef" @refresh="getUserDetail" />
    </PageContainer>
</template>

<script lang="ts" setup>
import {
    apiGetUser,
    apiPostBindMobile,
    apiPostSendCode,
    apiPostSetUserInfoSingle,
} from '~/api/user';

import BindMobile from './components/bindMobile.vue';
import BindWechat from './components/bindWechat.vue';
import EditPassword from './components/editPassword.vue';
import SetPassword from './components/setPassword.vue';

useHead({
    title: '编辑资料',
});

definePageMeta({
    layout: 'profile',
});

const userStore = useUserStore();

const BindWechatRef = getComponentExpose(BindWechat);
const BindMobileRef = getComponentExpose(BindMobile);
const EditPasswordRef = getComponentExpose(EditPassword);
const SetPasswordRef = getComponentExpose(SetPassword);

const nicknameRef = ref<{ input: HTMLInputElement }>();

const emit = defineEmits(['refresh']);

const isEdit = ref<boolean>(false);

const formData = reactive<{ avatar?: string; nickname?: string; mobile?: string }>({
    avatar: '',
    nickname: '',
    mobile: '',
});

const detail = ref<UserInfo>();

const avatarChange = async (e: UploadResponse) => {
    await apiPostSetUserInfoSingle({ field: 'avatar', value: e.uri });
    await userStore.getUser();
};

const updateNickname = async () => {
    isEdit.value = false;
    if (formData.nickname === '') {
        useMessage().warn('用户名不能为空');
        formData.nickname = detail.value?.nickname;
        return;
    }
    if (formData.nickname !== detail.value?.nickname) {
        await apiPostSetUserInfoSingle({ field: 'nickname', value: formData.nickname as string });
        await userStore.getUser();
        await getUserDetail();
    }
};

const getUserDetail = async () => {
    detail.value = await apiGetUser();
    formData.avatar = detail.value.avatar;
    formData.nickname = detail.value.nickname;
    formData.mobile = detail.value.mobile;
};

onMounted(() => {
    getUserDetail();
});
</script>
