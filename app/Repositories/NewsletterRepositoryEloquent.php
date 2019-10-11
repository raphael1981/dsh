<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\NewsletterRepository;
use App\Entities\Newsletter;
use App\Validators\NewsletterValidator;

/**
 * Class NewsletterRepositoryEloquent
 * @package namespace App\Repositories;
 */
class NewsletterRepositoryEloquent extends BaseRepository implements NewsletterRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Newsletter::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
