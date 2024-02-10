<?php

namespace Tests\Unit\Repository\Dto;

use App\Repositories\Dto\AccountDto;
use Tests\TestCase;

class AccountDtoTest extends TestCase
{
    public function dataProvider(): array
    {
        return [
            [
                [
                    'name' => 'test name',
                    'balance' => 100,
                    'id' => 1,
                ],
                new AccountDto('test name', 100, 1)
            ]
        ];
    }

    /**
     * @dataProvider dataProvider
     * @param array $expected
     * @param AccountDto $dto
     */
    public function testConstruct(array $expected, AccountDto $dto): void
    {
        self::assertEquals($expected, $dto->toArray());
    }
}
