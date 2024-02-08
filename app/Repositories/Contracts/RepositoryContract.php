<?php

namespace App\Repositories\Contracts;

use App\Repositories\Dto\AbstractDto;
use Illuminate\Database\Eloquent\Model;

interface RepositoryContract
{
    public function create(AbstractDto $dto) : Model;
}
