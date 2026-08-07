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

namespace ECidade\Financeiro\Empenho\Repository;

use cl_tiposerviconotafiscal;
use ECidade\Financeiro\Empenho\Model\TipoServicoNotaFiscal;
use Exception;

/**
 * Class TipoServicoNotaFiscalRepository
 * @package ECidade\Financeiro\Empenho\Repository
 */
class TipoServicoNotaFiscalRepository extends \BaseClassRepository
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
     * @param TipoServicoNotaFiscal|null $tipoServicoNotaFiscal
     * @throws Exception
     */
    public function delete(TipoServicoNotaFiscal $tipoServicoNotaFiscal = null)
    {
        $sequencial = $tipoServicoNotaFiscal instanceof TipoServicoNotaFiscal ? $tipoServicoNotaFiscal->getSequencial() : null;

        $dao = new cl_tiposerviconotafiscal();
        $dao->excluir($sequencial, implode(' AND ', $this->scopes));

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível excluir o tipo de serviço {$tipoServicoNotaFiscal->getCgm()->getNome()}.\nContate o suporte.");
        }
    }

    /**
     * @param $id
     * @param array $columns
     * @return bool|TipoServicoNotaFiscal
     * @throws Exception
     */
    public static function find($id, $columns = array('*'))
    {
        $dao = new cl_tiposerviconotafiscal();
        $sql = $dao->sql_query($id, implode(', ', $columns));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar o tipo de serviço de nota fiscal.\nContate o suporte.");
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $resultado = pg_fetch_array($rs);

        return TipoServicoNotaFiscal::fromState($resultado);
    }

    /**
     * @param array $columns
     * @return TipoServicoNotaFiscal[]
     * @throws Exception
     */
    public function all($columns = array('*'))
    {
        $dao = new cl_tiposerviconotafiscal();
        $sql = $dao->sql_query(null, implode(', ', $columns));
        $rs = db_query($sql);

        $tiposServicosNotaFiscal = array();

        if (pg_num_rows($rs) === 0) {
            return $tiposServicosNotaFiscal;
        }

        while ($tipoServicoNotaFiscal = pg_fetch_array($rs)) {
            $tiposServicosNotaFiscal[] = TipoServicoNotaFiscal::fromState($tipoServicoNotaFiscal);
        }

        return $tiposServicosNotaFiscal;
    }

    /**
     * @param $id
     * @param string $operator
     * @return TipoServicoNotaFiscalRepository
     */
    public function scopeSequencial($id, $operator = '=')
    {
        $this->scopes['sequencial'] = "e18_sequencial {$operator} {$id}";
        return $this;
    }

    /**
     * @param $referencia
     * @param string $operator
     * @return TipoServicoNotaFiscalRepository
     */
    public function scopeReferencia($referencia, $operator = '=')
    {
        $this->scopes['referencia'] = "e18_referencia {$operator} {$referencia}";
        return $this;
    }

    /**
     * @param $descricao
     * @param string $operator
     * @return TipoServicoNotaFiscalRepository
     */
    public function scopeDescricao($descricao, $operator = '=')
    {
        $this->scopes['descricao'] = "e18_descricao {$operator} {$descricao}";
        return $this;
    }
    /**
     * @param $key
     * @return TipoServicoNotaFiscalRepository
     */
    public function removeScope($key)
    {
        if (array_key_exists($key, $this->scopes)) {
            unset($this->scopes[$key]);
        }

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
     * @return TipoServicoNotaFiscal[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_tiposerviconotafiscal();
        $sql = $dao->sql_query(null, '*', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar os tipos de serviços de nota fiscal.\nContate o suporte.");
        }

        $tiposServicosNotaFiscal = array();

        if (pg_num_rows($rs) === 0) {
            return $tiposServicosNotaFiscal;
        }

        while ($tipoServicoNotaFiscal = pg_fetch_array($rs)) {
            $tiposServicosNotaFiscal[] = TipoServicoNotaFiscal::fromState($tipoServicoNotaFiscal);
        }

        return $tiposServicosNotaFiscal;
    }

    /**
     * @param TipoServicoNotaFiscal $tipoServicoNotaFiscal
     * @return TipoServicoNotaFiscal
     * @throws Exception
     */
    public function save(TipoServicoNotaFiscal $tipoServicoNotaFiscal)
    {
        $dao = new cl_tiposerviconotafiscal();
        $dao->e18_sequencial = $tipoServicoNotaFiscal->getSequencial();
        $dao->e18_referencia = $tipoServicoNotaFiscal->getReferencia();
        $dao->e18_descricao = $tipoServicoNotaFiscal->getDescricao();

        $tipoServicoNotaFiscal->getSequencial() ? $dao->alterar($tipoServicoNotaFiscal->getSequencial()) : $dao->incluir(null);

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível salvar as informações.\nContate o suporte.");
        }

        $tipoServicoNotaFiscal->setSequencial($dao->e18_sequencial);

        return $tipoServicoNotaFiscal;
    }
}
