<?php

namespace xVer\Symfony\Bundle\BaseAppBundle\Domain\Account;

use xVer\Bundle\DomainBundle\Domain\EntityObjectsCollection;

/**
  * @template-extends EntityObjectsCollection<Account>
 */
class AccountsCollection extends EntityObjectsCollection
{
    public function type(): string
    {
        return Account::class;
    }
}
