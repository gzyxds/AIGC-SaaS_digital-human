import { defineUniPages } from '@uni-helper/vite-plugin-uni-pages';

export default defineUniPages({
    // easycom 规则
    easycom: {
        autoscan: true,
        custom: {
            // uni-ui 规则如下配置
            '^uni-(.*)': '@dcloudio/uni-ui/lib/uni-$1/uni-$1.vue',
            '^(?!z-paging-refresh|z-paging-load-more)z-paging(.*)':
                'z-paging/components/z-paging$1/z-paging$1.vue',
        },
    },
    pages: [
        // {
        //     path: 'pages/demo/index',
        //     type: 'demo',
        //     style: {
        //         navigationBarTitleText: '组件列表',
        //     },
        // },
        {
            path: 'pages/home/index',
            type: 'home',
            style: {
                navigationBarTitleText: '首页',
                enablePullDownRefresh: true,
                navigationStyle: 'custom',
            },
        },

        {
            path: 'pages/product/index',
            type: 'product',
            style: {
                navigationBarTitleText: '作品',
            },
        },
        {
            path: 'pages/user/index',
            type: 'profile',
            style: {
                navigationBarTitleText: '我的',
                navigationStyle: 'custom',
            },
        },
        {
            path: 'pages/digital_people/index',
            style: {
                navigationBarTitleText: '创建数字人',
            },
        },
        {
            path: 'pages/create_profile/index',
            style: {
                navigationBarTitleText: '创建形象',
            },
        },
        {
            path: 'pages/create_sound/index',
            style: {
                navigationBarTitleText: '创建声音',
            },
        },
        {
            path: 'pages/login/index',
            style: {
                navigationBarTitleText: '登录微信',
                navigationStyle: 'custom',
            },
        },
        {
            path: 'pages/login/bind',
            style: {
                navigationBarTitleText: '绑定微信',
                navigationStyle: 'custom',
            },
        },
    ],
    subPackages: [
        {
            root: 'bundle',
            pages: [
                {
                    path: 'pages/agreement/index',
                    style: {
                        navigationBarTitleText: '政策协议',
                    },
                },
                {
                    path: 'pages/article/index',
                    style: {
                        navigationBarTitleText: '文章资讯',
                    },
                },
                {
                    path: 'pages/article_detail/index',
                    style: {
                        navigationBarTitleText: '文章详情',
                    },
                },
                {
                    path: 'pages/hashrate/index',
                    style: {
                        navigationBarTitleText: '算力充值',
                    },
                },
                {
                    path: 'pages/hashrate_log/index',
                    style: {
                        navigationBarTitleText: '算力明细',
                    },
                },
                {
                    path: 'pages/user_set/index',
                    style: {
                        navigationBarTitleText: '账号设置',
                    },
                },
                {
                    path: 'pages/profile/index',
                    style: {
                        navigationBarTitleText: '形象',
                    },
                },
                {
                    path: 'pages/change_nickname/index',
                    style: {
                        navigationBarTitleText: '修改昵称',
                    },
                },
                {
                    path: 'pages/change_mobile/index',
                    style: {
                        navigationBarTitleText: '修改手机号',
                    },
                },
                {
                    path: 'pages/change_password/index',
                    style: {
                        navigationBarTitleText: '修改密码',
                    },
                },
                {
                    path: 'pages/play_video/index',
                    style: {
                        navigationBarTitleText: '视频播放',
                    },
                },
            ],
        },
    ],
    globalStyle: {
        backgroundColor: '#0C0C0C',
        backgroundColorBottom: '#212122',
        backgroundColorTop: '#0C0C0C',
        backgroundTextStyle: 'light',
        navigationBarBackgroundColor: '#0C0C0C',
        navigationBarTextStyle: 'white',
        navigationBarTitleText: 'AI数字人系统',
        // navigationStyle: 'custom',
    },
    tabBar: {
        backgroundColor: '#212122',
        borderStyle: 'black',
        color: '#A9AEB0',
        selectedColor: '#135dec',
        list: [
            // {
            //     pagePath: 'pages/demo/index',
            //     iconPath: '/static/icons/tabbar/profile-inactive.png',
            //     selectedIconPath: '/static/icons/tabbar/profile-active.png',
            //     text: '组件',
            // },
            {
                pagePath: 'pages/home/index',
                iconPath: '/static/icons/tabbar/home-inactive.png',
                selectedIconPath: '/static/icons/tabbar/home-active.png',
                text: '首页',
            },
            {
                pagePath: 'pages/product/index',
                iconPath: '/static/icons/tabbar/product-inactive.png',
                selectedIconPath: '/static/icons/tabbar/product-active.png',
                text: '作品',
            },
            {
                pagePath: 'pages/user/index',
                iconPath: '/static/icons/tabbar/user-inactive.png',
                selectedIconPath: '/static/icons/tabbar/user-active.png',
                text: '我的',
            },
        ],
    },
});
