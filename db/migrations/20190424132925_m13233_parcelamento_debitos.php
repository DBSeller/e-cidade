<?php

use Classes\PostgresMigration;

class M13233ParcelamentoDebitos extends PostgresMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {
        $this->upDicionarioDados();
        $this->upEstruturaTipo();
    }

    public function down()
    {
        $this->downDicionarioDados();
        $this->downEstruturaTipo();
    }

    public function upDicionarioDados()
    {
        $this->execute(<<<SQL

            insert into db_syscampo values(1010441,'k40_permvalcadparc','bool','Campo booleano para a opção de alterar o valor de cada parcela no parcelamento','f', 'Permite alterar valor de cada parcela',1,'f','f','f',5,'text','Permite alterar valor de cada parcela');
            insert into db_syscampo values(1010442,'k40_permdataparc','bool','Campo booleano para a opção de alterar a data de cada parcela no parcelamento','f', 'Permite alterar vencimento de parcelas',1,'f','f','f',5,'text','Permite alterar data de cada parcela');
            insert into db_syscampo values(1010443,'k40_controlavencimento','bool','Campo booleano para a opção de controlar o vencimento do parcelamento até o final do ano','f', 'Controla Vencimento até o fim do ano',1,'f','f','f',5,'text','Controla Vencimento até o fim do ano');
            delete from db_sysarqcamp where codarq = 1257;
            insert into db_sysarqcamp values(1257,7574,1,289);
            insert into db_sysarqcamp values(1257,7575,2,0);
            insert into db_sysarqcamp values(1257,7576,3,0);
            insert into db_sysarqcamp values(1257,7577,4,0);
            insert into db_sysarqcamp values(1257,7578,5,0);
            insert into db_sysarqcamp values(1257,7824,6,0);
            insert into db_sysarqcamp values(1257,8624,7,0);
            insert into db_sysarqcamp values(1257,8625,8,0);
            insert into db_sysarqcamp values(1257,8626,9,0);
            insert into db_sysarqcamp values(1257,9675,10,0);
            insert into db_sysarqcamp values(1257,10648,11,0);
            insert into db_sysarqcamp values(1257,10773,12,0);
            insert into db_sysarqcamp values(1257,10986,13,0);
            insert into db_sysarqcamp values(1257,11008,14,0);
            insert into db_sysarqcamp values(1257,15297,15,0);
            insert into db_sysarqcamp values(1257,15298,16,0);
            insert into db_sysarqcamp values(1257,15299,17,0);
            insert into db_sysarqcamp values(1257,15300,18,0);
            insert into db_sysarqcamp values(1257,15301,19,0);
            insert into db_sysarqcamp values(1257,18286,20,0);
            insert into db_sysarqcamp values(1257,1010442,21,0);
            insert into db_sysarqcamp values(1257,1010441,22,0);
            insert into db_sysarqcamp values(1257,1010443,23,0);

            insert into db_sysarquivo values (1010442, 'parcvalor', 'Tabela que guarda os valores e datas de cada parcelamento preenchido pelo usuario', 'k189', '2019-04-24', 'Valores dos parcelamentos', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (54,1010442);

            insert into db_syscampo values(1010445,'k189_numpre','int8','Numpre do debito','0', 'Numpre do debito',1,'f','f','f',1,'text','Numpre do debito');
            insert into db_syscampo values(1010446,'k189_data','date','Data do debito','null', 'Data do debito',10,'f','f','f',1,'text','Data do debito');
            insert into db_syscampo values(1010447,'k189_valor','float8','Valor do débito','0', 'Valor do débito',10,'f','f','f',4,'text','Valor do débito');
            insert into db_syscampo values(1010448,'k189_numpar','int4','Numpar','0', 'Número da parcela',1,'f','f','f',1,'text','Número da parcela');
            delete from db_sysarqcamp where codarq = 1010442;
            insert into db_sysarqcamp values(1010442,1010445,1,0);
            insert into db_sysarqcamp values(1010442,1010448,2,0);
            insert into db_sysarqcamp values(1010442,1010447,3,0);
            insert into db_sysarqcamp values(1010442,1010446,4,0);

SQL
);
    }

    public function downDicionarioDados()
    {
        $this->execute(<<<SQL

            delete from db_sysarqcamp where codarq = 1257 and codcam in (1010442, 1010441, 1010443);
            delete from db_syscampo where codcam in (1010442, 1010441, 1010443);

            delete from db_sysarqcamp where codarq = 1010442 and codcam in (1010445, 1010448, 1010447, 1010446);
            delete from db_syscampo where codcam in (1010445, 1010448, 1010447, 1010446);

            delete from db_sysarqmod where codarq = 1010442;
            delete from db_sysarquivo where codarq = 1010442;

SQL
);
    }

    public function upEstruturaTipo()
    {
        $this->execute(<<<SQL
            ALTER TABLE cadtipoparc 
                ADD COLUMN k40_permvalcadparc boolean default 'f',
                ADD COLUMN k40_permdataparc boolean default 'f',
                ADD COLUMN k40_controlavencimento boolean default 'f';

            CREATE TABLE arrecadacao.parcvalor(
                k189_numpre integer not null,
                k189_numpar integer not null,
                k189_valor float8 default null,
                k189_data date default null,
                UNIQUE(k189_numpre, k189_numpar)
            );

SQL
);
    }

    public function downEstruturaTipo()
    {
        $this->execute("
            ALTER TABLE cadtipoparc 
                DROP COLUMN k40_permvalcadparc,
                DROP COLUMN k40_permdataparc,
                DROP COLUMN k40_controlavencimento;

            DROP TABLE arrecadacao.parcvalor;
");
    }
}
