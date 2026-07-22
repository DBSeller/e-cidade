<?php

use Classes\PostgresMigration;

class M10173FcRubricaEsocial extends PostgresMigration
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
         create  or replace function fc_rubrica_esocial(rubrica varchar, instituicao integer,
       OUT codigo_rubrica varchar,
       OUT identificador varchar,
       OUT descricao  varchar,
       OUT codigo_incidencia_irrf  varchar,
       OUT natureza INT
       ) returns record
as
$$
  declare
    codigo_formulario  integer;
    codigo_resposta  integer;
    parametros varchar;
    resposta record;

  begin

   codigo_rubrica := rubrica;
   parametros := '{instituicao='||instituicao||', codRubr='||rubrica||'}';

   select max(rh211_avaliacao)
     into codigo_formulario
     from esocialversaoformulario
    where rh211_esocialformulariotipo = 2;

    select db107_sequencial
      into codigo_resposta
      from (select db107_sequencial,array_accum(db103_identificadorcampo||'='||db106_resposta) as respostas
     from avaliacaopergunta
          inner join avaliacaogrupopergunta on db102_sequencial = db103_avaliacaogrupopergunta
          inner join avaliacaoperguntaopcao on db103_sequencial = db104_avaliacaopergunta
          inner join avaliacaoresposta      on db104_sequencial = db106_avaliacaoperguntaopcao
          inner join avaliacaogrupoperguntaresposta on db106_sequencial = db108_avaliacaoresposta
          inner join avaliacaogruporesposta on db107_sequencial = db108_avaliacaogruporesposta
   where db103_perguntaidentificadora is true
     and db102_avaliacao = codigo_formulario
     and db103_perguntaidentificadora is true group by 1 order by 1) as x
   where respostas @> parametros::text[];


  for resposta in
    select db107_sequencial,
           db103_identificadorcampo,
           case when db103_avaliacaotiporesposta in (1, 3) then db104_valorresposta else db106_resposta end as db106_resposta
     from avaliacaopergunta
          inner join avaliacaogrupopergunta on db102_sequencial = db103_avaliacaogrupopergunta
          inner join avaliacaoperguntaopcao on db103_sequencial = db104_avaliacaopergunta
          inner join avaliacaoresposta      on db104_sequencial = db106_avaliacaoperguntaopcao
          inner join avaliacaogrupoperguntaresposta on db106_sequencial = db108_avaliacaoresposta
          inner join avaliacaogruporesposta on db107_sequencial = db108_avaliacaogruporesposta
     and db102_avaliacao = codigo_formulario
     and db107_sequencial = codigo_resposta
     loop

     if resposta.db103_identificadorcampo = 'ideTabRubr' then
       identificador := resposta.db106_resposta;
     end if;

     if resposta.db103_identificadorcampo = 'dscRubr' then
       descricao := resposta.db106_resposta;
     end if;
     
     if resposta.db103_identificadorcampo = 'codIncIRRF' then
       codigo_incidencia_irrf := resposta.db106_resposta;
     end if;
     
     if resposta.db103_identificadorcampo = 'natRubr' then
        natureza := resposta.db106_resposta::INT;
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
         drop function if exists fc_rubrica_esocial(rubrica varchar, instituicao integer,
           OUT codigo_rubrica varchar,
           OUT identificador varchar,
           OUT descricao  varchar,
           OUT tipo_irrf  varchar,
           OUT natureza INT
         );
SQL
);

    }
}
