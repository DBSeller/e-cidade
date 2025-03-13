<?php

namespace App\Domain\Integracoes\EFDReinf\Repository;

use App\Domain\Core\Base\Repository\BaseRepository;
use App\Domain\Integracoes\EFDReinf\Models\EFDReinfUnidadeResponsavel;
use CgmRepository;
use Exception;

class UnidadeResponsavelRepository extends BaseRepository
{
    protected $modelClass;

    public function __construct()
    {
        $this->modelClass = EFDReinfUnidadeResponsavel::class;
    }

    public function getAll($instit)
    {
        $query = $this->newQuery();
        $query
            ->select('efd08_sequencial as id', 'efd08_cgm as cgm', 'z01_nome as descricao', 'z01_cgccpf as cnpj')
            ->join('cgm', 'efd08_cgm', '=', 'z01_numcgm')
            ->where('efd08_instit', '=', $instit)
            ->orderBy('z01_nome');

        return $query->get()->toArray();
    }

    public function save($data)
    {
        // verificar se possui cgm ou base já possui cadastro
        $numCgm   = $data['efd08_cgm'];
        $cgmRepo  = CgmRepository::getByCodigo($numCgm);
        $cnpjBase = substr($cgmRepo->getCnpj(), 0, 8);

        $query = $this->newQuery();
        $query
            ->select('efd08_sequencial')
            ->join('cgm', 'efd08_cgm', '=', 'z01_numcgm')
            ->where('efd08_cgm', '=', $numCgm)
            ->orWhere('z01_cgccpf', 'ilike', "'$cnpjBase%'");

        $result = $query->first();

        if (!empty($result)) {
            throw new \Exception("Cgm ou base do cnpj já cadastrada.");
            return;
        }

        // salvar dados da unidade
        return call_user_func($this->modelClass . '::create', $data);
    }

    public function delete($id)
    {
        $unidade = $this->find($id);

        if (empty($unidade)) {
            throw new Exception('Unidade não encontrada');
            return;
        }

        $unidade->delete();
    }
}
