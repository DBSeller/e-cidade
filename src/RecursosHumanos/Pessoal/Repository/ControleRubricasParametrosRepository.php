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

namespace ECidade\RecursosHumanos\Pessoal\Repository;

use cl_controlehorasextras;
use DBCompetencia;
use ECidade\RecursosHumanos\Pessoal\Model\ControleRubricasParametros;
use Exception;
use Instituicao;

/**
 * Class ControleHorasExtrasRepository
 * @package ECidade\RecursosHumanos\Pessoal\Repository
 */
class ControleRubricasParametrosRepository
{
    /**
     * @var array
     */
    private $scopes = array();

    /**
     * @var Object
     */
    private $dao;

    /**
     * ControleHorasExtrasRepository constructor.
     * @param cl_controlehorasextras $dao
     */
    public function __construct(cl_controlehorasextras $dao)
    {
        $this->dao = $dao;
    }

    /**
     * @param $id
     * @param array $columns
     * @return bool|ControleRubricasParametros
     * @throws Exception
     */
    public function find($id, $columns = array('*'))
    {
        $sql = $this->dao->sql_query($id, implode(', ', $columns));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar o controle de horas extras.");
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $resultado = pg_fetch_array($rs);

        $controleHorasExtras = ControleRubricasParametros::fromState($resultado);

        return $controleHorasExtras;
    }

    /**
     * @param Instituicao $instituicao
     * @param DBCompetencia $competencia
     * @return bool|ControleRubricasParametros
     * @throws Exception
     */
    public function buscarPorInstituicaoECompetencia(
        Instituicao $instituicao,
        DBCompetencia $competencia
    ) {
        $where = array(
            "rh232_instituicao = {$instituicao->getCodigo()}",
            "rh232_ano = {$competencia->getAno()}",
            "rh232_mes = {$competencia->getMes()}"
        );

        $sql = $this->dao->sql_query_file(null, '*', null, implode(" AND ", $where));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar o controle de horas extras.");
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $resultado = pg_fetch_array($rs);
        $controleHorasExtras = ControleRubricasParametros::fromState($resultado);
        return $controleHorasExtras->withRubricas();
    }

    /**
     * @param ControleRubricasParametros $controleHorasExtras
     * @return ControleRubricasParametros
     * @throws Exception
     */
    public function salvar(
        ControleRubricasParametros $controleHorasExtras
    ) {
        $this->dao->rh232_sequencial = $controleHorasExtras->getSequencial();
        $this->dao->rh232_instituicao = $controleHorasExtras->getInstituicao()->getCodigo();
        $this->dao->rh232_selecao = $controleHorasExtras->getSelecao()->getCodigo();
        $this->dao->rh232_ano = $controleHorasExtras->getAno();
        $this->dao->rh232_mes = $controleHorasExtras->getMes();

        if (!$controleHorasExtras->getSequencial()) {
            $this->dao->incluir(null);
        } else {
            $this->dao->alterar($controleHorasExtras->getSequencial());
        }

        if ($this->dao->erro_status === '0') {
            throw new Exception("Não foi possível salvar as informações.");
        }

        $controleHorasExtras->setSequencial($this->dao->rh232_sequencial);

        $this->associarControleHorasExtrasRubricas($controleHorasExtras);

        return $controleHorasExtras;
    }

    /**
     * @param $key
     * @return ControleRubricasParametrosRepository
     */
    public function removeScope($key)
    {
        if (array_key_exists($key, $this->scopes)) {
            unset($this->scopes[$key]);
        }
        return $this;
    }

    /**
     * @param ControleRubricasParametros $controleHorasExtras
     * @throws Exception
     */
    private function associarControleHorasExtrasRubricas(ControleRubricasParametros $controleHorasExtras)
    {
        $repository = new ControleRubricasParametrosRubricasRepository();
        $repository->removerPorControleHorasExtras($controleHorasExtras);

        if ($controleHorasExtras->getControleHorasExtrasRubricas()) {
            foreach ($controleHorasExtras->getControleHorasExtrasRubricas() as $controleHorasExtrasRubrica) {
                $repository->salvar($controleHorasExtras, $controleHorasExtrasRubrica);
            }
        }
    }

    /**
     * @param ControleRubricasParametros $controleHorasExtras
     * @throws Exception
     */
    public function remover(ControleRubricasParametros $controleHorasExtras)
    {
        $repository = new ControleRubricasParametrosRubricasRepository();
        $repository->removerPorControleHorasExtras($controleHorasExtras);

        $this->dao->excluir($controleHorasExtras->getSequencial());

        if ($this->dao->erro_status === '0') {
            throw new Exception("Não foi possível excluir o controle de horas extras.");
        }
    }
}
