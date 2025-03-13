<?php

use Classes\PostgresMigration;

class M15283CadastrosDeCargos extends PostgresMigration
{
    public function up()
    {
        $this->execute("
        ALTER TABLE rhfuncao ADD COLUMN rh37_descricaocompleta VARCHAR(255);
        ALTER TABLE rhfuncao ADD COLUMN rh37_rhinstrucao INT;

        ALTER TABLE rhfuncao ADD CONSTRAINT rhfuncao_rhinstrucao_fk FOREIGN KEY (rh37_rhinstrucao) REFERENCES rhinstrucao;

        CREATE  INDEX rhfuncao_rhinstrucao_in ON rhfuncao(rh37_rhinstrucao);

        INSERT INTO db_syscampo ( codcam, nomecam, conteudo, descricao, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel)
        values (1010974, 'rh37_descricaocompleta', 'varchar(255)', 'Guarda descrição completa do cargo', 'Descrição Completa', 255, 'true', 'true', 'false', 0, 'text', 'Descrição Completa'),
                (1010975, 'rh37_rhinstrucao', 'int4', 'Guarda o grau de instrução', 'Grau de Instrução', 10, 'true', 'false', 'false', 1, 'text', 'Grau de Instrução');

        INSERT INTO db_sysarqcamp
        values (1174,1010974,12,0),
            (1174,1010975,13,0);

        INSERT INTO db_sysforkey values(1174,1010975,1,1213,0);
        INSERT INTO db_sysindices values(1008518,'rhfuncao_rhinstrucao_in',1174,'0');
        INSERT INTO db_syscadind values(1008518,1010975,1);
        ");
    }

    public function down()
    {
        $this->execute("
            ALTER TABLE rhfuncao DROP COLUMN rh37_descricaocompleta;
            ALTER TABLE rhfuncao DROP COLUMN rh37_rhinstrucao;
            delete from db_sysforkey where codcam = 1010975;
            delete from db_sysindices where codind = 1008518;
            delete from db_syscadind where codind = 1008518;
            delete from db_sysarqcamp where codcam in (1010974, 1010975);
            delete from db_syscampo where codcam in (1010974, 1010975);
        ");
    }
}