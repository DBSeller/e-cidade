<?php

use Classes\PostgresMigration;

class M16999PagamentosObn extends PostgresMigration
{


    public function up() {
        $this->upDicionario();
        $this->upTabela();
    }

    public function down() {
        $this->downDicionario();
        $this->downTabela();  
    }

    private function upDicionario() {
        $sql = <<<SQL

        INSERT INTO db_sysarquivo (codarq, nomearq, descricao, sigla, dataincl, rotulo, tipotabela, naolibclass, naolibfunc, naolibprog, naolibform)
                           VALUES (1010532, 'empagedadosretornodetalhe', 'Dados Detalhados Retorno Bancário', 'e140 ', '2020-03-04', 'Dados Detalhados Retorno', 0, false, false, false, false);

        INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel)
                         VALUES (1011085, 'e140_codmov', 'int4', 'Código Movimento da Agenda de pagamentos', '0', 'Código Movimento da Agenda', 10, false, false, false, 1, 'text', 'Código Movimento da Agenda');

        INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel)
                         VALUES (1011086, 'e140_codret', 'int4', 'Código de retorno do arquivo', '', 'Código de retorno do arquivo', 20, false, false, false, 1, 'text', 'Código do Retorno');

        INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel)
                         VALUES (1011087, 'e140_valor', 'float4', 'Valor da linha do arquivo', '0', 'Valor', 10, false, false, false, 4, 'text', 'Valor');

        INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel)
                         VALUES (1011088, 'e140_numeroautenticacao', 'varchar(20)', 'Número da autenticação do boleto', '', 'Número da autenticação do boleto', 20, true, true, false, 0, 'text', 'Número da autenticação  do boleto');

        INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel)
                         VALUES (1011090, 'e140_datahoraprocessamento', 'varchar(10)', 'Data e Hora Processamento', '', 'Data e Hora Processamento', 10, false, false, false, 0, 'text', 'Data e Hora Processamento');

        INSERT INTO db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel)
                         VALUES (1011089, 'e140_linhaarquivo', 'varchar(700)', 'Dados com a informação da linha do tipo 4 - Pagamentos Código de Barras', '', 'Dados da Linha ', 1, false, true, false, 0, 'text', 'Dados da Linha ');

        INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES (1010532, 1011085, 1, 0);
        INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES (1010532, 1011086, 2, 0);
        INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES (1010532, 1011087, 3, 0);
        INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES (1010532, 1011088, 4, 0);
        INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES (1010532, 1011090, 5, 0);
        INSERT INTO db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES (1010532, 1011089, 6, 0);

        insert into db_sysforkey values(1010532,1011086,1,1207,0);
        insert into db_sysforkey values(1010532,1011085,1,1207,0);

        insert into db_sysindices values(1008552,'empagedadosretornodetalhe_codmov_in',1010532,'0');
        insert into db_sysindices values(1008553,'empagedadosretornodetalhe_codret_in',1010532,'0');

        insert into db_syscampo
          values (1011145, 'e76_linhaarquivo', 'varchar(700)', 'Linha tipo 2 do arquivo de retorno do OBN', ' ', 'Linha Arquivo', 700, 'f', 't', 't', 0, 'text', 'Linha Arquivo');
         
        insert into db_sysarqcamp values (1207,1011145,9,0);
        
        update db_syscampo set conteudo = 'varchar(100)', tamanho = 100 where nomecam = 'e75_arquivoret' ;


SQL;
        $this->execute($sql);
    }

    private function upTabela() {
        $sql = <<<SQL

        create table empenho.empagedadosretornodetalhe (e140_codmov integer not null, 
                                                        e140_codret integer not null, 
                                                        e140_valor numeric default 0, 
                                                        e140_numeroautenticacao varchar, 
                                                        e140_linhaarquivo varchar(700),
                                                        e140_datahoraprocessamento timestamp);

        ALTER TABLE empenho.empagedadosretornodetalhe ADD CONSTRAINT empagedadosretornodetalhe_codret_codmov_fk FOREIGN KEY (e140_codret, e140_codmov) REFERENCES empenho.empagedadosretmov(e76_codret, e76_codmov);

        create index empagedadosretornodetalhe_codmov_in on empenho.empagedadosretornodetalhe(e140_codmov);
        create index empagedadosretornodetalhe_codret_in on empenho.empagedadosretornodetalhe(e140_codret);    
       
        alter table empenho.empagedadosretmov add column e76_linhaarquivo varchar(700);
        alter table empagedadosret alter column e75_arquivoret type varchar(100);

        update db_layoutcampos set db52_nome = 'codigo_movimentacao' where db52_codigo = 10856;

        select setval('errobanco_e92_sequencia_seq', 266);

        insert into errobanco select nextval('errobanco_e92_sequencia_seq'), '00', 'ERRO NAO PROCESSADO', false, 2 ;
        insert into db_errobanco select '001', currval('errobanco_e92_sequencia_seq') ;
        
        insert into errobanco select nextval('errobanco_e92_sequencia_seq'), '09', 'NAO PROCESSADO', false, 2 ;
        insert into db_errobanco select '001', currval('errobanco_e92_sequencia_seq') ;
        
        insert into errobanco select nextval('errobanco_e92_sequencia_seq'), '01', 'PROCESSADO', true, 2 ;
        insert into db_errobanco select '001', currval('errobanco_e92_sequencia_seq') ;
        
        insert into errobanco select nextval('errobanco_e92_sequencia_seq'), '02', 'OB CANCELADA', false, 2 ;
        insert into db_errobanco select '001', currval('errobanco_e92_sequencia_seq') ;
        
        insert into errobanco select nextval('errobanco_e92_sequencia_seq'), '03', 'OB Crd.BB CPF/CNPJ nao confere', false, 2 ;
        insert into db_errobanco select '001', currval('errobanco_e92_sequencia_seq') ;
        
        insert into errobanco select nextval('errobanco_e92_sequencia_seq'), '06', 'Cancelada por comando', false, 2 ;
        insert into db_errobanco select '001', currval('errobanco_e92_sequencia_seq') ;
        
        insert into errobanco select nextval('errobanco_e92_sequencia_seq'), '07', 'OB cancelada/insuficiencia de saldo', false, 2 ;
        insert into db_errobanco select '001', currval('errobanco_e92_sequencia_seq') ;
        
        insert into errobanco select nextval('errobanco_e92_sequencia_seq'), '09', 'Demais cancelamentos', false, 2 ;
        insert into db_errobanco select '001', currval('errobanco_e92_sequencia_seq') ;
        
        insert into errobanco select nextval('errobanco_e92_sequencia_seq'), '11', 'OB Crd.BB conta benef.nao localizada', false, 2 ;
        insert into db_errobanco select '001', currval('errobanco_e92_sequencia_seq') ;
        
        insert into errobanco select nextval('errobanco_e92_sequencia_seq'), '21', 'OB Crd.BB DV ag.benef.invalida', false, 2 ;
        insert into db_errobanco select '001', currval('errobanco_e92_sequencia_seq') ;
        
        insert into errobanco select nextval('errobanco_e92_sequencia_seq'), '22', 'Dv Cta do benef.invalida', false, 2 ;
        insert into db_errobanco select '001', currval('errobanco_e92_sequencia_seq') ;
        
        insert into errobanco select nextval('errobanco_e92_sequencia_seq'), '26', 'Conv.unid.gestora nao cadastrada', false, 2 ;
        insert into db_errobanco select '001', currval('errobanco_e92_sequencia_seq') ;
        
        insert into errobanco select nextval('errobanco_e92_sequencia_seq'), '28', 'Agencia benef.inexistente', false, 2 ;
        insert into db_errobanco select '001', currval('errobanco_e92_sequencia_seq') ;
        
        insert into errobanco select nextval('errobanco_e92_sequencia_seq'), '99', 'TFI devolvida pela compensacao', false, 2 ;
        insert into db_errobanco select '001', currval('errobanco_e92_sequencia_seq') ;
        
        insert into errobanco
          select nextval('errobanco_e92_sequencia_seq'), '04', 'Relacao ja enviada ao banco. Registros com mesmo numero na relacao foram cancelados.', false, 2 ;
        insert into db_errobanco select '001', currval('errobanco_e92_sequencia_seq') ;
        
        insert into errobanco select nextval('errobanco_e92_sequencia_seq'), '08', 'Destino - Conta Corrente ou Poupanca invalida.', false, 2 ;
        insert into db_errobanco select '001', currval('errobanco_e92_sequencia_seq') ;
        
        insert into errobanco select nextval('errobanco_e92_sequencia_seq'), '17', 'O codigo da agencia e inexistente', false, 2 ;
        insert into db_errobanco select '001', currval('errobanco_e92_sequencia_seq') ;
        
        insert into errobanco select nextval('errobanco_e92_sequencia_seq'), '18', 'O contrato e inexistente no pagamento', false, 2 ;
        insert into db_errobanco select '001', currval('errobanco_e92_sequencia_seq') ;
        
        insert into errobanco select nextval('errobanco_e92_sequencia_seq'), '29', 'O codigo do tipo de identificador e diferente do CPF/CNPJ', false, 2 ;
        insert into db_errobanco select '001', currval('errobanco_e92_sequencia_seq') ;
        
        insert into errobanco select nextval('errobanco_e92_sequencia_seq'), '34', 'DV da agencia da UG/Gestao invalido', false, 2 ;
        insert into db_errobanco select '001', currval('errobanco_e92_sequencia_seq') ;

        update empagedadosretmovocorrencia set e02_errobanco = 269 where e02_errobanco = 1000000 ;
        update empagedadosretmovocorrencia set e02_errobanco = 268 where e02_errobanco = 1000001 ;
        update empagedadosretmovocorrencia set e02_errobanco = 267 where e02_errobanco = 1000002 ;

        delete from db_errobanco where e78_errobanco >= 1000000;
        delete from errobanco where e92_sequencia >= 1000000 ;

SQL;
        $this->execute($sql);
    }

    private function downDicionario() {
        $sql = <<<SQL

        delete from db_sysforkey where codarq = 1010532;

        delete from db_sysindices where codarq = 1010532;

        delete from db_sysarqcamp where codarq = 1010532;

        delete from db_sysarquivo where codarq = 1010532;

        delete from db_syscampo where codcam between 1011085 and 1011090;

        delete from db_sysarqcamp where codcam = 1011145 and codarq = 1207;
        delete from db_syscampo where codcam = 1011145;

        update db_syscampo set conteudo = 'varchar(20)', tamanho = 20 where nomecam = 'e75_arquivoret' ;

SQL;
        $this->execute($sql);
    }

    private function downTabela() {
        $sql = <<<SQL
        drop table empenho.empagedadosretornodetalhe;
        alter table empenho.empagedadosretmov drop column e76_linhaarquivo;
        alter table empagedadosret alter column e75_arquivoret type varchar(20);
SQL;
        $this->execute($sql);
    }

}