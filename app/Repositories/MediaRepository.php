<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface MediaRepository
 * @package namespace App\Repositories;
 */
interface MediaRepository extends RepositoryInterface
{

    public function searchByCriteria($data);
}
