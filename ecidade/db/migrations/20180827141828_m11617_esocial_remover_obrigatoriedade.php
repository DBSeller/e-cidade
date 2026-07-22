<?php

use Classes\PostgresMigration;

class M11617EsocialRemoverObrigatoriedade extends PostgresMigration
{
    public function up()
    {
        $this->acerto();
    }
    public function down()
    {
        $this->acerto();
    }

    private function acerto()
    {
        //-- S1000 - Empregador
        $this->execute("
            update avaliacaopergunta set db103_obrigatoria = 'f'
             where db103_sequencial in (
                    select db103_sequencial
                from habitacao.avaliacaogrupopergunta
                join habitacao.avaliacaopergunta on db103_avaliacaogrupopergunta = db102_sequencial
               where db102_avaliacao = 3000015
                and db102_identificadorcampo in (
                    'dadosIsencao',
                    'infoOP',
                    'infoEFR',
                    'infoEnte',
                    'infoOrgInternacional',
                    'softwareHouse',
                    'situacaoPJ',
                    'situacaoPF'
                )
            );"
        );

        // -- S1020 - Lotacao Tributaria
        $this->execute("
            update avaliacaopergunta set db103_obrigatoria = 'f'
             where db103_sequencial in (
                    select db103_sequencial
                from habitacao.avaliacaogrupopergunta
                join habitacao.avaliacaopergunta on db103_avaliacaogrupopergunta = db102_sequencial
               where db102_avaliacao = 3000014
                and db102_identificadorcampo in (
                    'infoProcJudTerceiros',
                    'infoEmprParcial'
                )
            );
        ");

        // -- S2200 - Servidor
        $this->execute("
            update avaliacaopergunta set db103_obrigatoria = 'f'
             where db103_sequencial in (
                    select db103_sequencial
                from habitacao.avaliacaogrupopergunta
                join habitacao.avaliacaopergunta on db103_avaliacaogrupopergunta = db102_sequencial
               where db102_avaliacao = 3000013
                and db102_identificadorcampo in (
                    'documentos',
                    'CTPS',
                    'RIC',
                    'RG',
                    'RNE',
                    'CNH',
                    'OC',
                    'brasil',
                    'exterior',
                    'trabEstrangeiro',
                    'infoDeficiencia',
                    'dependente',
                    'aposentadoria',
                    'contato',
                    'infoCeletista',
                    'trabTemporario',
                    'ideEstabVinc',
                    'ideTrabSubstituido',
                    'aprend',
                    'infoEstatutario',
                    'infoDecJud',
                    'localTrabGeral',
                    'localTrabDom',
                    'horContratual',
                    'horario',
                    'filiacaoSindical',
                    'alvaraJudicial',
                    'observacoes',
                    'sucessaoVinc',
                    'transfDom',
                    'afastamento',
                    'desligamento',
                    'dependente_1' ,
                    'dependente_10',
                    'dependente_2' ,
                    'dependente_3' ,
                    'dependente_4' ,
                    'dependente_5' ,
                    'dependente_6' ,
                    'dependente_7' ,
                    'dependente_8' ,
                    'dependente_9' ,
                    'observacoes_1',
                    'observacoes_2',
                    'observacoes_3'
                )
            );
        ");

        // -- S1010 - Tabela de Rubricas
        $this->execute("
            update avaliacaopergunta set db103_obrigatoria = 'f'
             where db103_sequencial in (
                    select db103_sequencial
                from habitacao.avaliacaogrupopergunta
                join habitacao.avaliacaopergunta on db103_avaliacaogrupopergunta = db102_sequencial
               where db102_avaliacao = 3000016
                and db102_identificadorcampo in (
                    'ideProcessoCP',
                    'ideProcessoIRRF',
                    'ideProcessoFGTS',
                    'ideProcessoSIND'
                )
            );
        ");

        // -- S1030 - Tabela de Cargos
        $this->execute("
            update avaliacaopergunta set db103_obrigatoria = 'f'
             where db103_sequencial in (
                    select db103_sequencial
                from habitacao.avaliacaogrupopergunta
                join habitacao.avaliacaopergunta on db103_avaliacaogrupopergunta = db102_sequencial
               where db102_avaliacao = 3000017
                and db102_identificadorcampo in (
                    'cargoPublico',
                    'novaValidade'
                )
            );
        ");

        // -- S1050 - Tabela de Horários
        $this->execute("
            update avaliacaopergunta set db103_obrigatoria = 'f'
             where db103_sequencial in (
                    select db103_sequencial
                from habitacao.avaliacaogrupopergunta
                join habitacao.avaliacaopergunta on db103_avaliacaogrupopergunta = db102_sequencial
               where db102_avaliacao = 3000019
                and db102_identificadorcampo in (
                    'horarioIntervalo'
                )
            );
        ");

        // -- S1070 - Tabela de Processos
        $this->execute("
            update avaliacaopergunta set db103_obrigatoria = 'f'
             where db103_sequencial in (
                    select db103_sequencial
                from habitacao.avaliacaogrupopergunta
                join habitacao.avaliacaopergunta on db103_avaliacaogrupopergunta = db102_sequencial
               where db102_avaliacao = 3000020
                and db102_identificadorcampo in (
                    'dadosProcJud',
                    'infoSusp_1',
                    'infoSusp_2',
                    'infoSusp_3',
                    'infoSusp_4',
                    'infoSusp_5'
                )
            );
        ");

        // -- S2250 - Aviso Prévio
        $this->execute("
            update avaliacaopergunta set db103_obrigatoria = 'f'
             where db103_sequencial in (
                    select db103_sequencial
                from habitacao.avaliacaogrupopergunta
                join habitacao.avaliacaopergunta on db103_avaliacaogrupopergunta = db102_sequencial
               where db102_avaliacao = 3000022
                and db102_identificadorcampo in (
                    'detAvPrevio',
                    'cancAvPrevio'
                )
            );
        ");

        // -- S2230 - Afastamento Temporário
        $this->execute("
            update avaliacaopergunta set db103_obrigatoria = 'f'
             where db103_sequencial in (
                    select db103_sequencial
                from habitacao.avaliacaogrupopergunta
                join habitacao.avaliacaopergunta on db103_avaliacaogrupopergunta = db102_sequencial
               where db102_avaliacao = 3000023
                and db102_identificadorcampo in (
                    'iniAfastamento',
                    'infoAtestado',
                    'emitente',
                    'infoCessao',
                    'infoMandSind',
                    'infoRetif',
                    'fimAfastamento'
                )
            );
        ");

        // -- S3000 - Exclusão de Eventos
        $this->execute("
            update avaliacaopergunta set db103_obrigatoria = 'f'
             where db103_sequencial in (
                    select db103_sequencial
                from habitacao.avaliacaogrupopergunta
                join habitacao.avaliacaopergunta on db103_avaliacaogrupopergunta = db102_sequencial
               where db102_avaliacao = 3000025
                and db102_identificadorcampo in (
                    'ideTrabalhador',
                    'ideFolhaPagto'
                )
            );
        ");
    }
}
