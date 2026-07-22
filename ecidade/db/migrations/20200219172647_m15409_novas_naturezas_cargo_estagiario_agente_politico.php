<?php

use Classes\PostgresMigration;

class M15409NovasNaturezasCargoEstagiarioAgentePolitico extends PostgresMigration
{
    public function up()
    {
        $this->execute('
            INSERT INTO rhnaturezaregime 
                (rh71_sequencial, rh71_descricao) 
            VALUES 
                (5, \'AGENTE POLÍTICO\'),
                (6, \'ESTAGIÁRIO\')
        ');

        $this->execute('
            INSERT INTO db_layoutcampos
                ( db52_codigo ,db52_layoutlinha ,db52_nome,
                  db52_descr ,db52_layoutformat ,db52_posicao,
                  db52_default ,db52_tamanho ,db52_ident ,db52_imprimir,
                  db52_alinha ,db52_obs ,db52_quebraapos
                ) 
            VALUES 
                (
                  171774 ,138 ,\'orgao_unidade\' ,
                  \'CÓDIGO DO ÓRGÃO + UNIDADE ORÇAMENTÁRIA\' ,1 ,394 ,
                  \'\' ,4 ,\'f\' ,\'t\' ,\'d\' ,\'\' ,0
                );
        ');
    }

    public function down()
    {
        $this->execute('
            DELETE FROM rhnaturezaregime
            WHERE 
                rh71_sequencial = 5
                OR rh71_sequencial = 6
        ');

        $this->execute('
            DELETE FROM db_layoutcampos 
            WHERE db52_codigo = 171774
        ');
    }
}
