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

use cl_areaprocedimentocomposicaoresultado;
use ECidade\Educacao\Escola\Model\AreaProcedimentoComposicaoResultado;
use ECidade\Educacao\Escola\Model\AreaProcedimentoResultado;
use Exception;

/**
 * Class AreaProcedimentoComposicaoResultadoRepository
 * @package ECidade\Educacao\Escola\Repository
 */
class AreaProcedimentoComposicaoResultadoRepository extends Repository
{
    public function get()
    {
        $dao = new cl_areaprocedimentocomposicaoresultado();
        $sql = $dao->sql_query_file(null, '*', null, implode(' and ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar Composição de Resultados.");
        }

        $procedimentoResultados = [];
        while ($state = pg_fetch_array($rs)) {
            $procedimentoResultados[] = AreaProcedimentoComposicaoResultado::fromState($state);
        }

        return $procedimentoResultados;
    }

    /**
     * @param AreaProcedimentoResultado $areaProcedimentoResultado
     * @return $this
     */
    public function scopeResultado(AreaProcedimentoResultado $areaProcedimentoResultado)
    {
        $this->scopes['resultado'] = "ed160_areaprocedimentoresultado = {$areaProcedimentoResultado->getCodigo()}";
        return $this;
    }

    /**
     * @param AreaProcedimentoComposicaoResultado $composicaoResultado
     * @return AreaProcedimentoComposicaoResultado
     * @throws Exception
     */
    public function salvar(AreaProcedimentoComposicaoResultado $composicaoResultado)
    {
        $dao = new cl_areaprocedimentocomposicaoresultado();
        $dao->ed160_areaprocedimentoresultado = $composicaoResultado->getAreaProcedimentoResultado()->getCodigo();
        $dao->ed160_areaprocedimentoavaliacao = $composicaoResultado->getAreaProcedimentoAvaliacao()->getCodigo();
        $dao->incluir(null);

        if ($dao->erro_status == 0) {
            throw new Exception("Erro ao incluir Composição de Resultado do Area.");
        }
        $composicaoResultado->setCodigo($dao->ed160_codigo);
        return $composicaoResultado;
    }
}
