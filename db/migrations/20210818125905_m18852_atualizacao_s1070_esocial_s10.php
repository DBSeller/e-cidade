<?php

use Classes\PostgresMigration;

class M18852AtualizacaoS1070EsocialS10 extends PostgresMigration
{
    public function up()
    {
        $sql = <<<SQL
            delete from avaliacaogrupoperguntaresposta where db108_avaliacaoresposta in (select db106_sequencial from avaliacaoresposta where db106_avaliacaoperguntaopcao in (3004070, 3003917, 3003918, 3003919, 3003920, 3003921, 3003923, 3003924));
            delete from avaliacaoresposta where db106_avaliacaoperguntaopcao in (3004070, 3003917, 3003918, 3003919, 3003920, 3003921, 3003923, 3003924);
            delete from avaliacaoperguntaopcao where db104_sequencial in (3004070, 3003917, 3003918, 3003919, 3003920, 3003921, 3003923, 3003924);
SQL;
        $this->execute($sql);
    }

    public function down()
    {
        $sql = <<<SQL
            INSERT INTO avaliacaoperguntaopcao
                (db104_sequencial, db104_avaliacaopergunta, db104_descricao, db104_aceitatexto, db104_identificador, db104_peso, db104_valorresposta, db104_identificadorcampo) VALUES
                 (3004070, 3000991, 'Número de Benefício (NB) do INSS', 'f', 'numero-de-beneficio-inss5a997a2c9f73b', 0, 3, 'tpProc');
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003917 ,3000996 ,'Autorização de trabalho de menor' ,'autorizacao-de-trabalho-de-menor' ,'false' ,0 ,'2' ,'indMatProc_2' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003918 ,3000996 ,'Dispensa, ainda que parcial, de contratação de pessoa com deficiência (PCD)' ,'dispensa-ainda-que-parcial-de-contratacao-de-pesso' ,'false' ,0 ,'3' ,'indMatProc_3' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003919 ,3000996 ,'Dispensa, ainda que parcial, de contratação de aprendiz' ,'dispensa-ainda-que-parcial-de-contratacao-de-apren' ,'false' ,0 ,'4' ,'indMatProc_4' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003920 ,3000996 ,'Segurança e Saúde do Trabalho' ,'seguranca-e-saude-do-trabalho' ,'false' ,0 ,'5' ,'indMatProc_5' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003921 ,3000996 ,'Conversão de Licença Saúde em Acidente de Trabalho' ,'conversao-de-licenca-saude-em-acidente-de-trabalho' ,'false' ,0 ,'6' ,'indMatProc_6' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003923 ,3000996 ,'Contribuição sindical' ,'contribuicao-sindical' ,'false' ,0 ,'8' ,'indMatProc_8' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003924 ,3000996 ,'Outros assuntos' ,'outros-assuntos' ,'false' ,0 ,'99' ,'indMatProc_99' );


SQL;
        $this->execute($sql);
    }
}
