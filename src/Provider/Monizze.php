<?php

namespace Jzecca\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;

class Monizze extends AbstractProvider
{
    use BearerAuthorizationTrait;

    private const SUPPORTED_LOCALES = ['en', 'fr', 'nl'];
    private const FALLBACK_LOCALE = 'en';

    protected $locale = self::FALLBACK_LOCALE;

    public function getBaseAuthorizationUrl(): string
    {
        return sprintf('https://auth.monizze.be/%s/oauth/authorize', $this->getLocale());
    }

    public function getBaseAccessTokenUrl(array $params): string
    {
        return 'https://api.monizze.be/oauth/token';
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token): string
    {
        return 'https://api.monizze.be/user';
    }

    protected function getDefaultHeaders(): array
    {
        return [
            'Accept' => 'application/json',
        ];
    }

    protected function getDefaultScopes(): array
    {
        return [];
    }

    protected function getAuthorizationParameters(array $options): array
    {
        $options = parent::getAuthorizationParameters($options);

        unset(
            $options['approval_prompt'],
            $options['scope'],
        );

        return $options;
    }

    protected function checkResponse(ResponseInterface $response, $data): void
    {
        if (empty($data['error'])) {
            return;
        }

        $code = 0;
        $error = $data['error'];

        if (is_array($error)) {
            $code = $error['code'];
            $error = $error['message'];
        }

        throw new IdentityProviderException($error, $code, $data);
    }

    protected function createResourceOwner(array $response, AccessToken $token): MonizzeUser
    {
        return new MonizzeUser($response);
    }

    private function getLocale(): string
    {
        $locale = strtolower($this->locale);

        if (!in_array($locale, self::SUPPORTED_LOCALES)) {
            $locale = self::FALLBACK_LOCALE;
        }

        return $locale;
    }
}
