<?php

use Classes\PostgresMigration;

class M18972SeparacaoS1000S1005 extends PostgresMigration
{
    public function up()
    {
        $this->adicionaEstrutura();
        $this->criaTabela();
        $this->adicionaFormulario();
        $this->migracao();
    }

    private function adicionaEstrutura()
    {
        $sql = <<<SQL
            -- Menu
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228575 ,'Tabela de Estabelecimentos,Obras ou Unidades de Órgãos Públicos' ,'Tabela de Estabelecimentos,Obras ou Unidades de Órgãos Públicos' ,'eso01_preenchimentoobras.php' ,'1' ,'1' ,'Formulário para preenchimento das informações que serão enviadas ao eSocial referente aos preenchimentos de Tabela de Estabelecimentos,Obras ou Unidades de Órgãos Públicos.' ,'true' );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10466 ,228575 ,20 ,10216 );

            -- Dicionario
            insert into configuracoes.db_sysarquivo values (1010828, 'avaliacaogruporespostaobras', 'Vinculo de respostas entre o formulário S1005 e o empregador', 'eso35', '2021-09-15', 'avaliacaogruporespostaobras', 0, 'f', 'f', 'f', 'f' );
            insert into configuracoes.db_sysarqmod values (81,1010828);
            insert into configuracoes.db_syscampo values(1013430,'eso35_sequencial','int8','Código Sequencial da tabela','0', 'Código Sequencial',10,'f','f','f',1,'text','Código Sequencial');
            insert into configuracoes.db_syscampo values(1013431,'eso35_avaliacaogruporesposta','int8','Código da resposta','0', 'Código da Resposta',10,'f','f','f',1,'text','Código da Resposta');
            insert into configuracoes.db_syscampo values(1013432,'eso35_empregador','int8','Código do Empregador','0', 'Código do Empregador',10,'f','f','f',1,'text','Código do Empregador');
            insert into configuracoes.db_syscampo values(1013433,'eso35_cnpj','varchar(20)','CNPJ do Órgão','', 'CNPJ do Órgão',20,'f','t','f',0,'text','CNPJ do Órgão');
            delete from configuracoes.db_sysarqcamp where codarq = 1010828;
            insert into configuracoes.db_sysarqcamp values(1010828,1013430,1,0);
            insert into configuracoes.db_sysarqcamp values(1010828,1013431,2,0);
            insert into configuracoes.db_sysarqcamp values(1010828,1013432,3,0);
            insert into configuracoes.db_sysarqcamp values(1010828,1013433,4,0);
            delete from configuracoes.db_sysprikey where codarq = 1010828;
            insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1010828,1013430,1,1013430);
            delete from configuracoes.db_sysforkey where codarq = 1010828 and referen = 0;
            insert into configuracoes.db_sysforkey values(1010828,1013432,1,42,0);
            insert into configuracoes.db_sysindices values(1008690,'eso35_sequencial_in',1010828,'1');
            insert into configuracoes.db_syscadind values(1008690,1013430,1);
            insert into configuracoes.db_syssequencia values(1001014, 'avaliacaogruporespostaobras_eso35_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update configuracoes.db_sysarqcamp set codsequencia = 1001014 where codarq = 1010828 and codcam = 1013430;
SQL;
        $this->execute($sql);
    }

    private function adicionaFormulario()
    {
        $sql  = <<<SQL
            insert into habitacao.avaliacao values (
                4000104,
                5,
                'S-1005 - Obras ou Unidades de Órgãos Públicos',
                'S-1005 - Obras ou Unidades de Órgãos Públicos',
                't',
                's1005-obras-unidades-orgaos-publicos-s10'
            );

            insert into recursoshumanos.esocialformulariotipo values (36, 'S-1005 - Obras ou Unidades de Órgãos Públicos');

            insert into recursoshumanos.esocialversaoformulario values (
                  (select  nextval('esocialversaoformulario_rh211_sequencial_seq')),
                  'S1.0',
                  4000104,
                  36
            );

            update habitacao.avaliacaogrupopergunta set db102_avaliacao  = 4000104 where db102_sequencial in (4000231, 3000206, 3000207, 3000208, 3000209, 3000211, 3000210, 3000212, 3000214, 3000215, 3000216);
            -- Pergunta vira obrigatoria
            update habitacao.avaliacaopergunta set db103_obrigatoria = 't' where db103_sequencial = 3000916;
            -- Nova ordenacao
            update habitacao.avaliacaogrupopergunta set db102_ordem = 1 where db102_sequencial = 3000206;
            update habitacao.avaliacaogrupopergunta set db102_ordem = 2 where db102_sequencial = 3000207;
            update habitacao.avaliacaogrupopergunta set db102_ordem = 3 where db102_sequencial = 3000208;
            update habitacao.avaliacaogrupopergunta set db102_ordem = 4 where db102_sequencial = 3000209;
            update habitacao.avaliacaogrupopergunta set db102_ordem = 5 where db102_sequencial = 3000210;
            update habitacao.avaliacaogrupopergunta set db102_ordem = 6 where db102_sequencial = 3000211;
            update habitacao.avaliacaogrupopergunta set db102_ordem = 7 where db102_sequencial = 4000231;
            update habitacao.avaliacaogrupopergunta set db102_ordem = 8 where db102_sequencial = 3000214;
            update habitacao.avaliacaogrupopergunta set db102_ordem = 9 where db102_sequencial = 3000215;
            update habitacao.avaliacaogrupopergunta set db102_ordem = 10 where db102_sequencial = 3000216;
SQL;
        $this->execute($sql);
    }

    private function criaTabela()
    {
        $sql = <<<SQL
            -- Criando  sequences
            CREATE SEQUENCE esocial.avaliacaogruporespostaobras_eso35_sequencial_seq
                INCREMENT 1
                MINVALUE 1
                MAXVALUE 9223372036854775807
                START 1
                CACHE 1;

            -- Módulo: esocial
            CREATE TABLE esocial.avaliacaogruporespostaobras(
                eso35_sequencial int4 not null,
                eso35_avaliacaogruporesposta int4 not null,
                eso35_empregador int4 not null,
                eso35_cnpj varchar(20) not null,
                CONSTRAINT avaliacaogruporespostaobras_sequ_pk PRIMARY KEY (eso35_sequencial)
            );

            -- CHAVE ESTRANGEIRA
            ALTER TABLE esocial.avaliacaogruporespostaobras ADD CONSTRAINT avaliacaogruporespostaobras_empregador_fk FOREIGN KEY (eso35_empregador) REFERENCES cgm;

            -- INDICES
            CREATE UNIQUE INDEX eso35_sequencial_in ON avaliacaogruporespostaobras(eso35_sequencial);
SQL;
        $this->execute($sql);
    }

    private function migracao()
    {
        $sql = <<<SQL
            SELECT
                eso03_cgm AS numcgm,
                max(db107_sequencial) AS resposta,
                (
                    SELECT
                        z01_cgccpf
                    FROM
                        cgm
                    WHERE
                    z01_numcgm = eso03_cgm
                ) AS cnpj
            FROM
                avaliacaogruporespostacgm
                JOIN avaliacaogruporesposta ON db107_sequencial = eso03_avaliacaogruporesposta
                JOIN avaliacaogrupoperguntaresposta ON db108_avaliacaogruporesposta = db107_sequencial
                JOIN avaliacaoresposta ON db106_sequencial = db108_avaliacaoresposta
                JOIN avaliacaoperguntaopcao ON db104_sequencial = db106_avaliacaoperguntaopcao
                JOIN avaliacaopergunta ON db103_sequencial = db104_avaliacaopergunta
                JOIN avaliacaogrupopergunta ON db103_avaliacaogrupopergunta = db102_sequencial
                JOIN avaliacao ON db102_avaliacao = db101_sequencial
            WHERE
                db101_sequencial = 3000015
            GROUP BY eso03_cgm;
SQL;


        $stmt = $this->query($sql); // returns PDOStatement
        $rows = $stmt->fetchAll(); // returns the result as an array

        foreach ($rows as $row) {
            $sqlInsert = <<<SQL
                insert into esocial.avaliacaogruporespostaobras values (
                    nextval('avaliacaogruporespostaobras_eso35_sequencial_seq'),
                    {$row['resposta']},
                    {$row['numcgm']},
                    '{$row['cnpj']}'
                );
SQL;
            $this->execute($sqlInsert);
        }

        // o SQL abaixo nao possui down pois na api não terá down tambem
        $sqlAtualizaReferencia = <<<SQL
            update esocial.esocialenvio set rh213_responsavelpreenchimento = (select z01_cgccpf from protocolo.cgm where z01_numcgm = rh213_empregador) where rh213_evento = '1005';
SQL;
        $this->execute($sqlAtualizaReferencia);
    }

    public function down()
    {
        $this->removeEstrutura();
        $this->removeTabela();
        $this->removeFormulario();
        $this->desfazMigracao();
    }

    private function removeFormulario()
    {
        $sql  = <<<SQL
            delete from recursoshumanos.esocialversaoformulario where rh211_esocialformulariotipo = 36;
            delete from recursoshumanos.esocialformulariotipo where rh209_sequencial = 36;
            update habitacao.avaliacaogrupopergunta set db102_avaliacao  = 3000015 where db102_sequencial in (4000231, 3000206, 3000207, 3000208, 3000209, 3000211, 3000210, 3000212, 3000214, 3000215, 3000216);
            delete from habitacao.avaliacao where db101_sequencial = 4000104;

            -- Retorna Pergunta
            update habitacao.avaliacaopergunta set db103_obrigatoria = 'f' where db103_sequencial = 3000916;

            -- Retorna ordenacao
            update habitacao.avaliacaogrupopergunta set db102_ordem = 1 where db102_sequencial in (3000206, 3000207, 3000208, 3000209, 3000210, 3000211, 3000214, 3000215, 3000216, 4000231);
SQL;
        $this->execute($sql);
    }

    private function removeEstrutura()
    {
        $sql = <<<SQL
            -- Dicionario
            delete from configuracoes.db_syssequencia where codsequencia = 1001014;
            delete from configuracoes.db_syscadind where codind = 1008690 and codcam = 1013430;
            delete from configuracoes.db_sysindices where codind = 1008690 and codarq = 1010828;
            delete from configuracoes.db_sysforkey where codarq = 1010828;
            delete from configuracoes.db_sysprikey where codarq = 1010828;
            delete from configuracoes.db_sysarqcamp where codarq = 1010828;
            delete from configuracoes.db_syscampo where codcam in (1013430, 1013431, 1013432, 1013433);
            delete from configuracoes.db_sysarqmod where codarq = 1010828;
            delete from configuracoes.db_sysarquivo where codarq = 1010828;

            -- Menu
            delete from db_menu where id_item_filho = 228575 AND modulo = 10216;
            delete from db_itensmenu where id_item = 228575;
SQL;
        $this->execute($sql);
    }

    private function removeTabela()
    {
        $sql = <<<SQL
        --DROP TABLE:
        DROP TABLE IF EXISTS esocial.avaliacaogruporespostaobras CASCADE;
        --Criando drop sequences
        DROP SEQUENCE IF EXISTS esocial.avaliacaogruporespostaobras_eso35_sequencial_seq;
SQL;
        $this->execute($sql);
    }

    private function desfazMigracao()
    {
        $this->table('configuracoes.bkpmigracaoesocials1000s1005')->drop()->save();
    }
}
