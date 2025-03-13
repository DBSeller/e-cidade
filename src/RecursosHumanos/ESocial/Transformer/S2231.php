<?php

namespace ECidade\RecursosHumanos\ESocial\Transformer;

use ECidade\RecursosHumanos\ESocial\Model\Configuracao;
use ECidade\RecursosHumanos\ESocial\Model\Formulario\Tipo;
use ECidade\RecursosHumanos\ESocial\Integracao\ESocial;
use ECidade\RecursosHumanos\ESocial\Integracao\ESocialEnvio;
use ECidade\RecursosHumanos\ESocial\Integracao\ESocialEnvioStatus;
use ECidade\RecursosHumanos\ESocial\Integracao\Recurso;
use ECidade\V3\Extension\Registry;
use ECidade\RecursosHumanos\ESocial\Mapeadores\Tabelas\CategoriaCNH;

/**
 * Class S2231
 * @package ECidade\RecursosHumanos\ESocial\Transformer
 */
class S2231
{
    /**
     * @var string $matricula
     */
    private $matricula;

    /**
     * @var mixed $dados
     */
    private $dados;

    /**
     * @var integer $idEvento
     */
    private $idEvento;

    /**
     * S2231 constructor.
     * @param $matricula
     */
    public function __construct($matricula = null)
    {
        if (!empty($matricula)) {
            $this->matricula = $matricula;
        }
    }

    /**
     * seta idEvento
     *
     * @param $idEvento
     */
    public function setIdEvento($idEvento)
    {
        $this->idEvento = $idEvento;
    }

    /**
     * Retorna idEvento
     *
     * @return int
     */
    public function getIdEvento()
    {
        return $this->idEvento;
    }

    /**
     * @throws \ECidade\RecursosHumanos\ESocial\Integracao\ESocialContextExceptionException
     */
    public function buscarDados($idRef = null)
    {

        $oESocial = new ESocial(Registry::get('app.config'), Recurso::CONSULTA_RECIBO);
        $oEmpregador = $this->getEmpregador();

        $params = new \stdClass();

        $params->idEvento = Tipo::S2231;
        $params->idReferencia = $this->matricula;
        
        if ($idRef) {
            $params->idReferencia = $idRef;
        }
        
        $params->inscricaoEmpregador = $oEmpregador->cnpj;
        
        $oESocial->setDados($params);
        $dadosRequest = $oESocial->request("GET");
        
        if (!empty($dadosRequest[0])) {
            $this->dados = $dadosRequest[0];
        }
        
        /**
         * Se não existir recibo para esse idReferencia,
         * o retorno será false.
         * Caso contrário, retornará true.
         */
        if (empty($this->dados->recibo)) {
            return false;
        }

        return true;
    }

    /**
     * Busca o empregador
     *
     * @return string
     */
    private function getEmpregador()
    {

        $codigoInstituicao = \InstituicaoRepository::getInstituicaoSessao()->getCodigo();

        $anoFolha  = \DBPessoal::getAnoFolha();
        $mesFolha  = \DBPessoal::getMesFolha();

        $sqlCgm = "
            SELECT DISTINCT
              z01_numcgm                      AS cgm,
              z01_cgccpf                      AS cnpj
            FROM rhlota
              INNER JOIN cgm ON rhlota.r70_numcgm = cgm.z01_numcgm
              inner join rhpessoalmov on  rh02_lota = r70_codigo
            WHERE r70_instit = {$codigoInstituicao}  and  rh02_regist = {$this->matricula}
            and rh02_anousu = {$anoFolha} and rh02_mesusu = {$mesFolha}
            ORDER BY z01_numcgm
        ";

        $resultadoSqlCgm = db_query($sqlCgm);

        if (!$resultadoSqlCgm) {
            $msg = "Ocorreu um erro ao consultar os CGM vinculado a lotação da matrícula {$this->matricula}.";
            throw new DBException($msg);
        }

        if (pg_num_rows($resultadoSqlCgm) == 0) {
            throw new DBException("Não há empregadores cadastrados para essa matrícula {$this->matricula}.");
        }

        $aEmpregador = \db_utils::getCollectionByRecord($resultadoSqlCgm);

        return $aEmpregador[0];
    }
}
