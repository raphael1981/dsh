<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface LeadsceneRepository
 * @package namespace App\Repositories;
 */
interface LeadsceneRepository extends RepositoryInterface
{
    public function prepareFastStructure($structure, $lang_tag);
}
