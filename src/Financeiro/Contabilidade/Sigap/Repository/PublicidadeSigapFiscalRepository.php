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

namespace ECidade\Financeiro\Contabilidade\Sigap\Repository;

use cl_publicidadesigapfiscal;
use ECidade\Educacao\Escola\Repository\Repository;
use ECidade\Financeiro\Contabilidade\Sigap\Model\PublicidadeSigapFiscal;
use Exception;
use Instituicao;

/**
 * Class PublicidadeSigapFiscalRepository
 * @package ECidade\Financeiro\Contabilidade\Sigap\Repository
 */
class PublicidadeSigapFiscalRepository extends Repository
{
    /**
     * @return PublicidadeSigapFiscal[]
     * @throws Exception
     */
    public function get()
    {
        $ordem = 'c136_periodo, c136_data_publicacao';
        $dao = new cl_publicidadesigapfiscal();
        $sql = $dao->sql_query_file(null, "*", $ordem, implode(' and ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar publicidades.");
        }

        $publicidades = [];
        while ($state = pg_fetch_array($rs)) {
            $publicidades[] = PublicidadeSigapFiscal::fromState($state);
        }

        return $publicidades;
    }

    /**
     * @param PublicidadeSigapFiscal $publicidade
     * @return PublicidadeSigapFiscal
     * @throws Exception
     */
    public function salvar(PublicidadeSigapFiscal $publicidade)
    {
        $dao = new cl_publicidadesigapfiscal();
        $dao->c136_codigo = $publicidade->getCodigo();
        $dao->c136_ano = $publicidade->getAno();
        $dao->c136_descricao = $publicidade->getDescricao();
        $dao->c136_tipo_relatorio = $publicidade->getCodigoTipoRelatorio();
        $dao->c136_data_publicacao = $publicidade->getDataPublicacao()->getDate();
        $dao->c136_meio_comunicacao = $publicidade->getMeioComunicacao()->getCodigo();
        $dao->c136_periodo = $publicidade->getPeriodo()->getCodigo();
        $dao->c136_link = $publicidade->getLink();
        $dao->c136_local_publicacao = $publicidade->getLocalPublicacao();
        $dao->c136_instituicao = $publicidade->getInstituicao()->getCodigo();

        if (empty($dao->c136_codigo)) {
            $dao->incluir(null);
        } else {
            $dao->alterar($dao->c136_codigo);
        }

        if ($dao->erro_status == 0) {
            throw new Exception("Erro ao salvar publicidade.");
        }

        $publicidade->setCodigo($dao->c136_codigo);
        return $publicidade;
    }

    /**
     * @param $ano
     * @return $this
     */
    public function scopeAno($ano)
    {
        $this->scopes['ano'] = "c136_ano = {$ano}";
        return $this;
    }

    /**
     * @param Instituicao $instituicao
     * @return $this
     */
    public function scopeInstituicao(Instituicao $instituicao)
    {
        $this->scopes['instituicao'] = "c136_instituicao = {$instituicao->getCodigo()}";
        return $this;
    }

    /**
     * @param $id
     * @return bool
     * @throws Exception
     */
    public function delete($id)
    {
        $dao = new cl_publicidadesigapfiscal();
        $dao->excluir($id);
        if ($dao->erro_status == 0) {
            throw new Exception("Erro ao excluir publicidade.");
        }
        return true;
    }
}
