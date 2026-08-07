<?php

use Classes\PostgresMigration;

class M12287EsocialVersao25S2206 extends PostgresMigration
{
    public function up()
    {
        $this->execute("
            update avaliacaoperguntaopcao set db104_sequencial = 4000347 , db104_avaliacaopergunta = 3002065 , db104_descricao = 'Prazo determinado, definido em dias' , db104_identificador = 'prazo-determinado5b991b0358d42' , db104_aceitatexto = 'false' , db104_peso = 0 , db104_valorresposta = '2' , db104_identificadorcampo = 'tpContr_2' where db104_sequencial = 4000347;
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002367 ,2 ,3000461 ,'Indicação do objeto determinante da contratação por prazo determinado:' ,'indicacao-do-objeto-determinante-da-c5bf82af14eb54' ,'false' ,'true' ,3 ,1 ,'' ,0 ,'false' ,'' ,'objDet' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000906 ,3002065 ,'Prazo determinado, vinculado à ocorrência de um fato' ,'prazo-determinado-vinculado-a-ocorre5bf82af14d135' ,'false' ,0 ,'3' ,'tpContr_3' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000907 ,3002367 ,'' ,'5bf82af14f9d3' ,'true' ,0 ,'' ,'objDet' );
        ");
    }

    public function down()
    {
        $this->execute("
            DELETE FROM avaliacaoperguntaopcao WHERE db104_sequencial IN (4000906, 4000907);
            DELETE FROM avaliacaopergunta WHERE db103_sequencial IN (3002367);
            UPDATE avaliacaoperguntaopcao SET db104_descricao = 'Prazo determinado' WHERE db104_sequencial = 4000347;
        ");
    }
}
