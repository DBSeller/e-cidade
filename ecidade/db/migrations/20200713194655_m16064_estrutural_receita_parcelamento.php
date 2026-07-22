<?php

use Classes\PostgresMigration;

class M16064EstruturalReceitaParcelamento extends PostgresMigration
{
    public function up()
    {
        $sql = "
            CREATE TABLE caixa.cadtipoparcrec (
            k180_cadtipoparc INTEGER NOT NULL DEFAULT 0,
            k180_estorc CHARACTER VARYING(15) NOT NULL DEFAULT 0,
            CONSTRAINT cadtipoparcrec_cadt_rec_pk PRIMARY KEY (k180_cadtipoparc, k180_estorc),
            CONSTRAINT cadtipoparcrec_cadtipoparc_fk FOREIGN KEY (k180_cadtipoparc) REFERENCES caixa.cadtipoparc (k40_codigo)
            );
            INSERT INTO db_sysarquivo VALUES (1010546,'cadtipoparcrec', 'Tipos de Receita que a regra de parcelamento utiliza, ou seja, os tipos de receitas que estiverem ligados ao tipo de parcelamento (cadtipoparc), seguirão a regra. Se não houverem registros nessa tabela, todos os tipos de débitos utilização a regra.','k180','2020-04-10','Tipos de Receitas que a regra de parcelamento usa',0,'f','f','f','f');
            INSERT INTO configuracoes.db_syscampo(
                        codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho,
                        nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel)
                VALUES (1011173,'k180_cadtipoparc','int4','Código do Parcelamento',0,'Código',10,'f','f','f',1,'text','Código');
            INSERT INTO configuracoes.db_syscampo(
                        codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho,
                        nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel)
                VALUES (1011174,'k180_estorc','varchar(15)','Estrut. da Receita',0,'Estrut. da Receita',15,'f','t','f',1,'text','Estrut. da Receita');
            INSERT INTO db_sysarqmod  VALUES (5,1010546);
            INSERT INTO db_sysarqcamp VALUES (1010546,1011173,1,0);
            INSERT INTO db_sysarqcamp VALUES (1010546,1011174,2,0);

            UPDATE db_syscampo SET rotulo = 'Valor Mínimo por Parcela', rotulorel = 'Valor Mínimo por Parcela' WHERE codcam = 505;
            UPDATE db_syscampo SET rotulo = 'Valor Máximo por Parcela', rotulorel = 'Valor Máximo por Parcela' WHERE codcam = 1010077;
            ALTER TABLE tipoparc add column vlrmindeb double precision NOT NULL default 0;
            ALTER TABLE tipoparc add column vlrmaxdeb double precision NOT NULL default 0;
            INSERT INTO db_syscampo VALUES (1011722,'vlrmindeb','float8','Valor Mínimo do Débito',0,'Valor Mínimo do Débito',15,'f','f','f',0,'text','Valor Mínimo do Débito');
            INSERT INTO db_syscampo VALUES (1011723,'vlrmaxdeb','float8','Valor máximo do Débito',0,'Valor Máximo do Débito',15,'f','f','f',0,'text','Valor Máximo do Débito');
            INSERT INTO db_sysarqcamp VALUES (95,1011722,17,0);
            INSERT INTO db_sysarqcamp VALUES (95,1011723,18,0);
        ";

        $this->execute($sql);
    }

    public function down()
    {
        $sql = "
            DROP TABLE caixa.cadtipoparcrec;
            DELETE FROM db_sysarqcamp WHERE codarq = 1010546;
            DELETE FROM db_sysarqcamp WHERE codcam IN (1011722,1011723);
            DELETE FROM db_sysarqmod WHERE codarq = 1010546;
            DELETE FROM db_syscampo WHERE codcam IN (1011173,1011174,1011722,1011723);
            DELETE FROM db_sysarquivo WHERE codarq = 1010546;
            UPDATE db_syscampo SET rotulo = 'Valor Mínimo', rotulorel = 'Valor Mínimo' WHERE codcam = 505;
            UPDATE db_syscampo SET rotulo = 'Valor Máximo', rotulorel = 'Valor Máximo' WHERE codcam = 1010077;
            ALTER TABLE tipoparc DROP COLUMN vlrmindeb;
            ALTER TABLE tipoparc DROP COLUMN vlrmaxdeb;
        ";

        $this->execute($sql);
    }
}
