<?php

use Classes\PostgresMigration;

class M16558CriaEstruturaBancoAgenciaPagadores extends PostgresMigration
{
   public function up()
   {
    $this->upDicionario();
    $this->upEstrutura();
   }

   public function down()
   {
       $this->downDicionario();
       $this->downEstrutura();
   }

   private function upDicionario()
   {
       $this->execute(<<<SQL
            INSERT INTO db_syscampo VALUES(1011863,'k15_bancopagamento','int4','Banco Pagamento','0', 'Banco Pagamento',6,'f','f','f',1,'text','Banco Pagamento');
            INSERT INTO db_syscampo VALUES(1011864,'k15_agenciapagamento','int4','Agência Pagamento','0', 'Agência Pagamento',6,'f','f','f',1,'text','Agência Pagamento');

            INSERT INTO db_sysarqcamp VALUES(116,1011863,58,0);
            INSERT INTO db_sysarqcamp VALUES(116,1011864,59,0);

            INSERT INTO db_syscampo VALUES(1011865,'bancopagamento','text','Banco Pagamento','0', 'Banco Pagamento',11,'f','f','f',1,'text','Banco Pagamento');
            INSERT INTO db_syscampo VALUES(1011866,'agenciapagamento','text','Agência Pagamento','0', 'Agência Pagamento',11,'f','f','f',1,'text','Agência Pagamento');

            INSERT INTO db_sysarqcamp VALUES(214,1011865,21,0);
            INSERT INTO db_sysarqcamp VALUES(214,1011866,22,0);
SQL
        );
   }

   private function downDicionario()
   {
       $this->execute(<<<SQL
            DELETE FROM db_sysarqcamp WHERE codcam IN (
                1011863,
                1011864,
                1011865,
                1011866
            );

            DELETE FROM db_syscampo WHERE codcam IN (
                1011863,
                1011864,
                1011865,
                1011866
            );
SQL
       );
   }

   private function upEstrutura()
   {
       $this->execute(<<<SQL
            ALTER TABLE caixa.cadban ADD COLUMN k15_bancopagamento integer NOT NULL DEFAULT 000000;
            ALTER TABLE caixa.cadban ADD COLUMN k15_agenciapagamento integer NOT NULL DEFAULT 000000;

            ALTER TABLE caixa.disbanco ADD COLUMN bancopagamento TEXT;
            ALTER TABLE caixa.disbanco ADD COLUMN agenciapagamento TEXT;
SQL
       );
   }

   private function downEstrutura()
   {
       $this->execute(<<<SQL
            ALTER TABLE caixa.cadban DROP COLUMN k15_bancopagamento;
            ALTER TABLE caixa.cadban DROP COLUMN k15_agenciapagamento;

            ALTER TABLE caixa.disbanco DROP COLUMN bancopagamento;
            ALTER TABLE caixa.disbanco DROP COLUMN agenciapagamento;
SQL
       );
   }
}
