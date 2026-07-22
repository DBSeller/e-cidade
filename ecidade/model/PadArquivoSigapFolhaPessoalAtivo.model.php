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

use ECidade\RecursosHumanos\Pessoal\Service\SigapFolhaLotacaoService;
require_once(modification('model/PadArquivoSigap.model.php'));

ini_set('memory_limit', '-1');

/**
 * Prove dados para a geração do arquivo dos servidores que possuiram movimentacao no periodo
 * do municipio para o SIGAP
 * @package Pad
 * @author  Bruno Souza
 * @version $Revision: 1.0
 */
final class PadArquivoSigapFolhaPessoalAtivo extends PadArquivoSigap
{

    /**
     *
     */
    public function __construct()
    {
        $this->sNomeArquivo = "Pessoal";
        $this->aDados = [];
        $this->aDadosAgrupados = [];
        $this->aTagsAgrupamento = ['agpfilho'];
        /**
         * [matricula=>[tags...]]
        */
        $this->aTagsRemovidas = [];
    }

    public function isAgrupamento($tag)
    {
        return in_array($tag, $this->aTagsAgrupamento);
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

        $oUnidadeOrcamentaria = SigapFolhaLotacaoService::getDadosUnidadeOrcamentaria($this->iCodigoTCE);

        $rubricasPrev  = '\'R901\', \'R902\', \'R903\',\'R904\',\'R905\',\'R906\'';
        $rubricasPrev .= '\'R907\', \'R908\', \'R909\',\'R910\',\'R911\',\'R912\'';

        $gruposRub = '1100, 1101, 1102, 1103, 1104, 1105';
        
        $sSqlPessoal = "
            select 
                distinct(rh02_regist),

                rh02_seqpes,
                z01_numcgm,
                z01_nome,
                z01_cgccpf,
                z01_mae,
                z01_pai,
                rh01_nasc,
                case when rh01_sexo = 'M' then '1' else '2' end as sexo,
                case when rh01_estciv < 3 then rh01_estciv else 3 end as estadocivil,
                
                {$this->rawQueryInssIrf()} as ppatro,
                {$this->rawQueryGeralFinanceiraNovo(4, 1100)} as vencbas,
                {$this->rawQueryGeralFinanceiraNovo(4, 1101)} as outrasef,
                {$this->rawQueryGeralFinanceiraNovo(4, 1102)} as gratrep,
                {$this->rawQueryGeralFinanceiraNovo(4, 1103)} as auxind,
                {$this->rawQueryGeralFinanceiraNovo(4, 1104)} as extras,
                {$this->rawQueryGeralFinanceiraNovo(4, 1105)} as decimo,

                {$this->rawQueryProventos(4, $gruposRub)} as outras,
                {$this->rawQueryDescontos()} as descontos,

                {$this->rawQueryFinanceiroByRubricas('\'R985\', \'R986\', \'R987\'')} as contribui,
                {$this->rawQueryFinanceiroByRubricas($rubricasPrev)} as prevserv,
                {$this->rawQueryFinanceiroByRubricas('\'R913\', \'R914\', \'R915\'')} as irrf,
                {$this->rawQueryFinanceiroByRubricas('\'R981\', \'R982\', \'R983\'')} as baseirrf,

                                
                rh01_admiss,
                rh37_descr as cargo,
                rh02_horasdiarias as carga_horaria,
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
                {$this->rawQueryGeralFinanceira(506)} valor_teto,
		case when rh02_instit = 1 then
                (select rh27_descr
                from pontofx  
                     inner join rhrubricas on r90_rubric = rh27_rubric
                                          and r90_instit = rh27_instit
                                          and (r90_rubric between '1036' and '1058' or r90_rubric = '0005')
                where r90_anousu = {$this->iAno}
		  and r90_mesusu = {$this->iMes}  and r90_regist = rh02_regist and r90_instit = 1 limit 1)
                else rh37_descr end as comissao


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

            {$oUnidadeOrcamentaria->whereLotacao}
            
            GROUP BY

            rh02_regist
            ,rh01_nasc
            ,sexo
            ,z01_mae
            ,z01_pai
            ,rh01_estciv
            ,vencbas
            ,outrasef
            ,gratrep
            ,auxind
            ,extras
            ,decimo
            ,contribui
            ,prevserv
            ,irrf
            ,baseirrf
            ,ppatro

            ,rh02_seqpes
            ,rh02_instit
            ,z01_numcgm
            ,z01_nome
            ,z01_cgccpf
            ,rh01_admiss
            ,cargo
            ,carga_horaria
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
        //  and rh02_regist = 329997
        //  and rh02_regist  = 311630
        // die($sSqlPessoal);
        
        $rsPessoal = db_query($sSqlPessoal);
        $iTotalLinhas = pg_num_rows($rsPessoal);

        $diaHoje = date('d');
        $sDiaMesAno = "{$this->iAno}-" . str_pad($this->iMes, 2, "0", STR_PAD_LEFT) . "-{$diaHoje}";
        $this->mesAnoMovimento = $sDiaMesAno;

        for ($i = 0; $i < $iTotalLinhas; $i++) {

            $oPessoal = db_utils::fieldsMemory($rsPessoal, $i);

            $sqlFilhos = "
                SELECT  nomefilho, cpffilho
                FROM 
                (
                    SELECT DISTINCT
                        rh31_nome               AS nomefilho
                        ,dp01_cpf                AS cpffilho
                        ,fc_valida_cpf(dp01_cpf) AS cpf_valido 
                    
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
                            
                        and rh02_regist = {$oPessoal->rh02_regist} 
                        and rh31_gparen = 'F'
                    ORDER BY rh31_nome asc  
                ) AS x
                WHERE cpf_valido = 't' 
            ";

            $rsFilhos = db_query($sqlFilhos);
            $totalFilhos = pg_num_rows($rsFilhos);
            $this->aDadosAgrupados[$oPessoal->rh02_regist] = pg_fetch_all($rsFilhos);

            $oPessoalRetorno = new stdClass();
            $oPessoalRetorno->pesCodigoEntidade = str_pad($this->iCodigoTCE, 4, "0", STR_PAD_LEFT);
            $oPessoalRetorno->pesNome = $oPessoal->z01_nome;
            
            /////////////////// NOVOS
            $iTamanhoPad = strlen($oPessoal->z01_cgccpf);
            $oPessoalRetorno->cnpj  = $oUnidadeOrcamentaria->cnpj;
            $oPessoalRetorno->cpf  = str_pad($oPessoal->z01_cgccpf, $iTamanhoPad, 0, STR_PAD_LEFT);
            $oPessoalRetorno->nis = $oPessoal->z01_pis;
            $oPessoalRetorno->matricula = $oPessoal->rh02_regist;

            $codRegime = strlen($oPessoal->rh30_regime) > 1 ? $oPessoal->rh30_regime : "0{$oPessoal->rh30_regime}";
            $oPessoalRetorno->regime = $codRegime;

            $oPessoalRetorno->quadro = '01';
            $oPessoalRetorno->cargo = $oPessoal->cargo;
            $oPessoalRetorno->funcao = $oPessoal->funcao;
            $oPessoalRetorno->comissao = $oPessoal->comissao;
            $oPessoalRetorno->lotacao = $oPessoal->r70_descr;
            $oPessoalRetorno->dataadm = $oPessoal->rh01_admiss;
            $oPessoalRetorno->dataexclusao = $oPessoal->data_exclusao;
            $oPessoalRetorno->tipoexclusao = $oPessoal->data_exclusao ? '01' : '';

            if(empty($oPessoal->data_exclusao)){
                $this->aTagsRemovidas[$oPessoal->rh02_regist][] = 'tipoexclusao';
            }
            
            $oPessoalRetorno->cargahoraria = $oPessoal->rh02_hrssem;
            $oPessoalRetorno->datanasc = $oPessoal->rh01_nasc;
            $oPessoalRetorno->sexo = $oPessoal->sexo;
            $oPessoalRetorno->pai = empty($oPessoal->z01_pai) ? 'Não informado' : $oPessoal->z01_pai;
            $oPessoalRetorno->mae = empty($oPessoal->z01_mae) ? 'Não informado' : $oPessoal->z01_mae;
            $oPessoalRetorno->estadocivil = $oPessoal->estadocivil;   
             
            if($oPessoal->estadocivil == 2){
                $oPessoalRetorno->conjuge = empty($oPessoal->nome_conjuge) ? 'Não informado' : $oPessoal->nome_conjuge;
                $oPessoalRetorno->cpfconjuge = (empty($oPessoal->cpf_conjuge) && $oPessoal->estadocivil == 2) ? 99999999999 : $oPessoal->cpf_conjuge;
            } else {
                $this->aTagsRemovidas[$oPessoal->rh02_regist][] = 'conjuge';
                $this->aTagsRemovidas[$oPessoal->rh02_regist][] = 'cpfconjuge';
            }

            // dd($this->aTagsRemovidas);
            
            $oPessoalRetorno->qtdefilhos = str_pad($oPessoal->quantidade_dependentes, 2, '0', STR_PAD_LEFT);

            // valores monetarios
            $oPessoalRetorno->descontos = $this->formataValorMonetario($oPessoal->descontos);
            $oPessoalRetorno->vencbas = $this->formataValorMonetario($oPessoal->vencbas);
            $oPessoalRetorno->outrasef = $this->formataValorMonetario($oPessoal->outrasef) ;
            $oPessoalRetorno->gratrep = $this->formataValorMonetario($oPessoal->gratrep) ;
            $oPessoalRetorno->auxind = $this->formataValorMonetario($oPessoal->auxind) ;
            $oPessoalRetorno->extras = $this->formataValorMonetario($oPessoal->extras) ;
            $oPessoalRetorno->decimo = $this->formataValorMonetario($oPessoal->decimo) ;
            $oPessoalRetorno->outras = $this->formataValorMonetario($oPessoal->outras) ;
            $oPessoalRetorno->contribui = $this->formataValorMonetario($oPessoal->contribui) ;
            $oPessoalRetorno->prevserv = $this->formataValorMonetario($oPessoal->prevserv);
            $oPessoalRetorno->irrf = $this->formataValorMonetario($oPessoal->irrf);
            $oPessoalRetorno->baseirrf = $this->formataValorMonetario($oPessoal->baseirrf);

            $resultadoPatronal = ($oPessoal->ppatro * $oPessoal->baseirrf) / 100;
            $oPessoalRetorno->prevpatronal = $this->formataValorMonetario($resultadoPatronal);


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
            // NOVA ESTRUTURA
            "cnpj",
            "cpf",
            "nis",
            "matricula",
            "regime",
            "quadro",
            "cargo",
            "funcao",
            "comissao",
            "lotacao",
            "dataadm",
            "dataexclusao",
            "tipoexclusao",
            "cargahoraria",
            "datanasc",
            "sexo",
            "pai",
            "mae",
            "estadocivil",
            "conjuge",
            "cpfconjuge",
            "qtdefilhos",
            "agpfilho",
            "vencbas",
            "outrasef",
            "gratrep",
            "auxind",
            "extras",
            "decimo",
            "outras",
            "descontos",
            "contribui",
            "prevserv",
            "prevpatronal",
            "irrf",
            "baseirrf",
        ];
        return $aElementos;
    }

    private function rawQueryFilhos()
    {
        return "(
            select array_agg(src_filhos) as array_filhos from
            (
                SELECT  nomefilho
                    ,cpffilho
                FROM 
                (
                    SELECT  DISTINCT rh31_nome      AS nomefilho 
                        ,dp01_cpf                AS cpffilho 
                        ,fc_valida_cpf(dp01_cpf) AS cpf_valido
                    FROM pessoal.rhpessoalmov as submov
                    INNER JOIN pessoal.rhdepend
                    ON rh31_regist = submov.rh02_regist
                    inner JOIN pessoal.rhdependeplug
                    ON dp01_regist = rh31_regist AND dp01_rhdepend = rh31_codigo 
                    AND rh31_regist = submov.rh02_regist
                    WHERE submov.rh02_regist = rhpessoalmov.rh02_regist 
                    AND rh31_gparen = 'F'
                    ORDER BY rh31_nome asc  
                ) AS x
                WHERE cpf_valido = 't'  
            ) as src_filhos
        )
        ";
    }

    private function rawQueryProventos($tipoGrupoCodigo, $grupoSeq)
    {
        return "(
            COALESCE(
                (
                    select 
                        sum(r14_valor)
                    from
                        gerfsal 
                    where
                        r14_anousu = {$this->iAno} and r14_mesusu = {$this->iMes} and
                        r14_instit = {$this->sListaInstit} and r14_regist = rh02_regist and r14_pd = 1
                        and r14_rubric not in
                        (
                            select rh114_rubrica from agrupamentorubrica 
                                inner join agrupamentorubricarubrica 
                                on rh114_agrupamentorubrica = rh113_sequencial 
                            where rh113_tipogrupo = {$tipoGrupoCodigo} and rh114_instituicao = {$this->sListaInstit} and rh113_sequencial in ({$grupoSeq})
                        )
                )

            , 00)
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
        , 00)
            
        )
        ";
    }

    private function rawQueryFinanceiroByRubricas($rubricas)
    {
        // dd($rubricas);
        return "(
        COALESCE(
            (
                select 
                    sum(r14_valor)
                from
                    gerfsal 
                where
                    r14_anousu = {$this->iAno} and r14_mesusu = {$this->iMes} and
                    r14_instit = {$this->sListaInstit} and r14_regist = rh02_regist
                    and r14_rubric in ({$rubricas})
            )
        , 00)
            
        )
        ";
    }

    private function rawQueryInssIrf()
    {
        return "
            (
                select coalesce
                (
                    (select distinct r33_ppatro from rhpessoalmov inssmov INNER join inssirf
                    on inssmov.rh02_anousu = r33_anousu and inssmov.rh02_mesusu = r33_mesusu and inssmov.rh02_instit = r33_instit
                    where r33_codtab = inssmov.rh02_tbprev + 2
                    and r33_anousu = {$this->iAno} and r33_mesusu = {$this->iMes}
                    and inssmov.rh02_instit = {$this->sListaInstit}
                    and inssmov.rh02_regist = rhpessoalmov.rh02_regist limit 1)

                , 00)
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

        return "(
            SELECT  count(cpffilho)
            FROM 
            (
                SELECT DISTINCT
                    rh31_nome               AS nomefilho
                    ,dp01_cpf                AS cpffilho
                    ,fc_valida_cpf(dp01_cpf) AS cpf_valido 
                
                from pessoal.rhpessoal
                inner join cgm
                    on rh01_numcgm = z01_numcgm
                inner join pessoal.rhpessoalmov pmf
                    on pmf.rh02_regist = rhpessoal.rh01_regist
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
                        
                    and pmf.rh02_regist = rhpessoalmov.rh02_regist
                    and rh31_gparen = 'F'
                ORDER BY rh31_nome asc  
            ) AS x
            WHERE cpf_valido = 't' 
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


    private function rawQueryGeralFinanceiraNovo($tipoGrupoCodigo, $grupoSeq)
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
                        where rh113_tipogrupo = {$tipoGrupoCodigo} and rh114_instituicao = {$this->sListaInstit} and rh113_sequencial = {$grupoSeq}
                    )
                    and {$sigla}regist = rh02_regist)
            , 0)
            ";

            $soma = ($campo === "gerfres") ? "" : "+";
            $rawGerf .= $soma;
        }

        // die($rawGerf);
        return $rawGerf;
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

    public function pg_array_parse($s, $start = 0, &$end = null)
    {
        if (empty($s) || $s[0] != '{') return null;
        $return = array();
        $string = false;
        $quote='';
        $len = strlen($s);
        $v = '';
        for ($i = $start + 1; $i < $len; $i++) {
            $ch = $s[$i];

            if (!$string && $ch == '}') {
                if ($v !== '' || !empty($return)) {
                    $return[] = $v;
                }
                $end = $i;
                break;
            } elseif (!$string && $ch == '{') {
                $v = pg_array_parse($s, $i, $i);
            } elseif (!$string && $ch == ','){
                $return[] = $v;
                $v = '';
            } elseif (!$string && ($ch == '"' || $ch == "'")) {
                $string = true;
                $quote = $ch;
            } elseif ($string && $ch == $quote && $s[$i - 1] == "\\") {
                $v = substr($v, 0, -1) . $ch;
            } elseif ($string && $ch == $quote && $s[$i - 1] != "\\") {
                $string = false;
            } else {
                $v .= $ch;
            }
        }

        $map = array_map(function($data){

            return str_replace(['(', ')'], '', $data);
            
        }, $return);
        
        return $map;
        // dd($map);
        return $return;
    }

    private function formataValorMonetario($valor)
    {
        return $valor == '0' ? '00' : number_format($valor, 2, ".", "");
    }
    
    

}

?>
