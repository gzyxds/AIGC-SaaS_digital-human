/** 分页返回数据类型 */
interface Paging<T> {
  lists: T[];
  page_no: number;
  page_size: number;
  count: number;
  extend: any;
}
