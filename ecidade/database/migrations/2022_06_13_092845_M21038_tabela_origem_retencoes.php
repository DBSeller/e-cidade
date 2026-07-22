<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21038TabelaOrigemRetencoes extends Migration
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
         DB::connection()->getPdo()->exec(<<<SQL
insert into db_sysarquivo values (1010943, 'empagemovretencoes', 'Armazena o movimento original que gerou o movimento de retenção e o valor total da retenção.', 'e145', '2022-06-13', 'Movimento Retenções', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (38, 1010943);

insert into db_syscampo
values (1014207,'e145_codigo','int4','Primary key','0', 'Código',10,'f','f','f',1,'text','Código'),
       (1014208,'e145_pagordem_id','int4','Vínculo com a ordem de compra','0', 'Ordem de Compra',10,'f','f','f',1,'text','Ordem de Compra'),
       (1014209,'e145_movimento_original','int4','Movimento original que gerou o movimento de retenção','0', 'Movimento Original',10,'f','f','f',1,'text','Movimento Original'),
       (1014210,'e145_movimento_retencao','int4','Movimento da retenção','0', 'Movimento Retenção',10,'f','f','f',1,'text','Movimento Retenção'),
       (1014211,'e145_valor_retencao','float4','Valor total da retenção','0', 'Valor total da retenção',10,'f','f','f',4,'text','Valor total da retenção');

insert into db_sysarqcamp
values (1010943,1014207,1,0),
       (1010943,1014208,2,0),
       (1010943,1014209,3,0),
       (1010943,1014210,4,0),
       (1010943,1014211,5,0);

insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010943,1014207,1,1014207);

insert into db_sysforkey
values (1010943,1014208,1,808,0),
       (1010943,1014209,1,995,0),
       (1010943,1014210,1,995,0);

insert into db_sysindices
values (1008785,'empagemovretencoes_pagordem_in',1010943,'0'),
       (1008786,'empagemovretencoes_movimento_original_in',1010943,'0'),
       (1008787,'empagemovretencoes_movimento_retenca_in',1010943,'0');

insert into db_syscadind
values (1008785,1014208,1),
       (1008786,1014209,1),
       (1008787,1014210,1);

insert into db_syssequencia values(1001070, 'empagemovretencoes_e145_codigo_seq', 1, 1, 9223372036854775807, 1, 1);
SQL
         );
    }

    private function downDicionario()
    {
         DB::connection()->getPdo()->exec(<<<SQL
delete from db_syssequencia where codsequencia = 1001070;
delete from db_syscadind where codind in (1008785, 1008786, 1008787);
delete from db_sysindices where codind in (1008785, 1008786, 1008787);
delete from db_sysforkey where codarq in (1010943);
delete from db_sysprikey where codarq = 1010943;
delete from db_sysarqcamp where codarq = 1010943;
delete from db_syscampo where codcam in (1014207, 1014208, 1014209, 1014210, 1014211);
delete from db_sysarqmod where codarq = 1010943;
delete from db_sysarquivo where codarq = 1010943;
SQL
         );
    }

    private function upEstrutura()
    {
        DB::connection()->getPdo()->exec(<<<SQL
CREATE TABLE empenho.empagemovretencoes(
  e145_codigo serial NOT NULL primary key,
  e145_pagordem_id int4 NOT NULL default 0,
  e145_movimento_original int4 NOT NULL default 0,
  e145_movimento_retencao int4 NOT NULL default 0,
  e145_valor_retencao numeric(15, 2) default 0
);

ALTER TABLE empenho.empagemovretencoes ADD CONSTRAINT empagemovretencoes_id_fk FOREIGN KEY (e145_pagordem_id) REFERENCES empenho.pagordem;
ALTER TABLE empenho.empagemovretencoes ADD CONSTRAINT empagemovretencoes_original_fk FOREIGN KEY (e145_movimento_original) REFERENCES empenho.empagemov;
ALTER TABLE empenho.empagemovretencoes ADD CONSTRAINT empagemovretencoes_retencao_fk FOREIGN KEY (e145_movimento_retencao) REFERENCES empenho.empagemov;

CREATE INDEX empagemovretencoes_pagordem_in ON empenho.empagemovretencoes(e145_pagordem_id);
CREATE INDEX empagemovretencoes_movimento_original_in ON empenho.empagemovretencoes(e145_movimento_original);
CREATE INDEX empagemovretencoes_movimento_retenca_in ON empenho.empagemovretencoes(e145_movimento_retencao);

select configuracoes.fc_auditoria_cria_funcao('empenho.empagemovretencoes');
SQL
        );
    }
    private function downEstrutura()
    {
        DB::connection()->getPdo()->exec(<<<SQL
DROP TABLE IF EXISTS empagemovretencoes CASCADE;
SQL
        );
    }
}
