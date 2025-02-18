import React, { useCallback, useEffect, useState } from 'react';
import { PaginationResponse } from "../models/PaginationResponse";
import ArticleService from '../services/ArticleService';

interface UseFetchReturn {
    loading: boolean;
    error: boolean;
    paginationData: PaginationResponse | null;
    setPaginationData: React.Dispatch<React.SetStateAction<PaginationResponse | null>>;
    setLoading: React.Dispatch<React.SetStateAction<boolean>>;
    setError: React.Dispatch<React.SetStateAction<boolean>>
}

const useArticles = (): UseFetchReturn => {
    const [loading, setLoading] = useState<boolean>(true);
    const [error, setError] = useState<boolean>(false);
    const [paginationData, setPaginationData] = useState<PaginationResponse | null>(null);

    const fetchArticles = useCallback(async () => {
        setLoading(true);
       try {
            const response = await ArticleService.get();
            setPaginationData(response);
        } catch (e) {
            console.error('Failed to fetch articles:', e);
            setError(true);
        } finally {
            setLoading(false);
        }
    }, []);

    useEffect(() => {
        fetchArticles();
    }, [fetchArticles]);

    return {
        loading,
        error,
        paginationData,
        setPaginationData,
        setLoading,
        setError
    };
}

export default useArticles;