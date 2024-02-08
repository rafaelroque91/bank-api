<?php

namespace App\Repositories\Dto;

class ExternalAuthResponseDto
{
    private bool $success;
    private bool $authorized;

    public static function createFromResponse(array $array): self
    {
        $dto = new self();
        $dto->success = ($array['success'] ?? false);
        $dto->authorized = ($array['authorized'] ?? false);

        return $dto;
    }

    public function getSuccess() : bool
    {
        return $this->success;
    }

    public function getAuthorized() : bool
    {
        return $this->authorized;
    }
}
