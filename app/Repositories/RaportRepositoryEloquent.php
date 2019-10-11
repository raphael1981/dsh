<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\RaportRepository;
use App\Entities\Raport;
use App\Validators\RaportValidator;

/**
 * Class RaportRepositoryEloquent
 * @package namespace App\Repositories;
 */
class RaportRepositoryEloquent extends BaseRepository implements RaportRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Raport::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
