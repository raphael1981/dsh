<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface ContentRepository
 * @package namespace App\Repositories;
 */
interface ContentRepository extends RepositoryInterface
{
    public function searchByCriteria($data);
    public function getFullContentData($id);
    public function fastSearchByTitle($phrase);
    public function getFilterResultsDataYearAndCategories($filters, $current_month, $year, $current_day, $is_current_year, $is_all, $all_filters, $order);
    public function getFilterResultsDataNoYearAndCategories($filters, $current_month, $year, $current_day, $is_current_year, $is_all, $all_filters, $order);
    public function getFilterResultsDataStaticAndCategories($all_filters, $order);
    public function getFilterResultsDataYearAndCategoriesArchive($filters, $current_month, $year, $is_all, $all_filters);
}
