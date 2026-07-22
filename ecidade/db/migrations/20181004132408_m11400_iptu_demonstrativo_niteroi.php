<?php

use Classes\PostgresMigration;

class M11400IptuDemonstrativoNiteroi extends PostgresMigration
{
    public function up()
    {
        $sql = "
            CREATE OR REPLACE FUNCTION fc_iptu_demonstrativo_niteroi(INTEGER, INTEGER, INTEGER, BOOLEAN)
              RETURNS TEXT
            LANGUAGE plpgsql
            AS $$
            DECLARE
            
              imatricula ALIAS FOR $1;
              ianousu ALIAS FOR $2;
              iidql ALIAS FOR $3;
              braise ALIAS FOR $4;
            
              tdemonstrativo TEXT DEFAULT '
                        ';
              tsqlconstr     TEXT DEFAULT '';
              tsqlisencao    TEXT DEFAULT '';
              ntotal         NUMERIC(15, 2) DEFAULT 0;
            
              itotalpontos   INTEGER DEFAULT 0;
              nareaedificada NUMERIC;
              inumpreverifica INTEGER DEFAULT 0;
            
              rvalores        RECORD;
              rdadosiptu      RECORD;
              rproprietario   RECORD;
              rendereco       RECORD;
              rconstr         RECORD;
              risenc          RECORD;
              rcaract         RECORD;
              rlotecaract     RECORD;
            
              labatimento     BOOLEAN DEFAULT FALSE;
              nDeducao NUMERIC(15, 2) DEFAULT 0;
            
            BEGIN
            
              IF braise
              THEN
                RAISE NOTICE ' GERANDO DEMONSTRATIVO DE CALCULO ...';
              END IF;
            
              -- Verifica se existe Pagamento Parcial para o débito informado
              SELECT j20_numpre INTO inumpreverifica
              FROM iptunump
              WHERE j20_matric = imatricula
                AND j20_anousu = ianousu
              LIMIT 1;
            
              IF found
              THEN
                SELECT fc_verifica_abatimento(1, (SELECT j20_numpre
                                                  FROM iptunump
                                                  WHERE j20_matric = imatricula
                                                    AND j20_anousu = ianousu
                                                  LIMIT 1)) :: BOOLEAN INTO labatimento;
            
                IF labatimento
                THEN
                --        raise exception '<erro>Operação Cancelada, Débito com Pagamento Parcial!</erro>';
                END IF;
              END IF;
              ------------------------------- dados do proprietario -------------------------
              SELECT cgm.z01_cgccpf,
                     cgm.z01_nome,
                     cgm.z01_ident,
                     cgm.z01_ender,
                     cgm.z01_numero,
                     cgm.z01_bairro,
                     cgm.z01_cep,
                     cgm.z01_munic,
                     cgm.z01_uf,
                     cgm.z01_telef,
                     cgm.z01_cadast
                  INTO rproprietario
              FROM cgm
                     INNER JOIN iptubase ON iptubase.j01_numcgm = cgm.z01_numcgm
              WHERE j01_matric = imatricula;
            
              tdemonstrativo := tdemonstrativo || LPAD('[ PROPRIETÁRIO ]--', 90, '-') || '
                        ';
              tdemonstrativo := tdemonstrativo || '
                        ';
              tdemonstrativo := tdemonstrativo || RPAD(' MATRICULA ', 55, '.') || ': ' || imatricula || '
                        ';
              tdemonstrativo :=
              tdemonstrativo || RPAD(' NOME/RAZAO SOCIAL ', 55, '.') || ': ' || trim(coalesce(rproprietario.z01_nome, '')) || '
                        ';
              tdemonstrativo := tdemonstrativo || RPAD(' CGC/CPF ', 55, '.') || ': ' || trim(coalesce(rproprietario.z01_cgccpf, ''))
                                || '
                        ';
              tdemonstrativo :=
              tdemonstrativo || RPAD(' IDENTIDADE/INSC.EST ', 55, '.') || ': ' || trim(coalesce(rproprietario.z01_ident, '')) || '
                        ';
              tdemonstrativo := tdemonstrativo || RPAD(' ENDERECO ', 55, '.') || ': ' || trim(coalesce(rproprietario.z01_ender, ''))
                                || '
                        ';
              tdemonstrativo :=
              tdemonstrativo || RPAD(' NUMERO ', 55, '.') || ': ' || trim(coalesce(rproprietario.z01_numero, 0) :: VARCHAR) || '
                        ';
              tdemonstrativo := tdemonstrativo || RPAD(' BAIRRO ', 55, '.') || ': ' || trim(coalesce(rproprietario.z01_bairro, ''))
                                || '
                        ';
              tdemonstrativo := tdemonstrativo || RPAD(' CEP ', 55, '.') || ': ' || trim(coalesce(rproprietario.z01_cep, '')) || '
                        ';
              tdemonstrativo :=
              tdemonstrativo || RPAD(' MUNICIPIO ', 55, '.') || ': ' || trim(coalesce(rproprietario.z01_munic, '')) || '
                        ';
              tdemonstrativo := tdemonstrativo || RPAD(' UF ', 55, '.') || ': ' || trim(coalesce(rproprietario.z01_uf, '')) || '
                        ';
              tdemonstrativo := tdemonstrativo || RPAD(' TELEFONE ', 55, '.') || ': ' || trim(coalesce(rproprietario.z01_telef, ''))
                                || '
                        ';
              tdemonstrativo := tdemonstrativo || RPAD(' DATA DO CADASTRO ', 55, '.') || ': ' ||
                                trim(coalesce(cast(rproprietario.z01_cadast AS TEXT), '')) || '
                        ';
              tdemonstrativo := tdemonstrativo || '
                        ';
            
              ------------------------------ endereco do imovel ------------------------------
              SELECT DISTINCT iptuconstr.j39_numero,
                              iptuconstr.j39_compl,
                              d.j88_descricao || ' ' ||a.j14_nome AS j14_nome,
                              c.j88_descricao || ' ' ||b.j14_nome AS logrconstr,
                              bairro.j13_descr,
                              lote.j34_setor,
                              lote.j34_quadra,
                              lote.j34_lote,
                              lote.j34_area
                  INTO rendereco
              FROM iptubase
                     LEFT JOIN iptuconstr ON j01_matric = j39_matric
                     LEFT JOIN ruas b ON j39_codigo = b.j14_codigo
                     LEFT JOIN ruastipo c ON b.j14_tipo = c.j88_codigo
                     INNER JOIN lote ON j34_idbql = j01_idbql
                     INNER JOIN bairro ON j34_bairro = j13_codi
                     INNER JOIN testpri ON j01_idbql = j49_idbql
                     INNER JOIN ruas a ON j49_codigo = a.j14_codigo
                     INNER JOIN ruastipo d ON a.j14_tipo = d.j88_codigo
              WHERE (iptuconstr.j39_matric IS NOT NULL OR iptuconstr.j39_dtdemo IS NOT NULL)
                AND j01_matric = imatricula;
            
              tdemonstrativo := tdemonstrativo || LPAD('[ ENDERECO DO IMÓVEL ]--', 90, '-') || '
                        ';
              tdemonstrativo := tdemonstrativo || '
                        ';
              tdemonstrativo := tdemonstrativo || RPAD(' LOGRADOURO ', 55, '.') || ': ' || trim(coalesce(rendereco.logrconstr, ''))
                                || '
                        ';
              tdemonstrativo :=
              tdemonstrativo || RPAD(' NUMERO ', 55, '.') || ': ' || trim(coalesce(rendereco.j39_numero :: VARCHAR, '')) || '
                        ';
              tdemonstrativo := tdemonstrativo || RPAD(' COMPLEMENTO ', 55, '.') || ': ' || trim(coalesce(rendereco.j39_compl, ''))
                                || '
                        ';
              tdemonstrativo := tdemonstrativo || RPAD(' BAIRRO ', 55, '.') || ': ' || trim(coalesce(rendereco.j13_descr, '')) || '
                        ';
              tdemonstrativo := tdemonstrativo || '
                        ';
            
              --------------------------------- dados do lote ---------------------------------
            
              tdemonstrativo := tdemonstrativo || LPAD('[ DADOS DO LOTE ]--', 90, '-') || '
                        ';
              tdemonstrativo := tdemonstrativo || '
                        ';
              tdemonstrativo := tdemonstrativo || RPAD(' SETOR/QUADRA/LOTE ', 55, '.') || ': ' || trim(
                  coalesce(rendereco.j34_setor, '') || '/' || coalesce(rendereco.j34_quadra, '') || '/' ||
                  coalesce(rendereco.j34_lote, '')) || '
                        ';
              tdemonstrativo :=
              tdemonstrativo || RPAD(' AREA ', 55, '.') || ': ' || trim(coalesce(rendereco.j34_area :: VARCHAR, '')) || '
                        ';
              tdemonstrativo :=
              tdemonstrativo || RPAD(' TESTADA PRINCIPAL ', 55, '.') || ': ' || trim(coalesce(rendereco.j14_nome, '')) || '
                        ';
              tdemonstrativo := tdemonstrativo || ' CARACTERISTICAS DO LOTE :
                        ';
              FOR rlotecaract IN SELECT j31_codigo, j31_descr, j31_grupo
                                 FROM carlote
                                        INNER JOIN caracter ON j35_caract = j31_codigo
                                 WHERE j35_idbql = iidql
              LOOP
                tdemonstrativo :=
                tdemonstrativo || LPAD(' ' || coalesce(rlotecaract.j31_codigo :: VARCHAR, ''), 40, '.') || ' - ' ||
                coalesce(rlotecaract.j31_descr, '') || ' - GRUPO : ' || rlotecaract.j31_grupo || '
                        ';
              END LOOP;
              tdemonstrativo := tdemonstrativo || '
                        ';
            
              ------------------------------ dados das construcoes ------------------------------
              tdemonstrativo := tdemonstrativo || LPAD('[ DADOS DAS CONSTRUÇÕES ]--', 90, '-') || '
                        ';
            
              tsqlconstr := 'select distinct j39_idcons,j39_area,j39_ano,valor,j39_matric,coalesce(pontos,0) as pontos from iptuconstr
                                                    inner join tmpiptucale on matric = j39_matric and idcons = j39_idcons
                                          where j39_matric = ' || imatricula;
              FOR rconstr IN EXECUTE tsqlconstr
              LOOP
                tdemonstrativo := tdemonstrativo || '
                        ';
                tdemonstrativo :=
                tdemonstrativo || RPAD(' CONSTRUÇÃO ', 55, '.') || ': ' || coalesce(rconstr.j39_idcons :: VARCHAR, '') || '
                        ';
                tdemonstrativo := tdemonstrativo || RPAD(' PONTUAÇÃO ', 55, '.') || ': ' || coalesce(rconstr.pontos :: VARCHAR, '')
                                  || '
                        ';
                tdemonstrativo :=
                tdemonstrativo || RPAD(' AREA ', 55, '.') || ': ' || coalesce(round(rconstr.j39_area, 2) :: VARCHAR, '') || '
                        ';
                tdemonstrativo :=
                tdemonstrativo || RPAD(' ANO DA CONSTRUÇÃO ', 55, '.') || ': ' || coalesce(rconstr.j39_ano :: VARCHAR, '') || '
                        ';
                tdemonstrativo := tdemonstrativo || RPAD(' VLR VENAL CONSTRUÇÃO ', 55, '.') || ': ' ||
                                  coalesce(round(rconstr.valor, 2) :: VARCHAR, '') || '
                        ';
            
                tdemonstrativo := tdemonstrativo || ' CARACTERISTICAS DA CONSTRUÇÃO :
                        ';
                FOR rcaract IN SELECT *
                               FROM carconstr
                                      INNER JOIN caracter ON j48_caract = j31_codigo
                               WHERE j48_matric = rconstr.j39_matric
                                 AND j48_idcons = rconstr.j39_idcons
                LOOP
                  tdemonstrativo :=
                  tdemonstrativo || LPAD(' ' || rcaract.j31_codigo, 40, '.') || ' - ' || coalesce(rcaract.j31_descr, '') ||
                  ' - GRUPO : ' || rcaract.j31_grupo || '
                        ';
                END LOOP;
            
              END LOOP;
              tdemonstrativo := tdemonstrativo || '
                        ';
            
              ------------------------------ dados do financeiro ------------------------------
            
              SELECT * FROM tmpdadosiptu INTO rdadosiptu;
            
              SELECT sum(coalesce(pontos, 0)), sum(areaed)
                  INTO itotalpontos FROM tmpiptucale;
            
              SELECT sum(areaed)
                  INTO nareaedificada FROM tmpiptucale;
            
              tdemonstrativo := tdemonstrativo || LPAD('[ CALCULO ' || coalesce(ianousu :: VARCHAR, '') || ' ]--', 90, '-') || '
                        ';
              tdemonstrativo := tdemonstrativo || '
                        ';
              tdemonstrativo := tdemonstrativo || RPAD(' PONTUAÇÃO ', 55, '.') || ': ' || coalesce(itotalpontos :: VARCHAR, '') || '
                        ';
              tdemonstrativo := tdemonstrativo || RPAD(' AREA P/ CALCULO ', 55, '.') || ': ' ||
                                coalesce(round((rdadosiptu.areat * rdadosiptu.fracao) / 100, 2) :: VARCHAR, '') || '
                        ';
              tdemonstrativo :=
              tdemonstrativo || RPAD(' FRACAO ', 55, '.') || ': ' || coalesce(round(rdadosiptu.fracao, 2) :: VARCHAR, '') || '%
                        ';
              tdemonstrativo :=
              tdemonstrativo || RPAD(' ALIQUOTA ', 55, '.') || ': ' || coalesce(round(rdadosiptu.aliq, 2) :: VARCHAR, '') || '%
                        ';
              tdemonstrativo :=
              tdemonstrativo || RPAD(' VALOR VENAL TERRENO ', 55, '.') || ': ' || coalesce(round(rdadosiptu.vvt, 2) :: VARCHAR, '')
              || '
                        ';
              tdemonstrativo :=
              tdemonstrativo || RPAD(' VALOR VENAL EDIFIC ', 55, '.') || ': ' || coalesce(round(rdadosiptu.vvc, 2) :: VARCHAR, '')
              || '
                        ';
              tdemonstrativo := tdemonstrativo || RPAD(' VALOR VENAL TOTAL ', 55, '.') || ': ' ||
                                coalesce(round(rdadosiptu.vvc, 2), 0) + coalesce(round(rdadosiptu.vvt, 2), 0) || '
                        ';
              tdemonstrativo :=
              tdemonstrativo || RPAD(' AREA DO TERRENO ', 55, '.') || ': ' || coalesce(round(rdadosiptu.areat, 2) :: VARCHAR, '') || '
                        ';
              tdemonstrativo :=
              tdemonstrativo || RPAD(' AREA EDIFICADA ', 55, '.') || ': ' || coalesce(round(nareaedificada, 2) :: VARCHAR, '') || '
                        ';
              tdemonstrativo :=
              tdemonstrativo || RPAD(' VALOR M2 DO TERRENO ', 55, '.') || ': ' || coalesce(round(rdadosiptu.vm2t, 2) :: VARCHAR, '')
              || '
                        ';
            
              FOR rvalores IN SELECT * FROM tmprecval
                                              INNER JOIN tabrec ON receita = k02_codigo LOOP
                tdemonstrativo :=
                tdemonstrativo || RPAD(' VALOR ' || coalesce(rvalores.k02_descr :: VARCHAR, '0'), 55, '.') || ': ' ||
                coalesce(round(rvalores.valor, 2) :: VARCHAR, '0') || '
                        ';
                ntotal := ntotal + coalesce(rvalores.valor, 0);
              END LOOP;
            
              /* VERIFICA E APLICA O DESCONTO DA LIC */
              IF EXISTS(
                  SELECT 1 FROM information_schema.tables WHERE table_schema = 'plugins'
                                                            AND table_name = 'iptulic'
              )
              THEN
                SELECT deducao INTO ndeducao FROM plugins.iptulic WHERE matricula = imatricula
                                                                    AND exercicio = ianousu;
            
                IF ndeducao <> 0
                THEN
                  ntotal := ntotal - ndeducao;
                  tdemonstrativo := tdemonstrativo || RPAD(' DESCONTO REFERENTE À L.I.C.', 55, '.') || ': ' || -ndeducao || '\n';
                END IF;
              END IF;
            
              tsqlisencao := 'SELECT k02_descr, j17_descr,  CASE
                                      WHEN iptucalhconf.j89_codhis IS NOT NULL THEN
                                          (SELECT sum(x.j21_valor)
                                           FROM iptucalv x
                                           WHERE x.j21_anousu = iptucalv.j21_anousu
                                             AND x.j21_matric = iptucalv.j21_matric
                                             AND x.j21_receit = iptucalv.j21_receit
                                             AND x.j21_codhis = iptucalhconf.j89_codhis)
                                        ELSE 0
                                    END AS j21_valorisen
                              FROM iptucalv
                              INNER JOIN iptucalh ON iptucalh.j17_codhis = j21_codhis
                              LEFT JOIN iptucalhconf ON iptucalhconf.j89_codhispai = j21_codhis
                              INNER JOIN tabrec ON tabrec.k02_codigo = j21_receit
                              WHERE j21_matric = ' || imatricula || ' AND j21_anousu = ' || ianousu || '
                                AND j17_codhis NOT IN  (SELECT j89_codhis FROM iptucalhconf) ORDER BY iptucalh.j17_codhis';
              FOR risenc IN EXECUTE tsqlisencao
              LOOP
                IF risenc.j21_valorisen IS NOT NULL AND risenc.j21_valorisen <> 0
                THEN
                  tdemonstrativo :=
                  tdemonstrativo || RPAD(' VALOR  ISENCAO ' || coalesce(risenc.j17_descr :: VARCHAR, ''), 55, '.') || ': ' ||
                  coalesce(round(risenc.j21_valorisen, 2) :: VARCHAR, '') || '
                        ';
                  ntotal := ntotal + risenc.j21_valorisen;
                END IF;
              END LOOP;
            
              tdemonstrativo := tdemonstrativo || RPAD(' TOTAL A PAGAR ', 55, '.') || ': ' || coalesce(ntotal, 0) || '
                        ';
            
              RETURN tdemonstrativo;
            
            END;
            $$;
        ";

        $this->execute($sql);
    }

    public function down()
    {
        $sql = "
            DROP FUNCTION public.fc_iptu_demonstrativo_niteroi(INTEGER, INTEGER, INTEGER, BOOLEAN)
        ";

        $this->execute($sql);
    }
}
