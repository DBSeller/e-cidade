<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
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

namespace ECidade\RecursosHumanos\RH\Efetividade\Repository;

use ECidade\RecursosHumanos\ESocial\Migracao\Servidor;
use ECidade\RecursosHumanos\RH\Efetividade\Model\EscalaServidor as EscalaServidorModel;
use ECidade\RecursosHumanos\RH\Efetividade\Model\Jornada as JornadaModel;
use ECidade\RecursosHumanos\RH\Efetividade\Model\Periodo as PeriodoEfetividade;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model\DiaTrabalho;

/**
 * Classe responsável pelas buscas e ações referentes a jornada
 * Class Jornada
 * @package ECidade\RecursosHumanos\RH\Efetividade\Repository
 * @author Fábio Esteves <fabio.esteves@dbseller.com.br>
 */
class Jornada extends \BaseClassRepository {

    /**
     * Sobrescreve o atributo da classe pai para
     * manter apenas as referências da classe atual
     */
    protected static $oInstance;

    /**
     * Retorna uma instância de Jornada
     * @param  $iCodigo
     * @return \ECidade\RecursosHumanos\RH\Efetividade\Model\Jornada|null
     * @throws \DBException
     */
    protected function make($iCodigo) {

        $oDaoJornada = new \cl_jornada();
        $sSqlJornada = $oDaoJornada->sql_query_file(null, '*', null, "rh188_sequencial = {$iCodigo}");
        $rsJornada   = db_query($sSqlJornada);

        if(!$rsJornada) {
            throw new \DBException("Erro ao buscar as informações da jornada.");
        }

        if(pg_num_rows($rsJornada) == 0) {
            throw new \DBException("Jornada não encontrada.");
        }

        return \db_utils::makeFromRecord($rsJornada, function($oRetorno) {

            $oJornada = new JornadaModel();
            $oJornada->setCodigo($oRetorno->rh188_sequencial);
            $oJornada->setDescricao($oRetorno->rh188_descricao);
            $oJornada->setHoras(JornadaHoras::getHorasPorJornada($oJornada));
            $oJornada->setFixo($oRetorno->rh188_fixo == 't');
            $oJornada->setDSR($oRetorno->rh188_tipo == 'D');
            $oJornada->setFolga($oRetorno->rh188_tipo == 'F');
            $oJornada->setDiaTrabalhado($oRetorno->rh188_tipo == 'T');
            $oJornada->setTipoDescricao($oRetorno->rh188_tipo);

            return $oJornada;
        }, 0);
    }

    public static function getInstanciaByCodigo($iCodigo) {
        return self::getInstanciaPorCodigo($iCodigo);
    }

    /**
     * @param DiaTrabalho $oDiaTrabalho
     * @param EscalaServidor $oEscalaServidor
     * @return mixed
     * @throws \BusinessException
     * @throws \DBException
     */
    public static function getOrdem(DiaTrabalho $oDiaTrabalho, EscalaServidorModel $oEscalaServidor)
    {
        $sSqlOrdem = "select (('{$oDiaTrabalho->getData()->getDate()}' - '{$oEscalaServidor->getEscalaTrabalho()->getDataBase()->getDate()}'::date) ";
        $sSqlOrdem .= "    % (select max(rh191_ordemhorario) ";
        $sSqlOrdem .= "            from gradeshorariosjornada ";
        $sSqlOrdem .= "           where rh191_gradehorarios = {$oEscalaServidor->getEscalaTrabalho()->getCodigo()}) + 1) as ordem ";
        $sSqlOrdem .= "  from ( select (select rh192_sequencial ";
        $sSqlOrdem .= "                   from escalaservidor ";
        $sSqlOrdem .= "                  where rh192_regist = {$oDiaTrabalho->getServidor()->getMatricula()} ";
        $sSqlOrdem .= "    and rh192_dataescala <= '{$oDiaTrabalho->getData()->getDate()}' ";
        $sSqlOrdem .= "                  order by rh192_dataescala desc limit 1) as codigo_escala, ";
        $sSqlOrdem .= "                 '{$oDiaTrabalho->getData()->getDate()}' as data ) as escalasperiodo ";
        $rsOrdem    = db_query($sSqlOrdem);

        if(!$rsOrdem) {
            throw new \DBException("Erro ao buscar a ordem da jornada.");
        }

        if(pg_num_rows($rsOrdem) == 0) {
            throw new \BusinessException("Ordem da grade de horário não encontrada.");
        }

        return \db_utils::fieldsMemory($rsOrdem, 0)->ordem;
    }

    /**
     * Retorna as jornadas de um servidor em um determinado período
     *
     * @param \Servidor $servidor
     * @param PeriodoEfetividade $periodo
     * @param array $tiposJornada
     * @return Jornada[] | array()
     */
    public static function getJornadasPorPeriodo(\Servidor $servidor, PeriodoEfetividade $periodo, array $tiposJornada = null)
    {
        $periodoAnterior = clone $periodo->getDataInicio();
        $periodoAnterior->modificarIntervalo('-1 month');

        $matricula     = $servidor->getMatricula();
        $periodoInicio = $periodo->getDataInicio();
        $periodoFim    = $periodo->getDataFim();
        $sqlJornadas   = " SELECT
                         *
                       FROM (SELECT
                               (data_inicio + qtde_somar)::date as data
                               ,{$matricula} as matricula
                             FROM (SELECT
                                      generate_series(0, (datas.data_fim - datas.data_inicio)) as qtde_somar
                                     ,datas.data_inicio
                                   FROM (SELECT 
                                            ('{$periodoAnterior->getDate()}')::date as data_inicio
                                           ,('{$periodoFim->getDate()}')::date as data_fim
                                         ) as datas
                                   ) as periodo
                             ) as periodo_servidor
                             ,fc_getjornadaservidornadata(data, matricula)";

        if(!empty($tiposJornada)) {
            $sqlJornadas.= " WHERE tipo IN ('". implode('\',\'', $tiposJornada) ."') ";
        }

        $sqlJornadas  .= " ORDER BY data";
        $rsJornadas    = db_query($sqlJornadas);

        if(!$rsJornadas) {
            $msg  = " Erro ao buscar as jornadas para o servidor ({$matricula})\n";
            $msg .= " no periodo ({$periodoInicio->getDate(\DBDate::DATA_PTBR)} - {$periodoFim->getDate(\DBDate::DATA_PTBR)})";
            throw new DBException($msg);
        }

        $qtdeJornadas = pg_num_rows($rsJornadas);
        if($qtdeJornadas == 0) {
            return array();
        }

        for ($i = 0; $i < $qtdeJornadas; $i++) {

            $jornada = \db_utils::fieldsMemory($rsJornadas, $i);
            $jornadas[str_replace('-', '', $jornada->data)]['data']    = new \DBDate($jornada->data);
            $jornadas[str_replace('-', '', $jornada->data)]['ordem']   = $jornada->ordem_jornada;
            $jornadas[str_replace('-', '', $jornada->data)]['jornada'] = self::getInstanciaPorCodigo($jornada->codigo_jornada);
        }

        return $jornadas;
    }

    public static function getJornadasNoIntervalo(\Servidor $servidor, PeriodoEfetividade $periodo, array $tiposJornada = null)
    {
        $matricula     = $servidor->getMatricula();
        $sqlJornadas   = " SELECT
                           *
                         FROM (SELECT
                                 (data_inicio + qtde_somar)::date as data
                                 ,{$matricula} as matricula
                               FROM (SELECT
                                        generate_series(0, (datas.data_fim - datas.data_inicio)) as qtde_somar
                                       ,datas.data_inicio
                                     FROM (SELECT 
                                              ('{$periodo->getDataInicio()->getDate()}')::date as data_inicio
                                             ,('{$periodo->getDataFim()->getDate()}')::date as data_fim
                                           ) as datas
                                     ) as periodo
                               ) as periodo_servidor
                               ,fc_getjornadaservidornadata(data, matricula)";

        if(!empty($tiposJornada)) {
            $sqlJornadas.= " WHERE tipo IN ('". implode('\',\'', $tiposJornada) ."') ";
        }

        $sqlJornadas  .= " ORDER BY data";
        $rsJornadas    = db_query($sqlJornadas);

        if(!$rsJornadas) {
            $msg  = " Erro ao buscar as jornadas para o servidor ({$matricula})\n";
            $msg .= " no periodo ({$periodo->getDate(\DBDate::DATA_PTBR)} - {$periodo->getDate(\DBDate::DATA_PTBR)})";
            throw new DBException($msg);
        }

        $qtdeJornadas = pg_num_rows($rsJornadas);
        if($qtdeJornadas == 0) {
            return array();
        }

        for ($i = 0; $i < $qtdeJornadas; $i++) {

            $jornada = \db_utils::fieldsMemory($rsJornadas, $i);
            $jornadas[str_replace('-', '', $jornada->data)]['data']    = new \DBDate($jornada->data);
            $jornadas[str_replace('-', '', $jornada->data)]['ordem']   = $jornada->ordem_jornada;
            $jornadas[str_replace('-', '', $jornada->data)]['jornada'] = self::getInstanciaPorCodigo($jornada->codigo_jornada);
        }

        return $jornadas;
    }

    /**
     * @param \Servidor $servidor
     * @param \DBDate $data
     * @return \ECidade\RecursosHumanos\RH\Efetividade\Model\Jornada
     * @throws \DBException
     */
    public function getJornadaServidorNoDia(\Servidor $servidor, \DBDate $data)
    {
        $sql = "select * from fc_getjornadaservidornadata('{$data->getDate()}', {$servidor->getMatricula()})";
        $rs = db_query($sql);

        if(!$rs) {
            throw new \DBException("Erro ao buscar a jornada do servidor {$servidor->getMatricula()} no dia {$data->getDate(\DBDate::DATA_PTBR)}.");
        }

        if(pg_num_rows($rs) == 0) {
            throw new \DBException("Nenhuma jornada encontrada para o servidor {$servidor->getMatricula()} no dia {$data->getDate(\DBDate::DATA_PTBR)}.");
        }

        return self::getInstanciaByCodigo(\db_utils::fieldsMemory($rs, 0)->codigo_jornada);
    }
}
