<?php

namespace App\Entity;

use App\Repository\AnswerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Contract\Entity\TimestampableInterface;
use Knp\DoctrineBehaviors\Model\Timestampable\TimestampableTrait;

#[ORM\Entity(repositoryClass: AnswerRepository::class)]
class Answer implements TimestampableInterface
{
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $question = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $answer = null;

    #[ORM\ManyToOne(inversedBy: 'answer')]
    private ?QuestionnaireAnswer $questionnaireAnswer = null;

    #[ORM\Column(options: ['default' => false])]
    private ?bool $gpt = false;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $instruction = null;

    #[ORM\Column(nullable: true)]
    private ?array $denominators = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $prompt = null;

    public function __toString(): string
    {
        return $this->question ?? '';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(?string $answer): self
    {
        $this->answer = $answer;

        return $this;
    }

    public function getQuestionnaireAnswer(): ?QuestionnaireAnswer
    {
        return $this->questionnaireAnswer;
    }

    public function setQuestionnaireAnswer(?QuestionnaireAnswer $questionnaireAnswer): self
    {
        $this->questionnaireAnswer = $questionnaireAnswer;

        return $this;
    }

    public function isGpt(): ?bool
    {
        return $this->gpt;
    }

    public function setGpt(bool $gpt): static
    {
        $this->gpt = $gpt;

        return $this;
    }

    public function getInstruction(): ?string
    {
        return $this->instruction;
    }

    public function setInstruction(?string $instruction): static
    {
        $this->instruction = $instruction;

        return $this;
    }

    public function getDenominators(): ?array
    {
        return $this->denominators;
    }

    public function setDenominators(?array $denominators): static
    {
        $this->denominators = $denominators;

        return $this;
    }

    public function getPrompt(): ?string
    {
        return $this->prompt;
    }

    public function setPrompt(string $prompt): static
    {
        $this->prompt = $prompt;

        return $this;
    }
}
