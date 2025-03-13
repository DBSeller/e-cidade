<?php

use Classes\PostgresMigration;

class M12259AlteracoesEstruturaMenus extends PostgresMigration
{
    public function up()
    {
        $this->dropView();
        $this->execute(<<<SQL_UP

alter table orctiporec alter column o15_codigosiconfi type varchar;

insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228096 ,'Configuração do atributo ES' ,'Configuração do atributo ES' ,'con4_siconfidotacaofinalidade001.php' ,'1' ,'1' ,'Configuração do atributo ES' ,'true' );
delete from db_menu where id_item_filho = 228096 AND modulo = 209;
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10510 ,228096 ,2 ,209 );
SQL_UP
);

        $this->createView();
    }
    public function down()
    {
        $this->dropView();
        $this->execute(<<<SQL_DOWN

alter table orctiporec alter column o15_codigosiconfi type varchar (5);

delete from db_menu where id_item_filho = 228096 AND modulo = 209;
delete from db_itensmenu where id_item = 228096;
SQL_DOWN
);
        $this->createView();
    }


    private function dropView()
    {
        $this->execute("DROP VIEW vs_planocontas;");
    }

    private function createView()
    {

        $this->execute(<<<SQL_UP_VIEW
        
create view vs_planocontas as
        SELECT *
        FROM CONPLANO
     	 INNER JOIN CONSISTEMA             ON C60_CODSIS = C52_CODSIS
   	     INNER JOIN CONCLASS               ON C60_CODCLA = C51_CODCLA
			 LEFT JOIN CONPLANOREDUZ           ON C60_CODCON = C61_CODCON and C60_ANOUSU =C61_ANOUSU
			 LEFT  JOIN CONPLANOCONTA          ON c63_ANOUSU = C60_ANOUSU
							                  and C61_REDUZ = C63_REDUZ
  	     LEFT JOIN CONPLANOEXE             ON C61_ANOUSU = C62_ANOUSU and C61_REDUZ  = C62_REDUZ
	     LEFT JOIN ORCTIPOREC              ON C61_CODIGO = O15_CODIGO
	     LEFT JOIN DB_CONFIG               ON CODIGO     = CONPLANOREDUZ.C61_INSTIT;
SQL_UP_VIEW
);
    }
}
