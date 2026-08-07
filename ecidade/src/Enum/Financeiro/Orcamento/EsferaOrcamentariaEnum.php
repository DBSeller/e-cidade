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

class EsferaOrcamentariaEnum extends Enum
{
    const ORCAMETNO_FISCAL = 10;
    const ORCAMETNO_SEGURIDADE_SOCIAL = 20;
    const ORCAMETNO_INVESTIMENTO = 30;

    /**
     * @return string
     * @throws Exception
     */
    public function name()
    {
        $data = array(
            self::ORCAMETNO_FISCAL => "F - Orçamento Fiscal",
            self::ORCAMETNO_SEGURIDADE_SOCIAL => "S - Orçamento da Seguridade Social",
            self::ORCAMETNO_INVESTIMENTO => "I - Orçamento de Investimento",
        );

        if (empty($data[$this->getValue()])) {
            throw new Exception('Esfera Orçamentária inválida.');
        }

        return $data[$this->getValue()];
    }

    /**
     * @return string
     * @throws Exception
     */
    public function sigla()
    {
        $data = array(
            self::ORCAMETNO_FISCAL => "F",
            self::ORCAMETNO_SEGURIDADE_SOCIAL => "S",
            self::ORCAMETNO_INVESTIMENTO => "I",
        );

        if (empty($data[$this->getValue()])) {
            throw new Exception('Esfera Orçamentária inválida.');
        }

        return $data[$this->getValue()];
    }
}
