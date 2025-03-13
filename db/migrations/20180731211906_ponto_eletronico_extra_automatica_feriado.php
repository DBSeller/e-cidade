<?php

use Classes\PostgresMigration;

class PontoEletronicoExtraAutomaticaFeriado extends PostgresMigration
{
    function up()
    {
        $this->upDDL();
        $this->upDicionarioDados();
    }

    function down()
    {
        $this->downDicionarioDados();
        $this->downDDL();
    }

    function upDDL()
    {
        $this->table('gradeshorarios', array('schema'=>'recursoshumanos'))
            ->addColumn('rh190_extra_autorizada_feriado',   'boolean', array('default'=>false))
            ->save();

        $escalasExtraAutomaticaFeriado = $this->fetchAll("SELECT * FROM gradeshorarios WHERE rh190_sequencial IN (108,196, 220, 273, 277, 279, 280, 281, 347, 370, 371,
                                                                                                                 372, 373, 377, 383, 384, 385, 386, 387, 450, 451, 453,
                                                                                                                 454, 465, 466, 496, 498, 499, 507, 508, 510, 511, 512,
                                                                                                                 513, 514, 515, 518, 519, 520, 523, 524) ");

        if(!empty($escalasExtraAutomaticaFeriado)) {

            foreach ($escalasExtraAutomaticaFeriado as $escalaExtraAutomaticaFeriado) {

                $escalaExtraAutomaticaFeriado         = (object)$escalaExtraAutomaticaFeriado;
                $escalaExtraAutomaticaFeriadoAlterado = $this->execute("UPDATE gradeshorarios SET rh190_extra_autorizada_feriado = true WHERE rh190_sequencial = {$escalaExtraAutomaticaFeriado->rh190_sequencial}");
                
                if(empty($escalaExtraAutomaticaFeriadoAlterado)) {
                    throw new Exception("NÃ£o foi alterar a escala de revezamento para pagar automaticamente as horas extras em feriado.");
                }
            }
        }
    }

    function upDicionarioDados()
    {
        $tabela_db_syscampo   = $this->table('db_syscampo',      array('schema'=>'configuracoes'));
        $tabela_db_sysarqcamp = $this->table('db_sysarqcamp',    array('schema'=>'configuracoes'));

        $tabela_db_syscampo->insert(array('codcam','nomecam','conteudo','descricao','valorinicial','rotulo','tamanho','nulo','maiusculo','autocompl','aceitatipo','tipoobj','rotulorel'),array(
            array(1009858,'rh190_extra_autorizada_feriado','bool','Define se a escala terá as horas extras calculadas de forma automática em feriado.','f','Extras automáticas em feriado',1,'f','f','f',5,'text','Extras automáticas em feriado'),
        ));

        $tabela_db_sysarqcamp->insert(array('codarq','codcam','seqarq','codsequencia'), array(
            array(4007, 1009858, 5, 0)
        ));
    }
    
    function downDicionarioDados()
    {
        $this->execute("DELETE FROM db_sysarqcamp   WHERE codcam IN (1009858)");
        $this->execute("DELETE FROM db_syscampo     WHERE codcam IN (1009858)");
    }

    function downDDL()
    {
        $this->table('gradeshorarios', array('schema'=>'recursoshumanos'))
            ->removeColumn('rh190_extra_autorizada_feriado')
            ->save();
    }

}
