<?php

namespace xVer\Symfony\Bundle\BaseAppBundle\Application\Query\Account;

use xVer\Symfony\Bundle\BaseAppBundle\Domain\Account\AccountInterface;

interface AccountQueryInterface
{
    public function byIdentifier(string $identifier): AccountInterface;
}
