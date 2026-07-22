<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20484CreateTableRhcedencia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->upRHCedencia();
        $this->upEstrutura();
        $this->upPopulaTabela();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->downRHCedencia();
        $this->downEstrutura();
    }

    public function upRHCedencia()
    {
        $sql=<<<SQL
            insert into configuracoes.db_sysarquivo values (1010896, 'rhcedencia', 'Cedencia do Servidor', 'rh261', '2022-04-11', 'cedencia', 0, 'f', 'f', 'f', 'f' );
            insert into configuracoes.db_sysarqmod values (28,1010896);
            insert into configuracoes.db_syscampo values(1013967,'rh261_seqpes','int4','chave estrangeira que referencia a tbl rhpessoalmov','0', 'fk',10,'f','f','f',1,'text','fk');
            insert into configuracoes.db_syscampo values(1013968,'rh261_credencial','char(1)','Credencial','', 'credencial',1,'f','t','f',0,'text','credencial');
            insert into configuracoes.db_syscampo values(1013969,'rh261_onus','char(1)','Status do Ônus','', 'Ônus',1,'f','t','f',0,'text','Ônus');
            insert into configuracoes.db_syscampo values(1013970,'rh261_ressarcimento','char(1)','Ressarcimento do Servidor','', 'Ressarcimento',1,'f','t','f',0,'text','Ressarcimento');
            insert into configuracoes.db_syscampo values(1013971,'rh261_datamovimentacao','date','Data Movimentação','null', 'Data Movimentação',10,'t','f','f',1,'text','Data Movimentação');
            insert into configuracoes.db_syscampo values(1013972,'rh261_devolucao','date','Data Devolução','null', 'Data Devolução',10,'t','f','f',1,'text','Data Devolução');
            insert into configuracoes.db_syscampo values(1013973,'rh261_cgm','varchar(10)','CGM Origem/Destino ','', 'CGM Origem/Destino ',10,'f','t','f',0,'text','CGM Origem/Destino ');
            insert into configuracoes.db_syscampo values(1013974,'rh261_matorigemcedente','char(30)','Matrícula Origem no Orgão Cedente','', 'Matrícula Origem no Orgão Cedente',30,'t','t','f',0,'text','Matrícula Origem no Orgão Cedente');
            insert into configuracoes.db_syscampo values(1013975,'rh261_servidorcedido','char(1)','Servidor Cedido será informado no eSocial(S1200/S1202)?','', 'Servidor Cedido eSocial(S1200/S1',1,'f','f','f',0,'text','Servidor Cedido eSocial(S1200/S1');
            insert into configuracoes.db_syscampo values(1013999,'rh261_regist','int4','Número de matrícula do servidor.','0', 'Matrícula',10,'f','f','f',1,'text','Matrícula');
            delete from configuracoes.db_sysarqcamp where codarq = 1010896;
            insert into configuracoes.db_sysarqcamp values(1010896,1013967,1,0);
            insert into configuracoes.db_sysarqcamp values(1010896,1013968,2,0);
            insert into configuracoes.db_sysarqcamp values(1010896,1013969,3,0);
            insert into configuracoes.db_sysarqcamp values(1010896,1013970,4,0);
            insert into configuracoes.db_sysarqcamp values(1010896,1013971,5,0);
            insert into configuracoes.db_sysarqcamp values(1010896,1013972,6,0);
            insert into configuracoes.db_sysarqcamp values(1010896,1013973,7,0);
            insert into configuracoes.db_sysarqcamp values(1010896,1013974,8,0);
            insert into configuracoes.db_sysarqcamp values(1010896,1013975,9,0);
            insert into configuracoes.db_sysarqcamp values(1010896,1013999,10,0);
SQL;
    DB::connection()->getPdo()->exec($sql);
    }

    public function downRHCedencia()
    {
        $sql=<<<SQL
            DELETE FROM configuracoes.db_sysarqcamp WHERE codarq = 1010896;
            DELETE FROM configuracoes.db_syscampo WHERE codcam IN(1013967, 1013968, 1013969, 1013970, 1013971, 1013972, 1013973, 1013974, 1013975, 1013999);
            DELETE FROM configuracoes.db_sysarqmod WHERE codmod = 28 AND codarq = 1010896;
            DELETE FROM configuracoes.db_sysarquivo WHERE codarq = 1010896;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    public function upEstrutura()
    {
        $sql=<<<SQL
                CREATE TABLE pessoal.rhcedencia(
                    rh261_seqpes		   int4 NOT NULL,
                    rh261_credencial	   char(1) NOT NULL,
                    rh261_onus		       char(1) NOT NULL,
                    rh261_ressarcimento	   char(1) NOT NULL,
                    rh261_datamovimentacao date NULL,
                    rh261_devolucao		   date NULL,
                    rh261_cgm		       varchar(10) NULL,
                    rh261_matorigemcedente char(30)NULL,
                    rh261_servidorcedido   char(1) NULL,
                    rh261_regist           int4 NULL,
                    PRIMARY KEY (rh261_seqpes)
                );
SQL;
    DB::connection()->getPdo()->exec($sql);
    }

    public function downEstrutura()
    {
        $sql=<<<SQL
        DROP TABLE IF EXISTS pessoal.rhcedencia;
SQL;
    DB::connection()->getPdo()->exec($sql);
    }

    public function upPopulaTabela()
    {
        $sql=<<<SQL
        insert into pessoal.rhcedencia
        select
            rpv.rh02_seqpes as rh261_seqpes,
            rpv.rh02_cedencia as rh261_credencial,
            rpv.rh02_onus as rh261_onus,
            rpv.rh02_ressarcimento as rh261_ressarcimento,
            rpv.rh02_datacedencia as rh261_datamovimentacao,
            null::date as rh261_devolucao,
            max(cg.z01_numcgm) as rh261_numcgm,
            null as rh261_matorigemcedente,
            'N' as rh261_servidorcedido,
            rpv.rh02_regist as rh261_regist
        from rhpessoalmov rpv
            left join cgm cg on cg.z01_cgccpf = rpv.rh02_cnpjcedencia
        where rpv.rh02_cedencia is not null and length(trim(rpv.rh02_cnpjcedencia)) > 11
        group by
            1,
            2,
            3,
            4,
            5,
            6,
            8,
            9,
            10
;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}
