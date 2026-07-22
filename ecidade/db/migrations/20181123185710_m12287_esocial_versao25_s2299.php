<?php

use Classes\PostgresMigration;

class M12287EsocialVersao25S2299 extends PostgresMigration
{
    public function up()
    {
        $this->execute("
            update avaliacaogrupopergunta set db102_descricao = 'Dados do desligamento do vínculo' where db102_sequencial = 3000397;
    
            insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem )
            values ( 3000522 ,3000026 ,'Informação do novo CPF do trabalhador' ,'informacao-do-novo-cpf-do-trabalhador' ,'mudancaCPF' ,5 );
            
            insert into avaliacaopergunta(db103_sequencial, db103_avaliacaotiporesposta, db103_avaliacaogrupopergunta, db103_descricao, db103_identificador, db103_obrigatoria, db103_ativo, db103_ordem, db103_tipo, db103_mascara, db103_dblayoutcampo, db103_perguntaidentificadora, db103_camposql, db103_identificadorcampo)
            values (3002369, 2, 3000522 ,'Preencher com o novo CPF do trabalhador' ,'preencher-com-o-novo-cpf-do-trabalhador' ,'false' ,'true' ,1 ,4 ,'' ,0 ,'false' ,'' ,'novoCPF' );
                   
            update avaliacaopergunta set db103_descricao = 'Indicativo de pensão alimentícia para fins de retenção de FGTS:' where db103_sequencial = 3001777;
            
            insert into avaliacaoperguntaopcao(db104_sequencial, db104_avaliacaopergunta, db104_descricao, db104_identificador, db104_aceitatexto, db104_peso, db104_valorresposta, db104_identificadorcampo)
            values (4000910 ,3002369, '', '5bfbccff78000' ,'true' ,0 , '' , 'novoCPF');
        ");
    }

    public function down()
    {

        $this->execute("
            UPDATE avaliacaopergunta SET db103_descricao = 'Tipo de pensão alimentícia:' WHERE db103_sequencial = 3001777;
            
            DELETE FROM avaliacaoperguntaopcao WHERE db104_sequencial = 4000910;
            DELETE FROM avaliacaopergunta WHERE db103_sequencial = 3002369;
            DELETE FROM avaliacaogrupopergunta WHERE db102_sequencial = 3000522;
        ");
    }
}
