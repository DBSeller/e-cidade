<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M22229AdicionaMenusNovosRelatorioTcepb extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->upDicionarioDados();
        $this->upEstrutura();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->downDicionarioDados();
        $this->downEstrutura();
    }


    public function upDicionarioDados()
    {
        $sql = <<<SQL
-- menu
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228779 ,'Índices Constitucionais e de Gestão Fiscal ' ,'Índices Constitucionais e de Gestão Fiscal ' ,'' ,'1' ,'1' ,'Índices Constitucionais e de Gestão Fiscal ' ,'true' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 3331 ,228779 ,59 ,209 );
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228780 ,'Estado da Paraíba' ,'Estado da Paraíba' ,'' ,'1' ,'1' ,'Estado da Paraíba para o menu: FINANCEIRO > Contabilidade > Relatórios > Índices Constitucionais e de Gestão Fiscal' ,'true' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228779 ,228780 ,1 ,209 );
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228781 ,'Relatório Gerencial de Aplicação em MDE' ,'Relatório Gerencial de Aplicação em MDE ' ,'con2_relatorioaplicacaomdetce001.php' ,'1' ,'1' ,' Relatório Gerencial de Aplicação em MDE na regra do TCE-PB' ,'true' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228780 ,228781 ,1 ,209 );
-- tabela movimentacoesauditoria
insert into db_sysarquivo values (1010989, 'movimentacoesauditoria', 'Tabela para salvar: Adições da Auditoria; Exclusões da Auditoria; Restos a Pagar Inscritos no Exercício sem Disponibilidade Financeira de Recursos do MDE; ', 'c170', '2022-10-11', 'movimentacoesauditoria', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (32,1010989);
insert into db_syscampo values(1014525,'c170_adicaoauditoria','float8','c170_adicaoauditoria relatorio mde','0', 'c170_adicaoauditoria',1,'f','f','f',4,'text','c170_adicaoauditoria');
insert into db_syscampo values(1014526,'c170_exclusaoauditoria','float8','Exclusões da Auditoria, relatorios mde','0', 'c170_exclusaoauditoria',1,'f','f','f',4,'text','c170_exclusaoauditoria');
insert into db_syscampo values(1014527,'c170_resto','float8','Restos a Pagar Inscritos no Exercício sem Disponibilidade Financeira de Recursos do MDE','0', 'c170_resto',1,'t','f','f',4,'text','c170_resto');
insert into db_syscampo values(1014528,'c170_anousu','float4','Ano comp','0', 'c170_anousu',1,'f','f','f',4,'text','c170_anousu');
insert into db_syscampo values(1014529,'c170_mes','int4','Mes de exercicio','0', 'c170_mes',1,'f','f','f',1,'text','c170_mes');
insert into db_sysarqcamp values(1010989,1014529,1,0);
insert into db_sysarqcamp values(1010989,1014528,2,0);
insert into db_sysarqcamp values(1010989,1014527,3,0);
insert into db_sysarqcamp values(1010989,1014526,4,0);
insert into db_sysarqcamp values(1010989,1014525,5,0);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010989,1014528,1,1014529);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010989,1014529,2,1014529);

SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    public function downDicionarioDados()
    {
        $sql = <<<SQL
-- menu
delete from db_menu where id_item_filho = 228781 AND modulo = 209;
delete from db_itensmenu where id_item = 228781;
delete from db_menu where id_item_filho = 228780 AND modulo = 209;
delete from db_itensmenu where id_item = 228780;
delete from db_menu where id_item_filho = 228779 AND modulo = 209;
delete from db_itensmenu where id_item = 228779;
-- movimentacoesauditoria
delete from db_sysprikey where codarq = 1010989 and codcam = 1014529;
delete from db_sysprikey where codarq = 1010989 and codcam = 1014528;
delete from db_sysarqcamp where codarq = 1010989 and codcam = 1014525;
delete from db_sysarqcamp where codarq = 1010989 and codcam = 1014526;
delete from db_sysarqcamp where codarq = 1010989 and codcam = 1014527;
delete from db_sysarqcamp where codarq = 1010989 and codcam = 1014528;
delete from db_sysarqcamp where codarq = 1010989 and codcam = 1014529;
delete from db_syscampo where codcam = 1014529;
delete from db_syscampo where codcam = 1014528;
delete from db_syscampo where codcam = 1014527;
delete from db_syscampo where codcam = 1014526;
delete from db_syscampo where codcam = 1014525;
delete from db_sysarqmod where codarq = 1010989;
delete from db_sysarquivo where codarq = 1010989;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    public function upEstrutura()
    {
        $sql = <<<SQL
CREATE TABLE contabilidade.movimentacoesauditoria (
c170_adicaoauditoria float,
c170_exclusaoauditoria float,
c170_resto float,
c170_anousu int not null ,
c170_mes int not null,
constraint pk_movimentacoesauditoria primary key (c170_anousu,c170_mes)
);
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    public function downEstrutura()
    {
        $sql = <<<SQL
drop table contabilidade.movimentacoesauditoria;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}
