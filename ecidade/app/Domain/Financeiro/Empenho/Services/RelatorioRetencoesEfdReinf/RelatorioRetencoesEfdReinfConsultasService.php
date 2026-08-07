<?php

namespace App\Domain\Financeiro\Empenho\Services\RelatorioRetencoesEfdReinf;

class RelatorioRetencoesEfdReinfConsultasService
{
    public function sqlRetencoesR2010($sCampoQuebrarSql, $where)
    {
        $sqlRetencoesR2010 = "
          select 
            cgmprestador.z01_numcgm as identificador_prestador,
            cgmprestador.z01_nome as nome_prestador, 
            cgmprestador.z01_cgccpf as cnpj_prestador, 
            'INSS PJ' as retencao_tipo, 
            (case when emptiposervicoobra.e154_tipo = '0'
                  then 'SERVIÇO'
                  when emptiposervicoobra.e154_tipo = '1'
                  then 'EMPREITADA TOTAL'
                  when emptiposervicoobra.e154_tipo = '2'
                  then 'EMPREITADA PARCIAL' end) as indicativo_obra_tipo,
            emptiposervicoobra.e154_cno as indicativo_obra_cno, 
            empnota.e69_numero as numero_nota, 
            empnota.e69_dtnota as data_emissao, 
            '' as referencia_tipo_servico_desc, 
            retencaoreceitas.e23_valorretencao as valor_retencao, 
            empnotaele.e70_vlrliq as valor_nota_liq, 
            empempenho.e60_numemp as empenho, 
            e50_codord,
            o41_orgao,          
            o41_unidade, 
            o41_descr,
            concat(empempenho.e60_codemp, '/', empempenho.e60_anousu) as empenho_numero, 
             (case when pc60_indicativocprb is true
                   then cast('3,5' as varchar) else retencaotiporec.e21_aliquota::varchar end) as aliquota

                from empenho.empnota 
          inner join pagordemnota on empnota.e69_codnota = pagordemnota.e71_codnota 
                                 and pagordemnota.e71_anulado is false 
          inner join empnotaele on empnotaele.e70_codnota = empnota.e69_codnota 
          inner join pagordem on e71_codord = pagordem.e50_codord 
          inner join retencaopagordem on pagordem.e50_codord = retencaopagordem.e20_pagordem 
          inner join retencaoreceitas on retencaopagordem.e20_sequencial = retencaoreceitas.e23_retencaopagordem 
          inner join retencaotiporec on retencaotiporec.e21_sequencial = retencaoreceitas.e23_retencaotiporec 
          inner join empempenho on empempenho.e60_numemp = pagordem.e50_numemp 
          inner join orcdotacao       on e60_coddot     = o58_coddot           
                                     and e60_anousu     = o58_anousu           
          inner join orcunidade       on o41_unidade    = o58_unidade           
                                     and o41_orgao      = o58_orgao           
                                     and o41_instit     = o58_instit           
                                     and o41_anousu     = o58_anousu  
          inner join db_config on db_config.codigo = empempenho.e60_instit 
          inner join cgm as cgmcontribuinte on cgmcontribuinte.z01_numcgm = db_config.numcgm 
           left join pagordemconta on pagordemconta.e49_codord = pagordem.e50_codord 
           left join cgm as cgmprestador on cgmprestador.z01_numcgm 
                     = coalesce(pagordemconta.e49_numcgm, empempenho.e60_numcgm) 
           left join pcforne on pcforne.pc60_numcgm = cgmprestador.z01_numcgm 
           left join emptiposervicoobra on emptiposervicoobra.e154_numemp = empempenho.e60_numemp 
           left join retencaoreceitasadicionais on retencaoreceitas.e23_sequencial 
                     = retencaoreceitasadicionais.e19_retencaoreceitas 
           left join tiposerviconotafiscal on tiposerviconotafiscal.e18_sequencial 
                     = retencaoreceitasadicionais.e19_tiposerviconotafiscal 
               
               where retencaoreceitas.e23_ativo = true 
                 and retencaotiporec.e21_retencaotipocalc = 4 $where
            order by empnota.e69_dtnota desc";
      

        $sqlRetencoesR2010 = "select {$sCampoQuebrarSql}, dados.*
        from ({$sqlRetencoesR2010}) as dados";
        return $sqlRetencoesR2010;
    }

    public function sqlRetencoesR2055($sCampoQuebrarSql, $where)
    {
        $sqlRetencoesR2055 ="
            select z01_numcgm as identificador_prestador,
                   z01_nome as nome_prestador, 
                   z01_cgccpf as cnpj_prestador,
                   (case when e23_valorretencao is not null and length(z01_cgccpf) = '11'
                   then 'INSS PF' 
                   when e23_valorretencao is not null and length(z01_cgccpf) = '14'
                   then 'INSS PJ' end) as retencao_tipo,  
                   'PRODUTOR RURAL' as indicativo_obra_tipo, 
                   ''  as indicativo_obra_cno,
                   e69_numero as numero_nota, 
                   e69_dtnota as data_emissao, 
                   '' as referencia_tipo_servico_desc,
                   e23_valorretencao as valor_retencao,
                   empnotaele.e70_vlrliq as valor_nota_liq, 
                   e60_numemp as empenho, 
                   e50_codord,
                   o41_orgao,          
                   o41_unidade, 
                   o41_descr,
                   concat(empempenho.e60_codemp, '/', empempenho.e60_anousu) as empenho_numero,
                   (case when pc60_indicativocprb is true
                   then cast('3,5' as varchar) else retencaotiporec.e21_aliquota::varchar end) as aliquota
        
                   from empenho.empnota 
             inner join empnotaele on empnotaele.e70_codnota = empnota.e69_codnota 
             inner join empempenho on e69_numemp = e60_numemp 
             inner join orcdotacao on e60_coddot   = o58_coddot           
                                  and e60_anousu   = o58_anousu           
             inner join orcunidade on o41_unidade  = o58_unidade           
                                  and o41_orgao    = o58_orgao           
                                  and o41_instit   = o58_instit           
                                  and o41_anousu   = o58_anousu  
             inner join pcforne on pc60_numcgm = e60_numcgm 
             inner join cgm on z01_numcgm = e60_numcgm 
             inner join cgmtipoempresa on z01_numcgm = z03_numcgm 
             inner join pagordemnota on empnota.e69_codnota = pagordemnota.e71_codnota 
                                    and pagordemnota.e71_anulado is false
             inner join pagordem on pagordem.e50_codord  = pagordemnota.e71_codord 
              left join retencaoreceitasprodutorrural on e158_empnota = e69_codnota 
              left join retencaoreceitas on e23_sequencial = e158_retencaoreceitas  
              left join retencaotiporec  on e21_sequencial = retencaoreceitas.e23_retencaotiporec 
              left join retencaoreceitasadicionais on retencaoreceitas.e23_sequencial 
                      = retencaoreceitasadicionais.e19_retencaoreceitas 
              left join tiposerviconotafiscal on tiposerviconotafiscal.e18_sequencial 
                      = retencaoreceitasadicionais.e19_tiposerviconotafiscal 
              left join emptipoaquisicaoproducaorural on e159_empempenho = e60_numemp 
                 where z03_tipoempresa in (35, 4120) $where
             order by empnota.e69_dtnota desc";

        $sqlRetencoesR2055 = "select {$sCampoQuebrarSql}, dados.*
          from ({$sqlRetencoesR2055}) as dados";
        return $sqlRetencoesR2055;
    }

    public function sqlTodasRetencoesEfdReinf($sCampoQuebrarSql, $where)
    {

        $sqlTodasRetencoesEfdReinf = $this->sqlRetencoesR2010($sCampoQuebrarSql, $where);
        $sqlTodasRetencoesEfdReinf .= " UNION ";
        $sqlTodasRetencoesEfdReinf .= $this->sqlRetencoesR2055($sCampoQuebrarSql, $where);

        return $sqlTodasRetencoesEfdReinf;
    }
}
