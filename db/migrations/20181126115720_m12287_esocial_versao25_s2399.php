<?php

use Classes\PostgresMigration;

class M12287EsocialVersao25S2399 extends PostgresMigration
{
    public function up()
    {
        $this->execute("
            INSERT INTO avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) 
            VALUES ( 3000524 ,3000033 ,'Informação do novo CPF do trabalhador' ,'informacao-do-novo-cpf-do-trabalhador5bfbe50784cde' ,'mudancaCPF' ,3 );
            
            INSERT INTO avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo )
            VALUES ( 3002373 ,1 ,3000501 ,'Indicativo de pensão alimentícia para fins de retenção de FGTS' ,'indicativo-de-pensao-alimenticia-para-fins-de-rete' ,'false' ,'true' ,3 ,1 ,'' ,0 ,'false' ,'' ,'pensAlim' ),
                   ( 3002374 ,2 ,3000501 ,'Percentual a ser destinado a pensão alimentícia' ,'percentual-a-ser-destinado-a-pensao-alimenticia' ,'false' ,'true' ,4 ,8 ,'' ,0 ,'false' ,'' ,'percAliment' ),
                   ( 3002375 ,2 ,3000501 ,'Valor da pensão alimentícia' ,'valor-da-pensao-alimenticia5bfbe50783891' ,'false' ,'true' ,5 ,8 ,'' ,0 ,'false' ,'' ,'vrAlim' ),
                   ( 3002376 ,2 ,3000524 ,'Preencher com o novo CPF do trabalhador.' ,'preencher-com-o-novo-cpf-do-trabalhad5bfbe507855c6' ,'false' ,'true' ,1 ,4 ,'' ,0 ,'false' ,'' ,'novoCPF' );
            
            INSERT INTO avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo )
            VALUES (4000914, 3002373 ,'Percentual de pensão alimentícia' ,'percentual-de-pensao-alimenticia5bfbe507807be' ,'false' ,0 ,'1' ,'pensAlim_1' ),
                   (4000915, 3002373 ,'Valor de pensão alimentícia' ,'valor-de-pensao-alimenticia5bfbe50781099' ,'false' ,0 ,'2' ,'pensAlim_2' ),
                   (4000916, 3002373 ,'Percentual e valor de pensão alimentícia' ,'percentual-e-valor-de-pensao-alimenti5bfbe507818a8' ,'false' ,0 ,'3' ,'pensAlim_3' ),
                   (4000917, 3002374 ,'' ,'5bfbe50783035' ,'true' ,0 ,'' ,'percAliment' ),
                   (4000918, 3002375 ,'' ,'5bfbe507844a6' ,'true' ,0 ,'' ,'vrAlim' ),
                   (4000919, 3002376 ,'' ,'5bfbe50786224' ,'true' ,0 ,'' ,'novoCPF' ),
                   (4001194, 3002260 ,'Mudança de CPF' ,'mudanca_de_cpf' ,'false' ,0 ,'07' ,'mtvDesligTSV_8' );
        ");
    }

    public function down()
    {
        $this->execute("
            DELETE FROM avaliacaoperguntaopcao WHERE db104_sequencial IN (4000914, 4000915, 4000916, 4000917, 4000918, 4000919, 4001194);
            DELETE FROM avaliacaopergunta WHERE db103_sequencial IN (3002373, 3002374, 3002375, 3002376);
            DELETE FROM avaliacaogrupopergunta WHERE db102_sequencial IN (3000524);
        ");
    }

}
