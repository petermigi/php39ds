
ALTER TABLE p39_privilege MODIFY module_name varchar(30) not null comment '模块名称';
ALTER TABLE p39_privilege MODIFY controller_name varchar(30) not null comment '控制器名称';
ALTER TABLE p39_privilege MODIFY action_name varchar(30) not null comment '方法名称';