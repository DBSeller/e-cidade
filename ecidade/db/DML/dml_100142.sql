update conhistdoc set c53_descr = 'CONCESSÃO DE TRANSFERÊNCIA FINANCEIRA'                 where c53_coddoc = 120;
update conhistdoc set c53_descr = 'ESTORNO DE CONCESSÃO DE TRANSFERÊNCIA FINANCEIRA'      where c53_coddoc = 121;
update conhistdoc set c53_descr = 'RECEBIMENTOS DE OUTRAS MOVIMENTAÇÕES EXTRAS'           where c53_coddoc = 150;
update conhistdoc set c53_descr = 'RECEBIMENTOS DE OUTRAS MOVIMENTAÇÕES EXTRAS - ESTORNO' where c53_coddoc = 152;
update conhistdoc set c53_descr = 'PAGAMENTOS DE OUTRAS MOVIMENTAÇÕES EXTRAS'             where c53_coddoc = 151;
update conhistdoc set c53_descr = 'PAGAMENTOS DE OUTRAS MOVIMENTAÇÕES EXTRAS - ESTORNO'   where c53_coddoc = 153;
update db_itensmenu set descricao = 'Outras Movimentações Extras' where id_item = 9385;
update db_itensmenu set descricao = 'Pagamento' where id_item = 9389;