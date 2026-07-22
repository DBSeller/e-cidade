<?php

use Classes\PostgresMigration;

class M10181AjusteFormularioS2300 extends PostgresMigration
{
    public function up()
    {

        $this->execute("update avaliacaopergunta set db103_tipo = 5 where db103_sequencial in (3001552, 3001545, 3001538, 3001531, 3001524, 3001517, 3001510, 3001503, 3001496, 3001489);");
        $this->execute("update avaliacaopergunta set db103_obrigatoria = false where db103_sequencial in (3001430, 3001437, 3001438, 3001564);");
        $this->execute(<<<SQL
            update avaliacaopergunta set db103_obrigatoria = false
             where db103_sequencial in (
                    select db103_sequencial
                 from avaliacaogrupopergunta
                 join avaliacaopergunta on  db103_avaliacaogrupopergunta = db102_sequencial
                 where db102_avaliacao = 3000027
                and db102_identificadorcampo in ('afastamento', 'termino', 'supervisorEstagio', 'infoTrabCedido', 'ageIntegracao', 'fgts', 'instEnsino', 'remuneracao', 'infoEstagiario', 'infoDirigenteSindical', 'infoTSVInicio', 'infoDeficiencia', 'cargoFuncao', 'dependente_1', 'dependente_10', 'dependente_2', 'dependente_3', 'dependente_4', 'dependente_5', 'dependente_6', 'dependente_7', 'dependente_8', 'dependente_9', 'contato')
                and db103_obrigatoria is true
             );
SQL
        );


        $this->execute(<<<SQL
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3001901 ,2 ,3000330 ,'Nome social para travesti ou transexual.' ,'nome-social-para-travesti-ou-transexu5b9129dca59ba' ,'false' ,'true' ,8 ,1 ,'' ,0 ,'false' ,'' ,'nmSoc' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3005822 ,3001901 ,'' ,'5b9129dca67d6' ,'true' ,0 ,'' ,'nmSoc' );
SQL
        );


        $this->execute(<<<SQL
            insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10575 ,'Inicial' ,'Inicial' ,'eso4_trabalhadorsemvinculo001.php' ,'1' ,'1' ,'Rotina de manutenção dos dados de trabalhadores sem vínculo empregatício com a instituição.' ,'true' );
            insert into db_menu (id_item, id_item_filho, menusequencia, modulo) values ( 10570 ,10575 ,1 ,10216 );
            update db_itensmenu set id_item = 10570 , descricao = 'Trabalhador sem vínculo' , help = 'Trabalhador sem vínculo' , itemativo = '1' , manutencao = '1' , desctec = 'Rotina de manutenção dos dados de trabalhadores sem vínculo empregatício com a instituição.' , libcliente = 'true' where id_item = 10570;
SQL
        );
    }
    
    

    public function down()
    {
        $this->execute(<<<SQL
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3001901;
            delete from avaliacaoperguntaopcao where db104_avaliacaopergunta = 3001901;
            delete from avaliacaopergunta where db103_sequencial = 3001901;

            delete from db_menu where id_item_filho = 10575 AND modulo = 10216;
            delete from db_itensmenu  where id_item = 10575;
            update db_itensmenu set descricao = 'Trabalhador sem vínculo', itemativo = '1', manutencao = '1', funcao = 'eso4_trabalhadorsemvinculo001.php' where id_item = 10570;
SQL
        );

    }
}
