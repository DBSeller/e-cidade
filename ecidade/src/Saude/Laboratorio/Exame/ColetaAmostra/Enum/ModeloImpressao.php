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

namespace ECidade\Saude\Laboratorio\Exame\ColetaAmostra\Enum;

/**
 * Class ModeloImpressao
 * @package ECidade\Saude\Laboratorio\Exame\ColetaAmostra\Model
 */
class ModeloImpressao
{
    const TIPO_M1 = 'M1';
    const TIPO_M2 = 'M2';
    const TIPO_M3 = 'M3';

    const CODIGO_TIPO_M1 = 1;
    const CODIGO_TIPO_M2 = 2;
    const CODIGO_TIPO_M3 = 3;

    const DESCRICAO_TIPO_M1 = 'Modelo 1 ( 3 x 10 - Código de Barras / Código Exame / Paciente )';
    const DESCRICAO_TIPO_M2 = 'Modelo 2 ( 3 x 10 - Código de Barras / Código Exame / Paciente )';
    const DESCRICAO_TIPO_M3 = 'Modelo 3';

    /**
     * @var array
     */
    private static $tiposDescricoes = array(
      self::TIPO_M1 => self::DESCRICAO_TIPO_M1,
      self::TIPO_M2 => self::DESCRICAO_TIPO_M2,
      self::TIPO_M3 => self::DESCRICAO_TIPO_M3
    );

    /**
     * @var array
     */
    private static $tiposCodigos = array(
      self::TIPO_M1 => self::CODIGO_TIPO_M1,
      self::TIPO_M2 => self::CODIGO_TIPO_M2,
      self::TIPO_M3 => self::CODIGO_TIPO_M3
    );

    /**
     * @var array
     */
    private static $codigosDescricoes = array(
      self::CODIGO_TIPO_M1 => self::DESCRICAO_TIPO_M1,
      self::CODIGO_TIPO_M2 => self::DESCRICAO_TIPO_M2,
      self::CODIGO_TIPO_M3 => self::DESCRICAO_TIPO_M3
    );

    /**
     * @return array
     */
    public static function getTiposDescricoes()
    {
        return self::$tiposDescricoes;
    }

    /**
     * @return array
     */
    public static function getTiposCodigos()
    {
        return self::$tiposCodigos;
    }

    /**
     * @return array
     */
    public static function getCodigosDescricoes()
    {
        return self::$codigosDescricoes;
    }
}
