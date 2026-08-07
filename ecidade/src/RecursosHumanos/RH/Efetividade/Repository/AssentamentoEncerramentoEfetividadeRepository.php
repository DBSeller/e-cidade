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

namespace ECidade\RecursosHumanos\RH\Efetividade\Repository;

use cl_assentamentosencerramentoefetividade;
use ECidade\RecursosHumanos\RH\Efetividade\Model\AssentamentoEncerramentoEfetividade;
use Exception;
use Instituicao;

class AssentamentoEncerramentoEfetividadeRepository
{
    /**
     * @var bool
     */
    protected $useJoin = false;

    /**
     * @var array
     */
    protected $scopes = array();

    /**
     * @param AssentamentoEncerramentoEfetividade $assentamentoEncerramentoEfetividade
     * @return AssentamentoEncerramentoEfetividade
     * @throws Exception
     */
    public static function save(AssentamentoEncerramentoEfetividade $assentamentoEncerramentoEfetividade)
    {
        $dao = new cl_assentamentosencerramentoefetividade();
        $dao->rh230_sequencial = $assentamentoEncerramentoEfetividade->getSequencial();
        $dao->rh230_assentamento = $assentamentoEncerramentoEfetividade->getAssentamento()->getCodigo();
        $dao->rh230_ano = $assentamentoEncerramentoEfetividade->getAno();
        $dao->rh230_mes = $assentamentoEncerramentoEfetividade->getMes();
        $dao->rh230_instituicao = $assentamentoEncerramentoEfetividade->getInstituicao()->getCodigo();

        $assentamentoEncerramentoEfetividade->getSequencial()
            ? $dao->alterar($assentamentoEncerramentoEfetividade->getSequencial())
            : $dao->incluir(null);

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível salvar o assentamento de efetividade.\nContate o suporte.");
        }

        $assentamentoEncerramentoEfetividade->setSequencial($dao->rh230_sequencial);

        return $assentamentoEncerramentoEfetividade;
    }

    /**
     * @param bool $useJoin
     * @return ColunaRepositorio
     */
    public function setUseJoin($useJoin)
    {
        $this->useJoin = (bool)$useJoin;
        return $this;
    }

    /**
     * @param int $ano
     * @param string $operator
     * @return $this
     */
    public function scopeAno($ano, $operator = '=')
    {
        $this->scopes['rh230_ano'] = "rh230_ano {$operator} {$ano}";
        return $this;
    }

    /**
     * @param string $mes
     * @param string $operator
     * @return $this
     */
    public function scopeMes($mes, $operator = '=')
    {
        if (mb_strtoupper($operator) === 'IN') {
            $mes = is_array($mes) ? $mes : array($mes);
            $mes = implode("'', '", $mes);
            $mes = "('{$mes}')";
        } else {
            $mes = "'{$mes}'";
        }

        $this->scopes['rh230_mes'] = "rh230_mes {$operator} {$mes}";
        return $this;
    }

    /**
     * @param int $instituicao
     * @param string $operator
     * @return $this
     */
    public function scopeInstituicao(Instituicao $instituicao, $operator = '=')
    {
        $this->scopes['rh230_instituicao'] = "rh230_instituicao {$operator} {$instituicao->getCodigo()}";
        return $this;
    }

    /**
     * @param string $matricula
     * @param string $operator
     * @return $this
     */
    public function scopeMatricula($matricula, $operator = '=')
    {
        if (mb_strtoupper($operator) === 'IN') {
            $matricula = is_array($matricula) ? $matricula : array($matricula);
            $matricula = implode("', '", $matricula);
            $matricula = "('{$matricula}')";
        } else {
            $matricula = "'{$matricula}'";
        }

        $this->scopes['h16_regist'] = "h16_regist {$operator} {$matricula}";
        return $this;
    }

    /**
     * @param array $columns
     * @return AssentamentoEncerramentoEfetividade[]
     * @throws Exception
     */
    public function get($columns = array('*'))
    {
        $dao = new cl_assentamentosencerramentoefetividade();

        if ($this->useJoin) {
            $dao->addJoin(
                'assenta',
                'h16_codigo',
                '=',
                'rh230_assentamento'
            );
        }

        $order = array("h16_assent", "h16_regist");
        $sql = $dao->sql($columns, $this->scopes, $order);
        $resultado = db_query($sql);

        if (!$resultado) {
            throw new Exception(
                "Não foi possível buscar os assentamentos do encerramento da efetividade.\nContate o suporte."
            );
        }

        $registros = array();

        if (pg_num_rows($resultado) === 0) {
            return $registros;
        }

        while ($registro = pg_fetch_array($resultado)) {
            $registros[] = AssentamentoEncerramentoEfetividade::fromState($registro);
        }

        return $registros;
    }

    /**
     * @param AssentamentoEncerramentoEfetividade|null $assentamentoEncerramentoEfetividade
     * @throws Exception
     */
    public function delete(AssentamentoEncerramentoEfetividade $assentamentoEncerramentoEfetividade = null)
    {
        $id = $assentamentoEncerramentoEfetividade instanceof AssentamentoEncerramentoEfetividade
            ? $assentamentoEncerramentoEfetividade->getSequencial()
            : null;

        $dao = new cl_assentamentosencerramentoefetividade();
        $dao->excluir($id, implode(' AND ', $this->scopes));

        if ($dao->erro_status === '0') {
            throw new Exception(
                "Não foi possível excluir os assentamentos do encerramento da efetividade.\nContate o suporte."
            );
        }
    }

    /**
     * @return $this
     */
    public function resetScopes()
    {
        $this->scopes = array();

        return $this;
    }

    /**
     * @param $key
     * @return $this
     */
    public function removeScope($key)
    {
        if (array_key_exists($key, $this->scopes)) {
            unset($this->scopes[$key]);
        }

        return $this;
    }
}
