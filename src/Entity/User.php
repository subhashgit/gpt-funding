<?php

namespace App\Entity;

use App\Entity\Grant\Suggestion;
use App\Entity\Message\Message;
use App\Entity\Notification\UserFundingNotification;
use App\Entity\Notification\UserGrantNotification;
use App\Entity\Request\Request;
use App\Enum\AccountType;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Sonata\UserBundle\Entity\BaseUser;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User extends BaseUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    protected $id;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: QuestionnaireAnswer::class, orphanRemoval: true)]
    private Collection $questionnaireAnswers;

    #[ORM\Column(options: ['default' => 0])]
    private ?int $points = 0;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserFilter::class, orphanRemoval: true)]
    private Collection $userFilters;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserFundingNotification::class, orphanRemoval: true)]
    private Collection $userFundingNotifications;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserGrantNotification::class, orphanRemoval: true)]
    private Collection $userGrantNotifications;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserLikes::class, orphanRemoval: true)]
    private Collection $userLikes;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $subscribed_at = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserCompany::class, orphanRemoval: true)]
    private Collection $companies;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserProject::class, orphanRemoval: true)]
    private Collection $projects;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Suggestion::class, orphanRemoval: true)]
    private Collection $suggestions;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserViews::class, orphanRemoval: true)]
    private Collection $views;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $notificationEmail = null;

    #[ORM\Column(type: Types::STRING, length: 255, enumType: AccountType::class, options: ['default' => AccountType::FREE])]
    private AccountType $accountType = AccountType::FREE;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $lastLoggedIn = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lastName = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $company = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $customerId = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Transaction::class, orphanRemoval: true)]
    private Collection $transactions;

    #[ORM\ManyToOne(inversedBy: 'user')]
    private ?Tariff $tariff = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserTariff::class)]
    private Collection $userTariffs;

    #[ORM\Column(options: ['default' => true])]
    private ?bool $brief = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Request::class, orphanRemoval: true)]
    private Collection $requests;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Message::class, orphanRemoval: true)]
    private Collection $messages;

    public function __construct()
    {
        $this->questionnaireAnswers = new ArrayCollection();
        $this->userFilters = new ArrayCollection();
        $this->userFundingNotifications = new ArrayCollection();
        $this->userGrantNotifications = new ArrayCollection();
        $this->userLikes = new ArrayCollection();
        $this->companies = new ArrayCollection();
        $this->projects = new ArrayCollection();
        $this->suggestions = new ArrayCollection();
        $this->views = new ArrayCollection();
        $this->transactions = new ArrayCollection();
        $this->userTariffs = new ArrayCollection();
        $this->requests = new ArrayCollection();
        $this->messages = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->email ?? '';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    //    /**
    //     * @see UserInterface
    //     */
    //    public function getRoles(): array
    //    {
    //        $roles = $this->roles;
    //        // guarantee every user at least has ROLE_USER
    //        $roles[] = 'ROLE_USER';
    //
    //        return array_unique($roles);
    //    }
    //
    //    public function setRoles(array $roles): void
    //    {
    //        $this->roles = $roles;
    //
    //    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection<int, QuestionnaireAnswer>
     */
    public function getQuestionnaireAnswers(): Collection
    {
        return $this->questionnaireAnswers;
    }

    public function addQuestionnaireAnswer(QuestionnaireAnswer $questionnaireAnswer): static
    {
        if (!$this->questionnaireAnswers->contains($questionnaireAnswer)) {
            $this->questionnaireAnswers->add($questionnaireAnswer);
            $questionnaireAnswer->setUser($this);
        }

        return $this;
    }

    public function removeQuestionnaireAnswer(QuestionnaireAnswer $questionnaireAnswer): static
    {
        if ($this->questionnaireAnswers->removeElement($questionnaireAnswer)) {
            // set the owning side to null (unless already changed)
            if ($questionnaireAnswer->getUser() === $this) {
                $questionnaireAnswer->setUser(null);
            }
        }

        return $this;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(int $points): static
    {
        $this->points = $points;

        return $this;
    }

    /**
     * @return Collection<int, UserFilter>
     */
    public function getUserFilters(): Collection
    {
        return $this->userFilters;
    }

    public function addUserFilter(UserFilter $userFilter): static
    {
        if (!$this->userFilters->contains($userFilter)) {
            $this->userFilters->add($userFilter);
            $userFilter->setUser($this);
        }

        return $this;
    }

    public function removeUserFilter(UserFilter $userFilter): static
    {
        if ($this->userFilters->removeElement($userFilter)) {
            // set the owning side to null (unless already changed)
            if ($userFilter->getUser() === $this) {
                $userFilter->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserFundingNotification>
     */
    public function getUserFundingNotifications(): Collection
    {
        return $this->userFundingNotifications;
    }

    public function addUserFundingNotification(UserFundingNotification $userFundingNotification): static
    {
        if (!$this->userFundingNotifications->contains($userFundingNotification)) {
            $this->userFundingNotifications->add($userFundingNotification);
            $userFundingNotification->setUser($this);
        }

        return $this;
    }

    public function removeUserFundingNotification(UserFundingNotification $userFundingNotification): static
    {
        if ($this->userFundingNotifications->removeElement($userFundingNotification)) {
            // set the owning side to null (unless already changed)
            if ($userFundingNotification->getUser() === $this) {
                $userFundingNotification->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserGrantNotification>
     */
    public function getUserGrantNotifications(): Collection
    {
        return $this->userGrantNotifications;
    }

    public function addUserGrantNotification(UserGrantNotification $userGrantNotification): static
    {
        if (!$this->userGrantNotifications->contains($userGrantNotification)) {
            $this->userGrantNotifications->add($userGrantNotification);
            $userGrantNotification->setUser($this);
        }

        return $this;
    }

    public function removeUserGrantNotification(UserGrantNotification $userGrantNotification): static
    {
        if ($this->userGrantNotifications->removeElement($userGrantNotification)) {
            // set the owning side to null (unless already changed)
            if ($userGrantNotification->getUser() === $this) {
                $userGrantNotification->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserLikes>
     */
    public function getUserLikes(): Collection
    {
        return $this->userLikes;
    }

    public function addUserLike(UserLikes $userLike): static
    {
        if (!$this->userLikes->contains($userLike)) {
            $this->userLikes->add($userLike);
            $userLike->setUser($this);
        }

        return $this;
    }

    public function removeUserLike(UserLikes $userLike): static
    {
        if ($this->userLikes->removeElement($userLike)) {
            // set the owning side to null (unless already changed)
            if ($userLike->getUser() === $this) {
                $userLike->setUser(null);
            }
        }

        return $this;
    }

    public function getSubscribedAt(): ?\DateTimeImmutable
    {
        return $this->subscribed_at;
    }

    public function setSubscribedAt(?\DateTimeImmutable $subscribed_at): static
    {
        $this->subscribed_at = $subscribed_at;

        return $this;
    }

    public function isSubscribed(): bool
    {
        return true;

        return null !== $this->subscribed_at
            && $this->subscribed_at->modify('+1 year')->getTimestamp() >= time();
    }

    /**
     * @return Collection<int, UserCompany>
     */
    public function getCompanies(): Collection
    {
        return $this->companies;
    }

    public function addCompany(UserCompany $company): static
    {
        if (!$this->companies->contains($company)) {
            $this->companies->add($company);
            $company->setUser($this);
        }

        return $this;
    }

    public function removeCompany(UserCompany $company): static
    {
        if ($this->companies->removeElement($company)) {
            // set the owning side to null (unless already changed)
            if ($company->getUser() === $this) {
                $company->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserProject>
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(UserProject $project): static
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
            $project->setUser($this);
        }

        return $this;
    }

    public function removeProject(UserProject $project): static
    {
        if ($this->projects->removeElement($project)) {
            // set the owning side to null (unless already changed)
            if ($project->getUser() === $this) {
                $project->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Suggestion>
     */
    public function getSuggestions(): Collection
    {
        return $this->suggestions;
    }

    public function addSuggestion(Suggestion $suggestion): static
    {
        if (!$this->suggestions->contains($suggestion)) {
            $this->suggestions->add($suggestion);
            $suggestion->setUser($this);
        }

        return $this;
    }

    public function removeSuggestion(Suggestion $suggestion): static
    {
        if ($this->suggestions->removeElement($suggestion)) {
            // set the owning side to null (unless already changed)
            if ($suggestion->getUser() === $this) {
                $suggestion->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserViews>
     */
    public function getViews(): Collection
    {
        return $this->views;
    }

    public function addView(UserViews $view): static
    {
        if (!$this->views->contains($view)) {
            $this->views->add($view);
            $view->setUser($this);
        }

        return $this;
    }

    public function removeView(UserViews $view): static
    {
        if ($this->views->removeElement($view)) {
            // set the owning side to null (unless already changed)
            if ($view->getUser() === $this) {
                $view->setUser(null);
            }
        }

        return $this;
    }

    public function isSubscriptionActive(): bool
    {
        return in_array('ROLE_ADMIN', $this->roles) or (null !== $this->userTariffs->last() and $this->userTariffs->last() and $this->userTariffs->last()->getDateEnd() >= date(time()));
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }

    public function getNotificationEmail(): ?string
    {
        return $this->notificationEmail;
    }

    public function setNotificationEmail(?string $notificationEmail): static
    {
        $this->notificationEmail = $notificationEmail;

        return $this;
    }

    public function getAccountType(): ?AccountType
    {
        return $this->accountType;
    }

    public function setAccountType(?AccountType $accountType): static
    {
        $this->accountType = $accountType;

        return $this;
    }

    public function getLastLoggedIn(): ?\DateTimeInterface
    {
        return $this->lastLoggedIn;
    }

    public function setLastLoggedIn(?\DateTimeInterface $lastLoggedIn): static
    {
        $this->lastLoggedIn = $lastLoggedIn;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(?string $company): static
    {
        $this->company = $company;

        return $this;
    }

    public function getCustomerId(): ?string
    {
        return $this->customerId;
    }

    public function setCustomerId(?string $customerId): static
    {
        $this->customerId = $customerId;

        return $this;
    }

    /**
     * @return Collection<int, Transaction>
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): static
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions->add($transaction);
            $transaction->setUser($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): static
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getUser() === $this) {
                $transaction->setUser(null);
            }
        }

        return $this;
    }

    public function getTariff(): ?Tariff
    {
        return $this->tariff;
    }

    public function setTariff(?Tariff $tariff): static
    {
        $this->tariff = $tariff;

        return $this;
    }

    /**
     * @return Collection<int, UserTariff>
     */
    public function getUserTariffs(): Collection
    {
        return $this->userTariffs;
    }

    public function addUserTariff(UserTariff $userTariff): static
    {
        if (!$this->userTariffs->contains($userTariff)) {
            $this->userTariffs->add($userTariff);
            $userTariff->setUser($this);
        }

        return $this;
    }

    public function removeUserTariff(UserTariff $userTariff): static
    {
        if ($this->userTariffs->removeElement($userTariff)) {
            // set the owning side to null (unless already changed)
            if ($userTariff->getUser() === $this) {
                $userTariff->setUser(null);
            }
        }

        return $this;
    }

    public function isBrief(): ?bool
    {
        return $this->brief;
    }

    public function setBrief(bool $brief): static
    {
        $this->brief = $brief;

        return $this;
    }

    /**
     * @return Collection<int, Request>
     */
    public function getRequests(): Collection
    {
        return $this->requests;
    }

    public function addRequest(Request $request): static
    {
        if (!$this->requests->contains($request)) {
            $this->requests->add($request);
            $request->setUser($this);
        }

        return $this;
    }

    public function removeRequest(Request $request): static
    {
        if ($this->requests->removeElement($request)) {
            // set the owning side to null (unless already changed)
            if ($request->getUser() === $this) {
                $request->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): static
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setAuthor($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getAuthor() === $this) {
                $message->setAuthor(null);
            }
        }

        return $this;
    }
}
