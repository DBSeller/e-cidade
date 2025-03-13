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

/**
 * Class AnexoSICONFIFactory
 * Factory para decidir qual relatório será instanciado, de acordo com o código e ano informado.
 */
abstract class AnexoSICONFIFactory {

  /**
   * Retorna uma instância de AnexoSICONFI de acordo com o ano e código informado.
   * @param integer $iAnoUsu
   * @param integer $iCodigoRelatorio Código do relatório.
   *
   * @return AnexoIFSICONFI|AnexoIDSICONFI|AnexoIGSICONFI|RelatoriosLegaisBase
   * @throws ParameterException
   */
  public static function getAnexoSICONFI($iAnoUsu, $iCodigoRelatorio) {

    switch ($iCodigoRelatorio) {

      case AnexoIDSICONFI::CODIGO_RELATORIO:
        return new AnexoIDSICONFI($iAnoUsu, $iCodigoRelatorio, AnexoSICONFI::CODIGO_PERIODO);

      case AnexoIFSICONFI::CODIGO_RELATORIO:
        return new AnexoIFSICONFI($iAnoUsu, $iCodigoRelatorio, AnexoSICONFI::CODIGO_PERIODO);

      case AnexoIGSICONFI::CODIGO_RELATORIO:
        return new AnexoIGSICONFI($iAnoUsu, $iCodigoRelatorio, AnexoSICONFI::CODIGO_PERIODO);

      default:
        throw new ParameterException("O código do relatório informado é inválido.");
    }
  }
}