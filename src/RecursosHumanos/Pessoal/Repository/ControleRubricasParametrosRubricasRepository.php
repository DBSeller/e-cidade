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

use cl_controlehorasextrasrubricas;
use DBCompetencia;
use ECidade\RecursosHumanos\Pessoal\Model\ControleRubricasParametros;
use ECidade\RecursosHumanos\Pessoal\Model\ControleRubricasParametrosRubricas;
use Instituicao;
use Exception;
use Rubrica;

class ControleRubricasParametrosRubricasRepository
{
    /**
     * @var Object
     */
    private $dao;

    /**
     * @var array
     */
    private $scopes = array();

    /**
     * @var array
     */
    private $order = array();

    /**
     * ControleHorasExtrasRepository constructor.
     */
    public function __construct()
    {
        $this->dao = new cl_controlehorasextrasrubricas();
    }

    /**
     * @param Instituicao $instituicao
     * @param string $operator
     * @return ControleRubricasParametrosRubricasRepository
     */
    public function scopeInstituicao(Instituicao $instituicao, $operator = ' = ')
    {
        $this->scopes['instituicao'] = " rh233_instituicao {$operator} {$instituicao->getCodigo()} ";
        return $this;
    }

    /**
     * @param Rubrica $rubrica
     * @param string $operator
     * @return ControleRubricasParametrosRubricasRepository
     */
    public function scopeRubrica(Rubrica $rubrica, $operator = ' = ')
    {
        $this->scopes['rubrica'] = " rh233_rubrica {$operator} {$rubrica->getCodigo()} ";
        return $this;
    }

    /**
     * @param boolean $permiteExclusao
     * @param string $operator
     * @return ControleRubricasParametrosRubricasRepository
     */
    public function scopePermiteExclusao($permiteExclusao, $operator = ' = ')
    {
        $this->scopes['permiteExclusao'] = " rh233_permite_exclusao {$operator} {$permiteExclusao} ";
        return $this;
    }

    /**
     * @param string $name
     * @param string $query
     * @return ControleRubricasParametrosRubricasRepository
     */
    public function scopeQuery($name, $query)
    {
        $this->scopes[$name] = $query;
        return $this;
    }

    /**
     * @param $campo
     * @param $direction
     */
    public function order($campo, $direction = 'DESC')
    {
        $this->order[$campo] = "{$campo} {$direction}";
    }

    /**
     * @param $key
     * @return ControleRubricasParametrosRubricasRepository
     */
    public function removeScope($key)
    {
        if (array_key_exists($key, $this->scopes)) {
            unset($this->scopes[$key]);
        }

        return $this;
    }

    public function clearScopes()
    {
        $this->scopes = array();
    }

    public function clearOrder()
    {
        $this->order = array();
    }

    public function reset()
    {
        $this->clearScopes();
        $this->clearOrder();
    }

    /**
     * @param ControleRubricasParametros $controleHorasExtras
     * @return array|ControleRubricasParametrosRubricas[]
     * @throws Exception
     */
    public function buscarPorControleHorasExtras(ControleRubricasParametros $controleHorasExtras)
    {
        $controleHorasExtrasRubricas = array();
        $where = "rh233_controlehorasextras = {$controleHorasExtras->getSequencial()}";
        $sql = $this->dao->sql_query_file(null, '*', 'rh233_rubrica', $where);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar a(s) rubrica(s) vinculadas ao controle de horas extras.");
        }

        if (pg_num_rows($rs) === 0) {
            return $controleHorasExtrasRubricas;
        }

        while ($dados = pg_fetch_array($rs)) {
            $controleHorasExtrasRubricas[] = ControleRubricasParametrosRubricas::fromState($dados);
        }

        return $controleHorasExtrasRubricas;
    }

    /**
     * @param ControleRubricasParametros $controleHorasExtras
     * @throws Exception
     */
    public function removerPorControleHorasExtras(ControleRubricasParametros $controleHorasExtras)
    {
        $where = array(
            "rh233_controlehorasextras = {$controleHorasExtras->getSequencial()}",
            "rh233_permite_exclusao is true"
        );

        $this->dao->excluir(null, implode(" AND ", $where));

        if ($this->dao->erro_status === '0') {
            throw new Exception("Erro ao excluir as rubricas vinculadas.");
        }
    }

    /**
     * @param ControleRubricasParametros $controleHorasExtras
     * @param ControleRubricasParametrosRubricas $controleHorasExtrasRubrica
     * @throws Exception
     */
    public function salvar(
        ControleRubricasParametros $controleHorasExtras,
        ControleRubricasParametrosRubricas $controleHorasExtrasRubrica
    ) {
        $this->dao->rh233_sequencial = null;
        $this->dao->rh233_controlehorasextras = $controleHorasExtras->getSequencial();
        $this->dao->rh233_instituicao = $controleHorasExtrasRubrica->getInstituicao()->getCodigo();
        $this->dao->rh233_rubrica = $controleHorasExtrasRubrica->getRubrica()->getCodigo();
        $this->dao->rh233_permite_exclusao = $controleHorasExtrasRubrica->isPermiteExclusao();
        $this->dao->incluir();

        if ($this->dao->erro_status === '0') {
            $codigoRubrica = $controleHorasExtrasRubrica->getRubrica()->getCodigo();
            throw new Exception("Não foi possível vincular a rubrica {$codigoRubrica}.");
        }
    }

    /**
     * @param string $campos
     * @return ControleRubricasParametrosRubricas[]
     * @throws Exception
     */
    public function get($campos = "*")
    {
        $sql = $this->dao->sql_query_file(
            null,
            $campos,
            null,
            implode(' and ', $this->scopes)
        );

        $rs = db_query($sql);
        if (!$rs) {
            throw new Exception("Não foi possível buscar a configuração das rubricas.");
        }

        if (pg_num_rows($rs) === 0) {
            return array();
        }

        $registros = array();
        while ($state = pg_fetch_array($rs)) {
            $registros[] = ControleRubricasParametrosRubricas::fromState($state);
        }

        $this->reset();

        return $registros;
    }

    /**
     * @param $id
     * @param array $columns
     * @return bool|ControleRubricasParametrosRubricas
     * @throws Exception
     */
    public function find($id, $columns = array('*'))
    {
        $sql = $this->dao->sql_query($id, implode(', ', $columns));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar a configuração das rubricas.");
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $resultado = pg_fetch_array($rs);
        return ControleRubricasParametrosRubricas::fromState($resultado);
    }

    /**
     * @param Instituicao $instituicao
     * @param DBCompetencia $competencia
     * @param Rubrica $rubrica
     * @return bool|ControleRubricasParametrosRubricas
     * @throws Exception
     */
    public function findOneByParams(Instituicao $instituicao, DBCompetencia $competencia, Rubrica $rubrica)
    {
        $sql = $this->dao->sql_query_instituicao_competencia(
            $instituicao->getCodigo(),
            $competencia->getAno(),
            $competencia->getMes(),
            trim($rubrica->getCodigo())
        );

        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar a configuração das rubricas.");
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $resultado = pg_fetch_array($rs);
        return ControleRubricasParametrosRubricas::fromState($resultado);
    }
}
