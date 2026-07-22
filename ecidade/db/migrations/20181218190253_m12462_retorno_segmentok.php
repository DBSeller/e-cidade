<?php

use Classes\PostgresMigration;

class M12462RetornoSegmentok extends PostgresMigration
{
    public function up()
    {
        $sSql = "
                INSERT INTO db_layoutlinha values (nextval('db_layoutlinha_db51_codigo_seq'), 102, 'DETALHE K', 3, 240,0,0,'','','f');

                INSERT INTO db_layoutcampos VALUES 
                (nextval('db_layoutcampos_db52_codigo_seq') , currval('db_layoutlinha_db51_codigo_seq') , 'oCodBanco'             , 'Codigo do Banco'               , 1 ,    1, '' ,   3 , 'f' , 't', 'd','',0),
                (nextval('db_layoutcampos_db52_codigo_seq') , currval('db_layoutlinha_db51_codigo_seq') , 'oLoteServico'          , 'Lote de Serviço'               , 1 ,    4, '' ,   4 , 'f' , 't', 'd','',0),
                (nextval('db_layoutcampos_db52_codigo_seq') , currval('db_layoutlinha_db51_codigo_seq') , 'oNsr'                  , 'NSR'                           , 1 ,    9, '' ,   5 , 'f' , 't', 'd','',0),
                (nextval('db_layoutcampos_db52_codigo_seq') , currval('db_layoutlinha_db51_codigo_seq') , 'oTipoMovimento'        , 'Tipo Movimento'                , 1 ,   15, '' ,   1 , 'f' , 't', 'd','',0),
                (nextval('db_layoutcampos_db52_codigo_seq') , currval('db_layoutlinha_db51_codigo_seq') , 'oCodInstrucao'         , 'Código Instrução'              , 1 ,   16, '' ,   2 , 'f' , 't', 'd','',0),
                (nextval('db_layoutcampos_db52_codigo_seq') , currval('db_layoutlinha_db51_codigo_seq') , 'oCodBarras'            , 'Código de Barras'              , 1 ,   18, '' ,  44 , 'f' , 't', 'd','',0),
                (nextval('db_layoutcampos_db52_codigo_seq') , currval('db_layoutlinha_db51_codigo_seq') , 'oFiller1'              , 'Filler'                        , 1 ,   62, '' ,  12 , 'f' , 't', 'e','',0),
                (nextval('db_layoutcampos_db52_codigo_seq') , currval('db_layoutlinha_db51_codigo_seq') , 'oDocumentoEmpresa'     , 'Número do documento na Empresa', 1 ,   74, '' ,   6 , 'f' , 't', 'e','',0),
                (nextval('db_layoutcampos_db52_codigo_seq') , currval('db_layoutlinha_db51_codigo_seq') , 'oFiller2'              , 'Filler'                        , 1 ,   80, '' ,  14 , 'f' , 't', 'e','',0),
                (nextval('db_layoutcampos_db52_codigo_seq') , currval('db_layoutlinha_db51_codigo_seq') , 'oDtLacamento'          , 'Data Lançamento'               , 1 ,   94, '' ,   8 , 'f' , 't', 'e','',0),
                (nextval('db_layoutcampos_db52_codigo_seq') , currval('db_layoutlinha_db51_codigo_seq') , 'oTipoMoeda'            , 'Tipo da Moeda'                 , 1 ,  102, '' ,   3 , 'f' , 't', 'e','',0),
                (nextval('db_layoutcampos_db52_codigo_seq') , currval('db_layoutlinha_db51_codigo_seq') , 'oQuantidadeMoeda'      , 'Quantidade de Moeda'           , 1 ,  105, '' ,  15 , 'f' , 't', 'e','',0),
                (nextval('db_layoutcampos_db52_codigo_seq') , currval('db_layoutlinha_db51_codigo_seq') , 'oValorLancamento'      , 'Valor Lançamento'              , 1 ,  120, '' ,  15 , 'f' , 't', 'e','',0),
                (nextval('db_layoutcampos_db52_codigo_seq') , currval('db_layoutlinha_db51_codigo_seq') , 'oNumeroDocumentoBanco' , 'Número Documento no banco'     , 1 ,  135, '' ,  10 , 'f' , 't', 'e','',0),
                (nextval('db_layoutcampos_db52_codigo_seq') , currval('db_layoutlinha_db51_codigo_seq') , 'oFiller3'              , 'Filler'                        , 1 ,  144, '' ,  11 , 'f' , 't', 'e','',0),
                (nextval('db_layoutcampos_db52_codigo_seq') , currval('db_layoutlinha_db51_codigo_seq') , 'oDtEfetivacao'         , 'Data da efetivação'            , 1 ,  155, '' ,   8 , 'f' , 't', 'e','',0),
                (nextval('db_layoutcampos_db52_codigo_seq') , currval('db_layoutlinha_db51_codigo_seq') , 'oValorEfetivacao'      , 'Valor da efetivação'           , 1 ,  163, '' ,  15 , 'f' , 't', 'e','',0),
                (nextval('db_layoutcampos_db52_codigo_seq') , currval('db_layoutlinha_db51_codigo_seq') , 'oOutrasInformacoes'    , 'Outras informações'            , 1 ,  178, '' ,  40 , 'f' , 't', 'e','',0),
                (nextval('db_layoutcampos_db52_codigo_seq') , currval('db_layoutlinha_db51_codigo_seq') , 'oFebraban'             , 'Uso FEBRABAN'                  , 1 ,  218, '' ,  12 , 'f' , 't', 'e','',0),
                (nextval('db_layoutcampos_db52_codigo_seq') , currval('db_layoutlinha_db51_codigo_seq') , 'oFavorecido'           , 'Aviso ao favorecido'           , 1 ,  230, '' ,   1 , 'f' , 't', 'e','',0),
                (nextval('db_layoutcampos_db52_codigo_seq') , currval('db_layoutlinha_db51_codigo_seq') , 'oOcorrencia'           , 'Ocorrências para retorno'      , 1 ,  231, '' ,  10 , 'f' , 't', 'e','',0),
                (nextval('db_layoutcampos_db52_codigo_seq') , currval('db_layoutlinha_db51_codigo_seq') , 'codigo_segmento'       , 'Cód. Segmento'                 , 1 ,   14, 'K',   1 , 't' , 't', 'e','',0),
                (nextval('db_layoutcampos_db52_codigo_seq') , currval('db_layoutlinha_db51_codigo_seq') , 'codigo_registro'       , 'Código do registro'            , 1 ,    8, '' ,   1 , 'f' , 't', 'd','',0);

        ";

        $this->execute($sSql);
    }

     public function down()
    {
        
        $sSql = "
                DELETE FROM db_layoutcampos WHERE db52_layoutlinha  in (select db51_codigo from db_layoutlinha where db51_layouttxt = 102 and db51_descr = 'DETALHE K');
                DELETE FROM db_layoutlinha where db51_layouttxt = 102 and db51_descr = 'DETALHE K';
        ";
        
        $this->execute($sSql);

    }

}
