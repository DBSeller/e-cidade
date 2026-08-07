<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20406AdicionandoCampoCodigoAgrupamento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->upDicionario();
        $this->upEstrutura();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->downDicionario();
        $this->downEstrutura();
    }

    private function upDicionario()
    {
        $sql = <<<SQL
            insert into configuracoes.db_sysarquivo values (1010877, 'empempenhooutrosdados', 'Outros dados relacionados ao empenho', 'e171', '2022-03-28', 'Outros dados relacionados ao empenho', 0, 'f', 'f', 'f', 'f' );
            insert into configuracoes.db_sysarqmod values (38,1010877);
            insert into configuracoes.db_syscampo values(1013842,'e171_numpemp','int4','código que referencia ao sequencial do empenho.','0', 'sequencial do empenho',8,'f','f','f',1,'text','sequencial do empenho');
            update configuracoes.db_syscampo set nomecam = 'e171_numemp', conteudo = 'int4', descricao = 'código que referencia ao sequencial do empenho.', valorinicial = '0', rotulo = 'sequencial do empenho', nulo = 'f', tamanho = 8, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'sequencial do empenho' where codcam = 1013842;
            delete from configuracoes.db_syscampodep where codcam = 1013842;
            delete from configuracoes.db_syscampodef where codcam = 1013842;
            insert into configuracoes.db_syscampo values(1013845,'e171_dados','text','Outros dados relacionados ao empenho.','', 'Outros dados relacionados ao empenho',1,'t','f','f',0,'text','Outros dados relacionados ao empenho');
            insert into configuracoes.db_syscampo values(1013848,'e171_numdadosemp','int4','Sequencial dos outros dados do empenho.','0', 'Sequencial dos outros dados do empenho',8,'f','f','f',1,'text','Sequencial dos outros dados do empenho');
            delete from configuracoes.db_sysarqcamp where codarq = 1010877;
            insert into configuracoes.db_sysarqcamp values(1010877,1013848,1,0);
            insert into configuracoes.db_sysarqcamp values(1010877,1013842,2,0);
            insert into configuracoes.db_sysarqcamp values(1010877,1013845,3,0);
            delete from configuracoes.db_sysprikey where codarq = 1010877;
            insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1010877,1013848,1,1013848);
            delete from configuracoes.db_sysforkey where codarq = 1010877 and referen = 0;
            insert into configuracoes.db_sysforkey values(1010877,1013842,1,1010877,0);
            delete from configuracoes.db_sysforkey where codarq = 1010877 and referen = 1010877;
            delete from configuracoes.db_sysforkey where codarq = 1010877 and referen = 0;
            insert into configuracoes.db_sysforkey values(1010877,1013842,1,889,0);
            insert into configuracoes.db_syssequencia values(1001042, 'empempenhooutrosdados_e171_numdadosemp_seq', 1, 1, 9223372036854775807, 1, 1);
            update configuracoes.db_sysarqcamp set codsequencia = 1001042 where codarq = 1010877 and codcam = 1013848;
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228642 ,'Manutenção de Agrupamentos da Folha de Pagamento' ,'Manutenção de Agrupamentos da Folha de Pagamento' ,'' ,'1' ,'1' ,'Manutenção de Agrupamentos da Folha de Pagamento' ,'true' );
            delete from db_menu where id_item_filho = 228642 AND modulo = 398;
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 4021 ,228642 ,15 ,398 );
            update db_itensmenu set id_item = 228642 , descricao = 'Manutenção de Agrupamentos da Folha de Pagamento' , help = 'Manutenção de Agrupamentos da Folha de Pagamento' , funcao = 'emp1_empempenhoagrupfolha001.php' , itemativo = '1' , manutencao = '1' , desctec = 'Manutenção de Agrupamentos da Folha de Pagamento' , libcliente = 'true' where id_item = 228642;
            update db_itensmenu set id_item = 228642 , descricao = 'Manutenção de Agrupamentos da Folha de Pagamento' , help = 'Manutenção de Agrupamentos da Folha de Pagamento' , funcao = 'emp1_empempenhoagrupfolha001.php' , itemativo = '1' , manutencao = '1' , desctec = 'Manutenção de Agrupamentos da Folha de Pagamento' , libcliente = 'false' where id_item = 228642;



SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downDicionario()
    {
        $sql = <<<SQL
                    delete from configuracoes.db_syssequencia where codsequencia = 1001042;
                    delete from configuracoes.db_sysforkey where codcam = 1013842;
                    delete from configuracoes.db_sysprikey where codcam = 1013848;
                    delete from configuracoes.db_sysarqcamp where codcam = 1013845;
                    delete from configuracoes.db_sysarqcamp where codcam = 1013842;
                    delete from configuracoes.db_sysarqcamp where codcam = 1013848;
                    delete from configuracoes.db_syscampo where codcam = 1013848;
                    delete from configuracoes.db_syscampo where codcam = 1013845;
                    delete from configuracoes.db_syscampo where codcam = 1013842;
                    delete from configuracoes.db_sysarqmod where codarq = 1010877;
                    delete from configuracoes.db_sysarquivo where codarq = 1010877;
                    delete from db_itensmenu where id_item = 228642;
                    delete from db_menu where id_item_filho = 228642 AND modulo = 398;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upEstrutura()
    {
        $sql = <<<SQL
                create table empenho.empempenhooutrosdados(
                    e171_numdadosemp int PRIMARY KEY,
                    e171_numemp int,
                    e171_dados jsonb,
                    constraint fk_empempenho
                        foreign key(e171_numemp) references empenho.empempenho(e60_numemp)
                );
                CREATE sequence empenho.empempenhooutrosdados_e171_numdadosemp_seq start 1;
SQL;
            DB::connection()->getPdo()->exec($sql);
    }

    private function downEstrutura()
    {
        $sql = <<<SQL
                 drop sequence empenho.empempenhooutrosdados_e171_numdadosemp_seq;
                 drop table empenho.empempenhooutrosdados;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}
