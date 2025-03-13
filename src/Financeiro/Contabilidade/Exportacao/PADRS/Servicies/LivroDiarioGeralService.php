<?php

namespace ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Servicies;

use ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Builders\v2022\LivroDiarioGeralBuilder;

class LivroDiarioGeralService extends PadService
{
    protected $fileName = 'TCE_4111.TXT';

    public function __construct($exercicio, $dataInicio, $dataFim, $instituicoes)
    {
        $this->instituicoes = $instituicoes;
        $this->ano = $exercicio;
        $this->dataInicial = $dataInicio;
        $this->dataFinal = $dataFim;
    }

    protected function getDados()
    {
        $sql = $this->sqlDiarioGeral();
        $rs = db_query($sql);
        while ($state = pg_fetch_array($rs)) {
            $builder = $this->getBuilder();
            yield $builder->addDados($state)->build();
        }
    }

    protected function getBuilder()
    {
        return new LivroDiarioGeralBuilder();
    }

    private function sqlDiarioGeral()
    {
        $codigosInstituicoes = implode(',', $this->instituicoes);
        $sWhere = "where lan.c69_data between '{$this->dataInicial}' and '{$this->dataFinal}' ";
        /**
         * Sub-select que descobre a origem da arrecadação da receita: planilha de receita ou arrecadação
         */
        $sSqlDadosTesouraria = "
         select lpad(
                    case when k82_seqpla::varchar is null
                         then k12_numpre::varchar
                    end, 10, '0'
                )
          from conlancambol
               inner join conlancamdoc  on c71_codlan = c77_codlan
               inner join corrente      on corrente.k12_id = c77_id
                                       and corrente.k12_autent = c77_autent
                                       and c77_databol = corrente.k12_data
               left  join corlanc       on corrente.k12_id = corlanc.k12_id
                                       and corrente.k12_autent = corlanc.k12_autent
                                       and corrente.k12_data= corlanc.k12_data
               left  join cornump       on cornump.k12_id = corrente.k12_id
                                       and cornump.k12_autent = corrente.k12_autent
                                       and cornump.k12_data = corrente.k12_data
               left  join corplacaixa   on corplacaixa.k82_id = corrente.k12_id
                                       and corplacaixa.k82_data = corrente.k12_data
                                       and corplacaixa.k82_autent = corrente.k12_autent
         where c77_codlan = c69_codlan limit 1
         ";

        $sSqlLancamentos = "select c69_sequen   as sequencial_lancamento,
               c60_estrut   as estrutural,
               codtrib      as orgaounidade,
               c78_chave    as numerolote,
               (select o15_recurso from orctiporec where o15_codigo = codigo_recurso) as recurso,
               complemento as complemento_recurso,
               c60_identificadorfinanceiro as indicadorsuperavitfinanceiro,
               (SELECT c65_sigla FROM consistemaconta WHERE c65_sequencial = c60_consistemaconta) AS naturezainformacao,
               case
                 when c53_tipo is null
                   then null
                 when c53_tipo in(10, 11)
                   then (select empempenho.e60_anousu ||
                                lpad(empempenho.e60_instit, 2, '0') || '0' || lpad(e60_codemp, 6, '0')::varchar
                           from conlancamemp
                                inner join empempenho on empempenho.e60_numemp = conlancamemp.c75_numemp
                          where c75_codlan = c69_codlan limit 1)
                 when c53_tipo in(20, 21)
                   then (select lpad(c66_codnota::varchar, 10,'0')::varchar
                           from conlancamnota
                          where c66_codlan = c69_codlan limit 1)
                 when c53_tipo in(30, 31)
                   then (select lpad(c80_codord::varchar, 10,'0')::varchar
                           from conlancamord
                          where c80_codlan = c69_codlan limit 1)
                   when c53_tipo in (40,41,50,51,60,61,70,71)
                   then (select lpad(o46_codlei::varchar, 10, '0')::varchar
                           from conlancamsup
                                inner join orcsuplem on orcsuplem.o46_codsup = conlancamsup.c79_codsup limit 1)
                 when c53_tipo in(100, 101)
                   then ({$sSqlDadosTesouraria})
                end          as numerodocumento,
               c69_codlan   as numerolancamento,
               c69_data     as datalancamento,
               round(c69_valor, 2)    as valor,
               tipo         as tipolancamento,
               ''           as numeroarquivamento,
               ''           as reservadofuturo,
               substr(replace(replace(c72_complem,'\\n',''), '\\r', ''), 1, 150) as historico,
               c53_tipo,
               (case when c53_tipo in(10, 11) then 1
                     when c53_tipo in (20,21) then 3
                     when c53_tipo in (30,11) then 2
                     when c53_tipo is null  then 0
                     else 9 end ) as tipodocumento
          from (select c69_sequen,
                       c69_codlan,
                       c69_data,
                       c69_valor,
                       c69_credito as reduz,
                       'C' as tipo,
                       codtrib,
                       substr(coalesce(c78_chave,'NAOINFORMADO'),1,12) as c78_chave,
                       conhistdoc.c53_coddoc,
                       substr(coalesce(c72_complem, o39_descr),1,150) as c72_complem,
                       pcr.c60_estrut,
                       pcr.c60_identificadorfinanceiro,
                       pcr.c60_consistemaconta,
                       case
                           when o201_orctiporec is not null
                               then o201_orctiporec
                           else cre.c61_codigo
                       end  codigo_recurso,
                       case
                           when o200_sequencial is not null and o200_tribunal is true
                               then o200_sequencial
                           else 0
                        end as complemento,
                       c53_tipo
                  from conlancamval lan
                       inner join conplanoreduz cre  on cre.c61_anousu            = c69_anousu
                                                    and cre.c61_anousu            = {$this->ano}
                                                    and cre.c61_instit            in ({$codigosInstituicoes})
                                                    and cre.c61_reduz             = c69_credito
                       inner join conplano pcr       on pcr.c60_anousu            = cre.c61_anousu
                                                    and pcr.c60_codcon            = cre.c61_codcon
                       inner join db_config          on db_config.codigo          = cre.c61_instit
                        left join conlancamcomplementorecurso  on o201_codlan   = lan.c69_codlan
                        left join complementofonterecurso on o200_sequencial = o201_complemento
                        left join conlancamdig       on conlancamdig.c78_codlan   = lan.c69_codlan
                        left join conlancamdoc       on conlancamdoc.c71_codlan   = lan.c69_codlan
                        left join conhistdoc         on conhistdoc.c53_coddoc     = conlancamdoc.c71_coddoc
                        left join conlancamcompl     on conlancamcompl.c72_codlan = lan.c69_codlan
                        left join conlancamsup       on conlancamsup.c79_codlan   = lan.c69_codlan
                        left join orcsuplem          on orcsuplem.o46_codsup      = conlancamsup.c79_codsup
                        left join orcprojeto          on orcprojeto.o39_codproj   = orcsuplem.o46_codlei
             {$sWhere}
                union all
                select c69_sequen,
                       c69_codlan,
                       c69_data,
                       c69_valor,
                       c69_debito as reduz,
                       'D' as tipo,
                       codtrib,
                       substr(coalesce(c78_chave,'NAOINFORMADO'),1,12) as c78_chave,
                       conhistdoc.c53_coddoc,
                       substr(coalesce(c72_complem, o39_descr),1,150) as c72_complem,
                       pdb.c60_estrut,
                       pdb.c60_identificadorfinanceiro,
                       pdb.c60_consistemaconta,
                       case
                           when o201_orctiporec is not null
                               then o201_orctiporec
                           else deb.c61_codigo
                       end codigo_recurso,
                       case
                                               when o200_sequencial is not null and o200_tribunal is true
                                                   then o200_sequencial
                                               else 0
                                            end as complemento,
                       c53_tipo
                  from conlancamval lan
                       inner join conplanoreduz deb  on deb.c61_anousu            = c69_anousu
                                                    and deb.c61_anousu            = {$this->ano}
                                                    and deb.c61_instit            in ({$codigosInstituicoes})
                                                    and deb.c61_reduz             = c69_debito
                       inner join conplano pdb       on pdb.c60_anousu            = deb.c61_anousu
                                                    and pdb.c60_codcon            = deb.c61_codcon
                       inner join db_config          on db_config.codigo          = deb.c61_instit
                        left join conlancamdig       on conlancamdig.c78_codlan   = lan.c69_codlan
                        left join conlancamcomplementorecurso  on o201_codlan   = lan.c69_codlan
                        left join complementofonterecurso on o200_sequencial = o201_complemento
                        left join conlancamdoc       on conlancamdoc.c71_codlan   = lan.c69_codlan
                        left join conhistdoc         on conhistdoc.c53_coddoc     = conlancamdoc.c71_coddoc
                        left join conlancamcompl     on conlancamcompl.c72_codlan = lan.c69_codlan
                        left join conlancamsup       on conlancamsup.c79_codlan   = lan.c69_codlan
                        left join orcsuplem          on orcsuplem.o46_codsup      = conlancamsup.c79_codsup
                        left join orcprojeto          on orcprojeto.o39_codproj   = orcsuplem.o46_codlei
             {$sWhere}
             ) as x
         order by c69_sequen ";

        return $sSqlLancamentos;
    }

    //plugin Santana
}
