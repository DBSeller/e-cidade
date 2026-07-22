<?php

use Classes\PostgresMigration;

class M10325ConfiguracaoInformacoesComplementares extends PostgresMigration
{
    public function up()
    {
        $this->upMenu();
        $this->upDicionario();
        $this->upAlterarTabelaInfocomplementarValor();
        $this->upMigrarDadosParaInfocomplementarValor();
        $this->upRemoverColunaTabelaInfocomplementarValor();
    }

    public function down()
    {
        $this->downMenu();
        $this->downDicionario();
        $this->downRemoverColunaTabelaInfocomplementarValor();
        $this->downMigrarDadosParaInfocomplementarValor();
        $this->downAlterarTabelaInfocomplementarValor();
    }

    private function upMenu()
    {
        $this->execute(<<<SQL
            insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10510 ,'Configurações' ,'Configurações' ,'' ,'1' ,'1' ,'Configurações' ,'true' );
            insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10496 ,10510 ,4 ,209 );
            insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10511 ,'Informações Complementares' ,'Informações Complementares' ,'con4_informacoescomplementares001.php' ,'1' ,'1' ,'Informações Complementares' ,'true' );
            insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10510 ,10511 ,2 ,209 );
            update db_itensmenu set descricao = 'Instituições', help='Instituições' where id_item = 10509;
            update db_menu set  id_item = 10510,menusequencia =1 where id_item_filho = 10509;
SQL
        );
    }

    private function downMenu()
    {
        $this->execute(<<<SQL
            delete from db_menu where id_item_filho in (10510, 10511) AND modulo = 209;
            delete from db_itensmenu where id_item in (10510, 10511);
SQL
        );
    }

    private function upAlterarTabelaInfocomplementarValor()
    {
        $this->execute(<<<SQL
          alter table contabilidade.infocomplementarvalor add column c123_infocomplementar integer;
          alter table contabilidade.infocomplementarvalor add column c123_conplanosistema integer;

          create index infocomplementarvalor_c123_infocomplementar_in on contabilidade.infocomplementarvalor using btree (c123_infocomplementar);
          create index infocomplementarvalor_c123_conplanosistema_in on contabilidade.infocomplementarvalor using btree (c123_conplanosistema);
          alter table contabilidade.infocomplementarvalor add constraint infocomplementarvalor_infcomplementar_fk foreign key (c123_infocomplementar) references contabilidade.conplanoinfocomplementar(c121_sequencial);
          alter table contabilidade.infocomplementarvalor add constraint infocomplementarvalor_sistema_fk foreign key (c123_conplanosistema) references contabilidade.conplanosistema(c122_sequencial);
SQL
        );
    }

    private function downAlterarTabelaInfocomplementarValor()
    {
        $this->execute(<<<SQL
          drop index infocomplementarvalor_c123_infocomplementar_in;
          drop index infocomplementarvalor_c123_conplanosistema_in;

          alter table contabilidade.infocomplementarvalor drop column c123_infocomplementar;
          alter table contabilidade.infocomplementarvalor drop column c123_conplanosistema;
SQL
        );
    }

    private function upMigrarDadosParaInfocomplementarValor()
    {
        $this->execute(<<<SQL
          update infocomplementarvalor set c123_infocomplementar = c120_infocomplementar , c123_conplanosistema = c120_conplanosistema from conplanoatributos where c123_conplanoatributos = c120_sequencial;
SQL
        );
    }

    private function downMigrarDadosParaInfocomplementarValor()
    {
        $this->execute(<<<SQL
          update infocomplementarvalor set c123_conplanoatributos = c120_sequencial 
            from conplanoreduz
            inner join conplano on c60_codcon = c61_codcon and c60_anousu = c61_anousu
            inner join conplanoatributos on c120_conplano = c60_codcon and c120_anousu = c60_anousu
            where c123_infocomplementar = c120_infocomplementar 
              and c123_reduzido = c61_reduz
              and c61_anousu = 2018;
SQL
        );
    }

    private function upDicionario()
    {
        $this->execute(<<<SQL
            insert into db_syscampo values(1009653,'c123_infocomplementar','int4','Chave estrangeira da tabela conplanoinfocomplementar.','0', 'Informação comeplementar',10,'f','f','f',1,'text','Informação comeplementar');
            insert into db_syscampo values(1009654,'c123_conplanosistema','int4','Chave estrangeira da tabela conplanosistema.','0', 'Sistema',10,'f','f','f',1,'text','Sistema');
            
            delete from db_sysarqcamp where codarq = 1010258;
            insert into db_sysarqcamp values(1010258,1009622,1,1000717);
            insert into db_sysarqcamp values(1010258,1009624,2,0);
            insert into db_sysarqcamp values(1010258,1009625,3,0);
            insert into db_sysarqcamp values(1010258,1009631,4,0);
            insert into db_sysarqcamp values(1010258,1009653,5,0);
            insert into db_sysarqcamp values(1010258,1009654,6,0);
            
            delete from db_sysforkey where codarq = 1010258 and referen = 1010255;
            update db_sysforkey set sequen = 1 where codarq = 1010258 and referen = 1010259;
            insert into db_sysforkey values(1010258,1009653,2,1010256,0);
            insert into db_sysforkey values(1010258,1009654,3,1010257,0);
SQL
        );
    }

    private function downDicionario()
    {
        $this->execute(<<<SQL
          
          delete from db_sysforkey where codarq = 1010258 and referen in (1010256, 1010257);
          
          delete from db_sysarqcamp where codarq = 1010258;
          insert into db_sysarqcamp values(1010258,1009622,1,1000717);
          insert into db_sysarqcamp values(1010258,1009624,2,0);
          insert into db_sysarqcamp values(1010258,1009625,3,0);
          insert into db_sysarqcamp values(1010258,1009631,4,0);
          insert into db_sysarqcamp values(1010258,1009623,5,0);
          
          insert into db_sysforkey values(1010258,1009623,2,1010255,0);
          
          delete from db_syscampo where codcam in (1009653, 1009654);
SQL
        );
    }

    private function upRemoverColunaTabelaInfocomplementarValor()
    {
        $this->execute(<<<SQL
          alter table contabilidade.infocomplementarvalor drop column c123_conplanoatributos;
SQL
        );
    }

    private function downRemoverColunaTabelaInfocomplementarValor()
    {
        $this->execute(<<<SQL
          alter table contabilidade.infocomplementarvalor add column c123_conplanoatributos integer;
          
          alter table contabilidade.infocomplementarvalor add constraint infocomplementarvalor_c123_conplanoatributos_fk foreign key (c123_conplanoatributos)
          references conplanoatributos;
SQL
        );
    }
}
