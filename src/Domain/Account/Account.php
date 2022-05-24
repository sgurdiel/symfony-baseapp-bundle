<?php

namespace xVer\Symfony\Bundle\BaseAppBundle\Domain\Account;

use InvalidArgumentException;
use Symfony\Component\Uid\Uuid;
use xVer\Bundle\DomainBundle\Domain\DomainException;
use xVer\Bundle\DomainBundle\Domain\EntityObjectInterface;
use xVer\Bundle\DomainBundle\Domain\TranslationVO;

class Account implements AccountInterface
{
    final public const AVAILABLE_ROLES = ['ROLE_ADMIN','ROLE_USER'];
    protected Uuid $id;
    protected string $email;
    /** @var list<string> $roles */
    protected array $roles = ['ROLE_USER'];

    /**
     * @param list<string> $roles
     */
    public function __construct(
        string $email,
        protected string $password,
        array $roles = ['ROLE_USER']
    ) {
        $this->id = Uuid::v4();
        $this->setEmail($email);
        $this->setRoles($roles);
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function sameId(EntityObjectInterface $otherEntityObject): bool
    {
        if (!$otherEntityObject instanceof Account) {
            throw new InvalidArgumentException();
        }
        return $this->getId()->equals($otherEntityObject->getId());
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    final public function setEmail(string $email): self
    {
        if (false === filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new DomainException(
                new TranslationVO('error_invalid_email', [], TranslationVO::DOMAIN_VALIDATORS),
                'email'
            );
        }
        $this->email = $email;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param list<string> $roles
     */
    protected function setRoles(array $roles): self
    {
        foreach ($roles as $role) {
            if (!in_array($role, self::AVAILABLE_ROLES)) {
                throw new DomainException(
                    new TranslationVO(
                        'invalidUserRole',
                        [],
                        TranslationVO::DOMAIN_VALIDATORS
                    ),
                    'role'
                );
            }
        }
        $this->roles = $roles;
        // guarantee every user at least has ROLE_USER
        if (!in_array('ROLE_USER', $this->roles)) {
            array_push($this->roles, 'ROLE_USER');
        }

        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    final public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
}
