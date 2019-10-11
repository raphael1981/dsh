<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\ViewProfileRepository;
use App\Entities\ViewProfile;
use App\Validators\ViewProfileValidator;

/**
 * Class ViewProfileRepositoryEloquent
 * @package namespace App\Repositories;
 */
class ViewProfileRepositoryEloquent extends BaseRepository implements ViewProfileRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ViewProfile::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
