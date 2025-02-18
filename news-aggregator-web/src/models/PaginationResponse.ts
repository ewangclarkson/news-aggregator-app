import {Pagination} from "./Pagination";
import {ArticleResponse} from "./ArticleResponse";

export interface PaginationResponse {
    data: ArticleResponse[];
    meta: Pagination
}