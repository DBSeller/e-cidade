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

use cl_rhpessoaloutrosvinculos;
use ECidade\RecursosHumanos\Pessoal\Model\ServidorOutrosVinculos;
use Exception;
use Instituicao;
use Servidor;

/**
 * Class ServidorOutrosVinculosRepository
 * @package ECidade\RecursosHumanos\Pessoal\Repository
 */
class ServidorOutrosVinculosRepository
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
     * @param ServidorOutrosVinculos|null $servidorOutrosVinculos
     * @throws Exception
     */
    public function delete(ServidorOutrosVinculos $servidorOutrosVinculos = null)
    {
        $id = $servidorOutrosVinculos instanceof ServidorOutrosVinculos ? $servidorOutrosVinculos->getSequencial() : null;

        $dao = new cl_rhpessoaloutrosvinculos();
        $dao->excluir($id, implode(' AND ', $this->scopes));

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível excluir.\nContate o suporte.");
        }
    }

    /**
     * @param $id
     * @param array $columns
     * @return bool|ServidorOutrosVinculos
     * @throws Exception
     */
    public static function find($id, $columns = array('*'))
    {
        $dao = new cl_rhpessoaloutrosvinculos();
        $sql = $dao->sql_query($id, implode(', ', $columns));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar o(s) outro(s) vínculo(s) do servidor.\nContate o suporte.");
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $resultado = pg_fetch_array($rs);

        return ServidorOutrosVinculos::fromState($resultado);
    }

    /**
     * @param array $columns
     * @return ServidorOutrosVinculos[]
     * @throws Exception
     */
    public function all($columns = array('*'))
    {
        $dao = new cl_rhpessoaloutrosvinculos();
        $sql = $dao->sql_query(null, implode(', ', $columns));
        $rs = db_query($sql);

        $servidoresOutrosVinculos = array();

        if (pg_num_rows($rs) === 0) {
            return $servidoresOutrosVinculos;
        }

        while ($servidorOutroVinculo = pg_fetch_array($rs)) {
            $servidoresOutrosVinculos[] = ServidorOutrosVinculos::fromState($servidorOutroVinculo);
        }

        return $servidoresOutrosVinculos;
    }

    /**
     * @return ServidorOutrosVinculos[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_rhpessoaloutrosvinculos();
        $sql = $dao->sql_query(null, '*', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar o(s) outro(s) vínculo(s) do servidor.\nContate o suporte.");
        }

        $servidorOutrosVinculos = array();

        if (pg_num_rows($rs) === 0) {
            return $servidorOutrosVinculos;
        }

        while ($servidorOutroVinculo = pg_fetch_array($rs)) {
            $servidorOutrosVinculos[] = ServidorOutrosVinculos::fromState($servidorOutroVinculo);
        }

        return $servidorOutrosVinculos;
    }

    /**
     * @return int
     * @throws Exception
     */
    public function count()
    {
        $dao = new cl_rhpessoaloutrosvinculos();
        $sql = $dao->sql_query(null, 'count(*)', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível o total de outros vínculos do servidor.\nContate o suporte.");
        }

        return (int)pg_fetch_result($rs, 0, 'count');
    }

    /**
     * @param ServidorOutrosVinculos $servidorOutrosVinculos
     * @return ServidorOutrosVinculos
     * @throws Exception
     */
    public function save(ServidorOutrosVinculos $servidorOutrosVinculos)
    {
        $dao = new cl_rhpessoaloutrosvinculos();
        $dao->rh224_sequencial = $servidorOutrosVinculos->getSequencial();
        $dao->rh224_tipocontribuicao = $servidorOutrosVinculos->getTipoContribuicao();
        $dao->rh224_tipoinscricao = $servidorOutrosVinculos->getTipoInscricao();
        $dao->rh224_numeroinscricao = $servidorOutrosVinculos->getNumeroInscricao();
        $dao->rh224_codigocategoria = $servidorOutrosVinculos->getCodigoCategoria();
        $dao->rh224_valorremuneracao = $servidorOutrosVinculos->getValorRemuneracao();
        $dao->rh224_instituicao = $servidorOutrosVinculos->getInstituicao()->getCodigo();
        $dao->rh224_ano = $servidorOutrosVinculos->getAno();
        $dao->rh224_mes = $servidorOutrosVinculos->getMes();
        $dao->rh224_matricula = $servidorOutrosVinculos->getServidor()->getMatricula();



        $dao->rh224_sequencial ? $dao->alterar($servidorOutrosVinculos->getSequencial()) : $dao->incluir(null);

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível o outro vínculo do servidor.\nContate o suporte.");
        }

        $servidorOutrosVinculos->setSequencial($dao->rh224_sequencial);

        return $servidorOutrosVinculos;
    }

    /**
     * @param $sequencial
     * @param string $operator
     * @return $this
     */
    public function scopeSequencial($sequencial, $operator = '=') {
        $this->scopes['sequencial'] = "rh224_sequencial {$operator} {$sequencial}";
        return $this;
    }

    /**
     * @param $tipoContribuicao
     * @param string $operator
     * @return $this
     */
    public function scopeTipoContribuicao($tipoContribuicao, $operator = '=') {
        $this->scopes['tipoContribuicao'] = "rh224_tipocontribuicao {$operator} {$tipoContribuicao}";
        return $this;
    }

    /**
     * @param $tipoInscricao
     * @param string $operator
     * @return $this
     */
    public function scopeTipoInscricao($tipoInscricao, $operator = '=') {
        $this->scopes['tipoInscricao'] = "rh224_tipoinscricao {$operator} {$tipoInscricao}";
        return $this;
    }

    /**
     * @param $numeroInscricao
     * @param string $operator
     * @return $this
     */
    public function scopeNumeroInscricao($numeroInscricao, $operator = '=') {
        $this->scopes['numeroInscricao'] = "rh224_numeroinscricao {$operator} {$numeroInscricao}";
        return $this;
    }

    /**
     * @param $codigoCategoria
     * @param string $operator
     * @return $this
     */
    public function scopeCodigoCategoria($codigoCategoria, $operator = '=') {
        $this->scopes['codigoCategoria'] = "rh224_codigocategoria {$operator} {$codigoCategoria}";
        return $this;
    }

    /**
     * @param $valorRemuneracao
     * @param string $operator
     * @return $this
     */
    public function scopeValorRemuneracao($valorRemuneracao, $operator = '=') {
        $this->scopes['valorRemuneracao'] = "rh224_valorremuneracao {$operator} {$valorRemuneracao}";
        return $this;
    }

    /**
     * @param $instituicao
     * @param string $operator
     * @return $this
     */
    public function scopeInstituicao(Instituicao $instituicao, $operator = '=') {
        $this->scopes['instituicao'] = "rh224_instituicao {$operator} {$instituicao->getCodigo()}";
        return $this;
    }

    /**
     * @param $ano
     * @param string $operator
     * @return $this
     */
    public function scopeAno($ano, $operator = '=') {
        $this->scopes['ano'] = "rh224_ano {$operator} {$ano}";
        return $this;
    }

    /**
     * @param $mes
     * @param string $operator
     * @return $this
     */
    public function scopeMes($mes, $operator = '=') {
        $this->scopes['mes'] = "rh224_mes {$operator} {$mes}";
        return $this;
    }

    /**
     * @param $servidor
     * @param string $operator
     * @return $this
     */
    public function scopeServidor(Servidor $servidor, $operator = '=') {
        $this->scopes['servidor'] = "rh224_matricula {$operator} {$servidor->getMatricula()}";
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
     * @return ServidorOutrosVinculosRepository
     */
    public function removeScope($key)
    {
        if (array_key_exists($key, $this->scopes)) {
            unset($this->scopes[$key]);
        }

        return $this;
    }
}
