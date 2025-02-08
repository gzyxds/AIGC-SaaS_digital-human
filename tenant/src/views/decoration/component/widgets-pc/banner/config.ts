const config = () => ({
    title: '图片设置',
    name: 'banner',
    isShow: true,
    prop: {
        data: [
            {
                image: ''
            }
        ]
    }
})

export type Prop = ReturnType<typeof config>['prop']
export default config
