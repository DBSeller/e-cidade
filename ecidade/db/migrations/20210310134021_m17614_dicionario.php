<?php

use Classes\PostgresMigration;

class M17614Dicionario extends PostgresMigration
{
    public function up()
    {
        $this->execute( "update db_syscampo set nomecam = 'pl20_subtitulo' where codcam = 1012563;");

        $this->execute(<<<SQL
insert into db_sysarquivo values (1010758, 'cronogramadesembolsodespesa', 'Cronograma de desembolso da despesa', 'pl30', '2021-03-10', 'Cronograma de desembolso', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (85,1010758);
insert into db_syscampo
values (1012878,'detalhamentoiniciativa_id','int4','FK com detalhamento ','0', 'Detalhamento',10,'f','f','f',1,'text','Detalhamento'),
       (1012879,'janeiro','float8','Valor referente ao mês','0', 'janeiro',10,'f','f','f',4,'text','janeiro'),
       (1012880,'fevereiro','float8','Valor referente ao mês','0', 'Fevereiro',30,'f','f','f',4,'text','Fevereiro'),
       (1012881,'marco','float8','Valor referente ao mês','0', 'Março',10,'f','f','f',4,'text','Março'),
       (1012882,'abril','float8','Valor referente ao mês','0', 'Abril',10,'f','f','f',4,'text','Abril'),
       (1012883,'maio','float8','Valor referente ao mês','0', 'Maio',10,'f','f','f',4,'text','Maio'),
       (1012884,'junho','float8','Valor referente ao mês','0', 'Junho',10,'f','f','f',4,'text','Junho'),
       (1012885,'julho','float4','Valor referente ao mês','0', 'Julho',10,'f','f','f',4,'text','Julho'),
       (1012886,'agosto','float8','Valor referente ao mês','0', 'Agosto',10,'f','f','f',4,'text','Agosto'),
       (1012887,'setembro','float8','Valor referente ao mês','0', 'Setembro',10,'f','f','f',4,'text','Setembro'),
       (1012888,'outubro','float8','Valor referente ao mês','0', 'Outubro',10,'f','f','f',4,'text','Outubro'),
       (1012889,'novembro','float8','Valor referente ao mês','0', 'Novembro',10,'f','f','f',4,'text','Novembro'),
       (1012890,'dezembro','float8','Valor referente ao mês','0', 'Dezembro',10,'f','f','f',4,'text','Dezembro');

insert into db_sysarqcamp
values (1010758,1011345,1,0),
       (1010758,1012878,2,0),
       (1010758,1012879,3,0),
       (1010758,1012880,4,0),
       (1010758,1012881,5,0),
       (1010758,1012882,6,0),
       (1010758,1012883,7,0),
       (1010758,1012884,8,0),
       (1010758,1012885,9,0),
       (1010758,1012886,10,0),
       (1010758,1012887,11,0),
       (1010758,1012888,12,0),
       (1010758,1012889,13,0),
       (1010758,1012890,14,0);

insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010758,1011345,1,1011345);
insert into db_sysforkey values(1010758,1012878,1,1010758,0);
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
delete from db_sysprikey where codarq = 1010758;
delete from db_sysforkey where codarq = 1010758;
delete from db_sysarqcamp where codarq = 1010758;
delete from db_sysarqmod where codarq = 1010758;
delete from db_syscampo where codcam in (1012878, 1012879, 1012880, 1012881, 1012882, 1012883, 1012884, 1012885, 1012886, 1012887, 1012888, 1012889, 1012890);
delete from db_sysarquivo where codarq = 1010758;
SQL
        );
    }
}
