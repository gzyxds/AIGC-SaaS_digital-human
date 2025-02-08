<script setup lang="ts">
import { apiPostSetUserInfoSingle } from '@/api/user';
import { useUpload } from '@/composables/useRequest';
import { useUserStore } from '@/stores/user';

const userStore = useUserStore();

// 退出登录弹窗ref
const confirmModalRef = ref();

// 退出登录
function handleLogout() {
    userStore.logout();
    uni.switchTab({ url: '/pages/user/index' });
}

// 选择头像
function chooseAvatar(e: any) {
    // #ifndef MP-WEIXIN
    uni.chooseImage({
        count: 1,
        sourceType: ['album', 'camera'],
        sizeType: ['compressed'],
        success: (res) => {
            console.log('选择图片成功', res);
            uploadAvatar(res.tempFilePaths[0]);
        },
        fail(err) {
            console.log('选择图片失败', err);
        },
    });
    // #endif

    if (e.detail.avatarUrl) {
        console.log('微信头像 e.detail.avatarUrl =>', e.detail.avatarUrl);
        uploadAvatar(e.detail.avatarUrl);
    }
}

function uploadAvatar(path: string) {
    const uploadTask = useUpload({
        type: 'image',
        data: { file: path },
        success: (res) => {
            console.log('头像上传成功', res);

            changeAvatar(res.uri);
        },
        fail: (errMsg) => {
            console.log('头像上传失败', errMsg);
        },
    });

    uploadTask.onProgressUpdate((res) => {
        uni.showLoading({
            title: '上传中' + `${res.progress}%`,
        });
        console.log('上传进度：', `${res.progress}%`);
        // console.log('已上传：', res.totalBytesSent);
        // console.log('总共：', res.totalBytesExpectedToSend);
        if (res.progress === 100) {
            uni.hideLoading();
        }
    });
}

// 发起头像更换请求
const changeAvatar = async (uri: string) => {
    await apiPostSetUserInfoSingle({
        field: 'avatar',
        value: uri,
    });

    userStore.getUser();
};

const toPage = (url: string) => uni.navigateTo({ url });

onMounted(() => {
    userStore.getUser();
});
</script>

<template>
    <view px-3 py-2>
        <view ml-2 text-foreground-light> 编辑资料 </view>

        <button
            hover-class="none"
            open-type="chooseAvatar"
            class="my-btn"
            mt-3
            between
            px-4
            py-3.5
            @click="chooseAvatar"
            @chooseavatar="chooseAvatar"
        >
            <image
                :src="userStore.userInfo?.avatar"
                mode="scaleToFill"
                h-92rpx
                w-92rpx
                rounded-full
            />
            <view center>
                <view text="sm foreground-light" mr-1> 更换头像 </view>
                <view i-tabler:chevron-right text="sm foreground-light" />
            </view>
        </button>

        <!-- <view mt-3 between bg-background rounded-28rpx px-4 py-3.5>
      <image src="https://img0.baidu.com/it/u=2191392668,814349101&fm=253&fmt=auto&app=138&f=JPEG?w=800&h=1399"
        mode="scaleToFill" rounded-full h-92rpx w-92rpx />
      <view center>
        <view text="sm foreground-light" mr-1>更换头像</view>
        <view i-tabler:chevron-right text="sm foreground-light" />
      </view>
    </view> -->

        <view mt-3 between rounded-28rpx bg-background px-4 py-3.5>
            <view text-foreground-muted> 昵称 </view>
            <view center @click="toPage('/bundle/pages/change_nickname/index')">
                <view text="sm foreground-light" mr-1>
                    {{ userStore.userInfo?.nickname }}
                </view>
                <view i-tabler:chevron-right text="sm foreground-light" />
            </view>
        </view>

        <view ml-2 mt-7.5 text-foreground-light> 账号与安全 </view>

        <view mt-3 between rounded-28rpx bg-background px-4 py-3.5>
            <view text-foreground-muted> 手机号 </view>
            <view center @click="toPage('/bundle/pages/change_mobile/index?mobile')">
                <view text="sm foreground-light" mr-1>
                    {{
                        userStore.userInfo?.mobile ? `+86 ${userStore.userInfo?.mobile}` : '未绑定'
                    }}
                </view>
                <view i-tabler:chevron-right text="sm foreground-light" />
            </view>
        </view>

        <view mt-3 between rounded-28rpx bg-background px-4 py-3.5>
            <view text-foreground-muted> 登录密码 </view>
            <view center @click="toPage('/bundle/pages/change_password/index')">
                <view text="sm foreground-light" mr-1>
                    {{ userStore.userInfo?.has_password ? '已设置' : '未设置' }}
                </view>
                <view i-tabler:chevron-right text="sm foreground-light" />
            </view>
        </view>

        <view class="footer" position="fixed left-0 bottom-0" w-full p-3>
            <button type="default" rounded-28rpx bg-background @click="confirmModalRef.open()">
                退出登录
            </button>
        </view>

        <!-- 退出登录提示弹窗 -->
        <confirm-modal
            ref="confirmModalRef"
            title="温馨提示"
            content="确定退出当前登录吗?"
            :confirm-callback="handleLogout"
        />
    </view>
</template>

<style lang="scss" scoped>
.footer {
    padding-bottom: calc(env(safe-area-inset-bottom) + 24rpx);
}

.my-btn {
    background: rgb(var(--ui-background)) !important;
    border-radius: 28rpx !important;
    height: auto;
}
</style>
