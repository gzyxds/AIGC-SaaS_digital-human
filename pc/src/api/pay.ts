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
 */
export const apiPostPrePay = (data: {
    from: 'recharge';
    order_id: number | string;
    pay_way: '2';
}) => {
    return usePostRequest<PrePayRes>('/pay/prepay', data);
};

/**
 * 支付
 */
export const apiPostPayStatus = (params: { from: 'recharge'; order_id: number | string }) => {
    return useGetRequest<PayStatusRes>('/pay/payStatus', params);
};
