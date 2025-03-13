<?php

namespace ECidade\Tributario\Juridico\ProcessoEletronico\Service;

use ECidade\Tributario\Juridico\ProcessoEletronico\Repository\CertidaoRepository;
use ECidade\Tributario\Juridico\ProcessoEletronico\Repository\AdvogadoRepository;
use ECidade\Tributario\Juridico\ProcessoEletronico\Repository\ProcessoEletronico as ProcessoEletronicoRepository;
use ECidade\Tributario\Juridico\ProcessoEletronico\Builder\EnvioRemessaBuilder;
use ECidade\Tributario\Juridico\ProcessoEletronico\Factory\DevedorFactory;
use ECidade\Tributario\Juridico\ProcessoEletronico\Enum\TipoListaEnum;
use ECidade\Tributario\Juridico\ProcessoEletronico\Integracao;
use \ECidade\Tributario\Juridico\ProcessoEletronico\Configuracao;
use ECidade\Tributario\Juridico\ProcessoEletronico\Repository\AutoInfracaoRepository;

/**
 * Class EnvioRemessaService
 * @package ECidade\Tributario\Juridico\ProcessoEletronico\Service
 */
class EnvioRemessaService
{
    /**
     * @var CertidaoRepository
     */
    private $oCertidaoRepository;

    /**
     * @var AdvogadoRepository
     */
    private $oAdvogadoRepository;

    /**
     * @var $oAutoInfracaoRepository
     */
    private $oAutoInfracaoRepository;

    /**
     * @var Configuracao
     */
    private $oCofiguracao;

    /**
     * @var \Instituicao
     */
    private $oInstituicao;

    /**
     * @var false|string
     */
    private $sDataGeracao;

    /**
     * @var false|string
     */
    private $sDataGeracaoEnvio;

    /**
     * @var Integracao
     */
    private $oIntegracao;

    /**
     * @var $oProcessoEletronico
     */
    private $oProcessoEletronico;

    /**
     * EnvioRemessaService constructor.
     * @param Configuracao $oConfiguracao
     * @param \Instituicao $oInstituicao
     * @param Integracao $oIntegracao
     */
    public function __construct(Configuracao $oConfiguracao, \Instituicao $oInstituicao,
                                Integracao $oIntegracao, \ECidade\Tributario\Juridico\ProcessoEletronico\ProcessoEletronico $oProcessoEletronico)
    {
        $this->oCofiguracao = $oConfiguracao;
        $this->oInstituicao = $oInstituicao;
        $this->oIntegracao = $oIntegracao;
        $this->oProcessoEletronico = $oProcessoEletronico;

        $this->oCertidaoRepository = new CertidaoRepository();
        $this->oAdvogadoRepository = new AdvogadoRepository();
        $this->oAutoInfracaoRepository = new AutoInfracaoRepository();


        $this->sDataGeracao = date('Y-m-d', db_getsession('DB_datausu'));
        $this->sDataGeracaoEnvio = date('YmdHis', db_getsession('DB_datausu'));

    }

    /**
     * Metodo buscar dados de envio
     *
     * @param $oDados
     * @return \stdClass
     * @throws \BusinessException
     * @throws \DBException
     */
    public function getObjectToSend($oDados)
    {
        return $this->makeObjectToSend($oDados);
    }

    private function getUfir($oDados)
    {
        /**
         * Verificamos o valor do indice UFIR
         */
        $sSqlVlrInfla = "select fc_vlinf from fc_vlinf('UFIR','{$oDados->dtufir}');";
        $rsVlrInfla = db_query($sSqlVlrInfla);
        $nVlrInfla = \db_utils::fieldsMemory($rsVlrInfla, 0)->fc_vlinf;

        return $nVlrInfla;
    }

    /**
     *  Metodo responsavel por construir o objeto de envio para tj
     *
     * @param $oDados
     * @return \stdClass
     * @throws \BusinessException
     * @throws \DBException
     */
    private function makeObjectToSend($oDados)
    {

        if (empty($this->oProcessoEletronico)) {
            throw new \BusinessException('Não existe processo eletrônico para a Inicial ' . $oDados->inicial);
        }

        $oDevedor = DevedorFactory::create(TipoListaEnum::CGM, $oDados->parte);
        $dataCal = $this->oProcessoEletronico->getDataCalculo()->format('Y-m-d');
        $anoCal = $this->oProcessoEletronico->getDataCalculo()->format('Y');
        $aCertidoes = $this->oCertidaoRepository->getCertidoes($dataCal, $anoCal, $oDados->certidoes);
        $oAdvog = $this->oAdvogadoRepository->getAdvogado($oDados->inicial);
        $oInicial = $this->oProcessoEletronico->getInicial();

        $nValorUFIR = $this->getUfir($oDados);

        $oDadosEnvioRemessa = new \stdClass();

        EnvioRemessaBuilder::createDadosBasicos($oDadosEnvioRemessa, array(
            'sDataGeracaoEnvio' => $this->sDataGeracaoEnvio,
            'codigo_processo_eletronico' => $oDados->codigo_processo_eletronico,
            'localidade' => $this->oCofiguracao->getLocalidade(),
            'valortotalinicial' => $oInicial->getValorAtualizadoAte(new \DateTime())
        ));

        EnvioRemessaBuilder::createPoloAtivo($oDadosEnvioRemessa, $this->oInstituicao);

        EnvioRemessaBuilder::createPoloPassivo($oDadosEnvioRemessa, $oDevedor);

        EnvioRemessaBuilder::createAdvogado($oDadosEnvioRemessa, $oAdvog);

        EnvioRemessaBuilder::createCertidoes($oDadosEnvioRemessa, $aCertidoes, $nValorUFIR);

        $oEnderecoDevedor = DevedorFactory::create($this->oIntegracao->getTipoLista(), $oDados->origem);

        EnvioRemessaBuilder::createDadosImovelInscricao($oDadosEnvioRemessa, $oDados, $oEnderecoDevedor);

        EnvioRemessaBuilder::createOutrosDados($oDadosEnvioRemessa, $oDados);

        if ($this->oIntegracao->getTipoLista() == "A") {
            $oAuto = $this->oAutoInfracaoRepository->getAuto($oDados->origem);
            EnvioRemessaBuilder::createAutoInfracao($oDadosEnvioRemessa, $oAuto);
        }

        return $oDadosEnvioRemessa;
    }

}
