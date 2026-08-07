<?php

use Classes\PostgresMigration;

class M13511DebContaPedidoHistorico extends PostgresMigration
{

    public function up()
    {

        $sql = "
        create table caixa.debcontapedidohistorico(
         d83_sequencial      bigserial not null,
         d83_debcontapedido  integer not null,
         d83_instit          integer not null,
         d83_banco           integer not null,
         d83_agencia         char(4) not null,
         d83_conta           varchar(14) not null,
         d83_datalanc        date not null,
         d83_horalanc        varchar(5) not null,
         d83_status          integer not null, 
         d83_acao            integer not null,
         d83_idempresa       varchar(25) not null,
         d83_codret          integer not null,
         CONSTRAINT debcontapedidohistorico_codi_pk PRIMARY KEY (d83_sequencial),
         CONSTRAINT debcontapedidohistorico_debcontapedido_fk FOREIGN KEY (d83_debcontapedido) REFERENCES caixa.debcontapedido (d63_codigo) MATCH FULL,
         CONSTRAINT debcontapedidohistorico_disarq_fk FOREIGN KEY (d83_codret) REFERENCES caixa.disarq (codret) MATCH FULL
        )
        WITH (
          OIDS=TRUE
        );

        COMMENT ON COLUMN caixa.debcontapedidohistorico.d83_acao IS '1 - Inclusão, 2 - Alteração, 3 - Exclusão';
        COMMENT ON COLUMN caixa.debcontapedidohistorico.d83_status IS '1 - Pendente, 2 - Ativo, 3 - Inativo';


        INSERT INTO db_sysarquivo VALUES (1010452, 'debcontapedidohistorico', 'Tabela utilizada para manter o histórico do movimento do débito em conta', 'd83', '2019-06-04', 'Débito Conta Pedido Histórico', 0, 'f', 'f', 't', 't' );
        INSERT INTO db_sysarqmod VALUES (5,1010452);
        INSERT INTO db_syscampo VALUES (1010539,'d83_sequencial','int8','Código sequencial da tabela','0', 'Código',19,'f','f','f',1,'text','Código');
        INSERT INTO configuracoes.db_sysarqcamp(codarq,codcam,seqarq,codsequencia) VALUES(1010452,1010539,1,0);
        INSERT INTO db_syscampo VALUES (1010540,'d83_debcontapedido','int4','Codigo do Pedido','0','Codigo Pedido',10,'f','f','f',1,'text','Código Pedido');
        INSERT INTO configuracoes.db_sysarqcamp(codarq,codcam,seqarq,codsequencia) VALUES(1010452,1010540,2,0);
        INSERT INTO db_syscampo VALUES (1010541,'d83_instit','int4','Codigo da Instituicao','0','Codigo da Instituicao',2,'f','f','f',0,'text','Código da instituicao');
        INSERT INTO configuracoes.db_sysarqcamp(codarq,codcam,seqarq,codsequencia) VALUES(1010452,1010541,3,0);
        INSERT INTO db_syscampo VALUES (1010542,'d83_banco','int4','Codigo do Banco','0','Codigo do Banco',3,'f','f','f',1,'text','Código do Banco');
        INSERT INTO configuracoes.db_sysarqcamp(codarq,codcam,seqarq,codsequencia) VALUES(1010452,1010542,4,0);
        INSERT INTO db_syscampo VALUES (1010543,'d83_agencia','char(4)','Agencia',null,'Agencia',4,'f','t','f',0,'text','Agencia');
        INSERT INTO configuracoes.db_sysarqcamp(codarq,codcam,seqarq,codsequencia) VALUES(1010452,1010543,5,0);
        INSERT INTO db_syscampo VALUES (1010544,'d83_conta','varchar(14)','Conta',null,'Conta',14,'f','f','f',0,'text','Conta');
        INSERT INTO configuracoes.db_sysarqcamp(codarq,codcam,seqarq,codsequencia) VALUES(1010452,1010544,6,0);
        INSERT INTO db_syscampo VALUES (1010545,'d83_datalanc','date','Data de lancamento', null,'Data de lancamento',10,'f','f','f',1,'text','Data de lancamento');
        INSERT INTO configuracoes.db_sysarqcamp(codarq,codcam,seqarq,codsequencia) VALUES(1010452,1010545,7,0);
        INSERT INTO db_syscampo VALUES (1010546,'d83_horalanc','char(5)','Hora de lancamento',null,'Hora de lancamento',5,'f','t','f',0,'text','Hora de lancamento');
        INSERT INTO configuracoes.db_sysarqcamp(codarq,codcam,seqarq,codsequencia) VALUES(1010452,1010546,8,0);
        INSERT INTO db_syscampo VALUES (1010547,'d83_status','int4','Status',0,'Status',1,'f','f','f',1,'text','Status');
        INSERT INTO configuracoes.db_sysarqcamp(codarq,codcam,seqarq,codsequencia) VALUES(1010452,1010547,9,0);
        INSERT INTO db_syscampo VALUES (1010548,'d83_acao','int4','Ação',0,'Ação',1,'f','f','f',1,'text','Ação');
        INSERT INTO configuracoes.db_sysarqcamp(codarq,codcam,seqarq,codsequencia) VALUES(1010452,1010548,10,0);
        INSERT INTO db_syscampo VALUES (1010549,'d83_idempresa','varchar(25)','Id Empresa',null,'Id Empresa',25,'t','t','f',0,'text','Id Empresa');
        INSERT INTO configuracoes.db_sysarqcamp(codarq,codcam,seqarq,codsequencia) VALUES(1010452,1010549,11,0);
        INSERT INTO db_syscampo VALUES (1010550,'d83_codret','int4','Codigo para identificar o arquivo de retorno',0,'Codigo do Arquivo',6,'f','f','f',null,null,'Código do Arquivo');
        INSERT INTO configuracoes.db_sysarqcamp(codarq,codcam,seqarq,codsequencia) VALUES(1010452,1010550,12,0);

        INSERT INTO db_itensmenu VALUES (228131, 'Débito em Conta', 'Débito em Conta','', 1, 1, 'Débito em Conta', 't');
        INSERT INTO db_itensmenu VALUES (228132, 'Lançamentos por Banco e Agência', 'Lançamentos por Banco e Agência', 'arr2_reldebcontapedidobcoagencia001.php', 1, 1, 'arr2_reldebcontapedidobcoagencia001.php', 't');
        INSERT INTO db_itensmenu VALUES (228133, 'Lançamentos por Matrícula', 'Lançamentos por Matrícula', 'arr2_reldebcontapedidomatricula001.php', 1, 1, 'arr2_reldebcontapedidomatricula001.php', 't');
        INSERT INTO db_itensmenu VALUES (228134, 'Pagamentos', 'Pagamentos', 'arr2_reldebcontapagamento001.php', 1, 1, 'arr2_reldebcontapagamento001.php', 't');

        INSERT INTO db_menu VALUES (30,228131,486,1985522);
        INSERT INTO db_menu VALUES (228131,228132,1,1985522);
        INSERT INTO db_menu VALUES (228131,228133,2,1985522);
        INSERT INTO db_menu VALUES (228131,228134,3,1985522);

        ";

        $this->execute($sql);
    }

   public function down()
    {
        $sql = "
        DROP TABLE caixa.debcontapedidohistorico;
        DELETE FROM db_sysarqcamp WHERE codarq = 1010452;
        DELETE FROM db_syscampo WHERE codcam IN (1010539,1010540,1010541,1010542,1010543,1010544,1010545,1010546,1010547,1010548,1010549,1010550);
        DELETE FROM db_sysarqmod WHERE codarq = 1010452;
        DELETE FROM db_sysarquivo WHERE codarq = 1010452;
        DELETE FROM db_menu WHERE id_item_filho = 228131;
        DELETE FROM db_itensmenu WHERE id_item IN (228131,228132,228133,228134);
        DELETE FROM db_menu WHERE id_item = 228131;

        ";

        $this->execute($sql);

    }
}
