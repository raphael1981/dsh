<?php

namespace App\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Interface LinkRepository
 * @package namespace App\Repositories;
 */
interface LinkRepository extends RepositoryInterface
{
    public function createTreeFromArray($array, $lang_id);
    public function getTreeArray($lang_id);
    public function getTreeArrayTreeUi($lang_id);
    public function updateTree($tree, $lang_id);
    public function getTreeArrayContentLinks($langid, $conid);
    public function getTreeArrayContentLinksEmpty($langid);
    public function updateTreeArrayContentLinks($langid, $conid, $tree);
    public function getTreeArrayAgendaLinksEmpty($langid);
    public function getTreeArrayAgendaLinks($langid, $aid);
    public function updateTreeArrayAgendaLinks($langid, $aid, $tree);
    public function getItems($id);
    public function getAllItems($id);
    public function setNewOrder($data, $link_id);
    public function setNewStatus($idlink, $idcnt, $type, $status);
    public function changeActive($idLink,$status);
    public function removeFromLink($linkId, $itemId,$type);
    public function templateUpdateLinkData($ttype, $tid, $lid, $params, $desc);
    public function getLinkLeafs($id);
    public function getAllItemsArrayLinks($array, $year);
}

