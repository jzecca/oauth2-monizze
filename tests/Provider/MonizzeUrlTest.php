<?php

namespace Jzecca\OAuth2\Client\Test\Provider;

use Jzecca\OAuth2\Client\Provider\Monizze;
use PHPUnit\Framework\TestCase;

class MonizzeBaseUrlTest extends TestCase
{
    public function provideOptions(): array
    {
        return [
            [[], 'en'],
            [['locale' => 'en'], 'en'],
            [['locale' => 'FR'], 'fr'],
            [['locale' => 'nL'], 'nl'],
            [['locale' => 'it'], 'en'],  // fallback
        ];
    }

    /**
     * @dataProvider provideOptions
     */
    public function testAuthorizationUrl(array $options, string $expectedLocale): void
    {
        $provider = new Monizze($options);
        $url = $provider->getAuthorizationUrl();
        $uri = parse_url($url);

        $this->assertEquals('https', $uri['scheme']);
        $this->assertEquals('auth.monizze.be', $uri['host']);
        $this->assertEquals(sprintf('/%s/oauth/authorize', $expectedLocale), $uri['path']);
    }

    public function testAccessTokenUrl(): void
    {
        $provider = new Monizze();
        $url = $provider->getBaseAccessTokenUrl([]);
        $uri = parse_url($url);

        $this->assertEquals('https', $uri['scheme']);
        $this->assertEquals('api.monizze.be', $uri['host']);
        $this->assertEquals('/oauth/token', $uri['path']);
    }

    public function testResourceOwnerDetailsUrl(): void
    {
        $provider = new Monizze();
        $url = $provider->getResourceOwnerDetailsUrl(MonizzeTest::mockAccessToken());
        $uri = parse_url($url);

        $this->assertEquals('https', $uri['scheme']);
        $this->assertEquals('api.monizze.be', $uri['host']);
        $this->assertEquals('/user', $uri['path']);
    }
}
