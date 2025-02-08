interface ArticleItem {
    click: number;
    id: number;
    cid: number;
    title: string;
    desc: string;
    image: string;
    create_time: string;
    update_time: string;
    collect: boolean;
}

interface ArticleDetail {
    click: number;
    id: number;
    tenant_id: number;
    cid: number;
    title: string;
    desc: string;
    abstract: string;
    image: string;
    author: string;
    content: string;
    is_show: number;
    sort: number;
    create_time: string;
    update_time: string;
    delete_time: null;
    collect: boolean;
}

interface AgreementItem {
    title: string;
    type: string;
    content: string;
    update_time: string;
}
