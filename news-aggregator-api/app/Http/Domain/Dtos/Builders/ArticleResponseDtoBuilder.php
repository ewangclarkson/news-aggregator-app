<?php

namespace App\Http\Domain\Dtos\Builders;

use App\Http\Domain\Dtos\ArticleResponseDto;

/**
 * Class ArticleResponseDtoBuilder
 *
 * A builder class for constructing instances of ArticleResponseDto.
 *
 * This class provides a fluent interface for setting the properties
 * of the ArticleResponseDto and creating an instance of it.
 *
 * @package App\Http\Domain\Dtos\Builders
 */
class ArticleResponseDtoBuilder
{
    private $id;
    private $title;
    private $content;
    private $category;
    private $source;
    private $author;
    private $description;
    private $link;
    private $image;
    private $published_at;

    /**
     * Set the ID of the article.
     *
     * @param mixed $id The unique identifier for the article.
     * @return $this Returns the builder instance for method chaining.
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Set the title of the article.
     *
     * @param mixed $title The title of the article.
     * @return $this Returns the builder instance for method chaining.
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Set the content of the article.
     *
     * @param mixed $content The content of the article.
     * @return $this Returns the builder instance for method chaining.
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Set the category of the article.
     *
     * @param mixed $category The category of the article.
     * @return $this Returns the builder instance for method chaining.
     */
    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }

    /**
     * Set the source of the article.
     *
     * @param mixed $source The source of the article.
     * @return $this Returns the builder instance for method chaining.
     */
    public function setSource($source)
    {
        $this->source = $source;
        return $this;
    }

    /**
     * Set the author of the article.
     *
     * @param mixed $author The author of the article.
     * @return $this Returns the builder instance for method chaining.
     */
    public function setAuthor($author)
    {
        $this->author = $author;
        return $this;
    }

    /**
     * Set the description of the article.
     *
     * @param mixed $description A brief description of the article.
     * @return $this Returns the builder instance for method chaining.
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Set the link of the article.
     *
     * @param mixed $link The link to the article.
     * @return $this Returns the builder instance for method chaining.
     */
    public function setLink($link)
    {
        $this->link = $link;
        return $this;
    }

    /**
     * Set the image of the article.
     *
     * @param mixed $image The image associated with the article.
     * @return $this Returns the builder instance for method chaining.
     */
    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * Set the publication date of the article.
     *
     * @param mixed $published_at The publication date of the article.
     * @return $this Returns the builder instance for method chaining.
     */
    public function setPublishedAt($published_at)
    {
        $this->published_at = $published_at;
        return $this;
    }

    /**
     * Build and return an ArticleResponseDto instance.
     *
     * @return ArticleResponseDto Returns a new instance of ArticleResponseDto
     *                           populated with the set properties.
     */
    public function build()
    {
        return new ArticleResponseDto(
            $this->id,
            $this->title,
            $this->content,
            $this->category,
            $this->source,
            $this->author,
            $this->description,
            $this->link,
            $this->image,
            $this->published_at
        );
    }
}