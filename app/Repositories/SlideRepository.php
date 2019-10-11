<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface SlideRepository
 * @package namespace App\Repositories;
 */
interface SlideRepository extends RepositoryInterface
{
    public function setNewOrder($data);
    public function setActive($data);
    public function remove($data);
    public function getById($id);
    public function updateImage($id,$imageName);
    public function setColor($id,$color);
    public function changeText($id,$field,$data);
}
