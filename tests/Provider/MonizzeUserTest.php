<?php

namespace Jzecca\OAuth2\Client\Test\Provider;

use Jzecca\OAuth2\Client\Provider\MonizzeUser;
use PHPUnit\Framework\TestCase;

class MonizzeUserTest extends TestCase
{
    /**
     * @var MonizzeUser
     */
    private $user;

    protected function setUp(): void
    {
        $this->user = new MonizzeUser([
            'data' => [
                'id' => 1068024,
                'firstname' => 'David',
                'lastname' => 'Lumaye',
                'email' => 'dlu@monizze.be',
                'language' => 'fr',
                'newsletter' => true,
                'hasActiveCard' => true,
            ],
        ]);
    }

    public function testUserDefaults(): void
    {
        $this->assertEquals(1068024, $this->user->getId());
        $this->assertEquals('David', $this->user->getFirstName());
        $this->assertEquals('Lumaye', $this->user->getLastName());
        $this->assertEquals('dlu@monizze.be', $this->user->getEmail());
        $this->assertEquals('fr', $this->user->getLanguage());
        $this->assertTrue($this->user->getNewsletter());
        $this->assertTrue($this->user->hasActiveCard());
    }

    public function testCanGetAllDataBackAsAnArray(): void
    {
        $data = $this->user->toArray();

        $expectedData = [
            'id' => 1068024,
            'firstname' => 'David',
            'lastname' => 'Lumaye',
            'email' => 'dlu@monizze.be',
            'language' => 'fr',
            'newsletter' => true,
            'hasActiveCard' => true,
        ];

        self::assertEquals($expectedData, $data);
    }
}
