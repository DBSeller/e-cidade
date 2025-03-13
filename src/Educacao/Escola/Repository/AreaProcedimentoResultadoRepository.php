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

use cl_areaprocedimentoresultado;
use ECidade\Educacao\Escola\Model\AreaProcedimento;
use ECidade\Educacao\Escola\Model\AreaProcedimentoResultado;
use Exception;

/**
 * Class AreaProcedimentoResultado
 * @package ECidade\Educacao\Escola\Repository
 */
class AreaProcedimentoResultadoRepository extends Repository
{
    /**
     * @return AreaProcedimentoResultado[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_areaprocedimentoresultado();
        $sql = $dao->sql_query_file(null, '*', null, implode(' and ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar Procedimentos da Area.");
        }

        $procedimentoResultados = [];
        while ($state = pg_fetch_array($rs)) {
            $areaProcedimentoResultado = AreaProcedimentoResultado::fromState($state);
            self::buscarComposicao($areaProcedimentoResultado);
            $procedimentoResultados[] = $areaProcedimentoResultado;
        }

        return $procedimentoResultados;
    }

    /**
     * @param $key
     * @return AreaProcedimentoResultado
     * @throws Exception
     */
    public static function find($key)
    {
        $dao = new cl_areaprocedimentoresultado();
        $sql = $dao->sql_query_file($key);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar Resultado do Procedimento da Area.");
        }

        $areaProcedimentoResultado = AreaProcedimentoResultado::fromState(pg_fetch_array($rs));
        self::buscarComposicao($areaProcedimentoResultado);
        return $areaProcedimentoResultado;
    }

    /**
     * @param AreaProcedimento $areaProcedimento
     * @return $this
     */
    public function scopeAreaProcedimento(AreaProcedimento $areaProcedimento)
    {
        $this->scopes['area_procedimento'] = "ed159_areaprocedimento = {$areaProcedimento->getCodigo()}";
        return $this;
    }

    /**
     * @param AreaProcedimentoResultado $areaProcedimentoResultado
     * @throws Exception
     */
    private static function buscarComposicao(AreaProcedimentoResultado $areaProcedimentoResultado)
    {
        $repositoryComposicao = new AreaProcedimentoComposicaoResultadoRepository();
        $areaProcedimentoResultado->setComposicao(
            $repositoryComposicao->scopeResultado($areaProcedimentoResultado)->get()
        );
    }

    /**
     * @return AreaProcedimentoResultado|null
     * @throws Exception
     */
    public function first()
    {
        $resultados = $this->get();
        if (empty($resultados)) {
            return null;
        }

        return array_shift($resultados);
    }

    public function salvar(AreaProcedimentoResultado $areaProcedimentoResultado)
    {
        $dao = new cl_areaprocedimentoresultado();
        $dao->ed159_codigo = $areaProcedimentoResultado->getCodigo();
        $dao->ed159_areaprocedimento = $areaProcedimentoResultado->getAreaProcedimento()->getCodigo();
        $dao->ed159_formaavaliacao = $areaProcedimentoResultado->getFormaAvaliacao()->getCodigo();
        $dao->ed159_resultado = $areaProcedimentoResultado->getTipoResultado()->getCodigo();
        $dao->ed159_formaobtencao = $areaProcedimentoResultado->getFormaObtencao()->getValue();

        if (empty($dao->ed159_codigo)) {
            $dao->incluir(null);
        } else {
            $dao->alterar($dao->ed159_codigo);
        }
        if ($dao->erro_status == 0) {
            throw new Exception("Erro ao salvar Resultado do Procedimento da Area.");
        }

        $areaProcedimentoResultado->setCodigo($dao->ed159_codigo);

        return $areaProcedimentoResultado;
    }

    /**
     * @param AreaProcedimentoResultado $areaProcedimentoResultado
     * @return bool
     * @throws Exception
     */
    public function excluir(AreaProcedimentoResultado $areaProcedimentoResultado)
    {
        $dao = new cl_areaprocedimentoresultado();
        $dao->excluir($areaProcedimentoResultado->getCodigo());

        if ($dao->erro_status == 0) {
            throw new Exception("Erro ao excluir Resultado do Procedimento da Area.");
        }

        return true;
    }
}
