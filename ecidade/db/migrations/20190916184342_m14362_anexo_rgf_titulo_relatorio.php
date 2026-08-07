<?php

use Classes\PostgresMigration;

class M14362AnexoRgfTituloRelatorio extends PostgresMigration
{

    public function up()
    {
        $this->execute(<<<SQL_UP
update orcparamrel set o42_descrrel = 'RGF - ANEXO IV (2018/2019)' where o42_codparrel = 185;

update orcparamseq 
   set o69_descr = substr('Operações de crédito não sujeitas ao limite para fins de contratação¹ (I)', 1, 60)
     , o69_labelrel = 'Operações de crédito não sujeitas ao limite para fins de contratação¹ (I)'
 where o69_codparamrel = 185 
   and o69_codseq = 10;

update orcparamseq 
   set o69_descr = substr('Operações de crédito não sujeitas ao limite para fins de contratação (II)', 1, 60)
     , o69_labelrel = 'Operações de crédito não sujeitas ao limite para fins de contratação (II)'
 where o69_codparamrel = 185 
   and o69_codseq = 16;





update orcparamrel set o42_descrrel = 'RGF - ANEXO I 9ª EDIÇÃO (2018/2019)' where o42_codparrel = 182;

update orcparamseq 
   set o69_descr = substr('Outras despesas de pessoal decorrentes de contratos de terceirização ou de contratação de forma indireta (§ 1º do art. 18 da LRF)', 1, 60)
     , o69_labelrel = 'Outras despesas de pessoal decorrentes de contratos de terceirização ou de contratação de forma indireta (§ 1º do art. 18 da LRF)'
 where o69_codparamrel = 182 
   and o69_codseq = 10;

update orcparamseq 
   set o69_descr = substr('= RECEITA CORRENTE LÍQUIDA AJUSTADA (VI)', 1, 60)
     , o69_labelrel = '= RECEITA CORRENTE LÍQUIDA AJUSTADA (VI)'
 where o69_codparamrel = 182 
   and o69_codseq = 19;


   
SQL_UP
);
    }

    public function down()
    {

        $this->execute(<<<SQL_DOWN

update orcparamrel set o42_descrrel = 'RGF - ANEXO IV (2018)' where o42_codparrel = 185;


update orcparamseq 
   set o69_descr = substr('Operações de crédito previstas no art. 7º § 3º da RSF nº 43/2001 1 (I)', 1, 60)
     , o69_labelrel = 'Operações de crédito previstas no art. 7º § 3º da RSF nº 43/2001 1 (I)'
 where o69_codparamrel = 185 
   and o69_codseq = 10;

update orcparamseq 
   set o69_descr = substr('Operações de crédito previstas no art. 7º § 3º da RSF nº 43/2001 1 (II)', 1, 60)
     , o69_labelrel = 'Operações de crédito previstas no art. 7º § 3º da RSF nº 43/2001 1 (II)'
 where o69_codparamrel = 185 
   and o69_codseq = 16;

update orcparamrel set o42_descrrel = 'RGF - ANEXO I 9ª EDIÇÃO (2018)' where o42_codparrel = 182;

update orcparamseq 
   set o69_descr = substr('Outras despesas de pessoal decorrentes de contratos de terceirização (§ 1º do art. 18 da LRF)', 1, 60)
     , o69_labelrel = 'Outras despesas de pessoal decorrentes de contratos de terceirização (§ 1º do art. 18 da LRF)'
 where o69_codparamrel = 182 
   and o69_codseq = 10;
   
update orcparamseq 
   set o69_descr = substr('IGUAL RECEITA CORRENTE LÍQUIDA AJUSTADA (VI)', 1, 60)
     , o69_labelrel = 'IGUAL RECEITA CORRENTE LÍQUIDA AJUSTADA (VI)'
 where o69_codparamrel = 182 
   and o69_codseq = 19;
   	
SQL_DOWN
);
    }
}
