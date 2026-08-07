<?php

use Classes\PostgresMigration;

class M14143Conlancamlogatributos extends PostgresMigration
{


    public function up()
    {
        $this->dicionario();
        $this->estrutura();
    }

    private function dicionario()
    {

$sqlUp = <<<SQL
        INSERT INTO configuracoes.db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES (1010695, 'c134_sequencial                         ', 'int4                                    ', 'Sequecial', '0', 'Sequecial', 8, false, false, false, 1, 'text', 'Sequecial');
        INSERT INTO configuracoes.db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES (1010696, 'c134_codlan                             ', 'int4                                    ', 'Código do lançamento', '0', 'Cód Lan', 8, false, false, false, 1, 'text', 'Cod.lan');
        INSERT INTO configuracoes.db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) VALUES (1010697, 'c134_mensagem                           ', 'text                                    ', 'Mensagem', '', 'Mensagem', 100, false, true, false, 0, 'text', 'Mensagem');
        INSERT INTO configuracoes.db_sysarquivo (codarq, nomearq, descricao, sigla, dataincl, rotulo, tipotabela, naolibclass, naolibfunc, naolibprog, naolibform) VALUES (1010466, 'conlancamlogatributos', 'conlancamlogatributos', 'c134 ', '2019-08-26', 'conlancamlogatributos', 0, false, false, false, false);
        INSERT INTO configuracoes.db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES (1010466, 1010696, 2, 0);
        INSERT INTO configuracoes.db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES (1010466, 1010697, 3, 0);
        INSERT INTO configuracoes.db_sysarqcamp (codarq, codcam, seqarq, codsequencia) VALUES (1010466, 1010695, 1, 1000849);
        INSERT INTO configuracoes.db_sysprikey (codarq, codcam, sequen, referen, camiden) VALUES (1010466, 1010695, 1, 0, 1010696);
        INSERT INTO configuracoes.db_sysforkey (codarq, codcam, sequen, referen, tipoobjrel) VALUES (1010466, 1010696, 1, 760, 0);
        INSERT INTO configuracoes.db_sysindices (codind, nomeind, codarq, campounico) VALUES (1008493, 'conlancamlogatributos_codlan_in', 1010466, '0');
        INSERT INTO configuracoes.db_syssequencia (codsequencia, nomesequencia, incrseq, minvalueseq, maxvalueseq, startseq, cacheseq) VALUES (1000849, 'conlancamlogatributos_c134_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
SQL;
        $this->execute($sqlUp);
    }

    private function estrutura()
    {

$sqlUp = <<<SQL
CREATE SEQUENCE contabilidade.conlancamlogatributos_c134_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;
CREATE TABLE contabilidade.conlancamlogatributos(
c134_sequencial		int4 default 0,
c134_codlan		    int4 default 0,
c134_mensagem 		text ,
CONSTRAINT conlancamlogatributos_sequ_pk PRIMARY KEY (c134_sequencial));
ALTER TABLE conlancamlogatributos
ADD CONSTRAINT conlancamlogatributos_codlan_fk FOREIGN KEY (c134_codlan)
REFERENCES conlancam;
CREATE  INDEX conlancamlogatributos_codlan_in ON conlancamlogatributos(c134_codlan);

SQL;
        $this->execute($sqlUp);
    }

    public function down()
    {
        $sqlDown = <<<SQL

drop table contabilidade.conlancamlogatributos; 
drop sequence contabilidade.conlancamlogatributos_c134_sequencial_seq; 

SQL;
        $this->execute($sqlDown);

        $sqlDownDD = <<<SQL
delete from db_syscampo
 using db_sysarqcamp 
 where db_sysarqcamp.codcam = db_syscampo.codcam 
   and codarq = 1010466;
delete from db_sysarqcamp   where codarq = 1010466;
delete from db_sysprikey    where codarq = 1010466;
delete from db_sysforkey    where codarq = 1010466;
delete from db_sysindices   where codarq = 1010466;
delete from db_syssequencia where codsequencia = 1000849;
delete from db_sysarquivo   where codarq = 1010466;
SQL;
        $this->execute($sqlDownDD);

    }

}
