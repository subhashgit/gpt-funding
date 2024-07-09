<?php

namespace App\Entity\Request;

use App\Entity\Message\ProjectMessage;
use App\Repository\Request\ProjectRequestRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectRequestRepository::class)]
class ProjectRequest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'request', targetEntity: ProjectMessage::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $message;

    public function __construct()
    {
        $this->message = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, ProjectMessage>
     */
    public function getMessage(): Collection
    {
        return $this->message;
    }

    public function addMessage(ProjectMessage $message): static
    {
        if (!$this->message->contains($message)) {
            $this->message->add($message);
            $message->setRequest($this);
        }

        return $this;
    }

    public function removeMessage(ProjectMessage $message): static
    {
        if ($this->message->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getRequest() === $this) {
                $message->setRequest(null);
            }
        }

        return $this;
    }
}
