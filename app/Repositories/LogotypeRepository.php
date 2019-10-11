<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface LogotypeRepository.
 *
 * @package namespace App\Repositories;
 */
interface LogotypeRepository extends RepositoryInterface
{
    public function searchByCriteria($data);
}
