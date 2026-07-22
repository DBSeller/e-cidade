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

namespace ECidade\Configuracao\RelatorioLegal\Repositorio;

use cl_periodo;
use ECidade\Configuracao\RelatorioLegal\Modelo\Periodo;
use ECidade\Configuracao\RelatorioLegal\Modelo\Relatorio;
use ECidade\Configuracao\RelatorioLegal\Registry\PeriodoRegistry;
use Exception;

class PeriodoRepositorio extends Repositorio
{
    public static function colunasPorRelatorio(Relatorio $relatorio)
    {
        $campos = "distinct periodo.*";
        $where = "o113_orcparamrel = {$relatorio->getSequencial()}";
        $dao = new cl_periodo();
        $sql = $dao->sql_query_vinculo_relatorio(null, $campos, 'o114_ordem', $where);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar os períodos.\nContate o suporte.");
        }

        $periodos = array();
        if (pg_num_rows($rs) === 0) {
            return $periodos;
        }

        while ($periodo = pg_fetch_array($rs)) {
            $periodos[] = Periodo::fromState($periodo);
        }

        return $periodos;
    }

    /**
     * @param $id
     * @return Periodo
     * @throws Exception
     */
    public static function find($id)
    {
        $dao = new cl_periodo();
        $rs = db_query($dao->sql_query_file($id));
        if (!$rs) {
            throw new Exception("Não foi possível buscar o período.");
        }

        if (pg_num_rows($rs) === 0) {
            return null;
        }

        return Periodo::fromState(pg_fetch_array($rs));
    }

    public function save(Periodo $periodo)
    {
        $dao = self::setDadosDao($periodo);

        $periodo->getSequencial() ? $dao->incluir(null) : $dao->alterar($periodo->getSequencial());

        if ($dao->erro_status == 0) {
            throw new Exception('Erro ao salvar período!');
        }

        $periodo->setSequencial($dao->o114_sequencial);
        return $periodo;
    }

    /**
     * @param Periodo $periodo
     * @return cl_periodo
     */
    private static function setDadosDao(Periodo $periodo)
    {
        $dao = new cl_periodo();
        $dao->o114_sequencial = $periodo->getSequencial();
        $dao->o114_descricao = $periodo->getDescricao();
        $dao->o114_qdtporano = $periodo->getQuantidadePorAno();
        $dao->o114_diainicial = $periodo->getDiaInicial();
        $dao->o114_mesinicial = $periodo->getMesInicial();
        $dao->o114_diafinal = $periodo->getDiaFinal();
        $dao->o114_mesfinal = $periodo->getMesFinal();
        $dao->o114_sigla = $periodo->getSigla();
        $dao->o114_ordem = $periodo->getOrdem();

        return $dao;
    }

    /**
     * Inclui ou altera um período conforme dados informados
     * @param Periodo $periodo
     * @return Periodo
     * @throws Exception
     */
    public function import(Periodo $periodo)
    {
        $dao = self::setDadosDao($periodo);

        PeriodoRegistry::get($periodo->getSequencial())
            ? $dao->alterar($periodo->getSequencial())
            : $dao->incluir($periodo->getSequencial());

        if ($dao->erro_status == 0) {
            throw new Exception('Erro ao salvar período!');
        }

        return $periodo;
    }
}
