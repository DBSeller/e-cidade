<?php
namespace ECidade\Tributario\Issqn\Repository;

use ECidade\Tributario\Arrecadacao\CadTipo;
use ECidade\Tributario\Caixa\Cast\ArrecadCollectionCast;
use ECidade\Tributario\Caixa\Entity\Repository\DebitoRepository;
use ECidade\Tributario\Caixa\Repository\ArrecadRepository;
use ECidade\Tributario\Caixa\Repository\ArreinscrRepository;
use ECidade\Tributario\Caixa\Repository\ArretipoRepository;
use ECidade\Tributario\Issqn\Model\Issbase;
use ECidade\Tributario\Library\DataBase;

class InscricaoDebitoRepository extends DebitoRepository
{
    protected $arreinscrRepository;
    protected $arretipoRepository;

    public function __construct(
        DataBase $dataBase,
        ArrecadRepository $arrecadRepository,
        ArrecadCollectionCast $arrecadCollectionCast,
        ArreinscrRepository $arreinscrRepository,
        ArretipoRepository $arretipoRepository
    ) {
        parent::__construct($dataBase, $arrecadRepository, $arrecadCollectionCast);
        $this->arreinscrRepository = $arreinscrRepository;
        $this->arretipoRepository = $arretipoRepository;
    }

    /**
     * @param Issbase $issbase
     * @param $cadtipo
     * @return \ECidade\Tributario\Caixa\Entity\Collection\DebitoCollection
     * @throws \ParameterException
     */
    public function findByIssbaseAndCadtipo(Issbase $issbase, $cadtipo)
    {
        if (!CadTipo::check($cadtipo)) {
            throw new \ParameterException("Tipo de débito inválido.");
        }

        $arreinscrList = $this->arreinscrRepository->findByIdentificador($issbase->getInscr());
        $numpres = array();

        foreach ($arreinscrList as $arreinscr) {
            $numpres[] = $arreinscr->getNumpre();
        }

        $numpres = implode(',', $numpres);

        $arretipoList = $this->arretipoRepository->findByCadtipo($cadtipo);
        $tipos = array();

        foreach ($arretipoList as $arretipo) {
            $tipos[] = $arretipo->getTipo();
        }

        $tipos = implode(',', $tipos);

        return $this->findAll("k00_numpre in ($numpres) and k00_tipo in ($tipos)");
    }
}
