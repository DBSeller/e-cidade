<?php

use Classes\PostgresMigration;

class M16868Rbt12Cnae extends PostgresMigration
{
    public function up()
    {
        $this->upDicionario();
        $this->upEstrutura();
        $this->upPopulaTabelas();
    }

    public function down()
    {
        $this->downDicionario();
        $this->downEstrutura();
    }

    private function upDicionario()
    {
        $this->execute(<<<SQL
            -- Cria parâmetro de escolha entre grupo de serviço e cnae
            INSERT INTO db_syscampo VALUES(1011893,'q60_formaaliquotarbt','int4','Define qual a forma que a prefeitura vai tratar o RBT12: buscando as alíquotas dos tributos envolvidos através do item de serviço ou através do cnae.','0', 'Método RBT12',1,'f','f','f',1,'text','Método RBT12');
            INSERT INTO db_sysarqcamp VALUES(664,1011893,32,0);

            -- Cria tabela isscnaeanexos e seus devidos campos
            INSERT INTO db_sysarquivo   VALUES (1010630, 'isscnaeanexos', 'Tabela de relação entre cnae e issgscadanexos', 'q178', '2020-11-25', '', 0, 'f', 'f', 'f', 'f' );
            INSERT INTO db_sysarqmod    VALUES (3,1010630);
            INSERT INTO db_syscampo     VALUES (1011894,'q178_sequencial','int4','Sequencial da tabela isscnaeanexos','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
            INSERT INTO db_syscampo     VALUES (1011895,'q178_cnae','int4','Relaciona a tabela cnae','0', 'CNAE',10,'f','f','f',1,'text','CNAE');
            INSERT INTO db_syscampo     VALUES (1011896,'q178_issgscadanexos','int4','Relaciona a tabela issgscadanexos','0', 'issgscadanexos',10,'f','f','f',1,'text','issgscadanexos');
            INSERT INTO db_syscampo     VALUES (1011897,'q178_data_fim','date','Data limite do anexo','null', 'Data limite',10,'f','f','f',1,'text','Data limite');

            -- Atribui os campos na tabela isscnaeanexos
            INSERT INTO db_sysarqcamp   VALUES (1010630,1011894,1,0);
            INSERT INTO db_sysarqcamp   VALUES (1010630,1011895,2,0);
            INSERT INTO db_sysarqcamp   VALUES (1010630,1011896,3,0);
            INSERT INTO db_sysarqcamp values(1010630,1011897,4,0);

            -- -- Cria FKs da tabela isscnaeanexos
            INSERT INTO db_sysprikey    (codarq,codcam,sequen,camiden) VALUES(1010630,1011894,1,1011894);
            INSERT INTO db_sysforkey    VALUES (1010630,1011895,1,1752,0);
            INSERT INTO db_sysforkey    VALUES (1010630,1011896,1,1010550,0);
            -- Cria sequence da tabela isscnaeanexos
            INSERT INTO db_syssequencia VALUES (1000977,'isscnaeanexos_q178_sequencial_seq',1,1,9223372036854775807,1,1);
            UPDATE db_sysarqcamp SET codsequencia = 1000977 WHERE codarq = 1010630 AND codcam = 1011894;

            -- Cria campo na tabela cnae
            INSERT INTO db_syscampo VALUES(1011898,'q71_aliquota','float4','Alíquota do CNAE.','0', 'Alíquota',11,'f','f','f',4,'text','Alíquota');
            INSERT INTO db_sysarqcamp VALUES(1752,1011898,6,0);
SQL
        );
    }

    public function downDicionario()
    {
        $this->execute(<<<SQL
            -- Remove parâmetro de escolha entre grupo de serviço e cnae
            DELETE FROM db_sysarqcamp WHERE codcam = 1011893;
            DELETE FROM db_syscampo WHERE codcam = 1011893;

            -- Remove PKs e FKs
            DELETE FROM db_sysarqcamp where codarq = 1010630;
            DELETE FROM db_sysprikey where codarq = 1010630;
            DELETE FROM db_sysforkey where codarq = 1010630;

            -- Remove campos da tabela isscnaeanexos
            DELETE FROM db_syscampo WHERE codcam IN (1011894,1011895,1011896,1011897);
            DELETE FROM db_sysarqmod WHERE codarq = 1010630;
            -- Remove tabela isscnaeanexos
            DELETE FROM db_sysarquivo WHERE codarq = 1010630;

            DELETE FROM db_syssequencia WHERE codsequencia = 1000977;

            -- Remove campo na tabela cnae
            DELETE FROM db_sysarqcamp WHERE codcam = 1011898;
            DELETE FROM db_syscampo WHERE codcam = 1011898;
SQL
        );
    }

    public function upEstrutura()
    {
        $this->execute(<<<SQL
            -- Cria parâmetro de escolha entre grupo de serviço e cnae
            ALTER TABLE parissqn ADD COLUMN q60_formaaliquotarbt INTEGER NOT NULL DEFAULT 1;

            -- Cria tabela isscnaeanexos
            CREATE TABLE issqn.isscnaeanexos (
                "q178_sequencial" serial NOT NULL,
                "q178_cnae" integer NOT NULL,
                "q178_issgscadanexos" integer NOT NULL,
                "q178_data_fim" DATE,
                CONSTRAINT "isscnaeanexos_pk" PRIMARY KEY ("q178_sequencial"),
                CONSTRAINT "isscnaeanexos_fk0" FOREIGN KEY ("q178_cnae") REFERENCES "cnae"("q71_sequencial"),
                CONSTRAINT "isscnaeanexos_fk1" FOREIGN KEY ("q178_issgscadanexos") REFERENCES "issgscadanexos"("q157_sequencial"),
                CONSTRAINT "isscnaeanexos_unique" UNIQUE ("q178_cnae", "q178_issgscadanexos", "q178_data_fim")
            );

            ALTER TABLE issqn.cnae ADD COLUMN q71_aliquota NUMERIC default 0;
SQL
        );
    }

    public function downEstrutura()
    {
        $this->execute(<<<SQL
            -- Remove parâmetro de escolha entre grupo de serviço e cnae
            ALTER TABLE parissqn DROP COLUMN q60_formaaliquotarbt;

            -- Remove tabela isscnaeanexos
            DROP TABLE issqn.isscnaeanexos;

            ALTER TABLE issqn.cnae DROP COLUMN q71_aliquota;
SQL
        );
    }

    public function upPopulaTabelas()
    {
        $this->execute(<<<SQL
            -- Insere dados na tabela isscnaeanexos
            INSERT INTO isscnaeanexos (q178_issgscadanexos, q178_cnae)
                VALUES
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'A0161001')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'A0161002')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'A0161003')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'A0161099')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'A0162801')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'A0162802')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'A0162803')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'A0162899')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'A0163600')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'B0910600')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'B0990401')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'B0990402')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'B0990403')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'C1821100')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'C1822901')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'C1822999')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'C1830001')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'C1830002')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'C1830003')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'C2539001')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'C2539002')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'C2950600')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'C3311200')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'C3313999')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'C3314799')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'C3315500')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'C3319800')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'C3321000')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'C3329599')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'E3811400')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'E3812200')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'F4212000')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'F4329199')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'G4520001')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'G4520002')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'G4520003')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'G4520004')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'G4520005')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'G4520006')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'G4520007')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'G4520008')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'G4543900')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'H4911600')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'H4923001')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'H4923002')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'H4924800')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'H4929999')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'H4940000')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'H4950700')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'H5022001')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'H5022002')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'H5099899')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'H5111100')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'H5112999')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'H5120000')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'H5130700')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'H5211799')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'H5212500')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'H5221400')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'H5223100')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'H5229001')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'H5229002')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'H5229099')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'H5239799')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'H5240199')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'I5590699')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'J5813100')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'J5819100')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'J5821200')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'J5823900')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'J5829800')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'J5911199')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'J5912001')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'J5912002')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'J5912099')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'J5913800')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'J5914600')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'J5920100')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'J6010100')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'J6021700')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'J6110899')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'J6120599')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'J6130200')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'J6141800')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'J6142600')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'J6143400')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'J6190699')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'J6319400')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'J6391700')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'J6399200')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'K6622300')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'M7312200')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'M7319002')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'M7319003')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'M7319099')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'M7420001')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'M7420002')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'M7420003')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'M7420004')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'M7420005')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'N7711000')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'N7719599')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'N7721700')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'N7722500')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'N7723300')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'N7729299')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'N7731400')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'N7733100')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'N7739001')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'N7739002')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'N7739003')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'N7739099')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'N7911200')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'N7912100')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'N7990200')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'N8020002')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'N8211300')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'N8219999')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'N8220200')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'N8230001')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'N8230002')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'N8291100')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'N8292000')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'N8299799')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'P8511200')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'P8512100')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'P8513900')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'P8520100')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'P8541400')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'P8592999')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'P8593700')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'P8599699')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'Q8622400')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'Q8712300')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'R9001999')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'R9003500')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'R9101500')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'R9103100')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'R9200399')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'R9311500')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'R9312300')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'R9319199')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'R9321200')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'R9329899')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'S9511800')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'S9512600')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'S9521500')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'S9529199')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'S9603399')),
                (1, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'S9609299')),
                (2, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'E3702900')),
                (2, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'F4120400')),
                (2, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'F4213800')),
                (2, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'F4223500')),
                (2, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'F4291000')),
                (2, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'F4299599')),
                (2, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'F4312600')),
                (2, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'F4313400')),
                (2, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'F4319300')),
                (2, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'F4321500')),
                (2, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'F4330499')),
                (2, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'F4391600')),
                (2, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'F4399199')),
                (2, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'N8012900')),
                (2, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'N8020001')),
                (2, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'N8111700')),
                (2, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'N8121400')),
                (2, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'N8122200')),
                (2, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'N8129000')),
                (2, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'N8130300')),
                (2, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'T9700500')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'E3701100')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'E3821100')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'E3822000')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'E3900500')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'G4611700')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'G4612500')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'G4613300')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'G4614100')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'G4615000')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'G4616800')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'G4617600')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'G4618499')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'G4619200')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'H5222200')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'H5232000')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'J5811500')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'J6202300')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'J6203100')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'J6204000')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'J6209100')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'J6311900')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'K6493000')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'K6550200')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'K6613400')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'K6619399')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'K6629100')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'K6630400')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'L6822600')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'M7020400')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'M7111100')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'M7112000')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'M7119799')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'M7120100')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'M7210000')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'M7220700')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'M7311400')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'M7319001')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'M7319004')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'M7320300')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'M7410299')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'M7490199')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'M7500100')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'N7740300')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'N7810800')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'N8030700')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'P8531700')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'P8532500')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'P8533300')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'P8542200')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'P8591100')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'Q8630599')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'Q8640299')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'Q8650001')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'Q8650002')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'Q8650003')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'Q8650004')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'Q8650005')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'Q8650006')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'Q8650007')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'Q8650099')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'Q8660700')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'Q8690999')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'Q8720499')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'Q8730199')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'Q8800600')),
                (3, (SELECT q71_sequencial FROM cnae WHERE q71_estrutural = 'R9313100'));

                -- Cria tabela temporária para armazenar dados de aliquota para atualizar na tabela cnae
                CREATE TABLE w_cnae_aliquota AS
                SELECT
                    DISTINCT q03_ativ,
                    q71_sequencial AS sequencial_cnae,
                    q71_estrutural AS estrutural_cnae,
                    db121_sequencial,
                    db121_estrutural,
                    q71_aliquota,
                    q136_valor,
                    q178_sequencial,
                    q178_issgscadanexos AS anexo_cnae,
                    q162_issgscadanexos AS anexo_serv
                FROM
                    ativid
                    LEFT JOIN atividcnae ON q03_ativ = q74_ativid
                    LEFT JOIN cnaeanalitica ON q74_cnaeanalitica = q72_sequencial
                    LEFT JOIN cnae ON q72_cnae = q71_sequencial
                    LEFT JOIN issgruposervicoativid ON q03_ativ = q127_ativid
                    LEFT JOIN issgruposervico ON q127_issgruposerviso = q126_sequencial
                    LEFT JOIN db_estruturavalor ON q126_db_estruturavalor = db121_sequencial
                    LEFT JOIN issconfiguracaogruposervico ON q126_sequencial = q136_issgruposervico
                    LEFT JOIN issgsanexos ON q126_sequencial = q162_issgruposervico
                    LEFT JOIN isscnaeanexos ON q71_sequencial = q178_cnae
                WHERE
                    db121_db_estrutura = 150000
                    AND q136_exercicio = date_part('year', CURRENT_DATE)
                    AND q71_sequencial IS NOT NULL
                    AND q03_limite IS NULL
                    AND q162_data_fim IS NULL
                    AND q178_data_fim IS NULL
                ORDER BY
                    q71_sequencial,
                    q03_ativ;

                -- Atualiza tabela cnae com os dados da tabela temporária
                UPDATE
                    cnae
                SET
                    q71_aliquota = q136_valor
                FROM
                    w_cnae_aliquota
                WHERE
                    w_cnae_aliquota.sequencial_cnae = cnae.q71_sequencial;

                -- Atualiza tabela isscnaeanexos com os dados da tabela temporária
                UPDATE
                    isscnaeanexos
                SET
                    q178_cnae = sequencial_cnae,
                    q178_issgscadanexos = anexo_serv
                FROM
                    w_cnae_aliquota
                WHERE
                    w_cnae_aliquota.q178_sequencial = isscnaeanexos.q178_sequencial
                    AND anexo_serv IS NOT NULL;

                -- Remove tabela temporária
                DROP TABLE w_cnae_aliquota;

SQL
        );
    }
}
