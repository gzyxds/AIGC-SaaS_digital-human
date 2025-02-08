export const homeMenus: MenuItem[] = [
    {
        icon: 'tabler:copy',
        title: '声音克隆',
        path: '/audio/clone',
    },
    {
        icon: 'tabler:volume',
        title: '声音合成',
        path: '/audio/synthesis',
    },
    {
        icon: 'tabler:users',
        title: '数字分身',
        path: '/avatar',
    },
];

export const sidebarMenus: MenuItem[] = [
    {
        icon: 'tabler:chart-pie',
        title: '数据看板',
        path: '/dashboard',
    },
    {
        icon: 'tabler:copy',
        title: '声音克隆',
        path: '/audio/clone',
    },
    {
        icon: 'tabler:volume',
        title: '声音合成',
        path: '/audio/synthesis',
    },
    {
        icon: 'tabler:users',
        title: '数字分身',
        path: '/avatar',
    },
];

export const tabbarMenus: MenuItem[] = [
    {
        icon: 'tabler:chart-pie',
        title: '数据看板',
        path: '/dashboard',
    },
    {
        icon: 'tabler:copy',
        title: '声音克隆',
        path: '/audio/clone',
    },
    {
        icon: 'tabler:volume',
        title: '声音合成',
        path: '/audio/synthesis',
    },
    {
        icon: 'tabler:users',
        title: '数字分身',
        path: '/avatar',
    },
    {
        icon: 'tabler:user',
        title: '我的',
        path: '/profile',
    },
];

export const profileSideNavs: MenuItem[] = [
    {
        icon: 'tabler:user',
        title: '个人信息',
        path: '/profile',
    },
    {
        icon: 'tabler:battery-vertical-charging-2',
        title: '充值中心',
        path: '/profile/recharge',
    },
    {
        icon: 'tabler:clock-record',
        title: '充值记录',
        path: '/profile/recharge-record',
    },
    {
        icon: 'tabler:file-text',
        title: '账单明细',
        path: '/profile/power-flow',
    },
];

interface siteNavigationItem {
    id: string;
    icon: string;
    label: string;
    defaultOpen?: boolean;
    path: string;
    list: MenuItem[];
}

export const siteNavigationList: siteNavigationItem[] = [
    {
        id: 'dashboard',
        icon: 'tabler:chart-pie',
        label: '数据看板',
        defaultOpen: true,
        path: '/dashboard',
        list: [{ icon: '', title: '数据看板', path: '/dashboard' }],
    },
    {
        id: 'audio-clone',
        icon: 'tabler:copy',
        label: '声音克隆',
        defaultOpen: true,
        path: '/audio/clone',
        list: [
            { icon: '', title: '我的音色', path: '/audio/clone' },
            { icon: '', title: '系统音色', path: '/audio/clone/common' },
            { icon: '', title: '创建音色', path: '/audio/clone/create' },
        ],
    },
    {
        id: 'audio-synthesis',
        icon: 'tabler:volume',
        label: '声音合成',
        defaultOpen: true,
        path: '/audio/synthesis',
        list: [
            { icon: '', title: '合成器', path: '/audio/synthesis?tab_index=0' },
            { icon: '', title: '合成记录', path: '/audio/synthesis?tab_index=1' },
        ],
    },
    {
        id: 'avatar',
        icon: 'tabler:users',
        label: '数字分身',
        defaultOpen: true,
        path: '/avatar',
        list: [
            { icon: '', title: '我的形象', path: '/avatar' },
            { icon: '', title: '我的作品', path: '/avatar/works' },
        ],
    },
    {
        id: 'profile',
        icon: 'tabler:user',
        label: '个人中心',
        defaultOpen: true,
        path: '/profile',
        list: [
            { icon: '', title: '我的信息', path: '/profile' },
            { icon: '', title: '充值中心', path: '/profile/recharge' },
            { icon: '', title: '充值记录', path: '/profile/recharge-record' },
            {
                icon: '',
                title: '账单明细',
                path: '/profile/power-flow',
            },
        ],
    },
];
