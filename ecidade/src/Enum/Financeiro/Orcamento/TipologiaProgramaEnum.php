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

namespace ECidade\Enum\Financeiro\Orcamento;

use ECidade\Enum\Enum;
use Exception;

/**
 * Class TipologiaProgramaEnum
 * @package ECidade\Enum\Financeiro\Orcamento
 */
class TipologiaProgramaEnum extends Enum
{
    const REVISAR_TIPOLOGIA = 0; // Esse não é um tipo válido. É uma anomalia de migração;
    const PROGRAMAS_FINALISTICOS = 1;
    const PROGRAMAS_APOIO_POLITICAS = 2;
    const PROGRAMAS_TEMATICOS = 3;
    const PROGRAMAS_GESTAO = 4;

    /**
     * @return string
     * @throws Exception
     */
    public function name()
    {
        $data = array(
            self::REVISAR_TIPOLOGIA => "Revise a tipologia do programa",
            self::PROGRAMAS_FINALISTICOS => "Programas Temáticos",
            self::PROGRAMAS_APOIO_POLITICAS => "Programas de Gestão, Manutenção e Serviços ao Estado",
            self::PROGRAMAS_TEMATICOS => "Programas Temáticos",
            self::PROGRAMAS_GESTAO => "Programas de Gestão, Manutenção e Serviços ao Estado",
        );

        if (empty($data[$this->getValue()])) {
            throw new Exception('Tipologia inválida.');
        }

        return $data[$this->getValue()];
    }
}
