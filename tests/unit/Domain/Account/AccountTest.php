<?php declare(strict_types=1);

namespace Tests\unit\Domain\Account;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;
use xVer\Bundle\DomainBundle\Domain\DomainException;
use xVer\Bundle\DomainBundle\Domain\EntityObjectInterface;
use xVer\Symfony\Bundle\BaseAppBundle\Domain\Account\Account;

/**
 * @covers xVer\Symfony\Bundle\BaseAppBundle\Domain\Account\Account
 */
class AccountTest extends TestCase
{
    public function testAccountObjectIsCreated(): void
    {
        $email = 'test@example.com';
        $password = 'password';
        $account = new Account($email, $password, ['ROLE_ADMIN']);
        $this->assertInstanceOf(Uuid::class, $account->getId());
        $this->assertTrue($account->sameId($account));
        $this->assertCount(2, $account->getRoles());
        $this->assertContains('ROLE_USER', $account->getRoles());
        $this->assertContains('ROLE_ADMIN', $account->getRoles());
        $this->assertSame($email, $account->getEmail());
        $this->assertSame($password, $account->getPassword());
        $this->assertSame($email, $account->getIdentifier());
    }

    public function testUpdateAccountWithInvalidEmailThrowsException(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('error_invalid_email');
        $account = new Account('test@example.com', 'password', ['ROLE_USER']);
        $invalid_email = 'test@example';
        $account->setEmail($invalid_email);
    }

    public function testUpdateAccountWithEmptyEmailThrowsException(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('error_invalid_email');
        $account = new Account('', 'password', ['ROLE_USER']);
        $invalid_email = 'test@example';
        $account->setEmail($invalid_email);
    }

    public function testCreateAccountWithInvalidRoleThrowsException(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('invalidUserRole');
        new Account('test@example.com', 'password', ['ROLE_NOEXISTS']);
    }

    public function testSetPassword(): void
    {
        $account = new Account('test@example.com', 'password', ['ROLE_USER']);
        $password = 'd77f3Sljj4d0s';
        $account->setPassword($password);
        $this->assertSame($password, $account->getPassword());
    }

    public function testSameIdWithInvalidEntityThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $account = new Account('test@example.com', 'password', ['ROLE_USER']);
        $entity = new class implements EntityObjectInterface { public function sameId(EntityObjectInterface $otherEntity): bool { return true; }};
        $account->sameId($entity);
    }
}
