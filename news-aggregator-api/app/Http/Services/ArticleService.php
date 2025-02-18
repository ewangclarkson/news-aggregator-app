<?php
namespace App\Http\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
/**
 * Interface ArticleService
 *
 * Defines the contract for services related to articles, including methods
 * for searching articles based on various criteria.
 */
interface ArticleService
{
    /**
     * Search for articles based on the provided request parameters.
     *
     * @param Request $request The incoming request containing search parameters.
     * @return Collection A collection of articles matching the search criteria.
     */
    public function search(Request $request);


    /**
     * Search Articles based on user preferences.
     *
     * @return mixed
     */
    public function get();


    /**
     * Get all categories and authors in the system
     * @return mixed
     */
    public function getUniqueCategoriesAndAuthors();
}