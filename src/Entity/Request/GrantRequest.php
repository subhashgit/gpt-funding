<?php

namespace App\Entity\Request;

use App\Entity\Grant as GrantEntity;
use App\Entity\Message\GrantMessage;
use App\Repository\Request\GrantRequestRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GrantRequestRepository::class)]
class GrantRequest extends Request
{
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    #[ORM\OrderBy(['createdAt' => 'DESC'])]
    private ?GrantEntity $grant = null;

    #[ORM\OneToMany(mappedBy: 'request', targetEntity: GrantMessage::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $messages;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
    }

    public function getGrant(): ?GrantEntity
    {
        return $this->grant;
    }

    public function setGrant(?GrantEntity $grant): static
    {
        $this->grant = $grant;

        return $this;
    }

    /**
     * @return Collection<int, GrantMessage>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(GrantMessage $message): static
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setRequest($this);
        }

        return $this;
    }

    public function removeMessage(GrantMessage $message): static
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getRequest() === $this) {
                $message->setRequest(null);
            }
        }

        return $this;
    }
}
