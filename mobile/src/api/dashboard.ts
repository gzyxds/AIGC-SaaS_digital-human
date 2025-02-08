export interface WorkbenchDataChartItem {
    name: string;
    type: 'line' | 'bar';
    date: string[];
    list: { name: string; data: number[] }[];
}

export interface PoverStaticItem {
    label: string;
    amount: string;
    type: number;
    changeAmount: string;
}

export interface WorksStatic {
    label: string;
    all: number;
    today: number;
}

/** 首页统计图表 */
export const apiGetWorkbenchChart = () => {
    return useGetRequest<WorkbenchDataChartItem[]>('/workbench/workChart');
};

/** 首页用户钱包 */
export const apiGetPowerStatic = () => {
    return useGetRequest<PoverStaticItem[]>('/workbench/userPowerStatic');
};

/** 首页用户作品数据 */
export const apiGetWorksStatic = () => {
    return useGetRequest<WorksStatic[]>('workbench/static');
};
