<?php

namespace App\Entity\Message;

use App\Entity\Request\ProjectRequest;
use App\Repository\Message\ProjectMessageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectMessageRepository::class)]
class ProjectMessage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'message')]
    private ?ProjectRequest $request = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRequest(): ?ProjectRequest
    {
        return $this->request;
    }

    public function setRequest(?ProjectRequest $request): static
    {
        $this->request = $request;

        return $this;
    }
}
