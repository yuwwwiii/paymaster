update `app_settings` set `set_id`='12',`comp_id`='1',`set_name`='Location as Company',`set_decimal_places`='0',`set_order`='0',`set_stat_type`='0' where `set_id`='11';
insert into `app_settings`(`set_id`,`comp_id`,`set_name`,`set_decimal_places`,`set_order`,`set_stat_type`) values ( '11','1','Annualize Tax on Last Pay Period of the Year','0','0','0');
alter table `app_settings` auto_increment=13 comment='' row_format=DYNAMIC;
