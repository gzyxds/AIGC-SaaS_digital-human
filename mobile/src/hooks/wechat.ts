// // #ifdef H5
// import weixin from 'weixin-js-sdk'
// import { useUserStore } from '@/stores/user'
// import { apiJsConfig, apiCodeUrlGet, apiOALogin } from '@/api/app'

// //判断是否为安卓环境
// function _isAndroid() {
//     const u = navigator.userAgent
//     return u.indexOf('Android') > -1 || u.indexOf('Adr') > -1
// }

// export function getSignLink() {
//     if (typeof window.signLink === 'undefined' || window.signLink === '') {
//         window.signLink = location.href.split('#')[0]
//     }
//     return _isAndroid() ? location.href.split('#')[0] : window.signLink
// }

// export function wxOaConfig() {
//     return new Promise((resolve, reject) => {
//         apiJsConfig().then((res) => {
//             console.log('res:', res) //微信配置
//             weixin.config({
//                 debug: false, // 开启调试模式
//                 appId: res.appId, // 必填，公众号的唯一标识
//                 timestamp: res.timestamp, // 必填，生成签名的时间戳
//                 nonceStr: res.nonceStr, // 必填，生成签名的随机串
//                 signature: res.signature, // 必填，签名
//                 jsApiList: res.jsApiList, // 必填，需要使用的JS接口列表
//                 success: () => {
//                     resolve('success')
//                 },
//                 fail: (res: any) => {
//                     reject('weixin config is fail')
//                 }
//             })
//         })
//     })
// }

// //获取微信登录url
// export function getWxUrl() {
//     apiCodeUrlGet().then((res) => {
//         location.href = res.url
//     })
// }

// //微信授权
// export function authLogin(code) {
//     return new Promise((resolve, reject) => {
//         apiOALogin({
//             code
//         }).then((res) => {
//             const { userStore } = useUserStore()
//             userStore.setToken(res.token)
//             resolve(res)
//         })
//     })
// }

// export function wxOaReady() {
//     return new Promise((resolve, reject) => {
//         weixin.ready(() => {
//             resolve('success')
//             console.log('111222')
//         })
//     })
// }

// export function wxOaShare(options: Record<any, any>) {
//     wxOaReady().then(() => {
//         const { shareTitle, shareLink, shareImage, shareDesc } = options
//         weixin.updateTimelineShareData({
//             title: shareTitle, // 分享标题
//             link: shareLink, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
//             imgUrl: shareImage // 分享图标
//         })
//         // 发送给好友
//         weixin.updateAppMessageShareData({
//             title: shareTitle, // 分享标题
//             link: shareLink, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
//             imgUrl: shareImage, // 分享图标
//             desc: shareDesc
//         })
//         // 发送到tx微博
//         weixin.onMenuShareWeibo({
//             title: shareTitle, // 分享标题
//             link: shareLink, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
//             imgUrl: shareImage, // 分享图标
//             desc: shareDesc
//         })
//     })
// }

// export function wxOaPay(options: Record<any, any>) {
//     console.log('options:', options)

//     return new Promise((reslove, reject) => {
//         wxOaReady()
//             .then(() => {
//                 console.log('微信支付', options)
//                 weixin.chooseWXPay({
//                     timestamp: options.timeStamp, // 支付签名时间戳，注意微信jssdk中的所有使用timestamp字段均为小写
//                     nonceStr: options.nonceStr, // 支付签名随机串，不长于 32 位
//                     package: options.package, // 统一支付接口返回的prepay_id参数值，提交格式如：prepay_id=***）
//                     signType: options.signType, // 签名方式，默认为'SHA1'，使用新版支付需传入'MD5'
//                     paySign: options.paySign, // 支付签名
//                     success: (res: any) => {
//                         reslove(res)
//                     },
//                     cancel: (res: any) => {
//                         reject(res)
//                     },
//                     fail: (res: any) => {
//                         reject(res)
//                     }
//                 })
//             })
//             .catch((err) => {
//                 console.log(err)
//             })
//     })
// }

// export function wxOaAddress() {
//     return new Promise((resolve, reject) => {
//         wxOaReady().then(() => {
//             weixin.openAddress({
//                 success: (res: any) => {
//                     resolve(res)
//                 },
//                 fail: (res: any) => {
//                     reject(res)
//                 }
//             })
//         })
//     })
// }

// export function getLocation() {
//     return new Promise((reslove, reject) => {
//         wxOaReady().then(() => {
//             weixin.getLocation({
//                 type: 'gcj02',
//                 success: (res: any) => {
//                     reslove(res)
//                 },
//                 fail: (res: any) => {
//                     reject(res)
//                 }
//             })
//         })
//     })
// }

// // #endif
