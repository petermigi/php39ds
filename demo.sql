ALTER TABLE p39_goods ADD promote_price decimal(10,2) not null default '0.00' comment '促销价格';
ALTER TABLE p39_goods ADD promote_start_date datetime not null comment '促销开始时间';
ALTER TABLE p39_goods ADD promote_end_date datetime not null comment '促销结束时间';
ALTER TABLE p39_goods ADD is_new enum('是','否') not null default '否' comment '是否新品'; 
ALTER TABLE p39_goods ADD is_hot enum('是','否') not null default '否' comment '是否热卖'; 
ALTER TABLE p39_goods ADD is_best enum('是','否') not null default '否' comment '是否精品'; 