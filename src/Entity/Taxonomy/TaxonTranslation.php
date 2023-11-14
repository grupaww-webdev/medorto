<?php

declare(strict_types=1);

namespace App\Entity\Taxonomy;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Taxonomy\Model\TaxonTranslation as BaseTaxonTranslation;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_taxon_translation")
 */
class TaxonTranslation extends BaseTaxonTranslation
{
    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    protected ?string $title;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }


}
