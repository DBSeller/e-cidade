<?php

namespace ECidade\Tributario\Issqn\Inscricao\Atividades\Repository;

use \BusinessException;
use \DBException;
use \db_utils;
use \db_query;
use ECidade\Tributario\Issqn\Inscricao\Atividades\Filter\ListagemAtividades as FiltroListagemAtividades;

/**
 * Classe para consulta de atividades para uma inscrição
 */
class Atividades
{

    private $tipoConsulta = 'CONSULTA_ATIVIDADES';

    public function tipoConsulta($tipoConsulta = null)
    {
        if (empty($tipoConsulta)) {
            return $this->tipoConsulta;
        }

        $this->tipoConsulta = $tipoConsulta;
        return $this;
    }

    public function listarAtividades(FiltroListagemAtividades $filtro)
    {
        return $this->getAll($filtro);
    }

    private function getAll(FiltroListagemAtividades $filtro)
    {
        switch ($this->tipoConsulta) {
            default:
                $campos = array(
                     "sequencial"  => "q03_ativ"
                    ,"descricao"   => "q03_descr"
                    ,"codigo"      => "case
                                              when q71_estrutural is null then  rh70_estrutural
                                              else q71_estrutural
                                       end"
                    ,"tipo"        => "q12_descr"
                    ,"risco"       => "case
                                              when q71_estrutural is null then  rh70_classificacaorisco
                                              else q71_classificacaorisco
                                       end"
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
            default:
                $from  = "
                         FROM ativid
                    LEFT JOIN clasativ ON clasativ.q82_ativ = ativid.q03_ativ
                    LEFT JOIN classe ON classe.q12_classe = clasativ.q82_classe
                    LEFT JOIN atividcbo ON atividcbo.q75_ativid = ativid.q03_ativ
                    LEFT JOIN rhcbo ON rhcbo.rh70_sequencial = atividcbo.q75_rhcbo
                    LEFT JOIN atividcnae ON atividcnae.q74_ativid = ativid.q03_ativ
                    LEFT JOIN cnaeanalitica ON cnaeanalitica.q72_sequencial = atividcnae.q74_cnaeanalitica
                    LEFT JOIN cnae ON cnae.q71_sequencial = cnaeanalitica.q72_cnae
                ";

                $order = array(
                     "q71_estrutural"
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

        $aWhere = $filtro->ajustaFiltros();

        if (!empty($aWhere)) {
            $where = implode(" AND ", $aWhere);
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
