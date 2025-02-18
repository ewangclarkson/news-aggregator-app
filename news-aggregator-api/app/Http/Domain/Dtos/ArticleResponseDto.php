<?php

namespace App\Http\Domain\Dtos;

use App\Http\Domain\Dtos\Builders\ArticleResponseDtoBuilder;

/**
 * Class ArticleResponseDto
 *
 * Data Transfer Object (DTO) for article response data.
 *
 * This class encapsulates the data structure for an article,
 * including its unique identifier, title, content, category,
 * source, author, description, link, image, and publication date.
 *
 * @package App\Http\Domain\Dtos
 */
class ArticleResponseDto
{
    public $id;
    public $title;
    public $content;
    public $category;
    public $source;
    public $author;
    public $description;
    public $link;
    public $image;
    public $published_at;

    /**
     * ArticleResponseDto constructor.
     */
    public function __construct($id, $title, $content, $category, $source, $author, $description, $link, $image, $published_at)
    {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->category = $category;
        $this->source = $source;
        $this->author = $author;
        $this->description = $description;
        $this->link = $link;
        $this->image = $image;
        $this->published_at = $published_at;
    }

    /**
     * Create a new instance of the ArticleResponseDtoBuilder.
     *
     * @return ArticleResponseDtoBuilder Returns a new instance of the builder
     *                                    for constructing ArticleResponseDto.
     */
    public static function builder()
    {
        return new ArticleResponseDtoBuilder();
    }
}