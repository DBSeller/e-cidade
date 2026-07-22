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

use cl_orcparamseqfiltropadrao;
use ECidade\Configuracao\RelatorioLegal\Modelo\Linha;
use ECidade\Configuracao\RelatorioLegal\Modelo\ConfiguracaoPadrao;
use ECidade\Configuracao\RelatorioLegal\Modelo\Relatorio;
use Exception;

class LinhaFiltroPadraoRepositorio extends Repositorio
{
    /**
     * @param int $ano
     * @param string $operator
     * @return $this
     */
    public function scopeAno($ano, $operator = '=')
    {
        $this->scopes['o132_anousu'] = "o132_anousu {$operator} {$ano}";
        return $this;
    }

    /**
     * @param Relatorio $relatorio
     * @param string $operador
     * @return $this
     */
    public function scopeRelatorio(Relatorio $relatorio, $operador = '=')
    {
        $this->scopes['relatorio'] = "o132_orcparamrel {$operador} {$relatorio->getSequencial()}";
        return $this;
    }

    /**
     * @param Linha $linha
     * @param string $operador
     * @return $this
     */
    public function scopeLinha(Linha $linha, $operador = '=')
    {
        $this->scopes['linha'] = "o132_orcparamseq {$operador} {$linha->getLinha()}";
        return $this;
    }

    /**
     * @return int
     * @throws Exception
     */
    public function count()
    {
        $dao = new cl_orcparamseqfiltropadrao();
        $sql = $dao->sql_query_file(null, 'count(*)', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar o total de filtros padrões.\nContate o suporte.");
        }

        return (int)pg_fetch_result($rs, 0, 'count');
    }

    /**
     * @return ConfiguracaoPadrao[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_orcparamseqfiltropadrao();
        $sql = $dao->sql_query(null, '*', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar a configuração padrão.\nContate o suporte.");
        }

        $filtros = array();
        if (pg_num_rows($rs) === 0) {
            return $filtros;
        }

        while ($linha = pg_fetch_array($rs)) {
            $filtros[] = ConfiguracaoPadrao::fromState($linha);
        }

        return $filtros;
    }

    /**
     * @param ConfiguracaoPadrao $filtroPadrao
     * @return ConfiguracaoPadrao
     * @throws Exception
     */
    public function import(ConfiguracaoPadrao $filtroPadrao)
    {
        $dao = $this->setDadosDao($filtroPadrao);

        self::find($filtroPadrao->getSequencial())
            ? $dao->alterar($filtroPadrao->getSequencial())
            : $dao->incluir($filtroPadrao->getSequencial());

        if ($dao->erro_status == '0') {
            throw new Exception('Não foi possível importar um filtro padrão.');
        }

        return $filtroPadrao;
    }

    /**
     * @param ConfiguracaoPadrao $filtroPadrao
     * @return cl_orcparamseqfiltropadrao
     */
    private static function setDadosDao(ConfiguracaoPadrao $filtroPadrao)
    {
        $dao = new cl_orcparamseqfiltropadrao();

        $dao->o132_sequencial = $filtroPadrao->getSequencial();
        $dao->o132_orcparamrel = $filtroPadrao->getRelatorio()->getSequencial();
        $dao->o132_orcparamseq = $filtroPadrao->getLinha()->getLinha();
        $dao->o132_anousu = $filtroPadrao->getAno();
        $dao->o132_filtro = pg_escape_string($filtroPadrao->getFiltro());

        return $dao;
    }

    /**
     * @param $sequencial
     * @return ConfiguracaoPadrao|null
     * @throws Exception
     */
    public static function find($sequencial)
    {
        $dao = new cl_orcparamseqfiltropadrao();
        $sql = $dao->sql_query_file($sequencial);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar a configuração padrão.");
        }

        if (pg_num_rows($rs) === 0) {
            return null;
        }

        return ConfiguracaoPadrao::fromState(pg_fetch_array($rs));
    }

    /**
     * @param ConfiguracaoPadrao|null $linhaFiltroPadrao
     * @throws Exception
     */
    public function delete(ConfiguracaoPadrao $linhaFiltroPadrao = null)
    {
        $id = $linhaFiltroPadrao instanceof ConfiguracaoPadrao ? $linhaFiltroPadrao->getSequencial() : null;

        $dao = new cl_orcparamseqfiltropadrao();
        $dao->excluir($id, implode(' AND ', $this->scopes));

        if ($dao->erro_status === '0') {
            throw new Exception("Não foi possível excluir o filtro padrão da linha a linha.\nContate o suporte.");
        }
    }
}
