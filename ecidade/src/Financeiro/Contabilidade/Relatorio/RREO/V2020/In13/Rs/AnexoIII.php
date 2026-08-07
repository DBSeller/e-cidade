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

namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2020\In13\Rs;

use ECidade\Configuracao\Opcao\Opcao;
use ECidade\Financeiro\Contabilidade\Balancete\Receita\Mensal;
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\InterfaceRelatorioLegal;
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2020\AnexoIII as AnexoIIIMdf;
use stdClass;

/**
 * Class AnexoXII
 * @package ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2020
 */
class AnexoIII extends AnexoIIIMdf
{
    /**
     * linha com o valor da RCL normal
     * @var int
     */
    const LINHA_RCL = 46;

    /**
     * linha com o valor da RCL extendida com endividadamento
     * @var int
     */
    const LINHA_RCL_ENDIVIDAMENTO = 48;

    /**
     * linha com o valor da RCL extendida com pessoal
     * @var int
     */
    const LINHA_RCL_PESSOAL = 50;

    public function __construct($ano, $codigorelatorio = null, $codigoPeriodo = null)
    {
        parent::__construct($ano, self::CODIGO_RELATORIO, $codigoPeriodo);
        $this->totalizadores = [
            2 => [3, 4, 5, 6, 7],
            9 => [10, 11],
            15 => [16, 17, 18, 19, 20, 21, 22, 23],
            25 => range(29, 36),
            1 => [2, 8, 9, 12, 13, 14, 15, 24],
            42 => [1, 25],
            46 => [42, 43],
            48 => [46, 47],
            50 => [48, 49],
        ];
        $this->linhasComBorda = [
            42 => 'TB',
            46 => 'TB',
            47 => 'TB',
            48 => 'TB',
            49 => 'TB',
            50 => 'TB'
        ];

        $this->linhasRelatorio = range(1, 25);
        $this->linhasRelatorio = array_merge($this->linhasRelatorio, range(29, 35), [42, 43]);
        $this->linhasRelatorio = array_merge($this->linhasRelatorio, range(46, 50));
        $this->linhasDedutoras = [25, 47, 49];
        $this->linhasSomarAbsoluto = array_merge([43], range(29, 35));
    }


    /**
     * @return array
     * @throws \Exception
     */
    public function getDados($trazerConfiguracaoPadrao = true)
    {
        $linhas = parent::getDados($trazerConfiguracaoPadrao);
        /**
         * AJustamos os nomes de algunas linhas:
         */
         $linhas[42]->descricao = str_repeat("  ", $linhas[42]->nivel)."III - SUBTOTAL (I - II)";
         $descricao = str_repeat("  ", $linhas[42]->nivel)."V - RECEITA CORRENTE LÍQUIDA (V = III + IV)";
         $linhas[46]->descricao = $descricao;

        $descricao = str_repeat("  ", $linhas[42]->nivel)."VI - DEDUÇÃO PREVISTA NO ART. 166 DA CF";
        $linhas[48]->descricao = $descricao;

        $descricao = str_repeat("  ", $linhas[42]->nivel)." VII - RECEITA CORRENTE LÍQUIDA AJUSTADA -";
        $descricao .= " EC86/2015 (VII = V - VI)";
        $linhas[50]->descricao = $descricao;
        return $linhas;
    }
}
