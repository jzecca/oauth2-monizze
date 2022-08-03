<?php

namespace Jzecca\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class MonizzeUser implements ResourceOwnerInterface
{
    /**
     * @var array
     */
    private $data;

    public function __construct(array $response)
    {
        $this->data = $response['data'];
    }

    public function getId()
    {
        return $this->data['id'];
    }

    public function getFirstName(): string
    {
        return $this->data['firstname'];
    }

    public function getLastName(): string
    {
        return $this->data['lastname'];
    }

    public function getEmail(): string
    {
        return $this->data['email'];
    }

    public function getLanguage(): string
    {
        return $this->data['language'];
    }

    public function getNewsletter(): bool
    {
        return $this->data['newsletter'];
    }

    public function hasActiveCard(): bool
    {
        return $this->data['hasActiveCard'];
    }

    public function toArray(): array
    {
        return $this->data;
    }
}
