<?php

use Classes\PostgresMigration;

class M17922AtualizacaoEnum extends PostgresMigration
{
    public function change()
    {
        $this->execute(<<<SQL

alter type origem_valores rename to _origem_valores;
create type origem_valores as enum (
  'PROGRAMA ESTRATEGICO',
  'INICIATIVA',
  'OBJETIVOS',
  'DETALHAMENTO INICIATIVA',
  'RECEITA',
  'INDICADOR AREA',
  'INDICADOR PROGRAMA',
  'META FISICA',
  'META FINANCEIRA',
  'TETO ORCAMENTARIO'
);

alter table planejamento.valores alter column pl10_origem TYPE origem_valores USING pl10_origem::text::origem_valores;
drop type _origem_valores;
SQL
        );
    }
}
