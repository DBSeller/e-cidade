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

use ECidade\Educacao\Escola\Model\AreaConhecimento;
use ECidade\Educacao\Escola\Model\DiarioAluno;
use ECidade\Educacao\Escola\Model\DiarioArea;
use ECidade\Educacao\Escola\Model\DiarioAreaResultado;
use Exception;

/**
 * Class DiarioAreaRepository
 * @package ECidade\Educacao\Escola\Repository
 */
class DiarioAreaRepository extends Repository
{
    private static function buscarResultado(DiarioArea $diarioArea)
    {
        $repository = new DiarioAreaResultadoRepository();
        $diarioAreaResultado = $repository->scopeDiarioArea($diarioArea)->first();
        if (!is_null($diarioAreaResultado)) {
            $diarioArea->setResultado($diarioAreaResultado);
        }
    }

    private static function buscarAvaliacoes(DiarioArea $diarioArea)
    {
        $repository = new DiarioAreaAvaliacaoRepository();
        $diarioArea->setAvaliacoes($repository->scopeDiarioArea($diarioArea)->get());
    }

    /**
     * @param $key
     * @return DiarioArea
     * @throws Exception
     */
    public static function find($key)
    {
        $dao = new \cl_diarioarea();
        $sql = $dao->sql_query_file($key);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar o diário da área.");
        }

        return DiarioArea::fromState(pg_fetch_array($rs));
    }

    /**
     * @return DiarioArea[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new \cl_diarioarea();
        $sql = $dao->sql_query_file(null, '*', null, implode(' and ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar o diário da área.");
        }

        $diarios = [];
        while ($state = pg_fetch_array($rs)) {
            $diarioArea = DiarioArea::fromState($state);
            self::buscarAvaliacoes($diarioArea);
            self::buscarResultado($diarioArea);

            $diarios[] = $diarioArea;
        }

        return $diarios;
    }

    /**
     * @return DiarioArea|null
     * @throws Exception
     */
    public function first()
    {
        $areas = $this->get();
        if (empty($areas)) {
            return null;
        }

        return array_shift($areas);
    }

    /**
     * @param DiarioAluno $diarioAluno
     * @return $this
     */
    public function scopeDiarioAluno(DiarioAluno $diarioAluno)
    {
        $this->scopes['diario_aluno'] = "ed162_diarioaluno = {$diarioAluno->getCodigo()}";
        return $this;
    }

    public function scopeAreaConhecimento(AreaConhecimento $areaConhecimento)
    {
        $this->scopes['area_conhecimento'] = "ed162_areaconhecimento = {$areaConhecimento->getCodigo()}";
        return $this;
    }


    /**
     * @param DiarioAluno $diarioAluno
     * @param AreaConhecimento $areaConhecimento
     * @return DiarioArea
     * @throws Exception
     */
    public function findOrCreate(DiarioAluno $diarioAluno, AreaConhecimento $areaConhecimento)
    {
        $diarioArea = $this->scopeDiarioAluno($diarioAluno)
            ->scopeAreaConhecimento($areaConhecimento)
            ->first();

        if (is_null($diarioArea)) {
            $diarioArea = new DiarioArea();
            $diarioArea->setAreaConhecimento($areaConhecimento);
            $diarioArea->setDiarioAluno($diarioAluno);

            $diarioArea = $this->salvar($diarioArea);
        }


        return $diarioArea;
    }


    /**
     * @param DiarioArea $diarioArea
     * @return DiarioArea
     * @throws Exception
     */
    public function salvar(DiarioArea $diarioArea)
    {
        $dao = new \cl_diarioarea();
        $dao->ed162_codigo = $diarioArea->getCodigo();
        $dao->ed162_areaconhecimento = $diarioArea->getAreaConhecimento()->getCodigo();
        $dao->ed162_diarioaluno = $diarioArea->getDiarioAluno()->getCodigo();

        if (empty($dao->ed162_codigo)) {
            $dao->incluir(null);
        } else {
            $dao->alterar($dao->ed162_codigo);
        }

        if ($dao->erro_status == 0) {
            throw new Exception("Erro ao salvar o área de conhecimento do diário do aluno.");
        }

        $diarioArea->setCodigo($dao->ed162_codigo);

        return $diarioArea;
    }
}
