<?php
declare(strict_types=1);

namespace App\Entity\Channel;

interface FacebookMessengerAwareInterface
{
    public function setEnableFacebookMessenger(?bool $availableOnDemand): void;

    public function isEnableFacebookMessenger(): ?bool;

    public function setFacebookPageId(?string $facebookPageId): void;

    public function getFacebookPageId(): ?string;
}
