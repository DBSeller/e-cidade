<?php

use Classes\PostgresMigration;

class M17806AdicionaCampoServicoMatmater extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<sql
            insert into db_syscampo values(1013168,'m60_servico','bool','Campo para classificar se material é do tipo serviço sim ou não','f', 'Serviço',1,'f','f','f',5,'text','Serviço');
            insert into db_sysarqcamp values(1016,1013168,8,0);

            alter table material.matmater add column m60_servico boolean default false;

            with materiais_vinculados as (
                  select m60_codmater, array_agg(distinct pc01_servico)::text[] as tem_inconsistencia
                  from matmater
                           join transmater ON transmater.m63_codmatmater = matmater.m60_codmater
                           join compras.pcmater ON pcmater.pc01_codmater = transmater.m63_codpcmater
                  group by m60_codmater
              ),
              materiais_servico as (
                  select * from materiais_vinculados where array_length(tem_inconsistencia, 1) = 1
              ), corrigir as (
                  select materiais_servico.m60_codmater
                      from materiais_servico
                           join matmater on matmater.m60_codmater = materiais_servico.m60_codmater
                           join transmater ON transmater.m63_codmatmater = materiais_servico.m60_codmater
                           join pcmater ON pcmater.pc01_codmater = transmater.m63_codpcmater
                  where pcmater.pc01_servico is true
              ) update matmater set m60_servico = 't' from corrigir where corrigir.m60_codmater = matmater.m60_codmater;
sql
        );
    }

    public function down()
    {
        $this->execute(<<<sql
            delete from db_sysarqcamp where codcam = 1013168;
            delete from db_syscampo where codcam = 1013168;

            alter table material.matmater drop column m60_servico;
sql
        );
    }
}
