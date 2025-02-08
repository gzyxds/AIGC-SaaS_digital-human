const config = {
    terminal: 1, //终端
    title: '数字人平台端', //网站默认标题
    version: '1.1.0', //版本号
    formworkVersion: '1.9.4',
    baseUrl: `${import.meta.env.VITE_APP_BASE_URL || ''}/`, //请求接口域名
    urlPrefix: 'platformapi', //请求默认前缀
    timeout: 10 * 1000 //请求超时时长
}

export default config
