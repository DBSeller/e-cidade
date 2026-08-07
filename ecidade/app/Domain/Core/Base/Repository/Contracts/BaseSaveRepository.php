<?php
namespace App\Domain\Core\Base\Repository\Contracts;

use App\Core\Base\Model\BaseModel;
use Exception;

interface BaseSaveRepository extends BaseRepository
{
    /**
     * @param BaseModel $model
     * @return BaseModel
     * @throws Exception
     */
    public function persist(BaseModel $model);
}
