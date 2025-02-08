const config = () => ({
    title: '标题设置',
    name: 'title',
    isShow: true,
    prop: {
        title: '即刻构建您的专属数字分身',
        subtitle: '仅需几十秒即可构建你的专属数字人分身'
    }
})

export type Prop = ReturnType<typeof config>['prop']
export default config
