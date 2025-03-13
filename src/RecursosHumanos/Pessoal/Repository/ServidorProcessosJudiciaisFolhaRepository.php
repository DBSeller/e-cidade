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

use cl_rhpessoalprocessosjudiciais;
use ECidade\RecursosHumanos\Pessoal\Model\ServidorProcessosJudiciaisFolha;
use Exception;
use Instituicao;
use Servidor;

/**
 * Class ServidorProcessosJudiciaisFolhaRepository
 * @package ECidade\RecursosHumanos\Pessoal\Repository
 */
class ServidorProcessosJudiciaisFolhaRepository
{
    /**
     * @var array
     */
    private $scopes = array();

    /**
     * @param array|int $ids
     * @return int
     * @throws Exception
     */
    public static function destroy($ids)
    {
        $count = 0;
        $ids = is_array($ids) ? $ids : func_get_args();

        $self = new self();

        foreach ($ids as $id) {
            $self->delete(self::find($id));
            $count++;
        }

        return $count;
    }

    /**
     * @param ServidorProcessosJudiciaisFolha|null $servidorProcessosJudiciaisFolha
     * @throws Exception
     */
    public function delete(ServidorProcessosJudiciaisFolha $servidorProcessosJudiciaisFolha = null)
    {
        $id = $servidorProcessosJudiciaisFolha instanceof ServidorProcessosJudiciaisFolha ? $servidorProcessosJudiciaisFolha->getSequencial() : null;

        $dao = new cl_rhpessoalprocessosjudiciais();
        $dao->excluir($id, implode(' AND ', $this->scopes));

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível excluir.\nContate o suporte.");
        }
    }

    /**
     * @param $id
     * @param array $columns
     * @return bool|ServidorProcessosJudiciaisFolha
     * @throws Exception
     */
    public static function find($id, $columns = array('*'))
    {
        $dao = new cl_rhpessoalprocessosjudiciais();
        $sql = $dao->sql_query($id, implode(', ', $columns));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar o(s) processo(s) judicial(ais) do servidor.\nContate o suporte.");
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $resultado = pg_fetch_array($rs);

        return ServidorProcessosJudiciaisFolha::fromState($resultado);
    }

    /**
     * @param array $columns
     * @return ServidorProcessosJudiciaisFolha[]
     * @throws Exception
     */
    public function all($columns = array('*'))
    {
        $dao = new cl_rhpessoalprocessosjudiciais();
        $sql = $dao->sql_query(null, implode(', ', $columns));
        $rs = db_query($sql);

        $servidorProcessosJudiciaisFolha = array();

        if (pg_num_rows($rs) === 0) {
            return $servidorProcessosJudiciaisFolha;
        }

        while ($processo = pg_fetch_array($rs)) {
            $servidorProcessosJudiciaisFolha[] = ServidorProcessosJudiciaisFolha::fromState($processo);
        }

        return $servidorProcessosJudiciaisFolha;
    }

    /**
     * @return ServidorProcessosJudiciaisFolha[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_rhpessoalprocessosjudiciais();
        $sql = $dao->sql_query(null, '*', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar o(s) processo(s) judicial(ais) do servidor.\nContate o suporte.");
        }

        $servidorProcessosJudiciaisFolha = array();

        if (pg_num_rows($rs) === 0) {
            return $servidorProcessosJudiciaisFolha;
        }

        while ($processo = pg_fetch_array($rs)) {
            $servidorProcessosJudiciaisFolha[] = ServidorProcessosJudiciaisFolha::fromState($processo);
        }

        return $servidorProcessosJudiciaisFolha;
    }

    /**
     * @return int
     * @throws Exception
     */
    public function count()
    {
        $dao = new cl_rhpessoalprocessosjudiciais();
        $sql = $dao->sql_query(null, 'count(*)', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar o total de processos do servidor.\nContate o suporte.");
        }

        return (int)pg_fetch_result($rs, 0, 'count');
    }

    /**
     * @param ServidorProcessosJudiciaisFolha $servidorProcessosJudiciaisFolha
     * @return ServidorProcessosJudiciaisFolha
     * @throws Exception
     */
    public function save(ServidorProcessosJudiciaisFolha $servidorProcessosJudiciaisFolha)
    {
        $dao = new cl_rhpessoalprocessosjudiciais();
        $dao->rh226_sequencial = $servidorProcessosJudiciaisFolha->getSequencial();
        $dao->rh226_ano = $servidorProcessosJudiciaisFolha->getAno();
        $dao->rh226_mes = $servidorProcessosJudiciaisFolha->getMes();
        $dao->rh226_matricula = $servidorProcessosJudiciaisFolha->getServidor()->getMatricula();
        $dao->rh226_instituicao = $servidorProcessosJudiciaisFolha->getInstituicao()->getCodigo();
        $dao->rh226_tipoprocesso = $servidorProcessosJudiciaisFolha->getTipoProcesso();
        $dao->rh226_numero = $servidorProcessosJudiciaisFolha->getNumeroProcesso();
        $dao->rh226_indicativosuspensao = $servidorProcessosJudiciaisFolha->getCodigoIndicativoSuspensao();
        $dao->rh226_sequencial ? $dao->alterar($servidorProcessosJudiciaisFolha->getSequencial()) : $dao->incluir(null);

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível o outro vínculo do servidor.\nContate o suporte.");
        }

        $servidorProcessosJudiciaisFolha->setSequencial($dao->rh226_sequencial);

        return $servidorProcessosJudiciaisFolha;
    }

    /**
     * @param $sequencial
     * @param string $operator
     * @return $this
     */
    public function scopeSequencial($sequencial, $operator = '=') {
        $this->scopes['sequencial'] = "rh226_sequencial {$operator} {$sequencial}";
        return $this;
    }

    /**
     * @param $instituicao
     * @param string $operator
     * @return $this
     */
    public function scopeInstituicao(Instituicao $instituicao, $operator = '=') {
        $this->scopes['instituicao'] = "rh226_instituicao {$operator} {$instituicao->getCodigo()}";
        return $this;
    }

    /**
     * @param $ano
     * @param string $operator
     * @return $this
     */
    public function scopeAno($ano, $operator = '=') {
        $this->scopes['ano'] = "rh226_ano {$operator} {$ano}";
        return $this;
    }

    /**
     * @param $mes
     * @param string $operator
     * @return $this
     */
    public function scopeMes($mes, $operator = '=') {
        $this->scopes['mes'] = "rh226_mes {$operator} {$mes}";
        return $this;
    }

    /**
     * @param $servidor
     * @param string $operator
     * @return $this
     */
    public function scopeServidor(Servidor $servidor, $operator = '=') {
        $this->scopes['servidor'] = "rh226_matricula {$operator} {$servidor->getMatricula()}";
        return $this;
    }

    /**
     * @param $tipoProcesso
     * @param string $operator
     * @return $this
     */
    public function scopeTipoProcesso($tipoProcesso, $operator = '=') {
        $this->scopes['tipoProcesso'] = "rh226_tipoprocesso {$operator} {$tipoProcesso}";
        return $this;
    }

    /**
     * @param $numeroProcesso
     * @param string $operator
     * @return $this
     */
    public function scopeNumeroProcesso($numeroProcesso, $operator = '=') {
        $this->scopes['numeroProcesso'] = "rh226_numero {$operator} {$numeroProcesso}";
        return $this;
    }

    /**
     * @param $indicativoSuspensao
     * @param string $operator
     * @return $this
     */
    public function scopeIndicativoSuspensao($indicativoSuspensao, $operator = '=') {
        $this->scopes['indicativoSuspensao'] = "rh226_indicativosuspensao {$operator} {$indicativoSuspensao}";
        return $this;
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
     * @return ServidorProcessosJudiciaisFolhaRepository
     */
    public function removeScope($key)
    {
        if (array_key_exists($key, $this->scopes)) {
            unset($this->scopes[$key]);
        }

        return $this;
    }
}
