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

use cl_diarioareaavaliacao;
use ECidade\Educacao\Escola\Model\AreaProcedimentoAvaliacao;
use ECidade\Educacao\Escola\Model\DiarioArea;
use ECidade\Educacao\Escola\Model\DiarioAreaAvaliacao;
use Exception;

/**
 * Class DiarioAreaAvaliacaoRepository
 * @package ECidade\Educacao\Escola\Repository
 */
class DiarioAreaAvaliacaoRepository extends Repository
{
    /**
     * @param $key
     * @return DiarioAreaAvaliacao
     * @throws Exception
     */
    private static function find($key)
    {
        $dao = new cl_diarioareaavaliacao();
        $sql = $dao->sql_query_file($key);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar as avaliações.");
        }

        return DiarioAreaAvaliacao::fromState(pg_fetch_array($rs));
    }

    /**
     * @return DiarioAreaAvaliacao[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_diarioareaavaliacao();
        $sql = $dao->sql_query_file(null, '*', null, implode(' and ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar as avaliações.");
        }

        $avaliacoes = [];
        while ($state = pg_fetch_array($rs)) {
            $avaliacoes[] = DiarioAreaAvaliacao::fromState($state);
        }
        return $avaliacoes;
    }

    /**
     * @return DiarioAreaAvaliacao|null
     * @throws Exception
     */
    public function first()
    {
        $avaliacoes = $this->get();
        if (empty($avaliacoes)) {
            return null;
        }

        return array_shift($avaliacoes);
    }

    /**
     * @param DiarioArea $diarioArea
     * @return DiarioAreaAvaliacaoRepository
     */
    public function scopeDiarioArea(DiarioArea $diarioArea)
    {
        $this->scopes['diario_area'] = "ed163_diarioarea = {$diarioArea->getCodigo()}";
        return $this;
    }

    /**
     * @param AreaProcedimentoAvaliacao $areaProcedimentoAvaliacao
     * @return $this
     */
    private function scopeAreaProcedimentoAvaliacao(AreaProcedimentoAvaliacao $areaProcedimentoAvaliacao)
    {
        $this->scopes['avaliacao'] = "ed163_areaprocedimentoavaliacao = {$areaProcedimentoAvaliacao->getCodigo()}";
        return $this;
    }

    /**
     * @param DiarioArea $diarioArea
     * @param AreaProcedimentoAvaliacao $areaProcedimentoAvaliacao
     * @return DiarioAreaAvaliacao
     * @throws Exception
     */
    public function findOrCreate(DiarioArea $diarioArea, AreaProcedimentoAvaliacao $areaProcedimentoAvaliacao)
    {

        $diarioAvaliacao = $this->scopeDiarioArea($diarioArea)
            ->scopeAreaProcedimentoAvaliacao($areaProcedimentoAvaliacao)
            ->first();

        if (is_null($diarioAvaliacao)) {
            $diarioAvaliacao = new DiarioAreaAvaliacao();
            $diarioAvaliacao->setDiarioArea($diarioArea);
            $diarioAvaliacao->setAreaProcedimentoAvaliacao($areaProcedimentoAvaliacao);
            $diarioAvaliacao = $this->salvar($diarioAvaliacao);
        }

        return $diarioAvaliacao;
    }

    /**
     * @param DiarioAreaAvaliacao $diarioAvaliacao
     * @return DiarioAreaAvaliacao
     * @throws Exception
     */
    public function salvar(DiarioAreaAvaliacao $diarioAvaliacao)
    {
        $dao = new cl_diarioareaavaliacao();
        $dao->ed163_codigo = $diarioAvaliacao->getCodigo();
        $dao->ed163_diarioarea = $diarioAvaliacao->getDiarioArea()->getCodigo();
        $dao->ed163_areaprocedimentoavaliacao = $diarioAvaliacao->getAreaProcedimentoAvaliacao()->getCodigo();
        $dao->ed163_nota = 'null';
        $nota = $diarioAvaliacao->getNota();
        if (!is_null($nota) && $nota !== '') {
            $dao->ed163_nota = $nota;
        }
        $dao->ed163_parecer = !is_null($diarioAvaliacao->getParecer()) ? $diarioAvaliacao->getParecer() : '';
        $dao->ed163_conceito = !is_null($diarioAvaliacao->getConceito()) ? $diarioAvaliacao->getConceito() : '';
        $dao->ed163_amparado = $diarioAvaliacao->isAmparado() ? 'true' : 'false';

        if (empty($dao->ed163_codigo)) {
            $dao->incluir(null);
        } else {
            $dao->alterar($dao->ed163_codigo);
        }

        if ($dao->erro_status == 0) {
            throw new Exception("Erro ao salvar avaliação da área de conhecimento.");
        }

        $diarioAvaliacao->setCodigo($dao->ed163_codigo);
        return $diarioAvaliacao;
    }
}
