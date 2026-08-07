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

namespace ECidade\Configuracao\RelatorioLegal\Repositorio;

use cl_orcparamseqcolunaestruturais;
use ECidade\Configuracao\RelatorioLegal\Modelo\Coluna;
use ECidade\Configuracao\RelatorioLegal\Modelo\ColunaEstrutural;
use ECidade\Configuracao\RelatorioLegal\Registry\ColunaEstruturalRegistry;
use Exception;

/**
 * Class ColunaEstruturalRepositorio
 * @package ECidade\Configuracao\RelatorioLegal\Repositorio
 */
class ColunaEstruturalRepositorio extends Repositorio
{
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
            $self->delete(ColunaEstruturalRegistry::get($id));
            $count++;
        }

        return $count;
    }

    /**
     * @param ColunaEstrutural|null $colunaEstrutural
     * @throws Exception
     */
    public function delete(ColunaEstrutural $colunaEstrutural = null)
    {
        $id = $colunaEstrutural instanceof ColunaEstrutural ? $colunaEstrutural->getSequencial() : null;

        $dao = new cl_orcparamseqcolunaestruturais();
        $dao->excluir($id, implode(' AND ', $this->scopes));

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível excluir.\nContate o suporte.");
        }
    }

    /**
     * @param $id
     * @param array $columns
     * @return bool|ColunaEstrutural
     * @throws Exception
     */
    public static function find($id, $columns = array('*'))
    {
        $dao = new cl_orcparamseqcolunaestruturais();
        $sql = $dao->sql_query($id, implode(', ', $columns));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar a(s) conta(s) vinculada(s) a coluna.\nContate o suporte.");
        }

        if (pg_num_rows($rs) === 0) {
            return false;
        }

        $resultado = pg_fetch_array($rs);

        return ColunaEstrutural::fromState($resultado);
    }

    /**
     * @param ColunaEstrutural $colunaEstrutural
     * @return ColunaEstrutural
     * @throws Exception
     */
    public static function save(ColunaEstrutural $colunaEstrutural)
    {
        $dao = new cl_orcparamseqcolunaestruturais();
        $dao->o158_sequencial = $colunaEstrutural->getSequencial();
        $dao->o158_exclusao = $colunaEstrutural->isExclusao() ? 't' : 'f';
        $dao->o158_estrutural = trim($colunaEstrutural->getEstrutural());
        $dao->o158_orcparamseqcoluna = $colunaEstrutural->getColuna()->getSequencial();
        $dao->o158_ano = $colunaEstrutural->getAno();
        $dao->o158_sequencial ? $dao->alterar($colunaEstrutural->getSequencial()) : $dao->incluir(null);

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível vincular a conta na coluna.\nContate o suporte.\n {$dao->erro_msg}");
        }

        $colunaEstrutural->setSequencial($dao->o158_sequencial);

        return $colunaEstrutural;
    }

    /**
     * @param array $columns
     * @return ColunaEstrutural[]
     * @throws Exception
     */
    public function all($columns = array('*'))
    {
        $dao = new cl_orcparamseqcolunaestruturais();
        $sql = $dao->sql_query(null, implode(', ', $columns));
        $rs = db_query($sql);

        $colunaEstruturais = array();

        if (pg_num_rows($rs) === 0) {
            return $colunaEstruturais;
        }

        while ($colunaEstrutural = pg_fetch_array($rs)) {
            $colunaEstruturais[] = ColunaEstrutural::fromState($colunaEstrutural);
        }

        return $colunaEstruturais;
    }

    /**
     * @return int
     * @throws Exception
     */
    public function count()
    {
        $dao = new cl_orcparamseqcolunaestruturais();
        $sql = $dao->sql_query_file(null, 'count(*)', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar o total de contas vinculadas a coluna.\nContate o suporte.");
        }

        return (int)pg_fetch_result($rs, 0, 'count');
    }

    /**
     * @param $sequencial
     * @param string $operator
     * @return $this
     */
    public function scopeSequencial($sequencial, $operator = '=')
    {
        $this->scopes['sequencial'] = "o158_sequencial {$operator} {$sequencial}";
        return $this;
    }

    /**
     * @param bool $exclusao
     * @param string $operator
     * @return $this
     */
    public function scopeExclusao($exclusao, $operator = '=')
    {
        $this->scopes['exclusao'] = "o158_exclusao {$operator} {$exclusao}";
        return $this;
    }

    /**
     * @param string $estrutural
     * @param string $operator
     * @return $this
     */
    public function scopeEstrutural($estrutural, $operator = '=')
    {
        $this->scopes['estrutural'] = "o158_estrutural {$operator} '{$estrutural}'";
        return $this;
    }

    /**
     * @param Coluna $coluna
     * @param string $operator
     * @return $this
     */
    public function scopeColuna(Coluna $coluna, $operator = '=')
    {
        $this->scopes['coluna'] = "o158_orcparamseqcoluna {$operator} {$coluna->getSequencial()}";
        return $this;
    }

    /**
     * @param int $ano
     * @param string $operator
     * @return $this
     */
    public function scopeAno($ano, $operator = '=')
    {
        $this->scopes['ano'] = "o158_ano {$operator} {$ano}";
        return $this;
    }

    /**
     * @return ColunaEstrutural|null
     * @throws Exception
     */
    public function first()
    {
        $registros = $this->get();

        return count($registros) > 0
            ? array_shift($registros)
            : null;
    }

    /**
     * @return ColunaEstrutural[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_orcparamseqcolunaestruturais();
        $sql = $dao->sql_query_file(
            null,
            '*',
            'o158_estrutural',
            implode(' AND ', $this->scopes)
        );
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar a(s) conta(s) vinculada(s) a coluna.\nContate o suporte.");
        }

        $colunaEstruturais = array();

        if (pg_num_rows($rs) === 0) {
            return $colunaEstruturais;
        }

        while ($colunaEstrutural = pg_fetch_array($rs)) {
            $colunaEstruturais[] = ColunaEstrutural::fromState($colunaEstrutural);
        }

        return $colunaEstruturais;
    }

    /**
     * @param ColunaEstrutural $colunaEstrutural
     * @return ColunaEstrutural
     * @throws Exception
     */
    public function import(ColunaEstrutural $colunaEstrutural)
    {
        $dao = new cl_orcparamseqcolunaestruturais();
        $dao->o158_orcparamseqcoluna = $colunaEstrutural->getColuna()->getSequencial();
        $dao->o158_estrutural = $colunaEstrutural->getEstrutural();
        $dao->o158_exclusao = $colunaEstrutural->isExclusao() ? 't' : 'f';
        $dao->o158_sequencial = $colunaEstrutural->getSequencial();
        $dao->o158_ano = $colunaEstrutural->getAno();

        ColunaEstruturalRegistry::get($colunaEstrutural->getSequencial())
            ? $dao->alterar($colunaEstrutural->getSequencial())
            : $dao->incluir($colunaEstrutural->getSequencial());

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível importar as configurações da coluna.");
        }

        return $colunaEstrutural;
    }
}
