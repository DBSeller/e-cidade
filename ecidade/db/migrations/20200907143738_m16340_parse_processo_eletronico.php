<?php

use Classes\PostgresMigration;

class M16340ParseProcessoEletronico extends PostgresMigration
{
    public function up()
    {
        $this->upDicionarioDados();
        $this->upDDL();
    }
    
    public function upDicionarioDados() 
    {
        $upDicionarioDados = <<<SQL
            INSERT INTO db_syscampo VALUES(1011804,'q150_alvaraautonomo_processoeletronico','int4','Código do processo de alvará de empresa da aplicação Processo Eletrônico','0', 'Processo Eletrônico Alvará Autonomo',19,'t','f','f',1,'text','Processo Eletrônico Alvará Autonomo');
            INSERT INTO db_syscampo VALUES(1011805,'q150_alvaraempresa_processoeletronico','int4','Código do processo de alvará de empresa da aplicação Processo Eletrônico','0', 'Processo Eletrônico Alvará Empresa',19,'t','f','f',1,'text','Processo Eletrônico Alvará Empresa');
            INSERT INTO db_syscampo VALUES(1011806,'q150_alvaramei_processoeletronico','int4','Código do processo de alvará MEI da aplicação Processo Eletrônico','0', 'Processo Eletrônico Alvará MEI',19,'t','f','f',1,'text','Processo Eletrônico Alvará MEI');

            UPDATE db_syscampo SET nomecam = 'q150_alvaraautonomo', conteudo = 'varchar(1)', descricao = 'Código do processo de alvará autonomo da aplicação Alvara On-line', valorinicial = '', rotulo = 'Código Alvará Autonomo', nulo = 'f', tamanho = 1, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Código Alvará Autonomo' where codcam = 1010759;
            DELETE FROM db_syscampodep WHERE codcam = 1010759;
            DELETE FROM db_syscampodef WHERE codcam = 1010759;

            UPDATE db_syscampo SET nomecam = 'q150_alvaraempresa', conteudo = 'varchar(1)', descricao = 'Código do processo de alvara empresa da aplicação Alvara On-line', valorinicial = '', rotulo = 'Código Processo Alvará Empresa', nulo = 'f', tamanho = 1, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Código Processo Alvará Empresa' WHERE codcam = 1010760;
            DELETE FROM db_syscampodep WHERE codcam = 1010760;
            DELETE FROM db_syscampodef WHERE codcam = 1010760;

            UPDATE db_syscampo SET nomecam = 'q150_alvaramei', conteudo = 'varchar(1)', descricao = 'Código do processo de alvara mei da aplicação Alvara On-line', valorinicial = '', rotulo = 'Código Processo Alvará Mei', nulo = 'f', tamanho = 1, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Código Processo Alvará Mei' WHERE codcam = 1010761;
            DELETE FROM db_syscampodep WHERE codcam = 1010761;
            DELETE FROM db_syscampodef WHERE codcam = 1010761;

            DELETE FROM db_sysarqcamp WHERE codarq = 1010473;
            INSERT INTO db_sysarqcamp VALUES (1010473,1010762,1,0);
            INSERT INTO db_sysarqcamp VALUES (1010473,1010759,2,0);
            INSERT INTO db_sysarqcamp VALUES (1010473,1010760,3,0);
            INSERT INTO db_sysarqcamp VALUES (1010473,1010761,4,0);
            INSERT INTO db_sysarqcamp VALUES (1010473,1010765,5,0);
            INSERT INTO db_sysarqcamp VALUES (1010473,1010766,6,0);
            INSERT INTO db_sysarqcamp VALUES (1010473,1010767,7,0);
            INSERT INTO db_sysarqcamp VALUES (1010473,1011804,8,0);
            INSERT INTO db_sysarqcamp VALUES (1010473,1011805,9,0);
            INSERT INTO db_sysarqcamp VALUES (1010473,1011806,10,0);
SQL;
        $this->execute($upDicionarioDados);
    }
    
    public function upDDL() 
    {
        $upDDL = <<<SQL
            INSERT INTO formareclamacao (p42_sequencial, p42_descricao) VALUES ((SELECT max(p42_sequencial)+1 FROM formareclamacao), 'Processo Eletronico');

            ALTER TABLE issqn.parametroprocessoeletronico
                ADD COLUMN q150_alvaraautonomo_processoeletronico integer;
            
            ALTER TABLE issqn.parametroprocessoeletronico
                ADD COLUMN q150_alvaraempresa_processoeletronico integer;
            
            ALTER TABLE issqn.parametroprocessoeletronico
                ADD COLUMN q150_alvaramei_processoeletronico integer;
SQL;
        $this->execute($upDDL);
    }

    public function down()
    {
        $this->downDicionarioDados();
        $this->downDDL();
    }

    public function downDicionarioDados() 
    {
        $downDicionarioDados = <<<SQL
            DELETE FROM db_sysarqcamp WHERE codarq = 1010473 AND codcam IN (1011804, 1011805, 1011806);
            
            DELETE FROM db_syscampodef WHERE codcam IN (1010761, 1010760, 1010759);
            DELETE FROM db_syscampodep WHERE codcam IN (1010761, 1010760, 1010759);

            DELETE FROM db_syscampo WHERE codcam IN (1011804, 1011805, 1011806);
SQL;
        $this->execute($downDicionarioDados);
    }
    
    public function downDDL() 
    {
        $downDDL = <<<SQL
            ALTER TABLE issqn.parametroprocessoeletronico DROP COLUMN q150_alvaramei_processoeletronico;
            ALTER TABLE issqn.parametroprocessoeletronico DROP COLUMN q150_alvaraempresa_processoeletronico;
            ALTER TABLE issqn.parametroprocessoeletronico DROP COLUMN q150_alvaraautonomo_processoeletronico;

            DELETE FROM tipoprocformareclamacao WHERE p43_formareclamacao IN (SELECT p42_sequencial FROM formareclamacao WHERE p42_descricao = 'Processo Eletronico');
            DELETE FROM formareclamacao WHERE p42_descricao = 'Processo Eletronico';
SQL;
        $this->execute($downDDL);
    }
}
