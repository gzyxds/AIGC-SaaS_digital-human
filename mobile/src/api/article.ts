import { useGetRequest, useListRequest } from '@/composables/useRequest'

/** 文章列表 */
// export const apiGetArticleList = () => {
//   return useListRequest<ArticleItem>('/article/lists');
// };
export const apiGetArticleList = (params: { page_no: number | string, page_size: number | string }) => {
  return useGetRequest<Paging<ArticleItem>>('/article/lists', params);
};

/** 文章详情 */
export const apiGetArticleDetail = (params: { id: number | string }) => {
  return useGetRequest<ArticleDetail>('/article/detail', params);
};
