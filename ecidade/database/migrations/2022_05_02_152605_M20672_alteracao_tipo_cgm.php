<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20672AlteracaoTipoCgm extends Migration
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
        $this->downEstrutura();
        $this->downDicionario();
    }

    private function upDicionario() {
        $sql = <<<SQL
            update configuracoes.db_syscampo set nomecam = 'rh261_numcgm', conteudo = 'int4', descricao = 'CGM Origem/Destino', valorinicial = '0', rotulo = 'CGM Origem/Destino', nulo = 't', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'CGM Origem/Destino' where codcam = 1013973;
            insert into configuracoes.db_sysforkey values(1010896,1013973,1,42,0);

            update db_syscampo set nomecam = 'rh02_ressarcimento', conteudo = 'char(1)', descricao = 'Campo que informa se a cedência possui ressarcimento.', valorinicial = 'X', rotulo = 'Ressarcimento', nulo = 't', tamanho = 1, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Ressarcimento' where codcam = 21936;
            update db_syscampo set nomecam = 'rh02_onus', conteudo = 'char(1)', descricao = 'Informa se existe onus na cedência de um servidor. Especifica se o Onus é da origem ou destino.', valorinicial = 'X', rotulo = 'Ônus', nulo = 't', tamanho = 1, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Ônus' where codcam = 21935;
            update db_syscampo set nomecam = 'rh02_cedencia', conteudo = 'char(1)', descricao = 'Tipo da cedência, pode ser Cedido, Adido ou Não se Aplica, sendo não se aplica a opção default.', valorinicial = 'X', rotulo = 'Tipo', nulo = 't', tamanho = 1, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Tipo Cedência' where codcam = 21934; 

SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downDicionario() {
        $sql = <<<SQL
            delete from configuracoes.db_sysforkey where codcam = 1013973;
            update configuracoes.db_syscampo set nomecam = 'rh261_cgm', conteudo = 'varchar(10)', descricao = 'CGM Origem/Destino', valorinicial = '0', rotulo = 'CGM Origem/Destino', nulo = 't', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'CGM Origem/Destino' where codcam = 1013973;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upEstrutura()
    {
        $sql=<<<SQL
                ALTER TABLE pessoal.rhcedencia rename column rh261_cgm to rh261_numcgm;
                ALTER TABLE pessoal.rhcedencia alter column rh261_numcgm type integer using (case when rh261_numcgm is null or rh261_numcgm = '' then null else rh261_numcgm::integer end);
                ALTER TABLE pessoal.rhcedencia add constraint rhcedencia_rh261_numcgm_fkey FOREIGN KEY (rh261_numcgm) REFERENCES protocolo.cgm (z01_numcgm);
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downEstrutura()
    {
        $sql=<<<SQL
                ALTER TABLE pessoal.rhcedencia drop constraint rhcedencia_rh261_numcgm_fkey;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}
