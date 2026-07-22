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

require_once(modification('model/PadArquivoSigap.model.php'));

/**
 * Prove dados para a geração do arquivo dos servidores que possuiram movimentacao no periodo
 * do municipio para o SIGAP
 * @package Pad
 * @author  Fabio Egidio
 * @version $Revision: 1.0
 */
final class PadArquivoSigapFolhaEstruturaLegal extends PadArquivoSigap
{
    /**
     * Construtor
     */
    public function __construct()
    {
        $this->sNomeArquivo = "EstruturaLegal";
        $this->aDados = array();
    }

    /**
     * Gera os dados para utilizacao posterior. Metodo geralmente usado
     * em conjuto com a classe PadArquivoEscritorXML
     * @return true;
     * @throws Exception
     */
    public function gerarDados()
    {
        if (empty($this->sDataInicial)) {
            throw new Exception("Data inicial nao informada!");
        }

        if (empty($this->sDataFinal)) {
            throw new Exception("Data final não informada!");
        }
        /**
         * Separamos a data do em ano, mes, dia
         */
        list($this->iAno, $this->iMes, $this->iDia) = explode("-", $this->sDataFinal);

        $this->sListaInstit = db_getsession("DB_instit");
        $dataMovimento = "{$this->iAno}-" . str_pad($this->iMes, 2, "0", STR_PAD_LEFT)
            . "-" . "01";
        $sSqlPessoal = "
            select distinct
                lpad({$this->iCodigoTCE},4,'0') as \"legCodigoEntidade\",
                '{$dataMovimento}' as \"legMesAnoMovimento\",
                rh37_lei as \"legNumeroLei\",
                rh37_datainicial as \"legDataLei\",
                rh37_funcao as \"legCodigoCargoFuncao\",
                rh37_descr as \"legCargoFuncao\",
                rh37_vagas as \"legQuantidade\",
                case
                    when (rh02_codreg in (2, 17, 19, 20, 26, 27, 28, 30, 101, 108))
                        then '01'
                    when (rh02_codreg in (5, 8, 104))
                        then '03'
                    when (rh02_codreg in (1, 3, 7, 9, 10, 11, 12, 14, 15, 16, 18, 23, 25, 29, 31, 32, 102, 105, 106, 109))
                        then '04'
                    else '02' end as \"legRegimeJuridico\",
                '01' as \"legTipoCargoFuncao\"
            from pessoal.rhpessoal
                inner join pessoal.rhpessoalmov
                    on rhpessoalmov.rh02_regist = rhpessoal.rh01_regist
                inner join pessoal.rhfuncao
		    on rhfuncao.rh37_funcao = rhpessoalmov.rh02_funcao
                   and rhfuncao.rh37_instit = rhpessoalmov.rh02_instit
                left join pessoal.rhpesrescisao
                    on rh05_seqpes = rh02_seqpes
            where
                rh02_anousu = {$this->iAno}
                and rh02_mesusu = {$this->iMes}
                and rh02_instit = {$this->sListaInstit}
                and rh05_seqpes is null
            ;";

        $rsPessoal = db_query($sSqlPessoal);
        db_utils::makeCollectionFromRecord($rsPessoal, function($registro){
            array_push($this->aDados, $registro);
        });
        return true;
    }

    /**
     * Publica quais elementos/Campos estão disponiveis para
     * o uso no momento da geração do arquivo
     *
     * @return array com elementos disponibilizados para a geração dos arquivo
     */
    public function getNomeElementos()
    {
        $aElementos = [
            "legCodigoEntidade",
            "legMesAnoMovimento",
            "legNumeroLei",
            "legDataLei",
            "legCodigoCargoFuncao",
            "legCargoFuncao",
            "legQuantidade",
            "legRegimeJuridico",
            "legTipoCargoFuncao"
        ];
        return $aElementos;
    }
}
?>
