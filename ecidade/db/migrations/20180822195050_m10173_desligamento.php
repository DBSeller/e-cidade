<?php

use Classes\PostgresMigration;

class M10173Desligamento extends PostgresMigration
{

    public function up()
    {
        $this->manutencaoTabelas();

        $aSql = array();

        $aSql[] = "insert into db_itensmenu(id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente) 
                   values (10566, 'Desligamento / Rescisão', 'Desligamento / Rescisão', 'eso01_preenchimentodesligamento.php', '1', '1', 'Desligamento / Rescisão', 'true');";
        $aSql[] = "insert into db_menu(id_item, id_item_filho, menusequencia, modulo) values (10220 ,10566 ,12 ,10216);";


        $aSql[] = "insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) 
                   values ( 10568 ,'Tabelas' ,'Tabelas' ,'con4_cargaformularios001.php?tipo_formulario=5' ,'1' ,'1' ,'Carga geral dos dados do Esocial' ,'true' );";
        $aSql[] = "insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10425 ,10568 ,1 ,10216 );";

        $aSql[] = "update db_itensmenu set funcao = '' where id_item = 10425;";

        $aSql[] = "insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) 
                   values ( 10569 ,'Desligamento/Término' ,'Desligamento/Término' ,'con4_cargaformulariorescisao001.php' ,'1' ,'1' ,'Dados de Desligamento' ,'true' );";

        $aSql[] = "insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10425 ,10569 ,2 ,10216 );";

        $aSql[] = "insert into esocialformulariotipo(rh209_sequencial,rh209_descricao) values(14, 'Desligamento Servidor');";

        $aSql[] = "alter table avaliacaoperguntaopcao alter db104_valorresposta type text";

        foreach ($aSql as $sSql) {
            $this->execute($sSql);
        }
    }

    public function down()
    {
        $this->removeManutencaoTabelas();

        $aSql   = array();
        $aSql[] = "DELETE FROM db_itensmenu WHERE id_item in (10566, 10569, 10568);";
        $aSql[] = "DELETE FROM db_menu WHERE id_item = 10220 AND id_item_filho = 10566 AND modulo = 10216;";
        $aSql[] = "DELETE FROM db_menu WHERE id_item_filho in(10568, 10569)";
        $aSql[] = "update db_itensmenu set funcao = 'con4_cargaformularios001.php?tipo_formulario=5' where id_item = 10425";
        $aSql[] = "DELETE FROM esocialformulariotipo WHERE rh209_sequencial = 14;";
        $aSql[] = "alter table avaliacaoperguntaopcao alter db104_valorresposta type varchar(255)";

        foreach ($aSql as $sSql) {
            $this->execute($sSql);
        }

    }

    public function manutencaoTabelas()
    {
        $sql = <<<SQL
          -- Inclusão da nova tabela avaliacaogruporespostarhpesrescisao
          insert into db_sysarquivo values (1010306, 'avaliacaogruporespostarhpesrescisao', 'Guarda o vinculo entre a rescisão e o grupo des respostas enviado ao eSocial.', 'eso15', '2018-08-24', 'avaliacaogruporespostarhpesrescisao', 0, 'f', 'f', 'f', 'f' );
          insert into db_sysarqmod values (81,1010306);

          insert into db_syscampo values(1009900,'eso15_codigorescisao','varchar(50)','Código identificador de cada rescisão enviada ao eSocial.','', 'Identificador da Rescisão',50,'f','t','f',0,'text','Identificador da Rescisão');
          insert into db_syscampo values(1009901,'eso15_avaliacaogruporesposta','int4','Código do grupo de resposta do formulário.','0', 'Código do Grupo de Resposta',10,'f','f','f',1,'text','Código do Grupo de Resposta');
          insert into db_syscampo values(1009902,'eso15_regist','int4','Matrícula do servidor.','0', 'Matrícula',10,'f','f','f',1,'text','Matrícula');
          insert into db_syscampo values(1009903,'eso15_cgmempregador','int4','Cgm do empregador conforme lotação vinculada ao servidor.','0', 'Cgm do Empregador',10,'f','f','f',1,'text','Cgm do Empregador');
          insert into db_syscampo values(1009905,'eso15_sequencial','int4','Sequencial da tabela que vincula a rescisão com o grupo de resposta.','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');

          insert into db_sysarqcamp values(1010306,1009905,1,0);
          insert into db_sysarqcamp values(1010306,1009900,2,0);
          insert into db_sysarqcamp values(1010306,1009901,3,0);
          insert into db_sysarqcamp values(1010306,1009902,4,0);
          insert into db_sysarqcamp values(1010306,1009903,5,0);

          insert into db_sysprikey values(1010306,1009905,1,1009905);
          insert into db_sysforkey values(1010306,1009901,1,2987,0);
          insert into db_sysforkey values(1010306,1009902,1,1153,0);
          insert into db_sysforkey values(1010306,1009903,1,42,0);

          insert into db_sysindices values(1008314,'avaliacaogruporespostarhpesrescisao_avaliacaogruporesposta_in',1010306,'0');
          insert into db_syscadind values(1008314,1009901,1);
          insert into db_sysindices values(1008315,'avaliacaogruporespostarhpesrescisao_regist_in',1010306,'0');
          insert into db_syscadind values(1008315,1009902,1);
          insert into db_sysindices values(1008316,'avaliacaogruporespostarhpesrescisao_cgmempregador_in',1010306,'0');
          insert into db_syscadind values(1008316,1009903,1);
          
          insert into db_syssequencia values(1000756, 'avaliacaogruporespostarhpesrescisao_eso15_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
          update db_sysarqcamp set codsequencia = 1000756 where codarq = 1010306 and codcam = 1009905;

          CREATE SEQUENCE esocial.avaliacaogruporespostarhpesrescisao_eso15_sequencial_seq
          INCREMENT 1
          MINVALUE 1
          MAXVALUE 9223372036854775807
          START 1
          CACHE 1;
          
          CREATE TABLE esocial.avaliacaogruporespostarhpesrescisao(
          eso15_sequencial int4 NOT NULL default 0,
          eso15_codigorescisao varchar(50) NOT NULL ,
          eso15_avaliacaogruporesposta int4 NOT NULL default 0,
          eso15_regist int4 NOT NULL default 0,
          eso15_cgmempregador int4 NOT NULL default 0,
          CONSTRAINT avaliacaogruporespostarhpesrescisao_sequ_pk PRIMARY KEY (eso15_sequencial));
          
          ALTER TABLE esocial.avaliacaogruporespostarhpesrescisao
          ADD CONSTRAINT avaliacaogruporespostarhpesrescisao_avaliacaogruporesposta_fk FOREIGN KEY (eso15_avaliacaogruporesposta)
          REFERENCES avaliacaogruporesposta;
          
          ALTER TABLE esocial.avaliacaogruporespostarhpesrescisao
          ADD CONSTRAINT avaliacaogruporespostarhpesrescisao_regist_fk FOREIGN KEY (eso15_regist)
          REFERENCES rhpessoal;
          
          ALTER TABLE esocial.avaliacaogruporespostarhpesrescisao
          ADD CONSTRAINT avaliacaogruporespostarhpesrescisao_cgmempregador_fk FOREIGN KEY (eso15_cgmempregador)
          REFERENCES cgm;
          
          CREATE INDEX avaliacaogruporespostarhpesrescisao_avaliacaogruporesposta_in ON esocial.avaliacaogruporespostarhpesrescisao(eso15_avaliacaogruporesposta);
          CREATE INDEX avaliacaogruporespostarhpesrescisao_regist_in ON esocial.avaliacaogruporespostarhpesrescisao(eso15_regist);
          CREATE INDEX avaliacaogruporespostarhpesrescisao_cgmempregador_in ON esocial.avaliacaogruporespostarhpesrescisao(eso15_cgmempregador);


          -- Alteração da tabela rhpesrescisao
          insert into db_syscampo values(1009899,'rh05_codigorescisao','varchar(50)','Código identificador de cada rescisão enviada ao eSocial.','', 'Identificador da Rescisão',50,'t','t','f',0,'text','Identificador da Rescisão');
          insert into db_syscampo values(1009906,'rh05_tiporescisao','int4','Define o tipo de rescisão, podendo ser 1 - Normal (demissão de funcionário) ou 2 - Complementar (após demissão normal, paga-se valor em função de acordos coletivos)','1', 'Tipo de Rescisão',1,'f','f','f',1,'text','Tipo de Rescisão');
        
          insert into db_sysarqcamp values(1161,1009899,14,0);
          insert into db_sysarqcamp values(1161,1009906,15,0);

          alter table rhpesrescisao
          add column rh05_codigorescisao varchar(50);

          alter table rhpesrescisao
          add column  rh05_tiporescisao integer default 1 not null;

          -- Alteração das rescisões p/ adicionar o código identificador da rescisão.
          UPDATE pessoal.rhpesrescisao
          SET rh05_codigorescisao = cast(rh02_regist::varchar||extract(YEAR FROM rh05_recis)::varchar||extract(MONTH FROM rh05_recis) AS bigint)
          FROM rhpessoalmov
          WHERE rh05_seqpes = rh02_seqpes;
          
          update rhpesrescisao set rh05_codigorescisao = '0000000000' where rh05_codigorescisao is null;

          alter table rhpesrescisao
          alter column rh05_codigorescisao set not null;
SQL;
        $this->execute($sql);
    }

    public function removeManutencaoTabelas()
    {
        $sql = <<<SQL
          -- Remoção da tabela avaliacaogruporespostarhpesrescisao
          delete from db_syssequencia where codsequencia = 1000756;
          delete from db_syscadind where codind in (1008314, 1008315, 1008316);
          delete from db_sysindices where codind in (1008314, 1008315, 1008316);
          delete from db_sysforkey where codarq = 1010306;
          delete from db_sysprikey where codarq = 1010306;
          delete from db_sysarqcamp where codarq = 1010306;
          delete from db_syscampo where codcam in (1009900, 1009901, 1009902, 1009903, 1009905);
          delete from db_sysarqmod where codarq = 1010306;
          delete from db_sysarquivo where codarq = 1010306;

          DROP SEQUENCE IF EXISTS esocial.avaliacaogruporespostarhpesrescisao_eso15_sequencial_seq;
          DROP TABLE IF EXISTS esocial.avaliacaogruporespostarhpesrescisao CASCADE;

          -- Remoção das colunas da tabela rhpesrescisao.
          delete from db_sysarqcamp where codcam in (1009899, 1009906);
          delete from db_syscampo where codcam in (1009899, 1009906);

          alter table rhpesrescisao
          drop column rh05_codigorescisao cascade;

          alter table rhpesrescisao
          drop column rh05_tiporescisao cascade;
SQL;
        $this->execute($sql);
    }
}
