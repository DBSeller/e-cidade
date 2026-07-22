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
namespace ECidade\Tributario\Arrecadacao;

/**
 * Classe com os tipo de debitos da tabela  cadtipo
 *
 * @package ECidade\Tributario\Arrecadacao
 * @author Augusto Oliveira <augusto.oliveira@dbseller.com.br>
 */
class CadTipo
{
    /**
     * @var ISSQN_FIXO integer
     */
    const ISSQN_FIXO = 2;
    /**
     * @var  ISSQN_VARIAVEL  integer
     */
    const ISSQN_VARIAVEL = 3;
    /**
     * @var CONTRIBUICAO_DE_MELHORIA integer
     */
    const CONTRIBUICAO_DE_MELHORIA = 4;
    /**
     * @var DIVIDA_ATIVA integer
     */
    const DIVIDA_ATIVA = 5;
    /**
     * @var PARCELAMENTO_DIVIDA_ATIVA integer
     */
    const PARCELAMENTO_DIVIDA_ATIVA = 6;
    /**
     * @var DIVERSOS integer
     */
    const DIVERSOS = 7;
    /**
     * @var ITBI
     */
    const ITBI = 8;
    /**
     * @var ALVARA integer
     */
    const ALVARA = 9;
    /**
     * @var NOTIFICACAO_FISCAL integer
     */
    const NOTIFICACAO_FISCAL = 10;
    /**
     * @var INICIAL_DE_DIVIDA_ATIVA integer
     */
    const INICIAL_DE_DIVIDA_ATIVA = 12;
    /**
     * @var  PARCELAMENTO_DE_INICIAL_DIV_ATIVA integer
     */
    const PARCELAMENTO_DE_INICIAL_DIV_ATIVA = 13;
    /**
     * @var PROTOCOLO_GERAL integer
     */
    const PROTOCOLO_GERAL = 14;
    /**
     * @var CERTIDAO_DO_FORO integer
     */
    const CERTIDAO_DO_FORO = 15;
    /**
     * @var  PARCELAMENTO_DIVERSO  integer
     */
    const PARCELAMENTO_DIVERSO = 16;
    /**
     * @var PARCELAMENTO_DE_CONTRIB_MELHORIA integer
     */
    const PARCELAMENTO_DE_CONTRIB_MELHORIA = 17;
    /**
     * @var INICIAL_FORO integer
     */
    const INICIAL_FORO = 18;
    /**
     * @var VISTORIAS integer
     */
    const VISTORIAS = 19;
    /**
     * @var SANEAMENTO_BASICO   integer
     */
    const SANEAMENTO_BASICO = 20;
    /**
     * @var CEMITERIO integer
     */
    const CEMITERIO = 21;
    /**
     * @var AUTO_DE_INFRACAO_FISCAL integer
     */
    const AUTO_DE_INFRACAO_FISCAL = 11;
    /**
     * @var IPTU integer
     */
    const IPTU = 1;
    /**
     * @var NAO_INFORMADO integer
     */
    const NAO_INFORMADO = 0;

    public static function check($cadtipo)
    {
        $cadtipoSuportados = array(
            self::ISSQN_FIXO,
            self::ISSQN_VARIAVEL,
            self::CONTRIBUICAO_DE_MELHORIA,
            self::DIVIDA_ATIVA,
            self::PARCELAMENTO_DIVIDA_ATIVA,
            self::DIVERSOS,
            self::ITBI,
            self::ALVARA,
            self::NOTIFICACAO_FISCAL,
            self::INICIAL_DE_DIVIDA_ATIVA,
            self::PARCELAMENTO_DE_INICIAL_DIV_ATIVA,
            self::PROTOCOLO_GERAL,
            self::CERTIDAO_DO_FORO,
            self::PARCELAMENTO_DIVERSO,
            self::PARCELAMENTO_DE_CONTRIB_MELHORIA,
            self::INICIAL_FORO,
            self::VISTORIAS,
            self::SANEAMENTO_BASICO,
            self::CEMITERIO,
            self::AUTO_DE_INFRACAO_FISCAL,
            self::IPTU,
            self::NAO_INFORMADO
        );

        return in_array($cadtipo, $cadtipoSuportados);
    }
}
