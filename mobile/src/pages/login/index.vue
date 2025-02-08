<script lang="ts" setup>
// #ifdef H5
import { apiGetOALogin } from '@/api/wechat';
import wechatOa, { UrlScene } from '@/utils/wechat';
import { onLoad } from '@dcloudio/uni-app';
// #endif

import { ref } from 'vue';

const userInfo = ref<any>();
const errMsg = ref<string>('正在处理，请稍等...');

// const OALogin = (data: any) => {
//     return new Promise((resolve, reject) => {
//         uni.request({
//             url: '/api/login/oaLogin',
//             method: 'POST',
//             data,
//             success: (res) => {
//                 const result = res.data as {
//                     code: number;
//                     data: any;
//                     msg: string;
//                     show: 0 | 1;
//                 };
//                 if (result.code === 1) {
//                     resolve(result.data);
//                 } else {
//                     uni.showToast({
//                         title: result.msg,
//                         icon: 'error',
//                     });
//                     // 回显错误消息
//                     errMsg.value = result.msg;
//                     reject(result.msg);
//                 }
//             },
//             fail: (err) => {
//                 reject(err);
//             },
//         });
//     });
// };
const oaLogin = async (options: any = { getUrl: true }) => {
    let data: any = null;
    const { code, key = '', getUrl } = options;

    if (code) {
        try {
            data = await apiGetOALogin({
                code,
                key,
            });
            userInfo.value = data;
        } catch (error) {
            // 回显错误消息
            errMsg.value = error;
            console.log(error);
        }
    }
    if (getUrl) {
        await wechatOa.getUrl(UrlScene.PC_LOGIN);
    }
};

onLoad((options) => {
    if (options) {
        const { code, state, scene } = options;

        if (code && state && scene) {
            wechatOa.setAuthData({
                code,
                scene,
            });
        }
        const authData = wechatOa.getAuthData();
        if (authData.code && authData.scene == UrlScene.PC_LOGIN) {
            try {
                oaLogin({ ...options, ...authData });
            } catch (error: any) {
                uni.showToast({
                    title: `授权失败:${error.message || JSON.stringify(error)}`,
                    icon: 'error',
                });
            } finally {
                wechatOa.setAuthData();
            }
        } else if (options.is_pc == 1 && options.key) {
            oaLogin();
        }
    }
});
</script>

<template>
    <view style="width: 100vw; height: 100vh; background-color: white">
        <view
            v-if="userInfo && userInfo.token"
            style="
                display: flex;
                justify-content: center;
                align-items: center;
                flex-direction: column;
                padding: 80px 0;
            "
        >
            <image
                :src="userInfo.avatar || '/static/logo.png'"
                style="width: 70px; height: 70px; border-radius: 100%; background-color: #eee"
                alt=""
            />
            <view style="padding: 0.5rem 0">
                {{ userInfo.nickname || '昵称' }}
            </view>
            <view style="font-size: 14px; color: #777"> 微信授权成功 </view>
        </view>
        <view
            v-else
            style="
                display: flex;
                justify-content: center;
                align-items: center;
                flex-direction: column;
                padding: 80px 0;
            "
        >
            正在登录，请稍等...
            {{ errMsg }}
        </view>
    </view>
</template>

<style lang="scss" scoped></style>
