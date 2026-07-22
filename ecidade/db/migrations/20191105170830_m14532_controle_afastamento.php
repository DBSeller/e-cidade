<?php

use Classes\PostgresMigration;

class M14532ControleAfastamento extends PostgresMigration
{
    public function up()
    {
        $this->criaDicionarioDados();
        $this->criaTabela();
        $this->populaTabela();
    }

    public function down()
    {
        $this->removeDicionarioDados();
        $this->removeTabela();
    }

    private function criaDicionarioDados()
    {
        $sql = "
            INSERT INTO db_sysarquivo VALUES (1010476, 'controleafastamento', 'Identifica quais rubricas são calculadas em um afastamento. ', 'rh231', '2019-11-05', 'controleafastamento', 0, 'f', 'f', 'f', 'f' );
            INSERT INTO db_sysarqmod VALUES (28,1010476);
            INSERT INTO db_syscampo VALUES(1010775,'rh231_rubrica','char(4)','Rubrica','', 'Rubrica',4,'f','f','f',0,'text','Rubrica');
            INSERT INTO db_syscampo VALUES(1010776,'rh231_instituicao','int4','Instituição','0', 'Instituição',2,'f','f','f',1,'text','Instituição');
            INSERT INTO db_syscampo VALUES(1010777,'rh231_tabelaprevidencia','int4','Tabela','0', 'Tabela',2,'f','f','f',1,'text','Tabela');
            INSERT INTO db_syscampo VALUES(1010778,'rh231_afastamento','int4','Afastamento','0', 'Afastamento',2,'f','f','f',1,'text','Afastamento');
            INSERT INTO db_syscampo VALUES(1010779,'rh231_ano','int4','Ano','0', 'Ano',4,'f','f','f',1,'text','Ano');
            INSERT INTO db_syscampo VALUES(1010780,'rh231_mes','int4','Mês','0', 'Mês',2,'f','f','f',1,'text','Mês');
            INSERT INTO db_syscampo VALUES(1010781,'rh231_sequencial','int4','Sequencial','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
            INSERT INTO db_sysarqcamp VALUES(1010476,1010781,1,0);
            INSERT INTO db_sysarqcamp VALUES(1010476,1010778,2,0);
            INSERT INTO db_sysarqcamp VALUES(1010476,1010775,3,0);
            INSERT INTO db_sysarqcamp VALUES(1010476,1010777,4,0);
            INSERT INTO db_sysarqcamp VALUES(1010476,1010776,5,0);
            INSERT INTO db_sysarqcamp VALUES(1010476,1010779,6,0);
            INSERT INTO db_sysarqcamp VALUES(1010476,1010780,7,0);
            INSERT INTO db_sysprikey (codarq,codcam,sequen,camiden) VALUES(1010476,1010781,1,1010781);
            INSERT INTO db_sysforkey VALUES(1010476,1010775,1,1177,0);
            INSERT INTO db_sysforkey VALUES(1010476,1010776,2,1177,0);
            INSERT INTO db_sysindices VALUES(1008495,'controleafastamento_rh231_sequencial_in',1010476,'0');
            INSERT INTO db_syscadind VALUES(1008495,1010781,1);
            INSERT INTO db_syssequencia VALUES(1000851, 'controleafastamento_rh231_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            UPDATE db_sysarqcamp SET codsequencia = 1000851 WHERE codarq = 1010476 AND codcam = 1010781;
        ";
        $this->execute($sql);
    }

    private function removeDicionarioDados()
    {
        $sql = "
            DELETE FROM db_syscadind WHERE codind = 1008495;
            DELETE FROM db_sysindices WHERE codind = 1008495;
            DELETE FROM db_sysforkey WHERE codarq = 1010476;
            DELETE FROM db_sysprikey WHERE codarq = 1010476;
            DELETE FROM db_sysarqcamp WHERE codarq = 1010476;
            DELETE FROM db_syscampo WHERE codcam IN (1010775,
                                                    1010776,
                                                    1010777,
                                                    1010778,
                                                    1010779,
                                                    1010780,
                                                    1010781);
            DELETE FROM db_sysarqmod WHERE codarq = 1010476;
            DELETE FROM db_sysarquivo WHERE codarq = 1010476; 
            DELETE FROM db_syssequencia WHERE codsequencia = 1000851;
        ";
        $this->execute($sql);
    }

    private function criaTabela()
    {
        $sql = "
            CREATE SEQUENCE controleafastamento_rh231_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;

            CREATE TABLE pessoal.controleafastamento(
            rh231_sequencial		INT4 NOT NULL DEFAULT nextval('controleafastamento_rh231_sequencial_seq'),
            rh231_afastamento		INT4 NOT NULL DEFAULT 0,
            rh231_rubrica		CHAR(4) NOT NULL ,
            rh231_tabelaprevidencia		INT4 NOT NULL DEFAULT 0,
            rh231_instituicao		INT4 NOT NULL DEFAULT 0,
            rh231_ano		INT4 NOT NULL DEFAULT 0,
            rh231_mes		INT4 NOT NULL DEFAULT 0,
            CONSTRAINT controleafastamento_sequ_pk PRIMARY KEY (rh231_sequencial));
            
            ALTER TABLE pessoal.controleafastamento
            ADD CONSTRAINT controleafastamento_rubrica_instituicao_fk FOREIGN KEY (rh231_rubrica,rh231_instituicao)
            REFERENCES rhrubricas;
            
            CREATE  INDEX controleafastamento_rh231_sequencial_in ON pessoal.controleafastamento(rh231_sequencial);
        ";
        $this->execute($sql);
    }

    private function populaTabela()
    {
        $situacoes = $this->fetchAll("SELECT *  FROM situacaoafastamento  WHERE rh166_sequencial  NOT IN (2, 4, 7)");
        $rubricas = $this->fetchAll("SELECT * FROM rhrubricas WHERE rh27_calcp = 't' AND rh27_ativo = 't'");
        $tabelas = array(3, 4, 5, 6);
        $competenciasInstituicoes = array();

        foreach ($situacoes as $situacao) {
            $codigoAfastamento = $situacao['rh166_sequencial'];

            foreach ($tabelas as $tabela) {

                foreach ($rubricas as $rubrica) {

                    $codigoRubrica = $rubrica['rh27_rubric'];
                    $instituicao = $rubrica['rh27_instit'];

                    if (isset($competenciasInstituicoes[$instituicao])) {
                        $ano = $competenciasInstituicoes[$instituicao]['ano'];
                        $mes = $competenciasInstituicoes[$instituicao]['mes'];
                    } else {
                        $competencia = $this->fetchRow("SELECT fc_anofolha({$instituicao}) AS ano, fc_mesfolha({$instituicao}) AS mes");
                        $ano = $competencia['ano'];
                        $mes = $competencia['mes'];
                        if (empty($mes) || empty($ano)) {
                            continue;
                        }
                        $competenciasInstituicoes[$instituicao] = $competencia;
                    }

                    $sql = "INSERT INTO pessoal.controleafastamento (
                                         rh231_afastamento,
                                         rh231_rubrica,
                                         rh231_tabelaprevidencia,
                                         rh231_instituicao,
                                         rh231_ano,
                                         rh231_mes) 
                                  VALUES (
                                    {$codigoAfastamento},
                                    '{$codigoRubrica}',
                                    $tabela,
                                    $instituicao,
                                    $ano,
                                    $mes)";
                    $this->execute($sql);
                } // endfor rubricas
            } // end for tabelas
        } // end for situacoes
    }

    private function removeTabela()
    {
        $sql = "
            DROP TABLE pessoal.controleafastamento;
            DROP SEQUENCE IF EXISTS controleafastamento_rh231_sequencial_seq;
        ";

        $this->execute($sql);
    }
}
