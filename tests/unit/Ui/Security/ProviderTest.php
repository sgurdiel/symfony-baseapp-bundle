<?php

namespace Tests\unit\Ui\Security;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use xVer\Symfony\Bundle\BaseAppBundle\Application\Query\Account\AccountQueryInterface;
use xVer\Symfony\Bundle\BaseAppBundle\Domain\Account\AccountInterface;
use xVer\Symfony\Bundle\BaseAppBundle\Ui\Entity\AuthUser;
use xVer\Symfony\Bundle\BaseAppBundle\Ui\Security\Provider;

/**
 * @covers xVer\Symfony\Bundle\BaseAppBundle\Ui\Security\Provider
 * @uses xVer\Symfony\Bundle\BaseAppBundle\Ui\Entity\AuthUser
 */
class ProviderTest extends TestCase
{
    public function testUserNotFoundThrowsException(): void
    {
        $this->expectException(UserNotFoundException::class);
        /** @var AccountQueryInterface&MockObject */
        $accountQueryMock = $this->getMockBuilder(AccountQueryInterface::class)->getMock();
        $accountQueryMock->expects($this->once())->method('byIdentifier')->willThrowException(new \DomainException());
        $provider = new Provider($accountQueryMock);
        $provider->loadUserByIdentifier('test@example.com');
    }

    public function testUserFound(): void
    {
        /** @var AccountInterface&MockObject */
        $account = $this->getMockBuilder(AccountInterface::class)->getMock();
        /** @var AccountQueryInterface&MockObject */
        $accountQueryMock = $this->getMockBuilder(AccountQueryInterface::class)->getMock();
        $accountQueryMock->expects($this->once())->method('byIdentifier')->willReturn($account);
        $provider = new Provider($accountQueryMock);
        $this->assertInstanceOf(AuthUser::class, $provider->loadUserByIdentifier('test@example.com'));
    }

    public function testRefreshUserNotFoundThrowsException(): void
    {        
        $this->expectException(UnsupportedUserException::class);
        /** @var UserInterface&MockObject */
        $authUser = $this->getMockBuilder(UserInterface::class)->getMock();
        /** @var AccountQueryInterface&MockObject */
        $accountQueryMock = $this->getMockBuilder(AccountQueryInterface::class)->getMock();
        $provider = new Provider($accountQueryMock);
        $provider->refreshUser($authUser);
    }

    public function testRefreshUserWithFound(): void
    {
        /** @var AccountInterface&MockObject */
        $account = $this->getMockBuilder(AccountInterface::class)->getMock();
        /** @var AccountQueryInterface&MockObject */
        $accountQueryMock = $this->getMockBuilder(AccountQueryInterface::class)->getMock();
        $accountQueryMock->expects($this->once())->method('byIdentifier')->willReturn($account);
        $provider = new Provider($accountQueryMock);
        $authUser = new AuthUser('', [], '');
        $this->assertInstanceOf(AuthUser::class, $provider->refreshUser($authUser));
    }

    public function testSupportsClass(): void
    {
        /** @var AccountQueryInterface&MockObject */
        $accountQueryMock = $this->getMockBuilder(AccountQueryInterface::class)->getMock();
        $provider = new Provider($accountQueryMock);
        $this->assertTrue($provider->supportsClass(AuthUser::class));
    }

    public function testUpgradePassword(): void
    {
        /** @var AccountQueryInterface&MockObject */
        $accountQueryMock = $this->getMockBuilder(AccountQueryInterface::class)->getMock();
        $provider = new Provider($accountQueryMock);
        $provider->upgradePassword(new AuthUser('', [], ''), '');
        $this->assertTrue(true);
    }
}