<?php

namespace xVer\Symfony\Bundle\BaseAppBundle\Ui\Security;

use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use xVer\Symfony\Bundle\BaseAppBundle\Application\Query\Account\AccountQueryInterface;
use xVer\Symfony\Bundle\BaseAppBundle\Ui\Entity\AuthUser;

/**
 * @template-implements PasswordUpgraderInterface<AuthUser>
 */
class Provider implements UserProviderInterface, PasswordUpgraderInterface
{
    public function __construct(
        protected readonly AccountQueryInterface $accountQuery
    ) {
    }

    /**
     * @throws UserNotFoundException if the user is not found
     */
    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        try {
            $account = $this->accountQuery->byIdentifier($identifier);
            return new AuthUser(
                $identifier,
                $account->getRoles(),
                $account->getPassword()
            );
        } catch (\Exception) {
            throw new UserNotFoundException();
        }
    }

    /**
     * Refreshes the user after being reloaded from the session.
     *
     * When a user is logged in, at the beginning of each request, the
     * User object is loaded from the session and then this method is
     * called. Your job is to make sure the user's data is still fresh by,
     * for example, re-querying for fresh User data.
     *
     * If your firewall is "stateless: true" (for a pure API), this
     * method is not called.
     *
     * @return UserInterface
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof AuthUser) {
            throw new UnsupportedUserException(sprintf('Invalid user class "%s".', $user::class));
        }
        return $this->loadUserByIdentifier($user->getUserIdentifier());
    }

    /**
     * Tells Symfony to use this provider for this User class.
     */
    public function supportsClass(string $class): bool
    {
        return AuthUser::class === $class || is_subclass_of($class, AuthUser::class);
    }

    /**
     * No user password upgrades scoped
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
    }
}
