UPDATE db_relatorio
SET db63_xmlestruturarel = '<?xml version="1.0" encoding="ISO-8859-1"?>
<Relatorio>
 <Versao>1.0</Versao>
 <Propriedades versao="1.0" nome="Relatório de Servidores por Tipo de Reajuste" layout="dbseller" formato="A4" orientacao="portrait" margemsup="0" margeminf="0" margemesq="20" margemdir="20" tiposaida="pdf"/>
 <Cabecalho></Cabecalho>
 <Rodape></Rodape>
 <Variaveis>
  <Variavel nome="$sTipoReajuste" label="Tipo de Reajuste" tipodado="varchar" valor="f"/>
 </Variaveis>
 <Campos>
  <Campo id="6964" nome="rh01_regist" alias="Matrícula" largura="18" alinhamento="c" alinhamentocab="c" mascara="t" totalizar="n" quebra=""/>
  <Campo id="217" nome="z01_nome" alias="Nome" largura="90" alinhamento="l" alinhamentocab="c" mascara="t" totalizar="n" quebra=""/>
  <Campo id="15613" nome="rh88_descricao" alias="Tipo Aposentadoria" largura="55" alinhamento="l" alinhamentocab="c" mascara="t" totalizar="n" quebra=""/>
  <Campo id="15614" nome="rh01_descricaoreajusteparidade" alias="Tipo de Reajuste" largura="30" alinhamento="l" alinhamentocab="c" mascara="t" totalizar="n" quebra=""/>
 </Campos>
 <Consultas>
  <Consulta tipo="Principal">
   <Select>
    <Campo id="6964"/>
    <Campo id="217"/>
    <Campo id="15613"/>
    <Campo id="15614"/>
   </Select>
   <From>select distinct rh01_regist,
                z01_nome,
                rh88_descricao,
                case when rh01_reajusteparidade = ''f'' then ''Real''
                else ''Paridade'' end as rh01_descricaoreajusteparidade
  from rhpessoal
      inner join cgm          on rhpessoal.rh01_numcgm = cgm.z01_numcgm
      inner join rhpessoalmov on rhpessoal.rh01_regist = rhpessoalmov.rh02_regist
      inner join rhtipoapos   on rhpessoalmov.rh02_rhtipoapos = rhtipoapos.rh88_sequencial

where rh01_reajusteparidade = $sTipoReajuste</From>
   <Where/>
   <Group></Group>
   <Order>
    <Ordem id="217" nome="z01_nome" ascdesc="asc" alias="Nome"/>
   </Order>
  </Consulta>
 </Consultas>
</Relatorio>
'
WHERE db63_sequencial = 28;

delete from db_syscadind    where codind       in (4132, 4133, 4134, 4135, 4136);
delete from db_sysindices   where codind       in (4132, 4133, 4134, 4135, 4136);
delete from db_sysprikey    where codarq       in (3748, 3749);
delete from db_sysforkey    where codarq       in (3748, 3749);
delete from db_sysarqarq    where codarq       in (3748, 3749);
delete from db_syssequencia where codsequencia in (1000409, 1000410, 1000411);
delete from db_syscampodef  where codcam       in (20830,20838,20832,20833,20827, 20828, 20829, 20839, 20840 ,20841, 20842, 20843);
delete from db_sysarqcamp   where codcam       in (20830,20838,20832,20833,20827, 20828, 20829, 20839, 20840 ,20841, 20842, 20843);
delete from db_syscampo     where codcam       in (20830,20838,20832,20833,20827, 20828, 20829, 20839, 20840 ,20841, 20842, 20843);
delete from db_sysarqmod    where codarq       in (3748,3749);
delete from db_sysarquivo   where codarq       in (3748,3749);
