import axiosApi from "../hooks/config/AxiosClient";
import {PaginationResponse} from "../models/PaginationResponse";
import {ArticleRequest} from "../models/ArticleRequest";
import {CategoryAndAuthorsResponse} from "../models/CategoryAndAuthorsResponse";

class ArticleService {
    constructor() {
    }

    get(): Promise<PaginationResponse> {
        return axiosApi({
            method: 'GET',
            url: '/articles',
        }).then(response => response.data);
    }

    search(articleRequest: ArticleRequest): Promise<PaginationResponse> {
        return axiosApi({
            method: 'POST',
            url: '/articles',
            data: articleRequest,
        }).then(response => response.data);
    }

    getCategoriesAndAuthors(): Promise<CategoryAndAuthorsResponse> {
        return axiosApi({
            method: 'GET',
            url: '/categories_authors',
        }).then(response => response.data);
    }

}

export default new ArticleService();