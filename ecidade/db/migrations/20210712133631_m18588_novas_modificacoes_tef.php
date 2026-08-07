<?php

use Classes\PostgresMigration;

class M18588NovasModificacoesTef extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
            ALTER TABLE caixa.operacoesrealizadastef ADD COLUMN k198_terminal INTEGER;
            ALTER TABLE caixa.operacoesrealizadastef ADD COLUMN k198_confirmadoautorizadora BOOLEAN DEFAULT FALSE;

            insert into db_syscampo values(1013334,'k198_terminal','int4','Campo que guarda o código do terminal','0', 'Terminal',11,'t','f','f',1,'text','Terminal');
            insert into db_sysarqcamp values(1010796,1013334,18,0);
            insert into db_sysforkey values(1010796,1013334,1,199,0);

            update db_syscampo set nomecam = 'k198_codigoaprovacao', conteudo = 'text', descricao = 'Código da aprovação retornado pelo CTFClient', valorinicial = '0', rotulo = 'Código Aprovação', nulo = 't', tamanho = 50, maiusculo = 'f', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Código Aprovação' where codcam = 1013231;
            update db_syscampo set nomecam = 'k198_retorno', conteudo = 'text', descricao = 'Campo que guarda todas as informações retornadas pelo CTFClient', valorinicial = '', rotulo = 'Retorno', nulo = 't', tamanho = 1000, maiusculo = 'f', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Retorno' where codcam = 1013234;

            insert into db_syscampo values(1013335,'k198_confirmadoautorizadora','text','Campo que informa se a operação foi confirmada pela autorizadora.','', 'Confirmado na Autorizadora',1,'t','t','f',0,'text','Confirmado na Autorizadora');
            insert into db_sysarqcamp values(1010796,1013335,19,0);

            select configuracoes.fc_auditoria_cria_funcao('caixa.operacoesrealizadastef');

            alter table caixa.operacoesrealizadastef alter column k198_retorno drop not null;

            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228547 ,'Operações Pendentes TEF' ,'Relatório com as operações pendentes de TEF' ,'arr2_operacoespendentestef001.php' ,'1' ,'1' ,'Relatório com as operações pendentes de TEF' ,'false' );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 30 ,228547 ,837 ,1985522 );

            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228548 ,'Operações Pendentes TEF' ,'Rotina para informar qual ação foi tomada sobre a operação no Portal Auttar' ,'arr2_operacoespendentestef002.php' ,'1' ,'1' ,'Rotina para informar qual ação foi tomada sobre a operação no Portal Auttar' ,'false' );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 32 ,228548 ,539 ,1985522 );
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
            ALTER TABLE caixa.operacoesrealizadastef DROP COLUMN k198_terminal;
            ALTER TABLE caixa.operacoesrealizadastef DROP COLUMN k198_confirmadoautorizadora;

            delete from db_sysforkey where codcam = 1013334;
            delete from db_sysarqcamp where codcam in (1013334, 1013335);
            delete from db_syscampo where codcam in (1013334, 1013335);

            select configuracoes.fc_auditoria_remove_funcao('caixa.operacoesrealizadastef');
SQL
        );
    }
}
