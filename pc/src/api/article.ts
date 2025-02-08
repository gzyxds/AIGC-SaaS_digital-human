/** 文章列表 */
export const apiGetArticleList = () => {
    return useListRequest<ArticleItem>('/article/lists');
};

/** 文章详情 */
export const apiGetArticleDetail = (params: { id: number | string }) => {
    return useGetRequest<ArticleDetail>('/article/detail', params);
};
