<?php

namespace App\Entity\Message;

use App\Entity\Request\FundingRequest;
use App\Repository\Message\FundingMessageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FundingMessageRepository::class)]
class FundingMessage extends Message
{
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?FundingRequest $request = null;

    public function getRequest(): ?FundingRequest
    {
        return $this->request;
    }

    public function setRequest(?FundingRequest $request): static
    {
        $this->request = $request;

        return $this;
    }
}
