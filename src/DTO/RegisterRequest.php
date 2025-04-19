<?php

namespace App\DTO;

class RegisterRequest
{
    public function __construct(
        public string $email = '',
        public string $password = '',
    ) {
    }
}
