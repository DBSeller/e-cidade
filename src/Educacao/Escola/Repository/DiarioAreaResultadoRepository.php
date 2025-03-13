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

use cl_diarioresultado;
use ECidade\Educacao\Escola\Model\AreaProcedimentoResultado;
use ECidade\Educacao\Escola\Model\DiarioArea;
use ECidade\Educacao\Escola\Model\DiarioAreaResultado;
use Exception;

/**
 * Class DiarioAreaResultadoRepository
 * @package ECidade\Educacao\Escola\Repository
 */
class DiarioAreaResultadoRepository extends Repository
{
    /**
     * @param $key
     * @return DiarioAreaResultado
     * @throws Exception
     */
    public static function find($key)
    {
        $dao = new \cl_diarioarearesultado();
        $sql = $dao->sql_query_file($key);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar resultado da área.");
        }

        return DiarioAreaResultado::fromState(pg_fetch_array($rs));
    }

    /**
     * @return array
     * @throws Exception
     */
    public function get()
    {
        $dao = new \cl_diarioarearesultado();
        $sql = $dao->sql_query_file(null, '*', null, implode(' and ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar resultado da área.");
        }

        $resultados = [];
        while ($state = pg_fetch_array($rs)) {
            $resultados[] = DiarioAreaResultado::fromState($state);
        }

        return $resultados;
    }

    /**
     * @return DiarioAreaResultado|null
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

    /**
     * @param DiarioArea $diarioArea
     * @return DiarioAreaResultadoRepository
     */
    public function scopeDiarioArea(DiarioArea $diarioArea)
    {
        $this->scopes['diario_area'] = "ed164_diarioarea = {$diarioArea->getCodigo()}";
        return $this;
    }

    /**
     * @param AreaProcedimentoResultado $procedimentoResultado
     * @return DiarioAreaResultadoRepository
     */
    public function scopeAreaProcedimentoResultado(AreaProcedimentoResultado $procedimentoResultado)
    {
        $this->scopes['procedimento'] = "ed164_areaprocedimentoresultado = {$procedimentoResultado->getCodigo()}";
        return $this;
    }

    /**
     * @param DiarioArea $diarioArea
     * @param AreaProcedimentoResultado $procedimentoResultado
     * @return DiarioAreaResultado
     * @throws Exception
     */
    public function findOrCreate(DiarioArea $diarioArea, AreaProcedimentoResultado $procedimentoResultado)
    {
        $resultadoArea = $this->scopeDiarioArea($diarioArea)
            ->scopeAreaProcedimentoResultado($procedimentoResultado)
            ->first();

        if (is_null($resultadoArea)) {
            $diarioResultado = new DiarioAreaResultado();
            $diarioResultado->setDiarioArea($diarioArea)
                ->setAreaProcedimentoResultado($procedimentoResultado);

            $diarioResultado = $this->salvar($diarioResultado);
        }

        return $diarioResultado;
    }

    public function salvar(DiarioAreaResultado $diarioResultado)
    {
        $dao = new \cl_diarioarearesultado();
        $dao->ed164_codigo = $diarioResultado->getCodigo();
        $dao->ed164_diarioarea = $diarioResultado->getDiarioArea()->getCodigo();
        $dao->ed164_areaprocedimentoresultado = $diarioResultado->getAreaProcedimentoResultado()->getCodigo();
        $dao->ed164_nota = 'null';
        $nota = $diarioResultado->getNota();
        if (!is_null($nota) && $nota !== '') {
            $dao->ed164_nota = $nota;
        }
        $parecer = $diarioResultado->getParecer();
        if (!is_null($parecer) && $parecer !== '') {
            $dao->ed164_parecer = $parecer;
        }

        $conceito = $diarioResultado->getConceito();
        if (!is_null($conceito) && $conceito !== '') {
            $dao->ed164_conceito = $conceito;
        }

        $dao->ed164_amparado = $diarioResultado->isAmparado() ? 'true' : 'false';
        $dao->ed164_resultado_avaliacao = $diarioResultado->getResultadoAvaliacao();
        $dao->ed164_resultado_frequencia = $diarioResultado->getResultadoFrequencia();

        if (empty($dao->ed164_codigo)) {
            $dao->incluir(null);
        } else {
            $dao->alterar($dao->ed164_codigo);
        }

        if ($dao->erro_status == 0) {
            throw new Exception("Erro ao salvar resultado da área de conhecimento.");
        }

        $diarioResultado->setCodigo($dao->ed164_codigo);
        return $diarioResultado;
    }
}
