<?php

declare(strict_types=1);

namespace App\Entity\Blog;

use Odiseo\BlogBundle\Model\ArticleTranslation as BaseArticleTranslation;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="odiseo_blog_article_translation")
 */
final class ArticleTranslation extends BaseArticleTranslation
{
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $intro;

    /**
     * @ORM\Column(type="text", name="first_paragraph", nullable=true)
     */
    private $firstParagraph;

    public function getFirstParagraph(): ?string
    {
        return $this->firstParagraph;
    }

    public function setFirstParagraph(?string $firstParagraph): void
    {
        $this->firstParagraph = $firstParagraph;
    }

    public function getIntro(): ?string
    {
        return $this->intro;
    }

    public function setIntro(?string $intro): void
    {
        $this->intro = $intro;
    }
}
