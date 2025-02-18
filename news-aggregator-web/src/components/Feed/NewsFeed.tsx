import React, { useState, useEffect, useCallback } from 'react';
import Loader from "../loader/Loader";
import ErrorComponent from "../Alert/404";
import ArticleService from "../../services/ArticleService";
import { ArticleRequest } from "../../models/ArticleRequest";
import icon from "../Assets/note.png";
import useArticles from "../../hooks/useArticles";
import './NewsFeed.scss';

declare global {
    interface Window {
        flatpickr: any; // Declare flatpickr on the window object
        $: any; // Declare jQuery in the window object
    }
}

const NewsSourceConstants = {
    NEWS_API: 'NEWS_ORG',
    GUARDIAN_NEWS_API: 'GUARDIAN_NEWS',
    NEW_YORK_TIME_NEWS_API: 'NEW_YORK_TIME_NEWS',
};

const NewsFeed: React.FC = () => {
    const { loading, error, paginationData, setPaginationData, setLoading, setError } = useArticles();
    const [currentPage, setCurrentPage] = useState<number>(1);
    const [searchParams, setSearchParams] = useState<ArticleRequest | null>(null);

    const [keyword, setKeyword] = useState('');
    const [sources, setSources] = useState(Object.values(NewsSourceConstants));
    const [selectedSources, setSelectedSources] = useState<string[]>([]);
    const [selectedCategories, setSelectedCategories] = useState<string[]>([]);
    const [selectedAuthors, setSelectedAuthors] = useState<string[]>([]);
    const [startDate, setStartDate] = useState<Date | null>(null);
    const [endDate, setEndDate] = useState<Date | null>(null);
    const [categories, setCategories] = useState<string[]>([]);
    const [authors, setAuthors] = useState<string[]>([]);

    const constructPayload = (page: number): ArticleRequest => ({
        keyword: searchParams?.keyword || null,
        category: searchParams?.category || null,
        source: searchParams?.source || null,
        author: searchParams?.author || null,
        startDate: searchParams?.startDate ? new Date(searchParams.startDate) : null,
        endDate: searchParams?.endDate ? new Date(searchParams.endDate) : null,
        page,
    });

    const fetchArticles = useCallback(async (page: number) => {
        try {
            setLoading(true);
            const payload = constructPayload(page);
            const response = await ArticleService.search(payload); // Adjust to your API call
            setPaginationData(response);
            setLoading(false);
        } catch (er) {
            console.error('Error fetching articles:', er);
            setError(true);
            setLoading(false);
        }
    }, [constructPayload]);

    useEffect(() => {
        if (searchParams) {
            fetchArticles(currentPage);
        }
    }, [searchParams, currentPage, fetchArticles]);

    useEffect(() => {
        const fetchCategoriesAndAuthors = async () => {
            try {
                const response = await ArticleService.getCategoriesAndAuthors();
                const { preferences, categories, authors } = response || {};
                setSources(preferences.source.length ? preferences.source : Object.values(NewsSourceConstants));
                setCategories(preferences.category.length ? preferences.category : categories);
                setAuthors(preferences.authors.length ? preferences.authors : authors);
            } catch (er) {
                console.error("Failed to fetch categories and authors:", er);
            }
        };

        fetchCategoriesAndAuthors();
    }, []);

    const handleSearch = async (e: React.FormEvent) => {
        e.preventDefault();
        const params: ArticleRequest = {
            keyword: keyword || null,
            category: selectedCategories.length ? selectedCategories : null,
            source: selectedSources.length ? selectedSources : null,
            author: selectedAuthors.length ? selectedAuthors : null,
            startDate: startDate,
            endDate: endDate,
            page: 1,
        };
        setLoading(true);
        try {
            const response = await ArticleService.search(params);
            setPaginationData(response);
            setLoading(false);
        } catch (er) {
            console.error('Error fetching articles:', er);
            setError(true);
            setLoading(false);
        }
    };

    const formatPublishedDate = (dateStr: string | null): string => {
        if (!dateStr) return "";
        const date = new Date(dateStr);
        if (isNaN(date.getTime())) return "";
        const optionsDate: Intl.DateTimeFormatOptions = { year: 'numeric', month: 'long', day: 'numeric' };
        return date.toLocaleDateString('en-US', optionsDate);
    };

    const handleRedirect = (link: string | null) => {
        if (link && typeof link === 'string' && (link.startsWith('http://') || link.startsWith('https://'))) {
            window.open(link, '_blank');
        }
    };

    const totalPages = paginationData ? paginationData.meta?.totalPages : 0;

    const handlePageChange = (page: number) => {
        if (page > 0 && page <= totalPages) {
            setCurrentPage(page);
            fetchArticles(page);
        }
    };

    useEffect(() => {
        const startPicker = window.flatpickr("#startDate", {
            onChange: (selectedDates: Date[]) => {
                setStartDate(selectedDates[0] || null);
            },
            dateFormat: "Y-m-d",
        });

        const endPicker = window.flatpickr("#endDate", {
            onChange: (selectedDates: Date[]) => {
                setEndDate(selectedDates[0] || null);
            },
            dateFormat: "Y-m-d",
        });

        // Initialize select2 for dropdowns
        const $select2 = window.$('.select2');

        $select2.select2({
            minimumResultsForSearch: Infinity,
            placeholder: "Select options",
        }).on('change', (e: any) => {
            const value = window.$(e.target).val();
            const $target = window.$(e.target);
            if ($target.hasClass('categories-select')) {
                setSelectedCategories(value || []);
            } else if ($target.hasClass('authors-select')) {
                setSelectedAuthors(value || []);
            } else if ($target.hasClass('sources-select')) {
                setSelectedSources(value || []);
            }
        });

        return () => {
            startPicker.destroy();
            endPicker.destroy();

            if ($select2.length) {
              //  $select2.select2('destroy');
            }
        };
    }, [categories, authors]);

    return (
        <>
            {loading && <Loader className="page-loader" />}
            {error && <ErrorComponent />}
            <div className="toolbar">
                <form className="search--focus col-12" onSubmit={handleSearch}>
                    <div className="row">
                        <div className="col-sm-3">
                            <input
                                type="text"
                                className="search__news"
                                value={keyword}
                                onChange={(e) => setKeyword(e.target.value)}
                                placeholder="Search Articles"
                            />
                        </div>
                        <div className="col-sm-3">
                            <div className="form-group">
                                <h6>Sources</h6>
                                <select className="select2 sources-select" multiple style={{ width: '100%' }}>
                                    <option disabled value="">Select sources</option>
                                    {sources.map((source) => (
                                        <option key={source} value={source}>
                                            {source.replace(/_/g, ' ')
                                                .split(' ')
                                                .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
                                                .join(' ')}
                                        </option>
                                    ))}
                                </select>
                            </div>
                        </div>
                        <div className="col-sm-3">
                            <div className="form-group">
                                <h6>Categories</h6>
                                <select className="select2 categories-select" multiple style={{ width: '100%' }}>
                                    <option disabled value="">Select categories</option>
                                    {categories.map((category) => (
                                        <option key={category} value={category}>
                                            {category}
                                        </option>
                                    ))}
                                </select>
                            </div>
                        </div>
                        <div className="col-sm-3">
                            <div className="form-group">
                                <h6>Authors</h6>
                                <select className="select2 authors-select" multiple style={{ width: '100%' }}>
                                    <option disabled value="">Select authors</option>
                                    {authors.map((author) => (
                                        <option key={author} value={author}>
                                            {author}
                                        </option>
                                    ))}
                                </select>
                            </div>
                        </div>
                        <div className="col-sm-3">
                            <div className="input-group">
                                <div className="input-group-prepend">
                                    <span className="input-group-text calendar-icon"></span>
                                </div>
                                <input
                                    id="startDate"
                                    type="text"
                                    className="form-control custom-input"
                                    placeholder="Start date"
                                />
                            </div>
                        </div>
                        <div className="col-sm-3">
                            <div className="input-group">
                                <div className="input-group-prepend">
                                    <span className="input-group-text calendar-icon"></span>
                                </div>
                                <input
                                    id="endDate"
                                    type="text"
                                    className="form-control custom-input"
                                    placeholder="End date"
                                />
                            </div>
                        </div>
                        <div className="col-sm-3">
                            <button
                                className="btn login__block__btn text-white"
                                style={{ width: 100, paddingTop: 10, paddingBottom: 10, marginTop: 0 }}
                                type="submit"
                            >
                                Search
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div className="contacts row">
                {paginationData?.data && paginationData.data.length > 0 ? paginationData.data.map(article => (
                    <div className="col-sm-4" key={article.id}>
                        <div className="card">
                            <img
                                className="card-img-top"
                                src={(article.image && (article.image.startsWith('http://') || article.image.startsWith('https://'))) ? article.image : icon}
                                alt="Article"
                            />
                            <div className="card-body">
                                <h4 className="card-title">{article.title || "Untitled"}</h4>
                                <h6 className="card-subtitle">
                                    by {article.author || "Unknown"} on {formatPublishedDate(article.published_at)}
                                </h6>
                                <p className="card-content">{article.content || "No content available."}</p>
                                <p>{article.description || "No description available."}</p>
                                <div className="listview listview--striped">
                                    <div className="listview__content">
                                        <div className="listview__attrs">
                                            <span>{article?.category}</span>
                                            <span>{article?.source?.replace(/_/g, ' ')}</span>
                                        </div>
                                    </div>
                                </div>
                                {article.link && (article.link.startsWith('http://') || article.link.startsWith('https://')) && (
                                    <a onClick={() => handleRedirect(article.link)} className="view-more text-left">View Article...</a>
                                )}
                            </div>
                        </div>
                    </div>
                )) : <p>No articles available.</p>}
            </div>
            {totalPages > 1 && (
                <nav>
                    <ul className="pagination justify-content-center">
                        <li className="page-item pagination-first">
                            <a className="page-link" onClick={() => handlePageChange(1)} href="#" aria-label="First">
                                First
                            </a>
                        </li>
                        <li className="page-item pagination-prev">
                            <a
                                className="page-link"
                                onClick={() => handlePageChange(currentPage - 1)}
                                href="#"
                                aria-disabled={currentPage === 1}
                                aria-label="Previous"
                            >
                                Prev
                            </a>
                        </li>
                        {Array.from({ length: totalPages }, (_, index) => (
                            <li key={index} className={`page-item ${currentPage === index + 1 ? 'active' : ''}`}>
                                <a className="page-link" onClick={() => handlePageChange(index + 1)} href="#">
                                    {index + 1}
                                </a>
                            </li>
                        ))}
                        <li className="page-item pagination-next">
                            <a
                                className="page-link"
                                onClick={() => handlePageChange(currentPage + 1)}
                                href="#"
                                aria-disabled={currentPage === totalPages}
                                aria-label="Next"
                            >
                                Next
                            </a>
                        </li>
                        <li className="page-item pagination-last">
                            <a className="page-link" onClick={() => handlePageChange(totalPages)} href="#"
                               aria-label="Last">
                                Last
                            </a>
                        </li>
                    </ul>
                </nav>
            )}
        </>
    );
};

export default NewsFeed;