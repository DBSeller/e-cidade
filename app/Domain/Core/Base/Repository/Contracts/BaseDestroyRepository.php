<?php
namespace App\Domain\Core\Base\Repository\Contracts;

use App\Core\Base\Model\BaseModel;
use Exception;

interface BaseDestroyRepository
{
    /**
     * @param BaseModel $model
     * @throws Exception
     */
    public function destroy(BaseModel $model);
}
