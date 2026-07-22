---Liberando menus
update db_itensmenu set libcliente = true where id_item = 10157;
update db_itensmenu set libcliente = true where id_item = 10158;
update db_itensmenu set libcliente = true where id_item = 10159;
update db_itensmenu set libcliente = true where id_item = 10160;
update db_itensmenu set libcliente = true where id_item = 9788;
update db_itensmenu set libcliente = true where id_item = 9789;
update db_itensmenu set libcliente = true where id_item = 9790;
update db_itensmenu set libcliente = true where id_item = 9791;
update db_itensmenu set libcliente = true where id_item = 9792;

---Eliminando duplicidade de menus
delete from db_menu where id_item_filho = 10159 and id_item = 1818 and modulo = 952; --(Excluir menu 10159 do vinculo com o menu 1818 na sequencia 112 e no modulo 952)
delete from db_menu where id_item_filho = 9792 and id_item = 9790 and modulo = 952; --(Excluir menu  9792 do vinculo com o menu 9790 na sequencia 2 e no modulo 952)
