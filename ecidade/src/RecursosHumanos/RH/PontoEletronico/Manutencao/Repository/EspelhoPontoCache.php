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

namespace ECidade\RecursosHumanos\RH\PontoEletronico\Manutencao\Repository;

class EspelhoPontoCache {

    /**
     * @var EspelhoPontoCache
     */
    private static $instancia;


    /**
     * @return EspelhoPontoCache
     */
    static function init()
    {
        if (empty($instancia)) {
            static::$instancia = new self;
        }

        return static::$instancia;
    }

    /**
     * EspelhoPontoCache constructor.
     */
    private function __construct()
    {
    }


    private function __clone()
    {
    }

    /**
     * @param \DBDate $data
     * @param int $matricula
     * @throws \DBException
     */
    public function invalidarCache(\DBDate $data, $matricula)
    {   
        $oDaoPontoEletronicoData = $this->getPontoeletronicoarquivodata($data, $matricula);
        
        if(!empty($oDaoPontoEletronicoData)) {
            $oDaoPontoEletronicoData->rh197_espelho_ponto_cache = null;
            $oDaoPontoEletronicoData->rh197_cache_valido = 'f';
            $oDaoPontoEletronicoData->alterar($oDaoPontoEletronicoData->rh197_sequencial);
            
            if(!$oDaoPontoEletronicoData->erro_status) {
                throw new \DBException($oDaoPontoEletronicoData->erro_msg);
            }
        }
    }

    /**
     * @param int $matricula
     * @param \DBDate $dataInicial
     * @param \DBDate $dataFinal
     * @throws \DBException
     * @throws \ParameterException
     */
    public function invalidarCacheNoPeriodo($matricula, \DBDate $dataInicial, \DBDate $dataFinal)
    {
        if ($dataInicial->getTimeStamp() > $dataFinal->getTimeStamp()) {
            throw new \ParameterException('Data inicial não pode ser maior que data final.');
        }

        // die('data inicial: '.$dataInicial.', data final:'.$dataFinal);
        $datas = \DBDate::getDatasNoIntervalo($dataInicial, $dataFinal);
        foreach ($datas as $data) {
            $this->invalidarCache($data, $matricula);
        }
    }

    /**
     * @param \DBDate $data
     * @param $matricula
     * @return mixed|null
     * @throws \DBException
     */
    public function getEspelhoPontoCache(\DBDate $data, $matricula)
    {
        $oDaoPontoEletronicoData = $this->getPontoeletronicoarquivodata($data, $matricula);
        
        if(empty($oDaoPontoEletronicoData)) {
            return null;
        }

        return $oDaoPontoEletronicoData->rh197_cache_valido == 't' && $oDaoPontoEletronicoData->rh197_espelho_ponto_cache ?  (($oDaoPontoEletronicoData->rh197_espelho_ponto_cache)) : null;
    }

    /**
     * @param \DBDate $data
     * @param $matricula
     * @return \cl_pontoeletronicoarquivodata|null
     * @throws \DBException
     */
    private function getPontoeletronicoarquivodata(\DBDate $data, $matricula)
    {
        $oDaoPontoEletronicoData    = new \cl_pontoeletronicoarquivodata();
        $sWherePontoEletronicoData  = "     rh197_data      = '{$data->getDate()}'";
        $sWherePontoEletronicoData .= " AND rh197_matricula = {$matricula}";
        $sSqlPontoEletronicoData    = $oDaoPontoEletronicoData->sql_query_file(
            null,
            '*',
            null,
            $sWherePontoEletronicoData
        );

        $rsPontoEletronicoData = db_query($sSqlPontoEletronicoData);

        if(!$rsPontoEletronicoData) {
            throw new \DBException('Erro ao buscar as informações do ponto no dia.');
        }

        if(pg_num_rows($rsPontoEletronicoData) > 0) {

            $oDadosRetorno = \db_utils::fieldsMemory($rsPontoEletronicoData, 0);

            $oDaoPontoEletronicoData->rh197_sequencial             = $oDadosRetorno->rh197_sequencial;
            $oDaoPontoEletronicoData->rh197_pontoeletronicoarquivo = $oDadosRetorno->rh197_pontoeletronicoarquivo;
            $oDaoPontoEletronicoData->rh197_data                   = $oDadosRetorno->rh197_data;
            $oDaoPontoEletronicoData->rh197_matricula              = $oDadosRetorno->rh197_matricula;
            $oDaoPontoEletronicoData->rh197_pis                    = $oDadosRetorno->rh197_pis;
            $oDaoPontoEletronicoData->rh197_horas_trabalhadas      = $oDadosRetorno->rh197_horas_trabalhadas;
            $oDaoPontoEletronicoData->rh197_horas_falta            = $oDadosRetorno->rh197_horas_falta;
            $oDaoPontoEletronicoData->rh197_horas_extras_50_d      = $oDadosRetorno->rh197_horas_extras_50_d;
            $oDaoPontoEletronicoData->rh197_horas_extras_75_d      = $oDadosRetorno->rh197_horas_extras_75_d;
            $oDaoPontoEletronicoData->rh197_horas_extras_100_d     = $oDadosRetorno->rh197_horas_extras_100_d;
            $oDaoPontoEletronicoData->rh197_horas_extras_50_n      = $oDadosRetorno->rh197_horas_extras_50_n;
            $oDaoPontoEletronicoData->rh197_horas_extras_75_n      = $oDadosRetorno->rh197_horas_extras_75_n;
            $oDaoPontoEletronicoData->rh197_horas_extras_100_n     = $oDadosRetorno->rh197_horas_extras_100_n;
            $oDaoPontoEletronicoData->rh197_horas_adicinal_noturno = $oDadosRetorno->rh197_horas_adicinal_noturno;
            $oDaoPontoEletronicoData->rh197_horas_atraso           = $oDadosRetorno->rh197_horas_atraso;
            $oDaoPontoEletronicoData->rh197_horas_saida_antecipada = $oDadosRetorno->rh197_horas_saida_antecipada;
            $oDaoPontoEletronicoData->rh197_afastamento            = $oDadosRetorno->rh197_afastamento;
            $oDaoPontoEletronicoData->rh197_espelho_ponto_cache    = $oDadosRetorno->rh197_espelho_ponto_cache;

            return $oDaoPontoEletronicoData;
        }

        return null;
    }

    /**
     * @param \DBDate $data
     * @param $matricula
     * @param $espelhoPontoCache
     * @throws \DBException
     */
    public function persist(\DBDate $data, $matricula, $espelhoPontoCache)
    {
        if (!is_array($espelhoPontoCache)) {
            throw new \ParameterException('Coleção de dados do Espelho Ponto a ser salvo está no formato errado.');
        }

        $oDaoPontoEletronicoData = $this->getPontoeletronicoarquivodata($data, $matricula);
        
        if(empty($oDaoPontoEletronicoData)) {
            $oDaoPontoEletronicoData = new \cl_pontoeletronicoarquivodata();
            $oDaoPontoEletronicoData->rh197_data = $data->getDate();
            $oDaoPontoEletronicoData->rh197_matricula = $matricula;
        }

        $oDaoPontoEletronicoData->rh197_espelho_ponto_cache = !empty($espelhoPontoCache) ? pg_escape_string(addslashes(serialize($espelhoPontoCache))) : '';
        $oDaoPontoEletronicoData->rh197_cache_valido        = 't';
        
        $sAcao = empty($oDaoPontoEletronicoData->rh197_sequencial) ? 'incluir' : 'alterar';
        
        $oDaoPontoEletronicoData->{$sAcao}($oDaoPontoEletronicoData->rh197_sequencial);

        if(!$oDaoPontoEletronicoData->erro_status) {
            throw new \DBException($oDaoPontoEletronicoData->erro_msg);
        }
    }

    /**
     * @param int[] $matriculas
     * @param \DBDate $dataInicial
     * @param \DBDate $dataFinal
     * @return array
     * @throws \DBException
     */
    public function getEspelhoPontoCacheValido($matriculas, \DBDate $dataInicial, \DBDate $dataFinal, $cacheValido = true, $retornaDados = true)
    {
        $oDaoPontoEletronicoData = new \cl_pontoeletronicoarquivodata();
        $where  = "     rh197_data  between '{$dataInicial->getDate()}' and '{$dataFinal->getDate()}'";
        $where .= ' AND rh197_matricula in (' . implode(',', $matriculas) .')';
        $where .= ' AND rh197_cache_valido = ' . ($cacheValido ? 'true' : 'false');
        $sql    = $oDaoPontoEletronicoData->sql_query_file(
            null,
            'rh197_data, rh197_matricula, rh197_espelho_ponto_cache',
            'rh197_data',
            $where
        );
        $rs = \db_query($sql);

        if (!$rs) {
            throw new \DBException('Não foi possível buscar as matriculas com cache inválido.');
        }

        $matriculasValidas = array();
        \db_utils::makeCollectionFromRecord($rs, function ($retorno) use (&$matriculasValidas, $retornaDados) {
            $matriculasValidas[$retorno->rh197_matricula][$retorno->rh197_data] = $retornaDados ? unserialize(stripslashes($retorno->rh197_espelho_ponto_cache)) : true;
        });

        return $matriculasValidas;
    }



}
