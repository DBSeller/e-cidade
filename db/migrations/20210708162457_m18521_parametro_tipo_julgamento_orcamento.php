<?php

use Classes\PostgresMigration;

class M18521ParametroTipoJulgamentoOrcamento extends PostgresMigration
{
    public function up() 
    {
        $this->upDicionario();
        $this->upEstrutura();
    }

    public function down()
    {
        
        $this->downDicionario();
        $this->downEstrutura();
    }
    
    public function upDicionario() 
    {
        $this->execute(<<<SQL
        insert into db_syscampo values(1013325,'pc30_tipojulgamentoorcamento','int4','Tipo de Julgamento para orçamento dos itens','0', 'Tipo de Julgamento',10,'f','f','f',1,'text','Tipo de Julgamento');
        insert into db_syscampodef values(1013325,'1','Menor Preço');
        insert into db_syscampodef values(1013325,'2','Média Orçada');
        insert into db_sysarqcamp values(1058,1013325,46,0);         
SQL
);          
    }

    public function downDicionario() 
    {
        $this->execute(<<<SQL
        delete 
          from db_syscampodef 
         where codcam = 1013325;

        delete 
          from db_sysarqcamp 
         where codcam = 1013325;

        delete 
         from db_syscampo 
        where codcam = 1013325; 
SQL
);          
    }

    public function upEstrutura()
    {  
        /**
         * pc30_tipojulgamentoorcamento = 1 - Menor Preço 2 - Média Orçada
         */
        $this->execute(<<<SQL
        alter table pcparam 
          add column pc30_tipojulgamentoorcamento integer not null default 1;
SQL
);      
    }

    public function downEstrutura()
    {      
        $this->execute(<<<SQL
        alter table pcparam 
         drop column pc30_tipojulgamentoorcamento;
SQL
);       
    }
}
