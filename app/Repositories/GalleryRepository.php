<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface GalleryRepository
 * @package namespace App\Repositories;
 */
interface GalleryRepository extends RepositoryInterface
{
    public function searchByCriteria($data);
    public function getGalleryWithPicturesByIdFastView($id);
    public function getGalleryFullData($id);
}
