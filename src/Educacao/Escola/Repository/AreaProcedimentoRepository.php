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

namespace ECidade\Educacao\Escola\Repository;

use cl_areaprocedimento;
use ECidade\Educacao\Escola\Model\AreaProcedimento;
use ECidade\Educacao\Escola\Model\AreaProcedimentoResultado;
use Exception;
use ProcedimentoAvaliacao;

/**
 * Class AreaProcedimentoRepository
 * @package ECidade\Educacao\Escola\Repository
 */
class AreaProcedimentoRepository extends Repository
{
    /**
     * @param $key
     * @return AreaProcedimento
     * @throws Exception
     */
    public static function find($key)
    {
        $dao = new cl_areaprocedimento();
        $sql = $dao->sql_query_file($key);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar Procedimentos da Area.");
        }

        $areaProcedimento = AreaProcedimento::fromState(pg_fetch_array($rs));

        self::buscarAvaliacoes($areaProcedimento);
        self::buscarResultado($areaProcedimento);

        return $areaProcedimento;
    }

    /**
     * @param AreaProcedimento $areaProcedimento
     * @throws Exception
     */
    private static function buscarResultado(AreaProcedimento $areaProcedimento)
    {
        $repositoryResultado = new AreaProcedimentoResultadoRepository();
        $resultado = $repositoryResultado->scopeAreaProcedimento($areaProcedimento)->first();
        if ($resultado instanceof AreaProcedimentoResultado) {
            $areaProcedimento->setResultado($resultado);
        }
    }

    /**
     * @param AreaProcedimento $areaProcedimento
     * @throws Exception
     */
    private static function buscarAvaliacoes(AreaProcedimento $areaProcedimento)
    {
        $repositoryAvaliacao = new AreaProcedimentoAvaliacaoRepository();
        $areaProcedimento->setAvaliacoes($repositoryAvaliacao->scopeAreaProcedimento($areaProcedimento)->get());
    }

    /**
     * @return AreaProcedimento[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_areaprocedimento();
        $sql = $dao->sql_query_file(null, '*', null, implode(' and ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar Procedimentos da Area.");
        }

        $procedimentosArea = [];
        while ($state = pg_fetch_array($rs)) {
            $areaProcedimento = AreaProcedimento::fromState($state);

            self::buscarAvaliacoes($areaProcedimento);
            self::buscarResultado($areaProcedimento);

            $procedimentosArea[] = $areaProcedimento;
        }

        return $procedimentosArea;
    }

    /**
     * @param ProcedimentoAvaliacao $procedimentoAvaliacao
     * @return $this
     */
    public function scopeProcedimento(ProcedimentoAvaliacao $procedimentoAvaliacao)
    {
        $this->scopes['procedimento'] = "ed157_procedimento = {$procedimentoAvaliacao->getCodigo()}";
        return $this;
    }

    /**
     * @return AreaProcedimento|null
     * @throws Exception
     */
    public function first()
    {
        $procedimentos = $this->get();
        if (empty($procedimentos)) {
            return null;
        }

        return array_shift($procedimentos);
    }

    public function salvar(AreaProcedimento $areaProcedimento)
    {
        $dao = new cl_areaprocedimento();
        $dao->ed157_codigo = $areaProcedimento->getCodigo();
        $dao->ed157_procedimento = $areaProcedimento->getProcedimento()->getCodigo();

        if (empty($dao->ed157_codigo)) {
            $dao->incluir(null);
        } else {
            $dao->alterar($dao->ed157_codigo);
        }

        if ($dao->erro_status == 0) {
            throw new Exception("Erro ao salvar Procedimento da Area.");
        }

        $areaProcedimento->setCodigo($dao->ed157_codigo);

        return $areaProcedimento;
    }

    /**
     * @param AreaProcedimento $areaProcedimento
     * @return bool
     * @throws Exception
     */
    public function excluir(AreaProcedimento $areaProcedimento)
    {
        $dao = new cl_areaprocedimento();
        $dao->ed157_codigo = $areaProcedimento->getCodigo();

        if (empty($dao->ed157_codigo)) {
            throw new Exception("Procedimento de Avaliação da Área de Conhecimento não informado.");
        }

        $dao->excluir($dao->ed157_codigo);

        return true;
    }
}
