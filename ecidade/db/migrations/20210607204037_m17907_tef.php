<?php

use Classes\PostgresMigration;

class M17907Tef extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
            update db_syscampo set nomecam = 'k196_valorminimoparcelafisica', conteudo = 'float8', descricao = 'Campo com o valor mínimo que cada parcela pode ter para pessoa física.', valorinicial = '0', rotulo = 'Valor Mínimo da Parcela PF', nulo = 't', tamanho = 11, maiusculo = 'f', autocompl = 'f', aceitatipo = 4, tipoobj = 'text', rotulorel = 'Valor Mínimo da Parcela PF' where codcam = 1013177;

            insert into db_syscampo values(1013279,'k196_valorminimoparcelajuridica','float4','Valor mínimo da parcela para pessoa jurídica.','0', 'Valor Mínimo da Parcela PJ',11,'t','f','f',4,'text','Valor Mínimo da Parcela PJ');
            insert into db_sysarqcamp values(1010787,1013279,6,0);

            alter table caixa.configuracoesteftipodebito rename column k196_valorminimoparcela to k196_valorminimoparcelafisica;
            alter table caixa.configuracoesteftipodebito add column k196_valorminimoparcelajuridica numeric;
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
            delete from db_sysarqcamp where codcam = 1013279;
            delete from db_syscampo where codcam = 1013279;
SQL
        );
    }
}
