<?php

use Classes\PostgresMigration;

class M13920ArquivoSigfisDiarias extends PostgresMigration
{
 
    public function up()
    {
        $sql = "insert into db_layouttxt( db50_codigo ,db50_layouttxtgrupo ,db50_descr ,db50_quantlinhas ,db50_obs ) values ( 306 ,4 ,'SIGFIS - DIÁRIAS' ,1 ,'' );
                insert into db_layoutlinha( db51_codigo ,db51_layouttxt ,db51_descr ,db51_tipolinha ,db51_tamlinha ,db51_linhasantes ,db51_linhasdepois ,db51_obs ,db51_separador ,db51_compacta ) values ( 10292 ,306 ,'REGISTRO - DIÁRIAS' ,3 ,464 ,0 ,0 ,'' ,'' ,'0' );
                insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 171753 ,10292 ,'cd_Unidade' ,'UNIDADE GESTORA' ,14 ,1 ,'' ,4 ,'f' ,'t' ,'d' ,'' ,0 );
                insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 171754 ,10292 ,'cd_UnidadeOrcamentaria' ,'UNIDADE ORÇAMENTÁRIA' ,14 ,5 ,'' ,4 ,'f' ,'t' ,'d' ,'' ,0 );
                insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 171755 ,10292 ,'nu_Empenho' ,'NÚMERO DO EMPENHO' ,14 ,9 ,'' ,10 ,'f' ,'t' ,'d' ,'' ,0 );
                insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 171756 ,10292 ,'dt_PagamentoEmpenho' ,'DATA PAGAMENTO EMPENHO' ,14 ,19 ,'' ,8 ,'f' ,'t' ,'d' ,'' ,0 );
                insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 171757 ,10292 ,'nu_MatriculaFuncionario' ,'MATRÍCULA DO FUNCIONÁRIO' ,14 ,27 ,'' ,10 ,'f' ,'t' ,'d' ,'' ,0 );
                insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 171758 ,10292 ,'dt_Ano' ,'ANO' ,14 ,37 ,'' ,4 ,'f' ,'t' ,'d' ,'' ,0 );
                insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 171759 ,10292 ,'nm_Funcionario' ,'NOME DO FUNCIONÁRIO' ,14 ,41 ,'' ,50 ,'f' ,'t' ,'d' ,'' ,0 );
                insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 171760 ,10292 ,'Reservado_tce' ,'RESERVADO TCE' ,14 ,91 ,'' ,100 ,'f' ,'t' ,'d' ,'' ,0 );
                insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 171761 ,10292 ,'de_MotivoViagem' ,'OBJETO DA VIAGEM' ,14 ,191 ,'' ,200 ,'f' ,'t' ,'d' ,'' ,0 );
                insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 171762 ,10292 ,'dt_Saida' ,'DATA DE SAÍDA' ,14 ,391 ,'' ,8 ,'f' ,'t' ,'d' ,'' ,0 );
                insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 171763 ,10292 ,'Reservado_tce_1' ,'RESERVADO TCE' ,14 ,399 ,'' ,5 ,'f' ,'t' ,'d' ,'' ,0 );
                insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 171764 ,10292 ,'dt_Retorno' ,'DATA DE RETORNO' ,14 ,404 ,'' ,8 ,'f' ,'t' ,'d' ,'' ,0 );
                insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 171765 ,10292 ,'Reservado_tce_2' ,'RESERVADO TCE' ,14 ,412 ,'' ,5 ,'f' ,'t' ,'d' ,'' ,0 );
                insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 171766 ,10292 ,'qt_Diarias' ,'QUANTIDADE DE DIÁRIAS' ,14 ,417 ,'' ,3 ,'f' ,'t' ,'d' ,'' ,0 );
                insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 171767 ,10292 ,'vl_TotalDiarias' ,'VALOR TOTAL DAS DIÁRIAS' ,14 ,420 ,'' ,16 ,'f' ,'t' ,'d' ,'' ,0 );
                insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 171768 ,10292 ,'dt_AnoMes' ,'COMPETÊNCIA' ,14 ,436 ,'' ,6 ,'f' ,'t' ,'d' ,'' ,0 );
                insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 171769 ,10292 ,'cd_Orgao' ,'CÓDIGO DO ÓRGÃO' ,14 ,442 ,'' ,4 ,'f' ,'t' ,'d' ,'' ,0 );
                insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 171770 ,10292 ,'nu_EmpenhoSup' ,'NÚMERO DO EMPENHO' ,14 ,446 ,'' ,10 ,'f' ,'t' ,'d' ,'' ,0 );
                insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 171771 ,10292 ,'Nu_Diaria' ,'NÚMERO DA DIÁRIA' ,14 ,456 ,'' ,9 ,'f' ,'t' ,'d' ,'' ,0 );
                update db_layoutcampos set db52_codigo = 171770 , db52_layoutlinha = 10292 , db52_nome = 'nu_EmpenhoSup' , db52_descr = 'NÚMERO DO SUBEMPENHO' , db52_layoutformat = 14 , db52_posicao = 446 , db52_default = '' , db52_tamanho = 10 , db52_ident = 'f' , db52_imprimir = 't' , db52_alinha = 'd' , db52_obs = '' , db52_quebraapos = 0 where db52_codigo = 171770;
                update db_layoutcampos set db52_posicao = db52_posicao+0 where db52_layoutlinha = 10292 and db52_posicao >= 446 and db52_codigo <> 171770;
                ";
        $this->execute($sql);

    }

    public function down ()
    {
        $sql = "
            delete from db_layoutcampos where db52_codigo in (171753, 171754, 171755, 171756, 171757, 171758, 171759, 171760, 171761, 171762, 171763, 171764, 171765, 171766, 171767, 171768, 171769, 171770, 171771);
            delete from db_layoutlinha where db51_codigo = 10292;
            delete from db_layouttxt where db50_codigo = 306;
        ";
        $this->execute($sql);
    }
}
