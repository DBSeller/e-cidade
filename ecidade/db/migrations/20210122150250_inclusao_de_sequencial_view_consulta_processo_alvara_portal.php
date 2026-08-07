<?php

use Classes\PostgresMigration;

class InclusaoDeSequencialViewConsultaProcessoAlvaraPortal extends PostgresMigration
{
    public function up()
    {

        $this->execute(<<<SQL

drop view if exists consulta_processo_alvara_portal;
create or replace view consulta_processo_alvara_portal as
select distinct ov01_dataatend,
                ov01_horaatend,
                ov02_nome,
                ov01_anousu,
                ov02_cnpjcpf,
                ov01_numero,
                ov01_requerente,
                p58_numero,
                p58_ano,
                p58_codproc,
                p51_descr,
                ov01_sequencial,
                case
                    when ov33_informacoesprocesso -> 'dados_responsavel' ->> 'razao_social' <> ''
                        then ov33_informacoesprocesso -> 'dados_responsavel' ->> 'razao_social'
                    else ov33_informacoesprocesso -> 'dados_empresa' ->> 'razao_social' end as empresa
from ouvidoriaatendimento
         inner join ouvidoriaatendimentocidadao on ov10_ouvidoriaatendimento = ov01_sequencial
         inner join cidadao on ov02_sequencial = ov10_cidadao and ov10_seq = ov02_seq
         left join processoouvidoria on ov09_ouvidoriaatendimento = ov01_sequencial
         left join protprocesso on ov09_protprocesso = p58_codproc
         left join tipoproc on p51_codigo = p58_codigo
         inner join ouvidoriaatendimentoprocessoeletronico on ov33_ouvidoriaatendimento = ov01_sequencial;

SQL
        );
    }

    public function down() {
        $this->execute("drop view consulta_processo_alvara_portal");
    }
}
