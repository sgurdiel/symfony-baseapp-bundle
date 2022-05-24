<?php

namespace xVer\Symfony\Bundle\BaseAppBundle\Domain\Account;

use xVer\Bundle\DomainBundle\Domain\EntityObjectRepositoryInterface;

interface AccountRepositoryInterface extends EntityObjectRepositoryInterface
{
    public function findByIdentifier(string $identifier): ?AccountInterface;
}
