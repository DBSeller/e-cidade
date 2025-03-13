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

namespace ECidade\Financeiro\Planejamento\Factory;

use ECidade\Enum\Financeiro\Planejamento\TipoEnum;
use Exception;

/**
 * Class LdoAnexosFactory
 * @package ECidade\Financeiro\Planejamento\Factory
 */
class LdoAnexosFactory
{
    public static function programa(array $parametros)
    {
        if (empty($parametros['tipo']) || mb_strtoupper($parametros['tipo']) !== TipoEnum::LDO) {
            throw new Exception("Tipo não informado ou não é LDO.", 403);
        }

        if (empty($parametros['anexo'])) {
            throw new Exception("Não foi informado o tipo do anexo.", 403);
        }

        $programa = 'pla2_anexos_ldo001.php';
        switch ($parametros['anexo']) {
            case '1':
                $rota = 'financeiro/planejamento/relatorios/anexo-um';
                return ['relatorio' => 250, 'programa' => $programa, 'rota' => $rota];
            case '2':
                $rota = 'financeiro/planejamento/relatorios/anexo-dois';
                return ['relatorio' => 251, 'programa' => $programa, 'rota' => $rota];
            case '3':
                $rota = 'financeiro/planejamento/relatorios/anexo-tres';
                return ['relatorio' => 252, 'programa' => $programa, 'rota' => $rota];
            case '4':
                $rota = 'financeiro/planejamento/relatorios/anexo-quatro';
                return ['relatorio' => 253, 'programa' => $programa, 'rota' => $rota];
            case '5':
                $rota = 'financeiro/planejamento/relatorios/anexo-cinco';
                return ['relatorio' => 254, 'programa' => $programa, 'rota' => $rota];
            case '6':
                $rota = 'financeiro/planejamento/relatorios/anexo-seis';
                return ['relatorio' => 257, 'programa' => $programa, 'rota' => $rota];
            case '7':
                $rota = 'financeiro/planejamento/relatorios/anexo-sete';
                return ['relatorio' => 255, 'programa' => $programa, 'rota' => $rota];
            case '8':
                $rota = 'financeiro/planejamento/relatorios/anexo-oito';
                return ['relatorio' => 256, 'programa' => $programa, 'rota' => $rota];
            default:
                throw new Exception("Não foi encontrado o relatório para o anexo informado.", 403);
        }
    }
}
