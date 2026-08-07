<?php

use Classes\PostgresMigration;

class M10173FcEmpregadorEsocial extends PostgresMigration
{
       public function up()
    {
        $this->execute(<<<SQL
         create  or replace function fc_empregador_esocial(cgm integer,
       OUT codigo integer,
       OUT nmRazao varchar,
       OUT classTrib varchar,
       OUT natJurid  varchar,
       OUT tpInsc varchar,
       OUT nrInsc varchar
       ) returns record
as
$$
  declare   
    
    resposta record;

  begin 
   
  for resposta in
    select db107_sequencial,
           db103_identificadorcampo,
           db102_identificadorcampo,
           case when db103_avaliacaotiporesposta in (1, 3) then db104_valorresposta else db106_resposta end as db106_resposta
     from avaliacaopergunta
          inner join avaliacaogrupopergunta on db102_sequencial = db103_avaliacaogrupopergunta
          inner join avaliacaoperguntaopcao on db103_sequencial = db104_avaliacaopergunta
          inner join avaliacaoresposta      on db104_sequencial = db106_avaliacaoperguntaopcao
          inner join avaliacaogrupoperguntaresposta on db106_sequencial = db108_avaliacaoresposta
          inner join avaliacaogruporesposta on db107_sequencial = db108_avaliacaogruporesposta
          inner join avaliacaogruporespostacgm on db107_sequencial = eso03_avaliacaogruporesposta
     where eso03_cgm = cgm
     loop

     codigo := cgm; 
     if resposta.db103_identificadorcampo = 'nmRazao' then
       nmRazao := resposta.db106_resposta;
     end if;

     if resposta.db103_identificadorcampo = 'classTrib' then
       classTrib := resposta.db106_resposta;
     end if;
     if resposta.db103_identificadorcampo = 'natJurid' then
       natJurid := resposta.db106_resposta;
     end if;
     
     if resposta.db102_identificadorcampo = 'ideEstab' and resposta.db103_identificadorcampo = 'tpInsc' then
       tpInsc := resposta.db106_resposta;
     
     end if;
     if resposta.db102_identificadorcampo = 'ideEstab' and resposta.db103_identificadorcampo = 'nrInsc' then
       nrInsc := resposta.db106_resposta;
     end if;

     end loop;

  return ;
  end;
$$
language 'plpgsql';         
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
         drop function if exists fc_empregador_esocial(cgm integer);
SQL
        );

    }
}
