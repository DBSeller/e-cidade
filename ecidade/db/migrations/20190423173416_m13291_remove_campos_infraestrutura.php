<?php

use Classes\PostgresMigration;

class M13291RemoveCamposInfraestrutura extends PostgresMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {
        $this->execute(<<<SQL
            delete from avaliacaoperguntaopcaolayoutcampo where ed313_avaliacaoperguntaopcao in (select db104_sequencial from avaliacaoperguntaopcao where db104_avaliacaopergunta in (3000021, 3000022));
            delete from avaliacaogrupoperguntaresposta where db108_avaliacaoresposta in (select db106_sequencial from avaliacaoresposta where db106_avaliacaoperguntaopcao in (select db104_sequencial from avaliacaoperguntaopcao where db104_avaliacaopergunta in (3000021, 3000022)));
            delete from avaliacaoresposta where db106_avaliacaoperguntaopcao in (select db104_sequencial from avaliacaoperguntaopcao where db104_avaliacaopergunta in (3000021, 3000022));
            delete from avaliacaoperguntaopcao where db104_avaliacaopergunta in (3000021, 3000022);
            delete from avaliacaopergunta where db103_sequencial in (3000021, 3000022);

            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values (4000246, 3, 3000013 ,'Tipo de atendimento:' ,'tipo-de-atendimento' ,'true' ,'true' ,7 ,1 ,'' ,0 ,'false' ,'' ,'tipo_de_atendimento' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001296, 4000246,'Escolarização' ,'escolarizacao' , 'false', 0, '', 'tipo_atendimento_escolarizacao');
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001297, 4000246,'Atividade Complementar' ,'atividade-complementar' , 'false', 0, '', 'tipo_atendimento_atividade_complementar');
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001298, 4000246,'Atendimento educacional especializado - AEE' ,'aee' , 'false', 0, '', 'tipo_atendimento_aee');

            delete from avaliacaoperguntaopcao where db104_sequencial = 4001211;
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values (3000021, 1, 3000013 ,'Atividade Complementar:' ,'atividade-complementar' ,'false' ,'true' ,7 ,1 ,'' ,0 ,'false' ,'' ,'atividade_complementar' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3000105, 3000021,'NÃO EXCLUSIVAMENTE' ,'atividade-complementar-nao-exclusivamente' , 'false', 0, '', 'atividade_complementar_nao_exclusivamente');
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3000106, 3000021,'NÃO OFERECE' ,'atividade-complementar-nao' , 'false', 0, '', 'atividade_complementar_nao');
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3000107, 3000021,'EXCLUSIVAMENTE' ,'atividade-complementar-exclusivamente' , 'false', 0, '', 'atividade_complementar_exclusivamente');

            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values (3000022, 1, 3000013 ,'Atendimento Educ. Especializado AEE:' ,'atendimento-aee' ,'false' ,'true' ,7 ,1 ,'' ,0 ,'false' ,'' ,'atendimento_educ_especializado_aee' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3000108, 3000022,'NÃO EXCLUSIVAMENTE' ,'atendimento-aee-nao-exclusivamente' , 'false', 0, '', 'atividade_complementar_nao_exclusivamente');
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3000109, 3000022,'NÃO OFERECE' ,'atendimento-aee-nao' , 'false', 0, '', 'atividade_complementar_nao');
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3000110, 3000022,'EXCLUSIVAMENTE' ,'atendimento-aee-exclusivamente' , 'false', 0, '', 'atividade_complementar_exclusivamente');


            delete from avaliacaoperguntaopcao where db104_sequencial in (
                4001296,
                4001297,
                4001298
            );
            delete from avaliacaopergunta where db103_sequencial = 4000246;

            insert into avaliacaoperguntaopcao(db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001211, 4000231,'Internet banda larga' ,'internet-banda-larga' , 'false', 0, '', 'internet_banda_larga');
SQL
        );
    }
}
