/**
 * 支付方式列表
 */
export const apiGetPayWayList = () => {
    return useGetRequest<PayWayItem[]>('/pay/powerPayWay');
};

/**
 * 获取价格
 */
export const apiPostRecharge = (data: { package: number | string }) => {
    return usePostRequest<RechargeRes>('/recharge/recharge', data);
};

/**
 * 获取价格
 * @deprecated 获取支付信息
 * 没有绑定微信需要传code
 */
export const apiPostPrePay = (data: {
    from: 'recharge';
    order_id: number | string;
    pay_way: '2';
    code?: string;
}) => {
    return usePostRequest<PrePayRes>('/pay/prepay', data);
};

/**
 * 支付
 */
export const apiPostPayStatus = (params: { from: 'recharge'; order_id: number | string }) => {
    return useGetRequest<PayStatusRes>('/pay/payStatus', params);
};

/**
 * 获取jsConfig-小程序微信支付配置
 */
export const apiGetJsConfig = (params: { url: string }) => {
    return useGetRequest<WXPayJsConfig>('/wechat/jsConfig', params);
};
