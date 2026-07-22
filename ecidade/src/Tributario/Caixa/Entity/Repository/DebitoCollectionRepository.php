<?php

namespace ECidade\Tributario\Caixa\Entity\Repository;

use ECidade\Tributario\Library\DataBase;
use ECidade\Tributario\Library\DataBaseRepository;
use ECidade\Tributario\Caixa\Repository\ArrecadRepository;
use ECidade\Tributario\Caixa\Cast\ArrecadCollectionCast;
use ECidade\Tributario\Juridico\Inicial\Repository\InicialNumpreRepository;

class DebitoCollectionRepository extends DataBaseRepository
{
    protected $arrecadRepository;

    public function __construct(
        DataBase $dataBase,
        ArrecadRepository $arrecadRepository,
        ArrecadCollectionCast $arrecadCollectionCast
    ) {
        parent::__construct($dataBase);

        $this->arrecadRepository = $arrecadRepository;
        $this->arrecadCollectionCast = $arrecadCollectionCast;
    }

    protected function findAll($where)
    {
        $arrecadCollection = $this->arrecadRepository->findAll($where);

        return $this->toDebitoCollection($arrecadCollection);
    }

    public function findByInicial($inicial)
    {
        $numpres = $this->getInicialNumpre($inicial);
        return $this->findAllByNumpres($numpres);
    }

    public function findByIniciais($iniciais)
    {
        $numpres = $this->getInicialNumpre($iniciais);
        return $this->findAllByNumpres($numpres);
    }

    public function findByNumpres($numpres)
    {
        $numpres = $this->getNumpreInicial($numpres);
        return $this->findAllByNumpresNumpar($numpres);
    }

    protected function toDebitoCollection($arrecadCollection)
    {
        return $this->arrecadCollectionCast->toDebitoCollection($arrecadCollection);
    }

    protected function findAllByNumpres($numpres)
    {
        return $this->findAll("k00_numpre in (".implode(',', $numpres).")");
    }

    /**
     * findAllByNumpresNumpar
     *
     * @param mixed $numpres
     * $numpres é um array onde a chave é o valor do numpre e o valor é um array onde os valores sao as parcelas
     *  (numpar)
     * exemplo:
     * 2142323 => array (0=>4,1=>8)
     * @return void
     */
    public function findAllByNumpresNumpar($numpres)
    {
        $where = "";
        foreach ($numpres as $numpre => $numpar) {
            if (!empty($where)) {
                $where .= " or (k00_numpre = {$numpre} and k00_numpar in (" . implode(',', $numpar) . "))";
            } else {
                $where .= "(k00_numpre = {$numpre} and k00_numpar in (" . implode(',', $numpar) . "))";
            }
        }
        return $this->findAll($where);
    }


    protected function getInicialNumpre($iniciais)
    {
        $inicialRepository = new InicialNumpreRepository();
        $where = array();
        if (is_array($iniciais)) {
            $where[] = 'v59_inicial';
            $where[] = ' in ';
            $where[] = '(' . implode(',', $iniciais) . ')';
        } else {
            $where[] = 'v59_inicial';
            $where[] = '=';
            $where[] = $iniciais;
        }
        $inicialRepository->where($where);
        return $this->convertInicialNumpreToNumpre($inicialRepository->get());
    }

    protected function convertInicialNumpreToNumpre($inicialNumpres)
    {
        $numpres = array();
        foreach ($inicialNumpres as $inicialNumpre) {
            $numpres[] = $inicialNumpre->getNumpre();
        }
        return $numpres;
    }
}
