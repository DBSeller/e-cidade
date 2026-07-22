<?php

use Classes\PostgresMigration;

class M17295PreMenusModuloPlanejamento extends PostgresMigration
{
    public function up()
    {
        $sSql = <<<SQL
            insert into db_itensmenu(id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente)
            values
            (228358, 'Planejamento' ,'Planejamento' ,'' ,'2' ,'1' ,'Modulo de planejamento' ,'true'),
            (228359, 'Cadastros' ,'Cadastros Modulo Planejamento' ,'' ,'1' ,'1' ,'Cadastros Modulo Planejamento' ,'true'),
            (228360, 'PPA' ,'Cadastro do PPA' ,'pla1_planejamento001.php?tipo=PPA' ,'1' ,'1' ,'Cadastro do PPA' ,'true'),
            (228361, 'LDO' ,'Cadastro da LDO' ,'pla1_planejamento001.php?tipo=LDO' ,'1' ,'1' ,'Cadastro da LDO' ,'true'),
            (228362, 'LOA' ,'Cadastro da LOA' ,'pla1_planejamento001.php?tipo=LOA' ,'1' ,'1' ,'Cadastro da LOA' ,'true'),
            (228363, 'Relatórios' ,'Relatório de Planejamento' ,'' ,'1' ,'1' ,'Relatório de Planejamento' ,'true'),
            (228364, 'Consultas' ,'Consultas do Planejamento' ,'' ,'1' ,'1' ,'Consultas do Modulo Planejamento' ,'true'),
            (228365, 'Procedimentos' ,'Procedimentos Planejamento' ,'' ,'1' ,'1' ,'Procedimentos do Módulo Planejamento' ,'true'),
            (228366, 'Cálculo das Projeções' ,'Cálculo das Projeções' ,'' ,'1' ,'1' ,'Cálculo das Projeções do Modulo Planejamento' ,'true'),
            (228367, 'Despesa' ,'Calculo Projeções de Despesa' ,'pla4_calculoprojecaodespesa001.php' ,'1' ,'1' ,'Calculo Projeções de Despesa do modulo Planejamento' ,'true'),
            (228368, 'Receita' ,'Calculo Projeções de Receita' ,'pla4_calculoprojecaoreceita001.php' ,'1' ,'1' ,'Calculo Projeções de Receita do modulo Planejamento' ,'true'),
            (228371, 'Controle de Aprovação' ,'Controle de Aprovação' ,'pla4_controleaprovacao004.php' ,'1' ,'1' ,'Da manutenção nos planos de governo, alternando a situação por exemplo para: Encaminhado ao poder legislativo, aprovado...' ,'true'),
            (228375, 'Manutenção' ,'Manutenção do planejamento' ,'' ,'1' ,'1' ,'Manutenção do planejamento' ,'true'),
            (228376, 'Despesa' ,'Manutenção da despesa' ,'' ,'1' ,'1' ,'Manutenção da despesa' ,'true'),
            (228377, 'Programa Estratégico' ,'Programa Estratégico' ,'pla4_programaestrategico.php' ,'1' ,'1' ,'Manutenção do Programa Estratégico' ,'true'),
            (228378, 'Iniciativas' ,'Iniciativas' ,'pla4_iniciativas.php' ,'1' ,'1' ,'Manutenção das iniciativas' ,'true'),
            (228379, 'Vínculos' ,'Vínculos' ,'' ,'1' ,'1' ,'Vínculos' ,'true'),
            (228380, 'Objetivo dos Programas com as Iniciativas' ,'Objetivo dos Programas com as Iniciativas' ,'pla4_vinc_objetivos_iniciativas.php' ,'1' ,'1' ,'Objetivo dos Programas com as Iniciativas' ,'true'),
            (228381, 'Obj. Estratégicos com os Programas Estratégicos' ,'Obj. Estratégicos com os Programas Estratégicos' ,'pla4_vinc_obj_estrategico_programa.php' ,'1' ,'1' ,'Obj. Estratégicos com os Programas Estratégicos' ,'true'),
            (228382, 'Área de Resultado com os Programas Estratégicos' ,'Área de Resultado com os Programas Estratégicos' ,'pla4_vinc_area_resultado_programa.php' ,'1' ,'1' ,'Área de Resultado com os Programas Estratégicos' ,'true');


            insert into db_modulos( id_item ,nome_modulo ,descr_modulo ,imagem ,temexerc ) values
            ( 228358 ,'Planejamento' ,'Planejamento' ,'228358.png' ,'true' );

            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values
            (228358, 228359, 1, 228358),
            (228359, 228360, 1, 228358),
            (228359, 228361, 2, 228358),
            (228359, 228362, 3, 228358),
            (228358, 228363, 2, 228358),
            (228358, 228364, 3, 228358),
            (228358, 228365, 4, 228358),
            (228365, 228366, 1, 228358),
            (228366, 228367, 1, 228358),
            (228366, 228368, 2, 228358),
            (228365, 228371, 2, 228358),
            (228365, 228375, 3, 228358),
            (228375, 228376, 1, 228358),
            (228376, 228377, 1, 228358),
            (228376, 228378, 2, 228358),
            (228376, 228379, 3, 228358),
            (228379, 228380, 1, 228358),
            (228379, 228381, 2, 228358),
            (228379, 228382, 3, 228358);

            insert into atendcadareamod select 78, 2,228358;

SQL;

        $this->execute($sSql);
    }

    public function down()
    {
        $sSql = <<<SQL

delete from atendcadareamod where at26_sequencia = 78;
delete from db_modulos where id_item = 228358;
delete from db_menu where modulo = 228358;
delete from db_itensmenu
where id_item in (228358, 228359, 228360, 228361, 228362, 228363, 228364, 228365, 228366, 228367, 228368, 228371,
                  228375, 228376, 228377, 228378, 228379, 228380, 228381, 228382);

SQL;

        $this->execute($sSql);
    }
}
