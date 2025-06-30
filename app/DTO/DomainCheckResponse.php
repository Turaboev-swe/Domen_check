<?php

namespace App\DTO;

class DomainCheckResponse
{
    public function __construct(
        public string $domain,
        public bool $isAvailable,
        public ?string $expiryDate = null,
        public ?string $error = null,
    ) {}
}
