<?php

namespace Tests\unit\Ui\Entity;

use xVer\Symfony\Bundle\BaseAppBundle\Ui\Entity\AuthUser;
use PHPUnit\Framework\TestCase;

/**
 * @covers xVer\Symfony\Bundle\BaseAppBundle\Ui\Entity\AuthUser
 */
class AuthUserTest extends TestCase
{
    public function testEntity(): void
    {
        $email = 'test@example.com';
        $roles = ['ROLE_USER'];
        $password = 'password';
        $authUser = new AuthUser($email, $roles, $password);
        $this->assertSame($email, $authUser->getUserIdentifier());
        $this->assertSame($roles, $authUser->getRoles());
        $this->assertSame($password, $authUser->getPassword());
        $authUser->eraseCredentials();
    }
}
