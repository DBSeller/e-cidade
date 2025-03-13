<?php

namespace ECidade\Tributario\Arrecadacao\Webservice;

use DBDate;
use ECidade\Tributario\Caixa\Model\Arrenumcgm;
use ECidade\Tributario\Issqn\Model\Issvar;
use ECidade\Tributario\Issqn\Repository\ConfVencIssqnAvulsoRepository;
use ECidade\V3\Extension\Registry;
use ECidade\Tributario\Arrecadacao\Model\Arrecad;

// include(modification("classes/db_issvar_classe.php"));

class ReciboNotaAvulsa extends \EmissaoBoleto
{
    /**
     * @var integer
     */
    private $cgm;

    /**
     * @var float
     */
    private $valor;

    /**
     * @var Registry
     */
    private $oContainer;

    /**
     * @var boolean
     */
    private $retornaBase64 = true;

    /***
     * @var integer
     */
    private $numeroNota;

    /**
     * @param int $cgm
     * @return ReciboNotaAvulsa
     */
    public function setCgm($cgm)
    {
        $this->cgm = $cgm;
        return $this;
    }

    /**
     * @param float $valor
     * @return ReciboNotaAvulsa
     */
    public function setValor($valor)
    {
        $this->valor = $valor;
        return $this;
    }

    /**
     * Método que carrega o container em um variável do escopo
     * @param void
     * @return ReciboNotaAvulsa
     */
    public function carregaContainer()
    {
        $this->oContainer = Registry::get('app.container')->get('tributario.container');
        return $this;
    }

    /**
     * Define se retorna um base64 ou o nome do arquivo
     * @param bool $retornaBase64
     */
    public function setRetornaBase64($retornaBase64 = true)
    {
        $this->retornaBase64 = $retornaBase64;

        return $this;
    }

    /**
     * @param int $numeroNota
     * @return ReciboNotaAvulsa
     */
    public function setNumeroNota($numeroNota)
    {
        $this->numeroNota = $numeroNota;
        return $this;
    }

    /**
     * @throws \Exception
     */
    public function gerar()
    {
        try {
            if (!\db_utils::inTransaction()) {
                throw new \Exception("Sem transação ativa");
            }

            $iNumpre = $this->oContainer->get("NumpreSequenceRepository")->next();

            $confVencIssqnAvulsoRepository = new ConfVencIssqnAvulsoRepository();
            $oConfVencIssqnAvulso = $confVencIssqnAvulsoRepository->getByAnousu(db_getsession("DB_anousu"));

            if (!empty($oConfVencIssqnAvulso->j178_diavenc)) {
                $sDataRecibo = date("Y-m-d", strtotime("+{$oConfVencIssqnAvulso->j178_diavenc} days"));
            } else {
                $iUltimoDiaMes = cal_days_in_month(CAL_GREGORIAN, date("m"), date("Y"));
                $sDataRecibo = date("Y-m")."-{$iUltimoDiaMes}";
            }

            $sSqlProximoDiaUtil = "SELECT fc_proximo_dia_util('{$sDataRecibo}') AS proximo_dia_util;";
            $rProximoDiaUtil = db_query($sSqlProximoDiaUtil);

            if (!$rProximoDiaUtil) {
                throw new \Exception("Erro ao buscar o próximo dia útil.");
            }

            $oProximoDiaUtil = \db_utils::fieldsMemory($rProximoDiaUtil, 0);

            $sDataRecibo = $oProximoDiaUtil->proximo_dia_util;

            /**
             * Gera arrecad
             */
            $arrecadModel = new Arrecad();

            $arrecadModel->setNumpre($iNumpre);
            $arrecadModel->setNumpar(1);
            $arrecadModel->setNumCgm($this->cgm);
            $arrecadModel->setDataOperacao(date("Y-m-d"));
            $arrecadModel->setReceita($oConfVencIssqnAvulso->j178_receita);
            $arrecadModel->setHistorico($oConfVencIssqnAvulso->j178_histdebito);
            $arrecadModel->setValor($this->valor);
            $arrecadModel->setDataVencimento($sDataRecibo);
            $arrecadModel->setNumTot(1);
            $arrecadModel->setNumDig(1);
            $arrecadModel->setTipo($oConfVencIssqnAvulso->j178_tipodebito);
            $arrecadModel->setTipoJM(1);

            $this->oContainer->get("ArrecadRepository")->persist($arrecadModel);

            $arrenumcgmModel = new Arrenumcgm();

            $arrenumcgmModel->setNumcgm($this->cgm);
            $arrenumcgmModel->setNumpre($iNumpre);

            $this->oContainer->get("ArrenumcgmRepository")->insert($arrenumcgmModel);

            $issvarModel = new Issvar();

            $issvarModel->setNumpre($iNumpre);
            $issvarModel->setNumpar(1);
            $issvarModel->setValor($this->valor);
            $issvarModel->setAno(date("Y", strtotime($sDataRecibo)));
            $issvarModel->setMes(date("m", strtotime($sDataRecibo)));
            $issvarModel->setHistor("Recibo referente a nota com código de Verificação: {$this->numeroNota}");
            $issvarModel->setAliq("0");
            $issvarModel->setBruto("0");
            $issvarModel->setVlrinf($this->valor);

            $this->oContainer->get("IssvarRepository")->persist($issvarModel);

            /**
             * Gera recibo
             */
            $this->adicionarDebito($iNumpre, 1);
            $this->setCodigoCgm($this->cgm);
            $this->setDataVencimento(new DBDate($sDataRecibo));
            $this->setForcaVencimento(true);
            $this->setModeloImpressao(30);
            $this->setHistorico("Recibo referente a nota com código de Verificação: {$this->numeroNota}");

            $oConvenio = $this->gerarRecibo();

            $this->imprimir($oConvenio);

            $sNomeArquivo = $this->getCaminhoPDF();

            if ($this->retornaBase64) {
                $sArquivoPDF = file_get_contents($sNomeArquivo);
                $sArquivoPDF = base64_encode($sArquivoPDF);

                unlink($sNomeArquivo);
            } else {
                $sArquivoPDF = $sNomeArquivo;
            }

            return utf8_encode_all([
                "sArquivo" => $sArquivoPDF,
                'aDebitos' => $this->getDebitos(),
                'sDataVencimento' => $this->oDataVencimento->getDate(DBDate::DATA_EN)
            ]);
        } catch (\Exception $oErro) {
            throw new \Exception($oErro->getMessage());
        }
    }
}
