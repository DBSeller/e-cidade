<?php

namespace ECidade\Tributario\Issqn\Acao\Transicao\Entity;

use ECidade\Configuracao\Workflow\Interfaces\Acao as AcaoInterface;
use ECidade\Tributario\Issqn\Repository\IssbaseRepository;

final class GerarAlvara extends AcaoBase implements AcaoInterface
{
    const TIPO_ALVARA_PROVISORIO = 2;

    protected $alvara;

    public function __construct(
        $processo,
        IssbaseRepository $issbaseRepository,
        ParametrosProcessoEletronicoBag $parameterBag
    ) {
        parent::__construct($processo, $issbaseRepository);

        $this->parameterBag = $parameterBag;
    }

    /**
     * @throws \BusinessException
     * @throws \ParameterException
     */
    public function validate()
    {
        $issbase = $this->getIssbase();
        $clissalvara = new \cl_issalvara;

        $sCampo   = " q123_sequencial, z01_nome,q123_inscr";
        $sWhere   = " q123_inscr = {$issbase->getInscr()}";
        $sSql     = $clissalvara->sql_queryAlvara("", $sCampo, "", $sWhere, null);
        $rsAlvara = $clissalvara->sql_record($sSql);

        if ($clissalvara->numrows == 0) {
            throw new \BusinessException("Não existe Alvará para inscrição vinculada a processo!");
        }

        $oAlvara  = \db_utils::fieldsMemory($rsAlvara, 0);
        $this->alvara = new \Alvara($oAlvara->q123_sequencial);
    }

    public function run()
    {

        $oLiberarAlvara  = $this->alvara->incluirMovimentacao(\MovimentacaoAlvara::TIPO_LIBERACAO);

        $oLiberarAlvara->setDataMovimentacao(date("Y-m-d", db_getsession("DB_datausu")));
        $oLiberarAlvara->setCodigoProcesso($this->processo);
        $oLiberarAlvara->setObservacao('Geração Alvará Online');
        $oLiberarAlvara->setUsuario(new \UsuarioSistema(db_getsession('DB_id_usuario')));
        $this->alvara->setTipoAlvara($this->parameterBag->getTipoAlvaraProvisorio());
        $oLiberarAlvara->processar();
    }
}
