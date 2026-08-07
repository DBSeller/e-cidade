<?php

use Classes\PostgresMigration;

class M10173FcLotacaoTributaria extends PostgresMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {

        $this->execute(<<<SQL
         create  or replace function fc_lotacaotributaria_esocial(cgm integer,
       OUT codigo integer,
       OUT codLotacao varchar,
       OUT tpLotacao varchar,     
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
          inner join avaliacaogruporespostalotacao on db107_sequencial = eso04_avaliacaogruporesposta
     where eso04_cgm = cgm
     loop

     codigo := cgm; 
     if resposta.db103_identificadorcampo = 'codLotacao' then
       codLotacao := resposta.db106_resposta;
     end if;

     if resposta.db103_identificadorcampo = 'tpLotacao' then
       tpLotacao := resposta.db106_resposta;
     end if;    
     
     if resposta.db103_identificadorcampo = 'tpInsc' then
       tpInsc := resposta.db106_resposta;
     
     end if;
     if resposta.db103_identificadorcampo = 'nrInsc' then
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
         drop function if exists fc_lotacaotributaria_esocial(cgm integer);
SQL
        );


    }
}
