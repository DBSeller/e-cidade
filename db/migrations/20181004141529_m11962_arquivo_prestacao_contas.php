<?php

use Classes\PostgresMigration;

class M11962ArquivoPrestacaoContas extends PostgresMigration
{
    public function up()
    {
        $this->execute("
update db_layoutcampos 
   set db52_default = 120,
       db52_descr = 'VERSÃO DO ARQUIVO DE NFES. FIXO 120'
 where db52_codigo = 14831;

update db_layoutcampos 
   set db52_tamanho = 332
 where db52_codigo = 14833;

insert into db_layoutcampos (
    db52_codigo, 
    db52_layoutlinha, 
    db52_nome, 
    db52_descr, 
    db52_layoutformat, 
    db52_posicao, 
    db52_default, 
    db52_tamanho, 
    db52_ident, 
    db52_imprimir, 
    db52_alinha, 
    db52_obs,
    db52_quebraapos
) values (
    (select nextval('db_layoutcampos_db52_codigo_seq')),
    865,
    'situacao_nfe',
    'SITUACAO_NFE',
    1,
    65,
    '',
    1,
    false,
    true,
    'd',
    '',
    0
);

update db_layoutcampos 
   set db52_posicao = 66
 where db52_codigo = 14844;

update db_layoutcampos 
   set db52_posicao = 81
 where db52_codigo = 14845;

update db_layoutcampos 
   set db52_posicao = 98
 where db52_codigo = 14846;

update db_layoutcampos 
   set db52_posicao = 148
 where db52_codigo = 14847;

update db_layoutcampos 
   set db52_tamanho = 355
 where db52_codigo = 14851;
        ");
    }

    public function down()
    {
        $this->execute("

delete from db_layoutcampos 
      where db52_layoutlinha = 865
        and db52_nome = 'situacao_nfe'
        and db52_descr = 'SITUACAO_NFE';

update db_layoutcampos 
   set db52_default = 110,
       db52_descr = 'VERSÃO DO ARQUIVO DE NFES. FIXO 110'
 where db52_codigo = 14831;

update db_layoutcampos 
   set db52_tamanho = 331
 where db52_codigo = 14833;

update db_layoutcampos 
   set db52_posicao = 65
 where db52_codigo = 14844;

update db_layoutcampos 
   set db52_posicao = 80
 where db52_codigo = 14845;

update db_layoutcampos 
   set db52_posicao = 97
 where db52_codigo = 14846;

update db_layoutcampos 
   set db52_posicao = 147
 where db52_codigo = 14847;

update db_layoutcampos 
   set db52_tamanho = 354
 where db52_codigo = 14851;

        ");
    }
}
