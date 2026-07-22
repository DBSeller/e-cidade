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
 * @author  Bruno Souza
 * @version $Revision: 1.0
 */
final class PadArquivoSigapFolhaPessoal extends PadArquivoSigap
{

    /**
     *
     */
    public function __construct()
    {
        $this->sNomeArquivo = "Pessoal";
        $this->aDados = [];
    }

    /**
     * Gera os dados para utilizacao posterior. Metodo geralmente usado
     * em conjuto com a classe PadArquivoEscritorXML
     * @return true;
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

        $sWhere = ECidade\RecursosHumanos\Pessoal\Service\SigapFolhaLotacaoService::rawQueryLotacao($this->iCodigoTCE);
        
        $sSqlPessoal = "
            select 
                distinct(rh02_regist),

                rh02_seqpes,
                z01_numcgm,
                z01_nome,
                z01_cgccpf,
                rh01_admiss,
                rh37_descr as cargo,
                rh02_horasdiarias as carga_horaria,
                {$this->rawQueryProventos()} as provento_bruto,
                {$this->rawQueryDescontos()} as descontos,
                {$this->rawQuerySalario()} as salario,
                {$this->rawQuerySituacao()} as situacao,
                rh30_regime,
                rh26_orgao as codigo_orgao,
                rh26_unidade,
                r70_descr,
                z01_pis,
                {$this->rawQueryFuncao()} as funcao,
                {$this->rawQueryFormaComissao()} as forma_comissao,
                rh37_cbo,
                {$this->rawQueryOnus()} as onus,
                {$this->rawQueryCodigoFuncao()} as codigo_funcao,
                rh37_funcao as codigo_cargo,
                rh37_lei,
                {$this->rawQueryNomeConjuge()} as nome_conjuge,
                {$this->rawQueryQuantidadeDependentes()} as quantidade_dependentes,
                {$this->rawQueryEstatutarioCLT()} as estatutario_clt,
                {$this->rawQueryCategoriaSituacao()} as categoria_situacao,
                rh02_hrssem,
                {$this->rawQueryRequisitoCargo()} as requisito_cargo,
                {$this->rawQueryDataAposentadoria()} as data_aposentadoria,
                {$this->rawQueryDataExclusao()} as data_exclusao,
                {$this->rawQueryConjugeCPF()} as cpf_conjuge,
                {$this->rawQueryGeralFinanceira(3)} horas_extras,
                {$this->rawQueryGeralFinanceira(506)} valor_teto


            from rhpessoal
            
            inner join cgm
            ON cgm.z01_numcgm = rhpessoal.rh01_numcgm

            inner join rhpessoalmov
            ON rhpessoalmov.rh02_regist = rhpessoal.rh01_regist

            inner join rhfuncao
	    ON  rhfuncao.rh37_funcao = rhpessoalmov.rh02_funcao
            and rhfuncao.rh37_instit = rhpessoalmov.rh02_instit

            inner join rhregime
            ON rhregime.rh30_codreg = rhpessoalmov.rh02_codreg

            inner join rhlota
            ON rhlota.r70_codigo = rhpessoalmov.rh02_lota
            and rhlota.r70_instit = rhpessoalmov.rh02_instit 

            left join rhlotaexe
            ON rhlotaexe.rh26_codigo = rhpessoalmov.rh02_lota
            AND rhlotaexe.rh26_anousu = {$this->iAno}

            LEFT JOIN rhpesrescisao
            ON rh05_seqpes = rh02_seqpes

            LEFT JOIN assenta
            ON h16_regist = rh02_regist

            where rh02_anousu = {$this->iAno} and rh02_mesusu = {$this->iMes}
            and rh02_instit = {$this->sListaInstit}

            AND rh05_seqpes is null

	    $sWhere

            GROUP BY

            rh02_regist

            ,rh02_seqpes
            ,z01_numcgm
            ,z01_nome
            ,z01_cgccpf
            ,rh01_admiss
            ,cargo
            ,carga_horaria
            ,provento_bruto
            ,descontos
            ,salario
            ,rh30_regime
            ,codigo_orgao
            ,rh26_unidade
            ,r70_descr
            ,z01_pis
            ,funcao
            ,rh02_vincrais
            ,rh02_codreg
            ,rh37_cbo
            ,onus
            ,codigo_funcao
            ,codigo_cargo
            ,rh37_lei
            ,nome_conjuge
            ,quantidade_dependentes
            ,rh02_cedencia
            ,rh02_hrssem

            order by 1 desc
        ;
        ";

        
        // die($sSqlPessoal);
        
        $rsPessoal = db_query($sSqlPessoal);
        $iTotalLinhas = pg_num_rows($rsPessoal);

        for ($i = 0; $i < $iTotalLinhas; $i++) {

            $sDiaMesAno = "{$this->iAno}-" . str_pad($this->iMes, 2, "0", STR_PAD_LEFT) . "-" . "01";
            $oPessoal = db_utils::fieldsMemory($rsPessoal, $i);

            $oPessoalRetorno = new stdClass();
            $oPessoalRetorno->pesCodigoEntidade = str_pad($this->iCodigoTCE, 4, "0", STR_PAD_LEFT);
            $oPessoalRetorno->pesMesAnoMovimento = $sDiaMesAno;
            $oPessoalRetorno->pesNome = $oPessoal->z01_nome;

            $iTamanhoPad = strlen($oPessoal->z01_cgccpf);
            $oPessoalRetorno->pesCpf  = str_pad($oPessoal->z01_cgccpf, $iTamanhoPad, 0, STR_PAD_LEFT);

            $oPessoalRetorno->pesMatricula = $oPessoal->rh02_regist;
            $oPessoalRetorno->pesFuncao = $oPessoal->funcao;
            $oPessoalRetorno->pesNivelReferencia = "0000";
            $oPessoalRetorno->pesDataAdmissao = $oPessoal->rh01_admiss;
            $oPessoalRetorno->pesProventoBruto = $this->formataValorMonetario($oPessoal->provento_bruto);
            $oPessoalRetorno->pesDescontos = $this->formataValorMonetario($oPessoal->descontos);

            $proventoLiquido = $this->formataValorMonetario(($oPessoal->provento_bruto - $oPessoal->descontos));
            $oPessoalRetorno->pesProventoLiquido = $proventoLiquido;

            $oPessoalRetorno->pesSituacao = $oPessoal->situacao;
            $oPessoalRetorno->pesCargaHoraria = $oPessoal->carga_horaria;

            $codRegime = strlen($oPessoal->rh30_regime) > 1 ? $oPessoal->rh30_regime : "0{$oPessoal->rh30_regime}";
            $oPessoalRetorno->pesRegimeJuridico = $codRegime;

            $oPessoalRetorno->pesOutroVinculo = "N";
            $oPessoalRetorno->pesVinculoExterno = "";
            $oPessoalRetorno->pesCnpjVinculoExterno = "";
            $oPessoalRetorno->pesCodigoOrgao = $oPessoal->codigo_orgao;
            $oPessoalRetorno->pesCodigoUnidadeOrcamentaria = $oPessoal->rh26_unidade;
            $oPessoalRetorno->pesSetorLotacao = $oPessoal->r70_descr;
            $oPessoalRetorno->pesNumPisPasep = $oPessoal->z01_pis;
            $oPessoalRetorno->pesCargo = $oPessoal->cargo;
            $oPessoalRetorno->pesFormaComissao = $oPessoal->forma_comissao;
            $oPessoalRetorno->pesFreqCargaHoraria = "01";
            $oPessoalRetorno->pesQuantPlantaoMensal = "00";
            $oPessoalRetorno->pesCargaPlantao = "00";
            $oPessoalRetorno->pesValorPlantao = "0.00";
            $oPessoalRetorno->pesValorDiaria = "0.00";
            $oPessoalRetorno->pesQuantidadeDiaria = "00";
	    if($this->formataValorMonetario($oPessoal->salario)+0 > 0){
	      $valor_salario = $this->formataValorMonetario($oPessoal->salario);
	    }else{
	      $valor_salario = 0.01;
	    }
            $oPessoalRetorno->pesSalarioContratual = $valor_salario;
            $oPessoalRetorno->pesHorasExtras = $this->formataValorMonetario($oPessoal->horas_extras);
            $oPessoalRetorno->pesCBO = $oPessoal->rh37_cbo;
            $oPessoalRetorno->pesOnus = $oPessoal->onus;
            $oPessoalRetorno->pesCodigoFuncao = $oPessoal->codigo_funcao;
            $oPessoalRetorno->pesCodigoCargo = $oPessoal->codigo_cargo;
            $oPessoalRetorno->pesNumeroLeiFuncao = "0";
            $oPessoalRetorno->pesNumeroLeiCargo = $oPessoal->rh37_lei;
            $oPessoalRetorno->pesNomeConjuge = $oPessoal->nome_conjuge;
            $oPessoalRetorno->pesCpfConjuge = $oPessoal->cpf_conjuge;
            $oPessoalRetorno->pesQuantidadeDependente = $oPessoal->quantidade_dependentes;
            $oPessoalRetorno->pesQuadro = '01';
            $oPessoalRetorno->pesEstatutarioCLT = $oPessoal->estatutario_clt;
            $oPessoalRetorno->pesCategoriaSituacao = $oPessoal->categoria_situacao;
            $oPessoalRetorno->pesCargaHorariaSemanal = $oPessoal->rh02_hrssem;
            $oPessoalRetorno->pesRequisitoCargo = $oPessoal->requisito_cargo;
            $oPessoalRetorno->pesDataAposentadoria = $oPessoal->data_aposentadoria;
            $oPessoalRetorno->pesDataExclusao = $oPessoal->data_exclusao;
            $oPessoalRetorno->pesValorTeto = $this->formataValorMonetario($oPessoal->valor_teto);
            $oPessoalRetorno->pesValorAbateTeto = "0.00";


            array_push($this->aDados, $oPessoalRetorno);

        }
        
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
            "pesCodigoEntidade",
            "pesMesAnoMovimento",
            "pesNome",
            "pesCpf",
            "pesMatricula",
            "pesFuncao",
            "pesNivelReferencia",
            "pesDataAdmissao",
            "pesProventoBruto",
            "pesDescontos",
            "pesProventoLiquido",
            "pesSituacao",
            "pesCargaHoraria",
            "pesRegimeJuridico",
            "pesOutroVinculo",
            "pesVinculoExterno",
            "pesCnpjVinculoExterno",
            "pesCodigoOrgao",
            "pesCodigoUnidadeOrcamentaria",
            "pesSetorLotacao",
            "pesNumPisPasep",
            "pesCargo",
            "pesFormaComissao",
            "pesFreqCargaHoraria",
            "pesQuantPlantaoMensal",
            "pesCargaPlantao",
            "pesValorPlantao",
            "pesValorDiaria",
            "pesQuantidadeDiaria",
            "pesSalarioContratual",
            "pesHorasExtras",
            "pesCBO",
            "pesOnus",
            "pesCodigoFuncao",
            "pesCodigoCargo",
            "pesNumeroLeiFuncao",
            "pesNumeroLeiCargo",
            "pesNomeConjuge",
            "pesCpfConjuge",
            "pesQuantidadeDependente",
            "pesQuadro",
            "pesEstatutarioCLT",
            "pesCategoriaSituacao",
            "pesCargaHorariaSemanal",
            "pesRequisitoCargo",
            "pesDataAposentadoria",
            "pesDataExclusao",
            "pesValorTeto",
            "pesValorAbateTeto"
        ];
        return $aElementos;
    }

    private function rawQueryProventos()
    {
        return "(
            COALESCE(
                (select 
                sum(r14_valor)
            from
                gerfsal 
            where
                r14_anousu = {$this->iAno} and r14_mesusu = {$this->iMes} and
                r14_instit = {$this->sListaInstit} and r14_regist = rh02_regist and r14_pd = 1)
            , 0.00)
        )
        ";
    }

    private function rawQueryDescontos()
    {
        return "(
            COALESCE(
                (select 
                sum(r14_valor)
            from
                gerfsal 
            where
                r14_anousu = {$this->iAno} and r14_mesusu = {$this->iMes} and
                r14_instit = {$this->sListaInstit} and r14_regist = rh02_regist and r14_pd = 2)
        , 0.00)
            
        )
        ";
    }

    private function rawQuerySalario()
    {
        return "
            case when rh02_salari <= 0
                then (
                    SELECT  
                        r02_valor
                    FROM rhpespadrao
                    inner JOIN padroes
                    ON r02_codigo = rh03_padrao AND rh03_regime = r02_regime AND r02_anousu = {$this->iAno} AND r02_mesusu = {$this->iMes}
                    WHERE 
                    rh03_seqpes IN ( 
                        rh02_seqpes     
                    ) AND

                    r02_anousu = {$this->iAno}
                    AND r02_mesusu = {$this->iMes}
                    
                )
                else rh02_salari
            end
        ";
    }

    private function rawQuerySituacao()
    {

        $dataUltimoDiaCompetencia = "(
            cast(rh02_anousu::varchar||'-'||rh02_mesusu::varchar||'-'||
            (SELECT  fc_ultimodiames(rh02_anousu,rh02_mesusu))::varchar AS date)
        )";

        $mes = (int)$this->iMes;
        
        $terminoCompetenciaAtual = "((extract(year from h16_dtterm) = {$this->iAno}) and (extract(month from h16_dtterm) = {$mes}))";
        $concessaoCompetenciaAtual = "((extract(year from h16_dtconc) = {$this->iAno}) and (extract(month from h16_dtconc) = {$mes}))";

        return "
            max(
                CASE
                
                WHEN rh05_seqpes is not null THEN '02'
                
                WHEN
                    h16_dtterm >= {$dataUltimoDiaCompetencia}
                    AND h16_assent = 103
                    AND h16_dtconc <= {$dataUltimoDiaCompetencia}
                THEN '04' 
                
                WHEN
                    h16_dtterm >= {$dataUltimoDiaCompetencia}
                    AND h16_assent = 217 
                    AND h16_dtconc <= {$dataUltimoDiaCompetencia}
                THEN '03'
                
                WHEN
                    h16_dtterm >= {$dataUltimoDiaCompetencia}
                    AND h16_assent IN (70, 71, 73, 74, 75, 76, 77, 78, 79, 225, 226, 227, 228, 100, 106, 113, 202, 103)
                    AND h16_dtconc <= {$dataUltimoDiaCompetencia}
                THEN '05'

                WHEN rh05_seqpes is null THEN '01'

                END
            )
        ";
    }

    private function rawQueryFuncao()
    {
        return "
        (
            select rh04_descr from rhcargo
            inner join rhpescargo 
            on rhpescargo.rh20_cargo = rhcargo.rh04_codigo 
            where rh20_seqpes = rh02_seqpes
        ) 
        ";
    }

    private function rawQueryCodigoFuncao()
    {
        return "
        (
            select rh20_cargo from rhpescargo
            where rh20_seqpes = rh02_seqpes
        ) 
        ";
    }

    private function rawQueryFormaComissao(){
        return "
        case
            when rh02_vincrais = 0 and rh02_codreg = 13
            then '02'
            
            else '09'
        end
        ";
    }

    private function rawQueryOnus()
    {
        return "
        CASE
            WHEN rh02_cedencia = 'C'
            THEN '01'

            WHEN rh02_cedencia = 'A'
            THEN '02'

            ELSE ''
        END
        ";
    }

    private function rawQueryNomeConjuge()
    {
        return "
        (
            select rh31_nome from rhdepend where rh31_regist = rh02_regist and rh31_gparen = 'C'
            limit 1
        )
        ";

    }

    private function rawQueryQuantidadeDependentes()
    {
        return "
        (
            select count(*) from rhdepend where rh31_regist = rh02_regist
        )
        ";

    }

    private function rawQueryEstatutarioCLT()
    {
        return "
        CASE
            WHEN rh02_codreg in ('02', '27', '101')
            THEN '01'

            ELSE '02'
        END
        ";

    }

    private function rawQueryCategoriaSituacao()
    {
        return "
        CASE
            WHEN rh02_codreg = '13'
            THEN '02'

            WHEN rh02_cedencia = 'C'
            THEN '03'

            WHEN rh02_cedencia = 'A'
            THEN '06'

            WHEN rh02_codreg in ('12', '106')
            THEN '05'

            WHEN rh02_codreg = '08'
            THEN '07'

            WHEN rh02_codreg in ('02', 27, '101')
            THEN '01'

            ELSE '08'
        END
        ";

    }

    private function rawQueryRequisitoCargo()
    {
        return "
        CASE
            WHEN rh37_funcao IN (
                15, 16, 34, 35, 40, 170, 178, 179, 181, 210, 360, 396,
                449, 455, 456, 667, 668, 669, 710, 711, 712, 713, 714, 1020
            )
            THEN '01'

            WHEN rh37_funcao IN (
                17, 73, 87, 88, 93, 117, 121, 122, 123, 175, 176, 187, 201, 202, 244,
                249, 253, 270, 272, 323, 356, 357, 375, 376, 451, 551, 552, 553,
                554, 555, 556, 557, 558, 559, 560, 561, 562, 563, 564, 565, 566,
                567, 585, 586, 587, 588, 589, 590, 591, 602, 603, 604, 630, 632, 715, 1018
            )
            THEN '02'

            WHEN rh37_funcao IN (
                314, 315, 316, 459, 539, 540, 568, 569, 570, 571, 572, 573, 574, 575,
                576, 577, 592, 593, 594, 595, 596, 597, 599, 600, 601, 623,
                633, 638, 694, 702, 716, 764, 770, 782, 809, 1003, 1004, 1005, 1008
            )
            THEN '03'

            WHEN rh37_funcao IN (
                300, 388
            )
            THEN '04'

            ELSE '00'
        END
        ";
    }

    private function rawQueryDataAposentadoria()
    {
        return "
        (
            select h16_dtconc from assenta where h16_assent IN (
                229, 228, 227, 225, 226
            ) and h16_regist = rh02_regist limit 1
        ) 
        ";
    }

    private function rawQueryDataExclusao()
    {
        return "
        (
            select h16_dtconc from assenta where h16_assent IN (
                70, 71, 72, 73, 74, 75, 76, 136
            ) and h16_regist = rh02_regist limit 1
        ) 
        ";
    }

    private function rawQueryConjugeCPF()
    {
        return "
        (
            select
                dp01_cpf
            from
                rhdependeplug
            inner join rhdepend
                on rhdepend.rh31_regist = rhdependeplug.dp01_regist
            where
                dp01_regist = rh02_regist and rh31_gparen = 'C'
            limit 1
        ) 
        ";
    }

    private function rawQueryGeralFinanceira($grupoCodigo)
    {

        $campos = [
            'gerfsal' => 'r14_',
            'gerfcom' => 'r48_',
            'gerfs13' => 'r35_',
            'gerfres' => 'r20_'
        ];
        
        $rawGerf = '';

        foreach ($campos as $campo => $sigla) {
            $rawGerf .= "coalesce(
                (
                    select sum({$sigla}valor) from {$campo} where {$sigla}anousu = {$this->iAno} and {$sigla}mesusu = {$this->iMes} and {$sigla}rubric in
                    (
                        select rh114_rubrica from agrupamentorubrica 
                            inner join agrupamentorubricarubrica 
                            on rh114_agrupamentorubrica = rh113_sequencial 
                        where rh113_codigo = {$grupoCodigo} and rh114_instituicao = {$this->sListaInstit} and rh113_tipogrupo = 3
                    )
                    and {$sigla}regist = rh02_regist)
            , 0)
            ";

            $soma = ($campo === "gerfres") ? "" : "+";
            $rawGerf .= $soma;
        }

        return $rawGerf;
    }

    private function formataValorMonetario($valor)
    {
        return number_format($valor, 2, ".", "");
    }
    
    

}

?>
