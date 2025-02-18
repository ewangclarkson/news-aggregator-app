export interface ArticleRequest {
    keyword: string | null;
    category: string[] | null;
    source: string[] | null;
    author: string[] | null;
    startDate: Date | null;
    endDate: Date | null;
    page: number | null;
}