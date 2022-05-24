<?php

namespace xVer\Symfony\Bundle\BaseAppBundle\Domain\Account;

use xVer\Bundle\DomainBundle\Domain\EntityObjectInterface;

interface AccountInterface extends EntityObjectInterface
{
    public function getEmail(): string;

    /**
     * @psalm-return list<string>
     */
    public function getRoles(): array;

    public function getPassword(): string;
}
