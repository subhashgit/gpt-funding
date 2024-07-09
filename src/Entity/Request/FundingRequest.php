<?php

namespace App\Entity\Request;

use App\Entity\Funding;
use App\Repository\Request\FundingRequestRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FundingRequestRepository::class)]
class FundingRequest extends Request
{
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?Funding $funding = null;

    public function getFunding(): ?Funding
    {
        return $this->funding;
    }

    public function setFunding(?Funding $funding): static
    {
        $this->funding = $funding;

        return $this;
    }
}
