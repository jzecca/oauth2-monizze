<?php

namespace Jzecca\OAuth2\Client\Test\Provider;

use Eloquent\Phony\Phpunit\Phony;
use GuzzleHttp\Psr7\Utils;
use Jzecca\OAuth2\Client\Provider\Monizze;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\QueryBuilderTrait;
use PHPUnit\Framework\TestCase;

class MonizzeTest extends TestCase
{
    use QueryBuilderTrait;

    /** @var Monizze */
    protected $provider;

    protected function setUp(): void
    {
        $this->provider = new Monizze([
            'clientId' => 'mock_client_id',
            'clientSecret' => 'mock_secret',
            'redirectUri' => 'none',
        ]);
    }

    public function testAuthorizationUrl(): void
    {
        $url = $this->provider->getAuthorizationUrl();
        $uri = parse_url($url);
        parse_str($uri['query'], $query);

        $this->assertArrayHasKey('client_id', $query);
        $this->assertArrayHasKey('redirect_uri', $query);
        $this->assertArrayHasKey('response_type', $query);
        $this->assertArrayHasKey('state', $query);
        $this->assertArrayNotHasKey('approval_prompt', $query);
        $this->assertArrayNotHasKey('scope', $query);

        $this->assertNotEmpty($this->provider->getState());
    }

    public function testUserData(): void
    {
        $userJson = '{"data":{"id":1068024,"firstname":"David","lastname":"Lumaye","email":"dlu@monizze.be","language":"fr","newsletter":true,"hasActiveCard":true}}';

        $response = Phony::mock('GuzzleHttp\Psr7\Response');
        $response->getHeader->returns(['application/json']);
        $response->getBody->returns(Utils::streamFor($userJson));

        $provider = Phony::partialMock(Monizze::class);
        $provider->getResponse->returns($response);

        $monizze = $provider->get();
        $token = $this->mockAccessToken();

        $user = $monizze->getResourceOwner($token);

        Phony::inOrder(
            $provider->fetchResourceOwnerDetails->called(),
        );

        $this->assertInstanceOf(ResourceOwnerInterface::class, $user);

        $this->assertEquals(1068024, $user->getId());
        $this->assertEquals('dlu@monizze.be', $user->getEmail());

        $user = $user->toArray();

        $this->assertArrayHasKey('id', $user);
        $this->assertArrayHasKey('email', $user);
    }

    public function testUserError(): void
    {
        $errorJson = '{"error": {"code": 400, "message": "I am an error"}}';

        $response = Phony::mock('GuzzleHttp\Psr7\Response');
        $response->getHeader->returns(['application/json']);
        $response->getBody->returns(Utils::streamFor($errorJson));

        $provider = Phony::partialMock(Monizze::class);
        $provider->getResponse->returns($response);

        $monizze = $provider->get();
        $token = $this->mockAccessToken();

        $this->expectException(IdentityProviderException::class);

        $user = $monizze->getResourceOwner($token);

        Phony::inOrder(
            $provider->getResponse->calledWith($this->instanceOf('GuzzleHttp\Psr7\Request')),
            $response->getHeader->called(),
            $response->getBody->called()
        );
    }

    public static function mockAccessToken(): AccessToken
    {
        return new AccessToken([
            'access_token' => 'mock_access_token',
        ]);
    }
}
