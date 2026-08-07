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
use cl_controlehorasextrasmatriculas;
use ECidade\RecursosHumanos\Pessoal\Model\ControleRubricasParametros;
use ECidade\RecursosHumanos\Pessoal\Model\ControleRubricasMatriculas;
use Exception;
use Instituicao;
use Servidor;

/**
 * Class ControleHorasExtrasMatriculasRepository
 * @package ECidade\RecursosHumanos\Pessoal\Repository
 */
class ControleRubricasMatriculasRepository
{
    /**
     * @var array
     */
    private $scopes = array();

    /**
     * @var cl_controlehorasextrasmatriculas
     */
    private $dao;

    /**
     * @var array
     */
    private $order = array();

    /**
     * ControleHorasExtrasMatriculasRepository
     * @param $dao cl_controlehorasextrasmatriculas
     */
    public function __construct($dao)
    {
        $this->dao = $dao;
    }

    /**
     * @param Instituicao $instituicao
     * @param string $operator
     * @return ControleRubricasMatriculasRepository
     */
    public function scopeInstituicao(Instituicao $instituicao, $operator = ' = ')
    {
        $this->scopes['instituicao'] = " rh234_instituicao {$operator} {$instituicao->getCodigo()} ";
        return $this;
    }

    /**
     * @param Servidor $servidor
     * @param string $operator
     * @return ControleRubricasMatriculasRepository
     */
    public function scopeServidor(Servidor $servidor, $operator = ' = ')
    {
        $this->scopes['matricula'] = " rh234_matricula {$operator} {$servidor->getMatricula()} ";
        return $this;
    }

    /**
     * @param int $ano
     * @param string $operator
     * @return ControleRubricasMatriculasRepository
     */
    public function scopeAno($ano, $operator = ' = ')
    {
        $this->scopes['ano'] = " rh234_ano {$operator} {$ano} ";
        return $this;
    }

    /**
     * @param int $mes
     * @param string $operator
     * @return ControleRubricasMatriculasRepository
     */
    public function scopeMes($mes, $operator = ' = ')
    {
        $this->scopes['mes'] = " rh234_mes {$operator} {$mes} ";
        return $this;
    }

    /**
     * @param string $name
     * @param string $query
     * @return ControleRubricasMatriculasRepository
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
     * @return ControleRubricasMatriculasRepository
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
     * @param string $campos
     * @return ControleRubricasMatriculas[]
     * @throws Exception
     */
    public function get($campos = "*")
    {
        $sql = $this->dao->sql_query_file(
            null,
            $campos,
            implode(' and ', $this->order),
            implode(' and ', $this->scopes)
        );

        $rs = db_query($sql);
        if (!$rs) {
            throw new Exception("Erro ao buscar os registros de controle de horas extras.");
        }

        if (pg_num_rows($rs) === 0) {
            return array();
        }

        $registros = array();
        while ($state = pg_fetch_array($rs)) {
            $registros[] = ControleRubricasMatriculas::fromState($state);
        }

        $this->reset();

        return $registros;
    }

    /**
     * @param $id
     * @param array $columns
     * @return bool|ControleRubricasMatriculas
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
        return ControleRubricasMatriculas::fromState($resultado);
    }

    /**
     * @param ControleRubricasMatriculas $controleHorasExtras
     * @return ControleRubricasMatriculas
     * @throws Exception
     */
    public function save(ControleRubricasMatriculas $controleHorasExtras)
    {
        $this->dao->rh234_sequencial = $controleHorasExtras->getSequencial();
        $this->dao->rh234_instituicao = $controleHorasExtras->getInstituicao()->getCodigo();
        $this->dao->rh234_matricula = $controleHorasExtras->getServidor()->getMatricula();
        $this->dao->rh234_ano = $controleHorasExtras->getAno();
        $this->dao->rh234_mes = $controleHorasExtras->getMes();
        $this->dao->rh234_horas_liberadas = $controleHorasExtras->getHorasLiberadas();

        if (!$controleHorasExtras->getSequencial()) {
            $this->dao->incluir(null);
        } else {
            $this->dao->alterar($controleHorasExtras->getSequencial());
        }

        if ($this->dao->erro_status === '0') {
            throw new Exception("Não foi possível salvar as informações.");
        }

        $controleHorasExtras->setSequencial($this->dao->rh234_sequencial);
        return $controleHorasExtras;
    }

    /**
     * @param ControleRubricasMatriculas $controleHorasExtrasMatriculas
     * @throws Exception
     */
    public function delete(ControleRubricasMatriculas $controleHorasExtrasMatriculas)
    {
        $this->dao->excluir($controleHorasExtrasMatriculas->getSequencial());

        if ($this->dao->erro_status === '0') {
            throw new Exception("Não foi possível excluir o registro de controle de horas extras.");
        }
    }

    /**
     * @return int
     * @throws Exception
     */
    public function destroy()
    {
        $rs = $this->dao->excluir(null, implode(' AND ', $this->scopes));

        if (!$rs) {
            throw new Exception('Não foi possível excluir os registros de controle de horas extras.');
        }

        $this->reset();

        return $this->dao->numrows_excluir;
    }

    /**
     * @param Instituicao $instituicao
     * @param int $ano
     * @param int $mes
     * @param null|int $matricula
     * @return ControleRubricasMatriculas[]
     * @throws Exception
     */
    public function buscaMatriculasConfiguradas(Instituicao $instituicao, $ano, $mes, $matricula = null)
    {
        $sql = $this->dao->sql_query_controle_horas_extras($instituicao->getCodigo(), $ano, $mes, $matricula);

        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar as matriculas configuradas.");
        }

        if (pg_num_rows($rs) === 0) {
            return array();
        }

        $registros = array();
        while ($state = pg_fetch_array($rs)) {
            $registros[] = ControleRubricasMatriculas::fromState($state);
        }

        $this->reset();

        return $registros;
    }

    /**
     * @param Instituicao $instituicao
     * @param $ano
     * @param $mes
     * @param $matricula
     * @return null|ControleRubricasMatriculas
     * @throws Exception
     */
    public function buscaConfiguracoesMatricula(Instituicao $instituicao, $ano, $mes, $matricula)
    {
        $configuracoesMatricula = $this->buscaMatriculasConfiguradas($instituicao, $ano, $mes, $matricula);
        return array_pop($configuracoesMatricula);
    }
}
