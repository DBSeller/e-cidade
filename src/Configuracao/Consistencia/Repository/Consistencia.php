<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

namespace ECidade\Configuracao\Consistencia\Repository;

use ECidade\Configuracao\Consistencia\ConsistenciaSistema;
use \cl_consistenciasistema;

/**
 * Class Consistencia
 * @package ECidade\Configuracao\Consistencia\Repository
 */
class Consistencia extends \BaseClassRepository
{
    /**
     * Consistencias do tipo encerramento
     * @var integer
     */
    const TIPO_ENCERRAMENTO = 100;

    /**
     * Representa a instancia da classe,
     *
     * @var Consistencia
     * @access protected
     */
    protected static $oInstance;

    /**
     * Retorna os JSONs das consistencias na estrutura consistenciasistema.
     *
     * @param integer $tipo
     * @return \stdClass[]
     * @throws \BusinessException
     * @throws \DBException
     * @throws \ParameterException
     */
    public function getArquivosConsistenciaPorTipo($tipo)
    {
        if (empty($tipo)) {
            $msg = "Não foi informado o sistema ao qual deseja buscar as configurações de inconsistência.";
            throw new \ParameterException($msg);
        }

        $rs = $this->getRecordConsistenciaSistema(" db160_sequencial, db160_json ", " db160_sistema = {$tipo} ");
        $consistencias = \db_utils::makeCollectionFromRecord($rs, function ($retorno) {
            $consistencia = new \stdClass();
            $consistencia->id = $retorno->db160_sequencial;
            $consistencia->jsonConsistencia = \JSON::create()->parse(preg_replace('/\n/', ' ', $retorno->db160_json));

            return $consistencia;
        });

        return $consistencias;
    }

    /**
     * Executa o SQL da consistência cadastrada no JSON
     *
     * @param  integer $idConsistencia
     * @return array|false
     * @throws \BusinessException
     * @throws \DBException
     * @throws \ParameterException
     */
    public function executarConsistencia($idConsistencia, array $filtros = null)
    {
        if (empty($idConsistencia)) {
            throw new \ParameterException("Código da consistência não informado.");
        }

        $rs = $this->getRecordConsistenciaSistema(" db160_json ", " db160_sequencial = {$idConsistencia} ");

        $jsonConsistencia = preg_replace('/\n/', ' ', \db_utils::fieldsMemory($rs, 0)->db160_json);

        $json = \json::create()->parse($jsonConsistencia, \JSON::UTF8_DECODE, false);
        $sqlCorrecao = $json->sql->consistencia;

        if (is_array($filtros)) {
            foreach ($filtros as $filtro) {
                $sqlCorrecao =   str_replace("#$filtro->nome#", urldecode($filtro->valor), $sqlCorrecao);
            }
        }
        $rsConsistencia = \db_query($sqlCorrecao);

        if (!$rsConsistencia) {
            throw new \DBException("Erro ao buscar os registros para correção.");
        }

        if (pg_num_rows($rsConsistencia) === 0) {
            return false;
        }

        $registrosConsistencia = \db_utils::makeCollectionFromRecord($rsConsistencia, function ($retorno) {
            return $retorno;
        });

        return $registrosConsistencia;
    }

    /**
     * Executa o update de correção informado no JSON.
     *
     * @param integer $idConsistencia
     * @param array $registrosCorrecao
     * @return bool
     * @throws \BusinessException
     * @throws \DBException
     * @throws \ParameterException
     */
    public function executarCorrecao($idConsistencia, $registrosCorrecao = array())
    {
        if (empty($idConsistencia)) {
            throw new \ParameterException("Código da consistência não informado.");
        }

        if (empty($registrosCorrecao)) {
            $this->executarCorrecaoGeral($idConsistencia);
            return true;
        }

        $rs = $this->getRecordConsistenciaSistema(" db160_json ", " db160_sequencial = {$idConsistencia} ");

        $jsonConsistencias =  preg_replace('/\n/', ' ', \db_utils::fieldsMemory($rs, 0)->db160_json);
        $consistenciaSistema = new ConsistenciaSistema();
        $consistenciaSistema->setConfiguracao(\JSON::create()->parse($jsonConsistencias));

        return $consistenciaSistema->processar($registrosCorrecao);
    }

    /**
     * Executa o sql de correção geral, aplicando determinada regra para todos os registros encontrados
     *
     * @param $idConsistencia
     * @return bool
     * @throws \BusinessException
     * @throws \DBException
     */
    private function executarCorrecaoGeral($idConsistencia)
    {

        $rs = $this->getRecordConsistenciaSistema(" db160_json ", " db160_sequencial = {$idConsistencia} ");
        $jsonConsistencias =  preg_replace('/\n/', ' ', \db_utils::fieldsMemory($rs, 0)->db160_json);
        $jsonConsistencias = \JSON::create()->parse($jsonConsistencias);

        if (empty($jsonConsistencias->sql) || empty($jsonConsistencias->sql->correcao)) {
            throw new \BusinessException("Não foi encontrado SQL para correção.");
        }

        $executaCorrecao = db_query($jsonConsistencias->sql->correcao);
        if (!$executaCorrecao) {
            $msg  = "O SQL configurado para correção está com sintaxe errada.";
            $msg .= " Contate o suporte para que seja verificado";
            throw new \DBException($msg);
        }

        return true;
    }

    /**
     * Retorna as opcoes de um select quando este for uma consulta SQL no json.
     *
     * @param string $idConsistencia
     * @param string $propriedadeCampoSelect
     * @return \stdClass[]
     * @throws \BusinessException
     * @throws \DBException
     * @throws \ParameterException
     */
    public function getOpcoesSelectPorConsultaSql($idConsistencia, $propriedadeCampoSelect)
    {
        if (empty($idConsistencia)) {
            throw new \ParameterException("Código da consistência não informado.");
        }

        if (empty($propriedadeCampoSelect)) {
            throw new \ParameterException("Propriedade do campo de seleção não informada.");
        }

        $rs = $this->getRecordConsistenciaSistema(" db160_json ", " db160_sequencial = {$idConsistencia} ");

        $jsonConsistencia =  preg_replace('/\n/', ' ', \db_utils::fieldsMemory($rs, 0)->db160_json);
        $campos =  \JSON::create()
            ->parse($jsonConsistencia)
            ->formulario
            ->campos;

        $sqlOpcao = "";
        foreach ($campos as $campo) {
            if (isset($campo->tipo) && $campo->tipo == 'select' && is_string($campo->opcoes)) {
                if ($campo->propriedade === $propriedadeCampoSelect) {
                    $sqlOpcao = $campo->opcoes;
                    break;
                }
            }
        }

        if (empty($sqlOpcao)) {
            throw new \Exception("Não foi encontrado uma consulta para as opções do combobox.");
        }

        $rsOpcoes = \db_query($sqlOpcao);
        $opcoes = \db_utils::makeCollectionFromRecord($rsOpcoes, function ($retorno) {
            return $retorno;
        });

        return $opcoes;
    }

    /**
     * Retorna o Record de uma consulta na estrutura consistenciasistema.
     *
     * @param string $campos
     * @param string $where
     * @return bool|resource
     * @throws \BusinessException
     * @throws \DBException
     */
    private function getRecordConsistenciaSistema($campos = "*", $where = "")
    {

        $daoConsistenciaSistema = new cl_consistenciasistema();
        $sql = $daoConsistenciaSistema->sql_query(null, $campos, null, $where);
        $rs = \db_query($sql);

        if (!$rs) {
            throw new \DBException("Erro ao buscar consistências.");
        }

        if (pg_num_rows($rs) == 0) {
            throw new \BusinessException("Nenhuma consistência cadastrada para o critério informada.");
        }

        return $rs;
    }

    /**
     * @param $idConsistencia
     * @return bool
     * @throws \BusinessException
     * @throws \DBException
     */
    public function possuiCorrecao($idConsistencia)
    {

        $rs = $this->getRecordConsistenciaSistema(" db160_json ", " db160_sequencial = {$idConsistencia} ");

        $jsonConsistencia =  preg_replace('/\n/', ' ', \db_utils::fieldsMemory($rs, 0)->db160_json);
        $sql = \JSON::create()->parse($jsonConsistencia)->sql;

        if (empty($sql)) {
            return false;
        }
        if (empty($sql->correcao)) {
            return false;
        }
        return true;
    }

    /**
     * @param $idConsistencia
     * @return string
     * @throws \BusinessException
     * @throws \DBException
     */
    public function getAjuda($idConsistencia)
    {
        $rs = $this->getRecordConsistenciaSistema(" db160_json ", " db160_sequencial = {$idConsistencia} ");
        $jsonConsistencia =  preg_replace('/\n/', ' ', \db_utils::fieldsMemory($rs, 0)->db160_json);
        $sql = \JSON::create()->parse($jsonConsistencia);
        if (!empty($sql->ajuda)) {
            return $sql->ajuda;
        }
        return '';
    }

    /**
     * Retorna as colunas disponíveis do JSON
     * @param $id
     * @return mixed
     * @throws \BusinessException
     * @throws \DBException
     */
    public function getColunas($id)
    {
        $rs = $this->getRecordConsistenciaSistema(" db160_json ", " db160_sequencial = {$id} ");
        $jsonConsistencia =  preg_replace('/\n/', ' ', \db_utils::fieldsMemory($rs, 0)->db160_json);
        $json = \JSON::create()->parse($jsonConsistencia);
        return $json->formulario->campos;
    }
}
