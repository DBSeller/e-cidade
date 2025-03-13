<?php
namespace App\Core\Base\Service;

use App\Domain\Core\Base\Repository\BaseRepository;
use Exception;

abstract class BaseService
{
    /**
     * @var BaseRepository
     */
    private $repository;

    /**
     * BaseService constructor.
     * @param BaseRepository $repository
     * @throws Exception
     */
    public function __construct(BaseRepository $repository)
    {
        $this->repository = $repository;
    }
}
