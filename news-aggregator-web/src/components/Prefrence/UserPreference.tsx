import React, { useEffect, useState } from 'react';
import UserPreferenceService from "../../services/UserPreferenceService";
import { UserPreferenceRequest } from "../../models/UserPreferenceRequest";
import ArticleService from "../../services/ArticleService";
import "./UserPreference.scss";

declare global {
    interface Window {
        $: any; // Declare jQuery in the window object
    }
}

const NewsSourceConstants = {
    NEWS_API: 'NEWS_ORG',
    GUARDIAN_NEWS_API: 'GUARDIAN_NEWS',
    NEW_YORK_TIME_NEWS_API: 'NEW_YORK_TIME_NEWS',
};

const UserPreference: React.FC = () => {
    const newsSources = Object.values(NewsSourceConstants);
    const [currentSources, setCurrentSources] = useState<string[]>([]);
    const [selectedSources, setSelectedSources] = useState<string[]>([]);
    const [categories, setCategories] = useState<string[]>([]);
    const [selectedCategories, setSelectedCategories] = useState<string[]>([]);
    const [authors, setAuthors] = useState<string[]>([]);
    const [selectedAuthors, setSelectedAuthors] = useState<string[]>([]);

    useEffect(() => {
        const fetchCategoriesAndAuthors = async () => {
            try {
                const response = await ArticleService.getCategoriesAndAuthors();
                setCategories(response.categories);
                setAuthors(response.authors);
            } catch (error) {
                console.error("Failed to fetch categories and authors:", error);
            }
        };

        const fetchCurrentPreferences = async () => {
            try {
                const data = await UserPreferenceService.get();
                if (data) {
                    setCurrentSources(data.source || []);
                    setSelectedSources(data.source || []);
                    setSelectedCategories(data.category || []);
                    setSelectedAuthors(data.authors || []);
                } else {
                    setCurrentSources([]);
                    setSelectedSources([]);
                }
            } catch (error) {
                console.error("Failed to fetch user preferences:", error);
            }
        };
        fetchCurrentPreferences();
        fetchCategoriesAndAuthors();
    }, []);

    useEffect(() => {
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
            }
        });

        return () => {
            if ($select2.length) {
                $select2.select2('destroy');
            }
        };
    }, [categories, authors]);

    const handleSourceClick = (source: string) => {
        setSelectedSources((prev) => {
            if (prev.includes(source)) {
                return prev.filter(s => s !== source);
            } else {
                return [...prev, source];
            }
        });
    };

    const handleSaveChanges = async () => {
        const success = await saveSelectedPreferences({
            preferences: {
                sources: selectedSources,
                categories: selectedCategories,
                authors: selectedAuthors || [],
            }
        });
        if (success) {
            setCurrentSources(selectedSources);
        }
    };

    const saveSelectedPreferences = async (preferences: UserPreferenceRequest): Promise<boolean> => {
        try {
            await UserPreferenceService.save(preferences);
            return true;
        } catch (error) {
            console.error("Failed to save user preferences:", error);
            return false;
        }
    };

    return (
        <>
            <div className="modal fade" id="modal-small" tabIndex={-1}>
                <div className="modal-dialog">
                    <div className="modal-content">
                        <div className="modal-header">
                            <h5 className="modal-title pull-left">User Preferences</h5>
                        </div>
                        <div className="card-body">
                            <h6 className="card-subtitle">Choose from the available news sources, categories, and authors to streamline your news feed.</h6>

                            <div className="tags">
                                <h6>News Sources</h6>
                                {newsSources.map((source) => (
                                    <a
                                        key={source}
                                        href="#"
                                        onClick={(e) => {
                                            e.preventDefault();
                                            handleSourceClick(source);
                                        }}
                                        style={{
                                            backgroundColor: selectedSources.includes(source) ? 'blue' : 'transparent',
                                            color: selectedSources.includes(source) ? 'white' : 'black',
                                            padding: '0.5rem',
                                            margin: '0.2rem',
                                            borderRadius: '4px',
                                            textDecoration: 'none',
                                            fontWeight: currentSources.includes(source) ? 'bold' : 'normal',
                                        }}
                                    >
                                        {source.replace(/_/g, ' ')
                                                .split(' ')
                                                .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
                                                .join(' ')} {currentSources.includes(source) ? '(Current)' : ''}
                                    </a>
                                ))}
                            </div>
                            <br />
                            <div className="row">
                                <div className="col-sm-12">
                                    <div className="form-group">
                                        <h6>Categories</h6>
                                        <select className="select2 categories-select" multiple style={{ width: '100%' }}>
                                            {categories.map((category) => (
                                                <option key={category} value={category} selected={selectedCategories.includes(category)}>
                                                    {category}
                                                </option>
                                            ))}
                                        </select>
                                    </div>
                                </div>
                                <div className="col-sm-12">
                                    <div className="form-group">
                                        <h6>Authors</h6>
                                        <select className="select2 authors-select" multiple style={{ width: '100%' }}>
                                            {authors.map((author) => (
                                                <option key={author} value={author} selected={selectedAuthors.includes(author)}>
                                                    {author}
                                                </option>
                                            ))}
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div className="modal-footer">
                            <button type="button" className="btn btn-link" onClick={handleSaveChanges}>
                                Save changes
                            </button>
                            <button type="button" className="btn btn-link" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
};

export default UserPreference;