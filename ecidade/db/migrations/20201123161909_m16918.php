<?php

use Classes\PostgresMigration;

class M16918 extends PostgresMigration
{

    public function up()
    {

        $sSql = <<<SQL

update orcparamseq set o69_descr    = 'Total dos Desembolsos de Pessoal/Demais Despesas por Função', 
                       o69_labelrel = 'Total dos Desembolsos de Pessoal/Demais Despesas por Função' 
where o69_codparamrel = 150 and o69_codseq = 90; 

update orcparamseq set o69_descr    = 'Total dos Desembolsos de Pessoal/Demais Despesas por Função', 
                       o69_labelrel = 'Total dos Desembolsos de Pessoal/Demais Despesas por Função' 
where o69_codparamrel = 171 and o69_codseq = 86; 

update orcparamseq set o69_descr    = 'Total dos Desembolsos de Pessoal/Demais Despesas por Função', 
                       o69_labelrel = 'Total dos Desembolsos de Pessoal/Demais Despesas por Função' 
where o69_codparamrel = 189 and o69_codseq = 90; 

update orcparamseq set o69_descr    = 'Total dos Desembolsos de Pessoal/Demais Despesas por Função', 
                       o69_labelrel = 'Total dos Desembolsos de Pessoal/Demais Despesas por Função' 
where o69_codparamrel = 226 and o69_codseq = 83; 

update orcparamseq set o69_descr    = 'Total dos Desembolsos de Pessoal/Demais Despesas por Função', 
                       o69_labelrel = 'Total dos Desembolsos de Pessoal/Demais Despesas por Função' 
where o69_codparamrel = 234 and o69_codseq = 85; 

SQL;
        
       $this->execute($sSql);

    }


    public function down()
    {

        $sSql = <<<SQL


update orcparamseq set o69_descr    = 'Total dos Desembolsos de Pessoal e Demais Despesas por Funçã', 
                       o69_labelrel = 'Total dos Desembolsos de Pessoal e Demais Despesas por Funçã' 
where o69_codparamrel = 150 and o69_codseq = 90; 

update orcparamseq set o69_descr    = 'Total dos Desembolsos de Pessoal e Demais Despesas por Funçã', 
                       o69_labelrel = 'Total dos Desembolsos de Pessoal e Demais Despesas por Funçã' 
where o69_codparamrel = 171 and o69_codseq = 86; 

update orcparamseq set o69_descr    = 'Total dos Desembolsos de Pessoal e Demais Despesas por Funçã', 
                       o69_labelrel = 'Total dos Desembolsos de Pessoal e Demais Despesas por Funçã' 
where o69_codparamrel = 189 and o69_codseq = 90; 

update orcparamseq set o69_descr    = 'Total dos Desembolsos de Pessoal e Demais Despesas por Funçã', 
                       o69_labelrel = 'Total dos Desembolsos de Pessoal e Demais Despesas por Funçã' 
where o69_codparamrel = 226 and o69_codseq = 83; 

update orcparamseq set o69_descr    = 'Total dos Desembolsos de Pessoal e Demais Despesas por Funçã', 
                       o69_labelrel = 'Total dos Desembolsos de Pessoal e Demais Despesas por Funçã' 
where o69_codparamrel = 234 and o69_codseq = 85; 


SQL;
        
        $this->execute($sSql);
    }
    
}
