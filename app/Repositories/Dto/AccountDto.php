<?php

namespace App\Repositories\Dto;

class AccountDto extends AbstractDto
{
    public function __construct(
        public readonly ?string $name,
        public readonly ?int $balance,
        public readonly ?int $id = null,
    ) {
    }


    public static function createFromRequest(array $array) : self
    {
        $dto = new self();
        $dto->name = $array['name']?? null;
        $dto->balance = self::formatCurrencyToDB($array['balance'] ?? 0);
        $dto->id = $array['id']?? null;
        return $dto;
    }
}
