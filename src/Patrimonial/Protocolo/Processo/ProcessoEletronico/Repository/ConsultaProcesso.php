<?php

namespace ECidade\Patrimonial\Protocolo\Processo\ProcessoEletronico\Repository;

use \DBDate;
use \BusinessException;
use \DBException;
use \db_utils;
use \db_query;
use ECidade\Patrimonial\Protocolo\Processo\ProcessoEletronico\Filter\ListagemProcessos as FiltroListagemProcessos;

/**
 * Classe para consulta de processos na base de dados
 */
class ConsultaProcesso
{
    const CONSULTA_PROCESSOS          = 'CONSULTA_PROCESSOS';
    const CONSULTA_OBJETO_SOLICITACAO = 'CONSULTA_OBJETO_SOLICITACAO';

    private $tipoConsulta = 'CONSULTA_PROCESSOS';

    public function tipoConsulta($tipoConsulta = null)
    {
        if (empty($tipoConsulta)) {
            return $this->tipoConsulta;
        }

        $this->tipoConsulta = $tipoConsulta;
        return $this;
    }

    public function objetoSolicitacao(FiltroListagemProcessos $filtro)
    {
        $solicitacoes = $this->getAll($filtro);
        return current($solicitacoes);
    }

    public function listarProcessos(FiltroListagemProcessos $filtro)
    {
        return $this->getAll($filtro);
    }

    private function getAll(FiltroListagemProcessos $filtro)
    {
        switch ($this->tipoConsulta) {
            case 'CONSULTA_OBJETO_SOLICITACAO':
                $campos = array(
                     "sequencial"     => "ov01_sequencial"
                    ,"tipo_processo"  => "ov01_tipoprocesso"
                    ,"processo"       => "ov01_numero||'/'||ov01_anousu"
                    ,"metadados"      => "ov33_informacoesprocesso"
                );
                break;

            default:
                $campos = array(
                     "sequencial"               => "ov01_sequencial"
                    ,"tipo_processo"            => "ov01_tipoprocesso"
                    ,"tipo_processo_descricao"  => "p51_descr"
                    ,"solicitante"              => "ov02_nome"
                    ,"processo"                 => "ov01_numero||'/'||ov01_anousu"
                    ,"data"                     => "ov01_dataatend"
                    ,"numero"                   => "ov01_numero"
                    ,"ano"                      => "ov01_anousu"
                );
                break;
        }

        $resultado   = array();
        $fnResultado = function ($retorno) use ($campos) {

            $items = array();

            foreach ($campos as $campo => $valor) {
                $valor = $retorno->{$campo};

                if (!empty($valor) && strpos($campo, 'data') !== false) {
                    $valor = DBDate::getInstance($valor)->getDate(DBDate::DATA_PTBR);
                }

                $items[$campo] = $valor;
            }

            return (object) $items;
        };

        $rsResultado = $this->getQuery($filtro, $campos);

        if (pg_num_rows($rsResultado) > 1) {
            $resultado = db_utils::makeCollectionFromRecord($rsResultado, $fnResultado);
        } elseif (pg_num_rows($rsResultado) == 1) {
            $resultado = array(db_utils::makeFromRecord($rsResultado, $fnResultado, 0));
        }

        return $resultado;
    }

    private function getQuery($filtro, $campos = null)
    {
        switch ($this->tipoConsulta) {
            case 'CONSULTA_OBJETO_SOLICITACAO':
                $from  = "
                          FROM ouvidoriaatendimento
                    LEFT JOIN ouvidoriaatendimentocidadao ON ov10_ouvidoriaatendimento = ov01_sequencial
                    INNER JOIN ouvidoriaatendimentoprocessoeletronico ON ov33_ouvidoriaatendimento = ov01_sequencial
                    LEFT JOIN cidadao ON ov02_sequencial = ov10_cidadao
                                      AND ov02_seq = ov10_seq
                    LEFT JOIN processoouvidoria ON ov09_ouvidoriaatendimento = ov01_sequencial
                ";

                $where = array();

                if ($filtro->getNumeroProcesso() != null) {
                    $where[] = "ov01_numero = {$filtro->getNumeroProcesso()}";
                }

                if ($filtro->getAnoProcesso() != null) {
                    $where[] = "ov01_anousu = {$filtro->getAnoProcesso()}";
                }

                if ($filtro->getCodigoProcessoProtocolo() != null) {
                    $where[] = "ov09_protprocesso = {$filtro->getCodigoProcessoProtocolo()}";
                } else {
                    $where[] = "ov09_ouvidoriaatendimento is null";
                }

                $erro = "Ocorreu um erro ao consultar a solicitação do processo\n";
                break;

            default:
                $from  = "FROM ouvidoriaatendimento
                    LEFT JOIN ouvidoriaatendimentocidadao ON ov10_ouvidoriaatendimento = ov01_sequencial
                    INNER JOIN ouvidoriaatendimentoprocessoeletronico ON ov33_ouvidoriaatendimento = ov01_sequencial
                    INNER JOIN tipoproc ON p51_codigo = ov01_tipoprocesso
                    LEFT JOIN cidadao ON ov02_sequencial = ov10_cidadao
                                      AND ov02_seq = ov10_seq
                    LEFT JOIN processoouvidoria ON ov09_ouvidoriaatendimento = ov01_sequencial
                ";

                $where = array(
                     "ov01_instit                       = {$filtro->getCodigoInstituicao()}"
                    ,"ov01_situacaoouvidoriaatendimento = {$filtro->getSituacaoOuvidoriaAtendimento()}"
                    ,"p51_codigo IN ( SELECT p41_tipoproc
                                        FROM tipoprocdepto
                                       WHERE p41_coddepto = {$filtro->getCodigoDepartamento()})"
                    ,"ov09_ouvidoriaatendimento is null"
                );

                if ($filtro->getDataInicio() !== null && $filtro->getDataFim() !== null) {
                    if ($filtro->getDataInicio() instanceof DBDate && $filtro->getDataFim() instanceof DBDate) {
                        $where[] = "    ov01_dataatend BETWEEN '{$filtro->getDataInicio()->getDate()}'
                                    AND '{$filtro->getDataFim()->getDate()}'";
                    } else {
                        $where[] = "ov01_dataatend BETWEEN '{$filtro->getDataInicio()}' AND '{$filtro->getDataFim()}'";
                    }
                } else {
                    if ($filtro->getDataInicio() !== null) {
                        if ($filtro->getDataInicio() instanceof DBDate) {
                            $where[] = "ov01_dataatend >= '{$filtro->getDataInicio()->getDate()}'";
                        } else {
                            $where[] = "ov01_dataatend >= '{$filtro->getDataInicio()}'";
                        }
                    }

                    if ($filtro->getDataFim() !== null) {
                        if ($filtro->getDataFim() instanceof DBDate) {
                            $where[] = "ov01_dataatend <= '{$filtro->getDataFim()->getDate()}'";
                        } else {
                            $where[] = "ov01_dataatend <= '{$filtro->getDataFim()}'";
                        }
                    }
                }

                $order = array(
                     "data"
                    ,"processo"
                );

                $erro = "Ocorreu um erro ao consultar os processos\n";
                break;
        }

        $camposConsulta = array();
        foreach ($campos as $key => $campo) {
            $camposConsulta[] = $campo . ' as ' . $key;
        }

        if (!empty($campos)) {
            $campos = implode(", ", $camposConsulta);
        } else {
            $campos = " * ";
        }

        $sql = "SELECT {$campos} {$from}";

        if (!empty($where)) {
            $where = implode(" AND ", $where);
            $sql  .= " WHERE {$where} ";
        }

        if (!empty($order)) {
            $order = implode(", ", $order);
            $sql  .= " ORDER BY {$order} ";
        }

        $rs  = db_query($sql);

        if (!$rs) {
            throw new DBException($erro . pg_last_error());
        }

        return $rs;
    }
}
