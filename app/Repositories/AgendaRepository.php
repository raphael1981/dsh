<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface AgendaRepository
 * @package namespace App\Repositories;
 */
interface AgendaRepository extends RepositoryInterface
{
    public function searchByCriteria($data);
    public function getFullAgendaData($id);
    public function fastSearchByTitle($phrase);
    public function getFilterResultsData($filters, $year, $month, $is_all);
    public function getFilterResultsDataYearAndCategories($filters, $current_month, $year, $current_day, $is_current_year, $is_all, $all_filters, $order);
    public function getFilterAdvancedResult($filters, $current_month, $current_year, $is_all, $all_filters);
}
