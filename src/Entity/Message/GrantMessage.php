<?php

namespace App\Entity\Message;

use App\Entity\Request\GrantRequest;
use App\Repository\Message\GrantMessageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GrantMessageRepository::class)]
class GrantMessage extends Message
{
    #[ORM\ManyToOne(inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?GrantRequest $request = null;

    public function getRequest(): ?GrantRequest
    {
        return $this->request;
    }

    public function setRequest(?GrantRequest $request): static
    {
        $this->request = $request;

        return $this;
    }
}
