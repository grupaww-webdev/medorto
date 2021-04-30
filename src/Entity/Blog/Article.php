<?php

declare(strict_types=1);

namespace App\Entity\Blog;

use Odiseo\SyliusBlogPlugin\Entity\Article as BaseArticle;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @property ArticleTranslation $translations
 * @ORM\Entity
 * @ORM\Table(name="odiseo_blog_article")
 */
final class Article extends BaseArticle
{
    public function getIntro(): ?string
    {
        return $this->getTranslation()->getIntro();
    }

    public function getFirstParagraph(): ?string
    {
        return $this->getTranslation()->getFirstParagraph();
    }
}
