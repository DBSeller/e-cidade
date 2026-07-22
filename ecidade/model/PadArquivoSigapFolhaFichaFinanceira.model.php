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

ini_set('memory_limit', '-1');
/**
 * Prove dados para a geração do arquivo dos servidores que possuiram movimentacao no periodo
 * do municipio para o SIGAP
 * @package Pad
 * @author  Fabio Egidio
 * @version $Revision: 1.0
 */
final class PadArquivoSigapFolhaFichaFinanceira extends PadArquivoSigap
{
    /**
     * Construtor
     */
    public function __construct()
    {
        $this->sNomeArquivo = "FichaFinanceira";
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

        $sSqlPessoal = "
            select distinct
                rh01_regist as matricula,
                rh01_instit as instituicao,
		lpad({$this->iCodigoTCE},4,'0') as \"ficCodigoEntidade\",
                '{$dataMovimento}' as \"ficMesAnoMovimento\",
                z01_nome as \"ficNome\",
                z01_cgccpf as \"ficCpf\"
            from pessoal.rhpessoal
                inner join cgm
                    on rh01_numcgm = z01_numcgm
                inner join pessoal.rhpessoalmov
                    on rhpessoalmov.rh02_regist = rhpessoal.rh01_regist
                left join pessoal.rhpesrescisao
                    on rh05_seqpes = rh02_seqpes
            where
                rh02_anousu = {$this->iAno}
                and rh02_mesusu = {$this->iMes}
                and rh02_instit = {$this->sListaInstit}
                and rh05_seqpes is null
                $sWhere
            order by
                z01_nome asc
            ;";

        $rsPessoal = db_query($sSqlPessoal);

        if (!$rsPessoal) {
            throw new Exception("Erro ao buscar os dados das matriculas.");
        }
        db_utils::makeCollectionFromRecord($rsPessoal, function($registro) {
            $registro;
            $dadosPagamentos = $this->getPagamentos($registro, $this->iAno, $this->iMes);
            foreach ($dadosPagamentos as $pagamento) {
                array_push($this->aDados, $pagamento);
            }
            unset($registro);
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
            "ficCodigoEntidade",
            "ficMesAnoMovimento",
            "ficNome",
            "ficCpf",
            "ficValorVerba",
            "ficQuantidadeVerba",
            "ficCodigoVerbaTCE",
            "ficCodigoVerbaEntidade",
            "ficDescricaoVerbaEntidade",
            "ficTipoVerba",
            "ficIncidenciaIRRF",
            "ficIncidenciaPrevidencia",
            "ficRemuneracaoIndenizacao",
            "ficNumeroLei"
        ];
        return $aElementos;
    }

    /**
     * @param $matricula
     * @param $ano
     * @param $mes
     */
    private function getPagamentos($registro, $ano, $mes)
    {
        $siglas = ["gerfsal" => "r14", "gerfcom" => "r48", "gerfres" => "r20", "gerfs13" => "r35"];
        $retorno = [];

        foreach ($siglas as $tabela => $sigla) {
            $registro;
            $sql = "
                select
                    {$sigla}_valor as \"ficValorVerba\",
                    {$sigla}_quant as \"ficQuantidadeVerba\",
                    translate({$sigla}_rubric,'R','9') as \"ficCodigoVerbaEntidade\",
                    rh27_descr as \"ficDescricaoVerbaEntidade\",
                    rh27_rhfundamentacaolegal as \"ficNumeroLei\",
                    case when ({$sigla}_pd = 1) then 'P'
                        when ({$sigla}_pd = 2) then 'D'
                        when ({$sigla}_pd = 3) then 'B'
                        end as \"ficTipoVerba\",
                    case when rh27_rubric in (select r09_rubric from basesr 
                                              where r09_anousu = {$ano} 
                                                and r09_mesusu = {$mes} 
                                                and r09_instit = {$registro->instituicao} 
                                                and r09_rubric = rh27_rubric 
                                                and r09_base = 'B004')
                         then 'S' else 'N' end as \"ficIncidenciaIRRF\",
                    case when rh27_rubric in (select r09_rubric from basesr 
                                              where r09_anousu = {$ano} 
                                                and r09_mesusu = {$mes} 
                                                and r09_instit = {$registro->instituicao} 
                                                and r09_rubric = rh27_rubric 
                                                and r09_base = 'B013')
                         then 'S' else 'N' end as \"ficIncidenciaPrevidencia\",
                    rh113_codigo as \"ficCodigoVerbaTCE\",
                    case when rh113_codigo in (1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 15, 16, 17, 18, 19, 20, 21, 22, 23, 29) then 'R'
                        when rh113_codigo in (11, 12, 13, 14, 24, 25, 26, 27, 28, 30) then 'I'
                        when rh113_codigo in (501, 502, 503, 504, 505, 506, 507, 508, 701, 702, 703, 997, 998) then 'N'
                        else 'N'
                    end as \"ficRemuneracaoIndenizacao\"
                from
                    {$tabela}
                    inner join rhrubricas on
                        rh27_instit = {$registro->instituicao}
                        and rh27_rubric = {$sigla}_rubric
                    inner join agrupamentorubricarubrica on
                        rh114_rubrica = rh27_rubric
                        and rh114_instituicao = {$registro->instituicao}
                    inner join agrupamentorubrica on
                        rh113_sequencial = rh114_agrupamentorubrica
                        and rh113_tipogrupo = 3
                where
                    {$sigla}_regist = {$registro->matricula}
                    and {$sigla}_anousu = {$ano}
                    and {$sigla}_mesusu = {$mes}
                    and {$sigla}_instit = {$registro->instituicao}
                order by
                    {$sigla}_pd asc,
                    {$sigla}_rubric asc";

            $folha = db_query($sql);
            if (!$folha) {
                throw new Exception("Erro ao buscar os dados da folha.");
            }
            $dadosFolha = db_utils::makeCollectionFromRecord($folha, function($dado) {

                $dado->ficValorVerba = $this->formataValorMonetario($dado->ficValorVerba);
                $dado->ficQuantidadeVerba = $this->formataQuantidadeVerba($dado->ficQuantidadeVerba);
                
                return $dado;
            });
            unset($folha);
            foreach ($dadosFolha as $dadoFolha) {
                $dado = (object) array_merge((array) $registro, (array) $dadoFolha);
                $retorno[] = $dado;
                unset($dadosFolha);
            }
        }
        return $retorno;
    }

    private function formataQuantidadeVerba($valor)
    {
        $valor = number_format($valor, 1, ".", "");
        
        $quantidadeVerba = $valor > 99.9 ? 99.9 : $valor;

        return $quantidadeVerba;
    }

    private function formataValorMonetario($valor)
    {
        return number_format($valor, 2, ".", "");
    }
}
?>
