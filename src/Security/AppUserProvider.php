<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\Mapping\ClassMetadata;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\Proxy;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class AppUserProvider implements UserProviderInterface
{
    public function __construct(private ManagerRegistry $registry, private readonly UserRepository $userRepository) {}

    public function loadUserByUsername($username)
    {
        return $this->fetchUser($username);
    }

    private function fetchUser(string $username): User
    {
        $user = $this->userRepository->getUser($username);

        if (!$user) {
            throw new UserNotFoundException(sprintf('Username "%s" does not exist.', $username));
        }

        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        $class = User::class;
        if (!$user instanceof $class) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_debug_type($user)));
        }

        $repository = $this->userRepository;
        if ($repository instanceof UserProviderInterface) {
            $refreshedUser = $repository->refreshUser($user);
        } else {
            // The user must be reloaded via the primary key as all other data
            // might have changed without proper persistence in the database.
            // That's the case when the user has been changed by a form with
            // validation errors.
            if (!$id = $this->getClassMetadata()->getIdentifierValues($user)) {
                throw new \InvalidArgumentException('You cannot refresh a user from the EntityUserProvider that does not contain an identifier. The user object has to be serialized with its own identifier mapped by Doctrine.');
            }

            $refreshedUser = $repository->find($id);
            if (null === $refreshedUser) {
                $e = new UserNotFoundException('User with id '.json_encode($id).' not found.');
                $e->setUserIdentifier(json_encode($id));

                throw $e;
            }
        }

        if ($refreshedUser instanceof Proxy && !$refreshedUser->__isInitialized()) {
            $refreshedUser->__load();
        }

        return $refreshedUser;
    }

    public function supportsClass(string $class): bool
    {
        return User::class === $class || is_subclass_of($class, User::class);
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        return $this->fetchUser($identifier);
    }

    private function getClassMetadata(): ClassMetadata
    {
        return $this->getObjectManager()->getClassMetadata(User::class);
    }

    private function getObjectManager(): ObjectManager
    {
        return $this->registry->getManager();
    }
}
