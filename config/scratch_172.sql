select  c75_numemp,
        hash,
        round(sum(valor), 2) as valor,
       array_accum(distinct c71_coddoc) as coddoc
from (
select c124_lancamento,
       c71_coddoc,
       c75_numemp,
       sum(case when c124_natureza = 'C' then c124_valor * -1 else c124_valor end) as valor,
       array_to_string(array_agg(c123_valor||'#'||c121_sigla order by c121_sequencial), '|')   as hash
from contabilidade.infocomplementarvalor
         inner join contabilidade.conplanoatributolancamentos on c123_conplanoatributolancamentos = c124_sequencial
         inner join contabilidade.conplanoinfocomplementar
                    on infocomplementarvalor.c123_infocomplementar = conplanoinfocomplementar.c121_sequencial
         inner join contabilidade.conlancam on conplanoatributolancamentos.c124_lancamento = conlancam.c70_codlan
         inner join contabilidade.conlancamdoc on conlancam.c70_codlan = conlancamdoc.c71_codlan
         inner join contabilidade.conlancamemp on conlancam.c70_codlan = c75_codlan
where c123_reduzido = 19174
  and c124_data between '2019-01-01' and '2019-11-30'
group by c124_lancamento,
         c124_valor,
         c71_coddoc,
         c75_numemp
) as x
where hash like '0201#PO|%#FR|3339036000000#ND|08244#FS|0#ES'

 group by c75_numemp,
          hash
having round(sum(valor), 2) <> 0;

