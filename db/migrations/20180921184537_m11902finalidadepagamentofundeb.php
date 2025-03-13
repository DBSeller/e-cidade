<?php

use Classes\PostgresMigration;

class M11902finalidadepagamentofundeb extends PostgresMigration
{
    public function up()
    {
        $this->execute("
insert into finalidadepagamentofundeb (
  e151_sequencial,
  e151_codigo,
  e151_descricao
) values 
((select nextval('finalidadepagamentofundeb_e151_sequencial_seq')), '50', 'Aplicação em Poupança'),
((select nextval('finalidadepagamentofundeb_e151_sequencial_seq')), '90', 'Migração Saldo Portaria 3992-FNS'),
((select nextval('finalidadepagamentofundeb_e151_sequencial_seq')), '93', 'Transf Município sem Gestão Plena'),
((select nextval('finalidadepagamentofundeb_e151_sequencial_seq')), '94', 'Folha Pagam SUS'),
((select nextval('finalidadepagamentofundeb_e151_sequencial_seq')), '95', 'Pagamento Prestador Municipal'),
((select nextval('finalidadepagamentofundeb_e151_sequencial_seq')), '96', 'Pagamento Prestador Estadual'),
((select nextval('finalidadepagamentofundeb_e151_sequencial_seq')), '98', 'Transferência Tributos Retidos')
");
    }

    public function down()
    {
        $this->execute("
delete from finalidadepagamentofundeb where e151_codigo = '50' and e151_descricao = 'Aplicação em Poupança';
delete from finalidadepagamentofundeb where e151_codigo = '90' and e151_descricao = 'Migração Saldo Portaria 3992-FNS';
delete from finalidadepagamentofundeb where e151_codigo = '93' and e151_descricao = 'Transf Município sem Gestão Plena';
delete from finalidadepagamentofundeb where e151_codigo = '94' and e151_descricao = 'Folha Pagam SUS';
delete from finalidadepagamentofundeb where e151_codigo = '95' and e151_descricao = 'Pagamento Prestador Municipal';
delete from finalidadepagamentofundeb where e151_codigo = '96' and e151_descricao = 'Pagamento Prestador Estadual';
delete from finalidadepagamentofundeb where e151_codigo = '98' and e151_descricao = 'Transferência Tributos Retidos';
");
    }
}
