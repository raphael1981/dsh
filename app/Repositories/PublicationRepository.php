<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface PublicationRepository
 * @package namespace App\Repositories;
 */
interface PublicationRepository extends RepositoryInterface
{
    public function searchByCriteria($data);
    public function getFullPublicationData($id);
}
