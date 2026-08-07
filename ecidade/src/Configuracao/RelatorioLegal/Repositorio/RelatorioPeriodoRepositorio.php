<?php
/*
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

namespace ECidade\Configuracao\RelatorioLegal\Repositorio;

use cl_orcparamrelperiodos;
use ECidade\Configuracao\RelatorioLegal\Modelo\Periodo;
use ECidade\Configuracao\RelatorioLegal\Modelo\Relatorio;
use ECidade\Configuracao\RelatorioLegal\Modelo\RelatorioPeriodo;
use Exception;

class RelatorioPeriodoRepositorio extends Repositorio
{
    /**
     * @param array $campos
     * @return RelatorioPeriodo[]
     * @throws Exception
     */
    public function get($campos = array("*"))
    {
        $dao = new cl_orcparamrelperiodos();
        $sql = $dao->sql_query_file(null, implode(', ', $campos), null, implode(' and ', $this->scopes));
        $rs = db_query($sql);
        if (!$rs) {
            throw new Exception("Não foi possível buscar os períodos do relatorio.");
        }

        $periodos = array();
        if (pg_num_rows($rs) === 0) {
            return array();
        }

        while ($periodo = pg_fetch_array($rs)) {
            $periodos[] = RelatorioPeriodo::fromState($periodo);
        }

        return $periodos;
    }

    /**
     * @param Periodo $periodo
     * @param string $operador
     * @return $this
     */
    public function scopePeriodo(Periodo $periodo, $operador = '=')
    {
        $this->scopes['periodo'] = "o113_periodo {$operador} {$periodo->getSequencial()}";
        return $this;
    }

    /**
     * @param Relatorio $relatorio
     * @param string $operador
     * @return $this
     */
    public function scopeRelatorio(Relatorio $relatorio, $operador = '=')
    {
        $this->scopes['relatorio'] = "o113_orcparamrel {$operador} {$relatorio->getSequencial()}";
        return $this;
    }

    /**
     * @param RelatorioPeriodo $relatorioPeriodo
     * @return RelatorioPeriodo
     * @throws Exception
     */
    public function import(RelatorioPeriodo $relatorioPeriodo)
    {
        $dao = self::setDao($relatorioPeriodo);

        self::find($relatorioPeriodo->getSequencial())
            ? $dao->alterar()
            : $dao->incluir($relatorioPeriodo->getSequencial());

        if ($dao->erro_status === '0') {
            throw new Exception('Não foi possível associar um período a um formulário.');
        }

        return $relatorioPeriodo;
    }

    private static function setDao(RelatorioPeriodo $relatorioPeriodo)
    {
        $dao = new cl_orcparamrelperiodos();

        $dao->o113_sequencial = $relatorioPeriodo->getSequencial();
        $dao->o113_periodo = $relatorioPeriodo->getPeriodo()->getSequencial();
        $dao->o113_orcparamrel = $relatorioPeriodo->getRelatorio()->getSequencial();

        return $dao;
    }

    /**
     * @param int $id
     * @return RelatorioPeriodo
     * @throws Exception
     */
    public static function find($id)
    {
        $dao = new cl_orcparamrelperiodos();
        $rs = db_query($dao->sql_query_file($id));

        if (!$rs) {
            throw new Exception("Não foi possível buscar o período.");
        }

        if (pg_num_rows($rs) === 0) {
            return null;
        }

        return RelatorioPeriodo::fromState(pg_fetch_array($rs));
    }

    /**
     * @param RelatorioPeriodo $relatorioPeriodo
     * @throws Exception
     */
    public function delete(RelatorioPeriodo $relatorioPeriodo = null)
    {
        $id = $relatorioPeriodo instanceof RelatorioPeriodo ? $relatorioPeriodo->getSequencial() : null;

        $dao = new cl_orcparamrelperiodos();
        $dao->excluir($id, implode(' AND ', $this->scopes));

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível excluir os vínculos dos períodos com o relatório.\nContate o suporte.");
        }
    }
}
