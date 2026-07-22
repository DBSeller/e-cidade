
--#### Relatório 160 - Anexo XI - 2015

--## Linhas 6, 7 e 8

-- Despesas empenhadas
update orcparamseqorcparamseqcoluna
  set o116_formula = '#empenhado_acumulado - #anulado_acumulado'
  where o116_codparamrel = 160 and o116_codseq in(6, 7, 8) and o116_orcparamseqcoluna = 174;

-- Despesas Liquidadas
update orcparamseqorcparamseqcoluna
  set o116_formula = '#liquidado_acumulado - #anulado_acumulado'
  where o116_codparamrel = 160 and o116_codseq in(6, 7, 8) and o116_orcparamseqcoluna = 175;

-- Despesas Pagas
update orcparamseqorcparamseqcoluna
  set o116_formula = '#pago_acumulado - #anulado_acumulado'
  where o116_codparamrel = 160 and o116_codseq in(6, 7, 8) and o116_orcparamseqcoluna = 176;

-- Inscrito em Restos a Pagar Não Processados
update orcparamseqorcparamseqcoluna
  set o116_formula = '(#empenhado_acumulado - #anulado_acumulado) - (#liquidado_acumulado - #anulado_acumulado)'
  where o116_codparamrel = 160 and o116_codseq in(6, 7, 8) and o116_orcparamseqcoluna = 35;

-- RP a pagar
update orcparamseqorcparamseqcoluna
  set o116_formula = '(#liquidado_acumulado - #anulado_acumulado) - (#pago_acumulado - #anulado_acumulado)'
  where o116_codparamrel = 160 and o116_codseq in(6, 7, 8) and o116_orcparamseqcoluna = 160;

-- Saldo
update orcparamseqorcparamseqcoluna
  set o116_formula = '((#dot_ini + #suplementado_acumulado) - #reduzido_acumulado) - (#pago_acumulado - #anulado_acumulado)'
  where o116_codparamrel = 160 and o116_codseq in(6, 7, 8) and o116_orcparamseqcoluna = 184;

--## Linha 2

-- Previsão Atualizada
update orcparamseqorcparamseqcoluna
  set o116_formula = '#saldo_inicial_prevadic'
  where o116_codparamrel = 160 and o116_codseq in(2) and o116_orcparamseqcoluna = 26;

-- Saldo
update orcparamseqorcparamseqcoluna
  set o116_formula = '(#saldo_inicial_prevadic - #saldo_arrecadado)'
  where o116_codparamrel = 160 and o116_codseq in(2) and o116_orcparamseqcoluna = 184;

--#### Relatório 159 - Anexo 9 RREO Edição 6

--## Linhas 2, 3 e 4

-- Valor Liquidado
update orcparamseqorcparamseqcoluna
  set o116_formula = '#liquidado_acumulado - #anulado_acumulado'
  where o116_codparamrel = 159 and o116_codseq in(2, 3, 4) and o116_orcparamseqcoluna = 148;

-- Inscrito em Restos a Pagar Não Processados
update orcparamseqorcparamseqcoluna
  set o116_formula = '(#empenhado_acumulado - #anulado_acumulado) - (#liquidado_acumulado - #anulado_acumulado)'
  where o116_codparamrel = 159 and o116_codseq in(2, 3, 4) and o116_orcparamseqcoluna = 188;
