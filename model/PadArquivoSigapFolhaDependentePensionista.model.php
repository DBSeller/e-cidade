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
final class PadArquivoSigapFolhaDependentePensionista extends PadArquivoSigap
{
    /**
     * Construtor
     */
    public function __construct()
    {
        $this->sNomeArquivo = "DependentePensionista";
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

        $sWhere = ECidade\RecursosHumanos\Pessoal\Service\SigapFolhaLotacaoService::rawQueryLotacao($this->iCodigoTCE);


	$sSqlPessoal = "select * from (
            select distinct
                lpad({$this->iCodigoTCE},4,'0') as \"depCodigoEntidade\",
                '{$dataMovimento}' as \"depMesAnoMovimento\",
                z01_nome as \"depNomeTitular\",
                z01_cgccpf as \"depCpfTitular\",
                rh31_nome as \"depNomePensionistaDependente\",
                dp01_cpf as \"depCpfPensionistaDependente\",
                fc_valida_cpf(dp01_cpf) as cpf_valido ,
                '01' as \"depTipo\"
            from pessoal.rhpessoal
                inner join cgm
                    on rh01_numcgm = z01_numcgm
                inner join pessoal.rhpessoalmov
                    on rhpessoalmov.rh02_regist = rhpessoal.rh01_regist
                inner join pessoal.rhdepend
                    on rh31_regist = rh01_regist
                inner join pessoal.rhdependeplug
		    on dp01_regist   = rh31_regist
                   and dp01_rhdepend = rh31_codigo
                left join pessoal.rhpesrescisao
                    on rh05_seqpes = rh02_seqpes
            where
                rh02_anousu = {$this->iAno}
                and rh02_mesusu = {$this->iMes}
                and rh02_instit = {$this->sListaInstit}
                and rh05_seqpes is null
                $sWhere
            order by
		z01_nome asc ) as x
            where cpf_valido = 't' 
            ";

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
            "depCodigoEntidade",
            "depMesAnoMovimento",
            "depNomeTitular",
            "depCpfTitular",
            "depNomePensionistaDependente",
            "depCpfPensionistaDependente",
            "depTipo"
        ];
        return $aElementos;
    }
}
?>
