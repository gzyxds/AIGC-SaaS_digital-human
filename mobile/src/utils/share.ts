export default {
    data() {
        return {}
    },
    onLoad: function () {},
    // @ts-ignore
    onShareAppMessage(res) {
        const that = this
        const imageUrl = that.shareUrl || ''
        if (res.from === 'button') {
            //这块需要传参，不然链接地址进去获取不到数据
            const path = `/` + that.$scope.route + `?item=` + that.$scope.options.item
            return {
                title: '商品分享~',
                path,
                imageUrl
            }
        }
        if (res.from === 'menu') {
            return {
                title: '线上预约',
                imageUrl
            }
        }
    },
    // 分享到朋友圈
    onShareTimeline() {
        return {
            title: '线上预约',
            path: '/pages/index/index',
            imageUrl: ''
        }
    },
    methods: {}
}
