<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\TemplateRepository;
use App\Entities\Template;
use App\Validators\TemplateValidator;

/**
 * Class TemplateRepositoryEloquent
 * @package namespace App\Repositories;
 */
class TemplateRepositoryEloquent extends BaseRepository implements TemplateRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Template::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
