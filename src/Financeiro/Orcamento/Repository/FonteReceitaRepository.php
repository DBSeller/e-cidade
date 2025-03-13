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

namespace ECidade\Financeiro\Orcamento\Repository;

use cl_orcfontes;
use ECidade\Financeiro\Orcamento\Model\FonteReceita;
use ECidade\Financeiro\Orcamento\Model\NaturezaDespesa;
use Exception;

/**
 * Class FonteReceitaRepository
 * @package ECidade\Financeiro\Orcamento\Repository
 */
class FonteReceitaRepository
{
    private $campos = [
        'orcfontes.*',
        'exists(select 1 from contabilidade.conplanoorcamentoanalitica
        where c61_codcon = o57_codfon and c61_anousu = o57_anousu) as analitica'
    ];

    /**
     * @param array $order
     * @return FonteReceita[]
     * @throws Exception
     */
    public function get($order = [null])
    {
        $campos = implode(', ', $this->campos);
        $order = implode(', ', $order);

        $dao = new cl_orcfontes();
        $sql = $dao->sql_query_file(null, null, $campos, $order, implode(' and ', $this->scopes));
        $rs = db_query($sql);
        if (!$rs) {
            throw new Exception("Erro ao buscar as fontes de receita.");
        }

        $dados = [];
        while ($state = pg_fetch_array($rs)) {
            $dados[] = FonteReceita::fromState($state);
        }

        return $dados;
    }

    /**
     * @param integer $ano
     * @return $this
     */
    public function scopeAno($ano, $operador = '=')
    {
        $this->scopes['ano'] = "o57_anousu {$operador} {$ano}";
        return $this;
    }

    /**
     * @param $fonte
     * @return $this
     */
    public function scopeFonte($fonte)
    {
        $this->scopes['fonte'] = "o57_fonte like '{$fonte}%'";
        return $this;
    }

    /**
     * Filtra apenas os elementos analiticas
     * @return $this
     */
    public function scopeApenasFonteAnalitica()
    {
        $this->scopes['tipo'] = "
        exists(select 1 from contabilidade.conplanoorcamentoanalitica
        where c61_codcon = o57_codfon and c61_anousu = o57_anousu)
        ";

        return $this;
    }

    /**
     * Filtra apenas os elementos sintéticas
     * @return $this
     */
    public function scopeApenasFonteSintetica()
    {
        $this->scopes['tipo'] = "
        not exists(select 1 from contabilidade.conplanoorcamentoanalitica
        where c61_codcon = o57_codfon and c61_anousu = o57_anousu)
        ";

        return $this;
    }
}
