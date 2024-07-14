<?php declare(strict_types=1);

namespace Tests\unit\Domain\Account;

use PHPUnit\Framework\TestCase;
use xVer\Symfony\Bundle\BaseAppBundle\Domain\Account\Account;
use xVer\Symfony\Bundle\BaseAppBundle\Domain\Account\AccountsCollection;

/**
 * @covers xVer\Symfony\Bundle\BaseAppBundle\Domain\Account\AccountsCollection
 */
class AccountsCollectionTest extends TestCase
{
    public function testCollection(): void
    {
        $accountsCollection = new AccountsCollection([]);
        $this->assertSame(Account::class, $accountsCollection->type());
    }
}
