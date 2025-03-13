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

namespace ECidade\Tributario\Arrecadacao\Repository;

use ECidade\Tributario\Arrecadacao\ParametrosTributario as ParametrosTributarioModel;

/**
 * Class ParametrosTributario
 * @package ECidade\Tributario\Arrecadacao\Repository
 */
class ParametrosTributario extends \BaseClassRepository
{
    protected static $oInstance;

    /**
     * @param int $ano
     * @param int|null $instituicao
     * @return ParametrosTributarioModel
     * @throws \DBException
     * @throws \ParameterException
     */
    public function getParametrosPorInstituicaoAno($ano, $instituicao = null)
    {
        if (empty($ano)) {
            throw new \ParameterException("Ano, para busca dos parâmetros do tributário, não informado.");
        }

        $instituicao = !empty($instituicao) ? $instituicao : \InstituicaoRepository::getInstituicaoSessao()->getCodigo();

        if (isset(static::getInstance()->aColecao["{$ano}#{$instituicao}"])) {
            return static::getInstance()->aColecao["{$ano}#{$instituicao}"];
        }

        $daoNumpref = new \cl_numpref();
        $sqlNumpref = $daoNumpref->sql_query_file($ano, $instituicao);
        $rsNumpref = db_query($sqlNumpref);

        if (!$rsNumpref) {
            throw new \DBException("Erro ao buscar os parâmetros do tributário.");
        }

        if (pg_num_rows($rsNumpref) == 0) {
            throw new \DBException("Parâmetros do tributário não encontrado.");
        }

        return self::make(\db_utils::fieldsMemory($rsNumpref, 0));
    }

    /**
     * @param $dadosParametrosInstituicao
     * @return ParametrosTributarioModel
     */
    protected function make($dadosParametrosInstituicao)
    {
        $parametrosInstituicao = new ParametrosTributarioModel();
        $parametrosInstituicao->setAno($dadosParametrosInstituicao->k03_anousu);
        $parametrosInstituicao->setInstituicao($dadosParametrosInstituicao->k03_instit);
        $parametrosInstituicao->setTipoReciboProtocolo($dadosParametrosInstituicao->k03_reciboprot);

        static::getInstance()->add($parametrosInstituicao);

        return $parametrosInstituicao;
    }

    /**
     * @param ParametrosTributarioModel $parametrosInstituicao
     */
    protected function add($parametrosInstituicao)
    {
        $indice = "{$parametrosInstituicao->getAno()}#{$parametrosInstituicao->getInstituicao()}";
        static::getInstance()->aColecao[$indice] = $parametrosInstituicao;
    }
}
