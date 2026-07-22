<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class M19629DicionarioDados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
insert into db_sysarquivo values (1010916, 'arretipopix', 'Pix por Tipo de Débito', '', '2022-05-11', 'Pix por Tipo de Débito', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (5,1010916);
insert into db_sysarqarq values (82,1010916);
insert into db_syscampo values(1014072,'codtipopix','int4','Código Sequencial','0', 'Código Sequencial',8,'f','f','f',1,'text','Código Sequencial');
insert into db_syscampo values(1014074,'modsistema','bool','Habilita Emissão Pix','f', 'Habilita Emissão Pix',1,'f','f','f',5,'text','Habilita Emissão Pix');
insert into db_syscampo values(1014075,'moddbpref','bool','Habilita Emissão Pix DBpref','f', 'Habilita Emissão Pix DBpref',1,'f','f','f',5,'text','Habilita Emissão Pix DBpref');
insert into db_syscampo values(1014077,'qtdemissao','int4','Quantidade de emissão','0', 'Quantidade de emissão',8,'t','f','f',1,'text','Quantidade de emissão');
insert into db_syscampo values(1014078,'valorfinal','varchar(100)','Valor Final','', 'Valor Final',100,'t','f','f',0,'text','Valor Final');
insert into db_sysarqcamp values(1010916,1014072,1,1001060);
insert into db_sysarqcamp values(1010916,1014075,2,0);
insert into db_sysarqcamp values(1010916,1014074,3,0);
insert into db_sysarqcamp values(1010916,1014077,4,0);
insert into db_sysarqcamp values(1010916,380,5,0);
insert into db_sysarqcamp values(1010916,502,6,0);
insert into db_sysarqcamp values(1010916,503,7,0);
insert into db_sysarqcamp values(1010916,755,8,0);
insert into db_sysarqcamp values(1010916,1014078,9,0);
insert into db_sysarqcamp values(1010916,1012583,10,0);
insert into db_sysarqcamp values(1010916,1012584,11,0);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010916,1014072,1,1014072);
insert into db_sysforkey values(1010916,380,1,82,0);
insert into db_syssequencia values(1001060, 'arretipopix_codtipopix_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1001060 where codarq = 1010916 and codcam = 1014072;
insert into db_sysarquivo values (1010917, 'recibobarpix', 'Recibo do pix', '', '2022-05-11', 'Recibo do pix', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (5,1010917);
insert into db_syscampo values(1014080,'k00_criacaosolicitacao','date','Data da Solicitação','null', 'Data da Solicitação',10,'t','f','f',1,'text','Data da Solicitação');
insert into db_syscampo values(1014081,'k00_estadosolicitacao','varchar(50)','Estado da Solicitação','', 'Estado da Solicitação',50,'t','t','f',0,'text','Estado da Solicitação');
insert into db_syscampo values(1014082,'k00_conciliacaosolicitante','varchar(100)','Conciliação Solicitante','', 'Conciliação Solicitante',100,'f','t','f',0,'text','Conciliação Solicitante');
insert into db_syscampo values(1014083,'k00_numeroversaosolicitacaopagamento','int4','Numero Versão Solicitação do Pagamento','0', 'Numero Versão Solicitação do Pagamento',8,'t','f','f',1,'text','Numero Versão Solicitação do Pagamento');
insert into db_syscampo values(1014084,'k00_linkqrcode','varchar(100)','Link QRCode','', 'Link QRCode',100,'f','t','f',0,'text','Link QRCode');
insert into db_syscampo values(1014085,'k00_qrcode','varchar(300)','QRCode','', 'QRCode',300,'f','t','f',0,'text','QRCode');
insert into db_sysarqcamp values(1010917,361,1,0);
insert into db_sysarqcamp values(1010917,362,2,0);
insert into db_sysarqcamp values(1010917,9206,3,0);
insert into db_sysarqcamp values(1010917,1014080,4,0);
insert into db_sysarqcamp values(1010917,1014081,5,0);
insert into db_sysarqcamp values(1010917,1014082,6,0);
insert into db_sysarqcamp values(1010917,1014083,7,0);
insert into db_sysarqcamp values(1010917,1014084,8,0);
insert into db_sysarqcamp values(1010917,1014085,9,0);
insert into db_sysarqcamp values(1010917,1012583,10,0);
insert into db_sysarqcamp values(1010917,1012584,11,0);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010917,361,1,361);
insert into db_sysindices values(1008783,'caixa_recibobarpix_k00_numpre_k00_numpar_index',1010917,'0');
insert into db_syscadind values(1008783,361,1);
insert into db_syscadind values(1008783,362,2);
insert into db_sysarquivo values (1010935, 'db_bancos_pix', 'Vinculo dos dados de pix ao cadastro de banco', 'db90', '2022-05-25', 'Cadastro Pix Ao Banco', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (7,1010935);
insert into db_sysarqarq values (1185,1010935);
insert into db_syscampo values(1014166,'db90_codban_pix','int4','Sequencial','0', 'Sequencial',8,'f','f','f',1,'text','Sequencial');
insert into db_syscampo values(1014168,'db90_tipo_ambiente','int4','Tipo de Ambiente','0', 'Tipo de Ambiente',10,'f','f','f',1,'text','Tipo de Ambiente');
insert into db_syscampo values(1014169,'db90_login','varchar(255)','Usuário da api pix','', 'Usuário',255,'f','t','f',0,'text','Usuário');
insert into db_syscampo values(1014170,'db90_senha','varchar(255)','Senha da Api pix','', 'Senha',255,'f','t','f',0,'text','Senha');
insert into db_syscampo values(1014171,'db90_chave_api','varchar(255)','Client ID','', 'Client ID',255,'f','t','f',0,'text','Client ID');
insert into db_syscampo values(1014172,'db90_chave_pix','varchar(255)','Chave pix para recebimento de pagamento','', 'Chave Pix',255,'f','t','f',0,'text','Chave Pix');
insert into db_syscampo values(1014173,'db90_numconv','varchar(255)','Número do convênio com banco','', 'Número do convênio',255,'f','t','f',0,'text','Número do convênio');
insert into db_syscampo values(1014174,'db90_cnpj_municipio','bool','Usa CNPJ do Município','f', 'Usa CNPJ do Município',1,'f','f','f',5,'text','Usa CNPJ do Município');
insert into db_syscampo values(1014175,'db90_cnpj','varchar(15)','CNPJ','', 'CNPJ',15,'t','t','f',0,'text','CNPJ');
insert into db_sysarqcamp values(1010935,1014166,1,1001065);
insert into db_sysarqcamp values(1010935,7148,2,0);
insert into db_sysarqcamp values(1010935,1014168,3,0);
insert into db_sysarqcamp values(1010935,1014169,4,0);
insert into db_sysarqcamp values(1010935,1014170,5,0);
insert into db_sysarqcamp values(1010935,1014171,6,0);
insert into db_sysarqcamp values(1010935,1014172,7,0);
insert into db_sysarqcamp values(1010935,1014173,8,0);
insert into db_sysarqcamp values(1010935,1014174,9,0);
insert into db_sysarqcamp values(1010935,1014175,10,0);
insert into db_sysarqcamp values(1010935,1012583,11,0);
insert into db_sysarqcamp values(1010935,1012584,12,0);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010935,1014166,1,1014166);
insert into db_sysforkey values(1010935,7148,1,1185,1);
insert into db_sysindices values(1008784,'configuracoes_db_bancos_pix_db90_codban_unique',1010935,'1');
insert into db_syscadind values(1008784,7148,1);
insert into db_syssequencia values(1001065, 'db_bancos_pix_db90_codban_pix_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1001065 where codarq = 1010935 and codcam = 1014166;
insert into db_sysarquivo values (1010936, 'arretipopixasso', 'Vinculo tipo de debito com api do banco', '', '2022-05-25', 'Vinculo tipo de debito com api do banco', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (5,1010936);
insert into db_sysarqcamp values(1010936,8995,1,0);
insert into db_sysarqcamp values(1010936,7148,2,0);
insert into db_sysarqcamp values(1010936,380,3,0);
insert into db_sysarqcamp values(1010936,1012583,4,0);
insert into db_sysarqcamp values(1010936,1012584,5,0);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010936,8995,1,8995);
insert into db_sysforkey values(1010936,7148,1,1185,0);
insert into db_sysforkey values(1010936,380,1,82,0);
insert into db_sysarquivo values (1010937, 'modcarnepadraopix', 'Emissão de pix por carne padrao', '', '2022-05-25', 'Emissão de pix por carne padrao', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (5,1010937);
insert into db_syscampo values(1014176,'k48_sequencial_pix','int4','Sequencial','0', 'Sequencial',8,'f','f','f',1,'text','Sequencial');
insert into db_syscampo values(1014177,'k48_ammpix','bool','Permite Emissão Pix','f', 'Permite Emissão Pix',1,'f','f','f',5,'text','Permite Emissão Pix');
insert into db_sysarqcamp values(1010937,1014176,1,0);
insert into db_sysarqcamp values(1010937,8881,2,0);
insert into db_sysarqcamp values(1010937,1014177,3,0);
insert into db_sysarqcamp values(1010937,1012583,4,0);
insert into db_sysarqcamp values(1010937,1012584,5,0);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010937,1014176,1,1014176);
insert into db_sysforkey values(1010937,8881,1,1516,0);
insert into db_sysarquivo values (1010938, 'modcarnepadraopixasso', 'Vinculo Modelo com api de emissão pix', '', '2022-05-25', 'Vinculo Modelo com api de emissão pix', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (5,1010938);
insert into db_sysarqcamp values(1010938,8995,1,0);
insert into db_sysarqcamp values(1010938,7148,2,0);
insert into db_sysarqcamp values(1010938,8881,3,0);
insert into db_sysarqcamp values(1010938,1012583,4,0);
insert into db_sysarqcamp values(1010938,1012584,5,0);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010938,8995,1,8995);
insert into db_sysforkey values(1010938,8881,1,1516,0);
insert into db_sysforkey values(1010938,7148,1,1185,0);

SQL
);
        DB::statement("SELECT configuracoes.fc_auditoria_cria_funcao('caixa.recibobarpix');");
        DB::statement("SELECT configuracoes.fc_auditoria_cria_funcao('configuracoes.db_bancos_pix');");
        DB::statement("SELECT configuracoes.fc_auditoria_cria_funcao('caixa.arretipopix');");
        DB::statement("SELECT configuracoes.fc_auditoria_cria_funcao('caixa.arretipopixasso');");
        DB::statement("SELECT configuracoes.fc_auditoria_cria_funcao('caixa.modcarnepadraopix');");
        DB::statement("SELECT configuracoes.fc_auditoria_cria_funcao('caixa.modcarnepadraopixasso');");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::connection()->getPdo()->exec(<<<SQL
delete from
    db_sysarqcamp
where
    codarq in (
        1010916,
        1010917,
        1010935,
        1010936,
        1010937,
        1010938
    );

delete from
    db_sysarqmod
where
    codarq in (
        1010916,
        1010917,
        1010935,
        1010936,
        1010937,
        1010938
    );

delete from
    db_sysprikey
where
    codarq in (
        1010916,
        1010917,
        1010935,
        1010936,
        1010937,
        1010938
    );

delete from
    db_sysforkey
where
    codarq in (
        1010916,
        1010917,
        1010935,
        1010936,
        1010937,
        1010938
    );

delete from
    db_syssequencia
where
    codsequencia in (
        1001060,
        1001065
    );

delete from
    db_sysindices
where
    codind in (
        1008783,
        1008784
    );

delete from
    db_syscampo
where
    codcam in(
        1014072,
        1014074,
        1014075,
        1014077,
        1014078,
        1014080,
        1014081,
        1014082,
        1014083,
        1014084,
        1014085,
        1014166,
        1014168,
        1014169,
        1014170,
        1014171,
        1014172,
        1014173,
        1014174,
        1014175,
        1014176,
        1014177
    );

delete from
    db_syscadind
where
    codind in(
        1008783,
        1008784
    );

delete from 
    db_sysarqarq 
where codarq in (
        1010916,
        1010917,
        1010935,
        1010936,
        1010937,
        1010938
    );
delete from
    db_sysarquivo
where
    codarq in (
        1010916,
        1010917,
        1010935,
        1010936,
        1010937,
        1010938
    );
SQL
);

    }
}
