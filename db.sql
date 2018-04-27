create database ds39;

use ds39;

set names utf8;


drop table if exists p39_goods;

create table p39_goods(
    id mediumint unsigned not null auto_increment comment 'Id',
    goods_name varchar(150) not null comment '商品名称',
    market_price decimal(10,2) not null comment '市场价格',
    shop_price decimal(10,2) not null comment '本店价格',
    goods_desc longtext comment '商品描述',
    is_on_sale enum('是','否') not null default '是' comment '是否上架',
    is_delete enum('是','否') not null default '否' comment '是否放到回收站',
    addtime datetime not null comment '添加时间',
    logo varchar(150) not null default '' comment '原图',
    sm_logo varchar(150) not null default '' comment '小图',
    mid_logo varchar(150) not null default '' comment '中图',
    big_logo varchar(150) not null default '' comment '大图',
    mbig_logo varchar(150) not null default '' comment '更大图',
    brand_id mediumint unsigned not null default '0' comment '品牌id',
    cat_id mediumint unsigned not null default '0' comment '主分类id',
    type_id mediumint unsigned not null default '0' comment '类型id',
    promote_price decimal(10,2) not null default '0.00' comment '促销价格',
    promote_start_date datetime not null comment '促销开始时间',
    promote_end_date datetime not null comment '促销结束时间',
    is_new enum('是','否') not null default '否' comment '是否新品', 
    is_hot enum('是','否') not null default '否' comment '是否热卖', 
    is_best enum('是','否') not null default '否' comment '是否精品',
    is_floor enum('是','否') not null default '否' comment '是否推荐到楼层',
    sort_num tinyint unsigned not null default '100' comment '排序的数字',
    primary key(id),
    key promote_price(promote_price),
    key promote_start_date(promote_start_date),
    key promote_end_date(promote_end_date), 
    key is_new(is_new),   
    key is_hot(is_hot),   
    key is_best(is_best),   
    key shop_price(shop_price),
    key addtime(addtime),
    key brand_id(brand_id),
    key cat_id(cat_id),
    key sort_num(sort_num),
    key is_on_sale(is_on_sale)

)engine=InnoDB default charset=utf8 comment '商品';



drop table if exists p39_brand;
create table p39_brand
(
    id mediumint unsigned not null auto_increment comment 'Id',
    brand_name varchar(30) not null default '' comment '品牌名称',
    site_url varchar(150) not null default '' comment '官方网址',
    logo varchar(150) not null default '' comment '品牌Logo图片',
    primary key(id)
)engine=InnoDB default charset=utf8 comment '品牌';


drop table if exists p39_member_level;
create table p39_member_level
(
    id mediumint unsigned not null auto_increment comment 'Id',
    level_name varchar(30) not null default '' comment '级别名称',
    jifen_bottom mediumint unsigned not null comment '积分下限',
    jifen_top mediumint unsigned not null comment '积分上限',    
    primary key(id)
)engine=InnoDB default charset=utf8 comment '会员级别';


drop table if exists p39_member_price;
create table p39_member_price
(
    price decimal(10,2) not null comment '会员价格',
    level_id mediumint unsigned not null comment '级别id',
    goods_id mediumint unsigned not null comment '商品id',
    key level_id(level_id),    
    key goods_id(goods_id)  
)engine=InnoDB default charset=utf8 comment '会员价格';


drop table if exists p39_category;
create table p39_category
(
    id mediumint unsigned not null auto_increment comment 'Id',
    cat_name varchar(30) not null comment '分类名称',
    parent_id mediumint unsigned not null default '0' comment '上级分类的Id, 0:顶级分类',
    is_floor enum('是','否') not null default '否' comment '是否推荐到楼层',
    primary key (id)
)engine=InnoDB default charset=utf8 comment '分类';

//p39_category测试数据

INSERT INTO `p39_category` (`id`, `cat_name`, `parent_id`) VALUES
(1, '家用电器', 0),
(2, '手机、数码、京东通信', 0),
(3, '电脑、办公', 0),
(4, '家居、家具、家装、厨具', 0),
(5, '男装、女装、内衣、珠宝', 0),
(6, '个护化妆', 0),
(21, 'iPhone', 2),
(8, '户外运动', 0),
(9, '汽车、汽车用品', 0),
(10, '母婴、玩具乐器', 0),
(11, '食品、酒类、生鲜、特产', 0),
(12, '营养保健', 0),
(13, '图书、音像、电子书', 0),
(14, '彩票、旅行、充值、票务', 0),
(15, '理财、众筹、白条、保险', 0),
(16, '大家电', 1),
(17, '生活电器', 1),
(18, '厨房电器', 1),
(19, '个护健康', 1),
(20, '五金家装', 1),
(22, '冰箱', 16);

//商品扩展分类表
drop table if exists p39_goods_cat;
create table p39_goods_cat
(  
    cat_id mediumint unsigned not null comment '分类id',
    goods_id mediumint unsigned not null comment '商品id',
    key goods_id(goods_id),  
    key cat_id(cat_id)    
)engine=InnoDB default charset=utf8 comment '商品扩展分类';

/**************************** 属性相关表 **************************/
drop table if exists p39_type;
create table p39_type
(  
    id mediumint unsigned not null auto_increment comment '类型id',
    type_name varchar(30) not null comment '类型',
    primary key(id)    
)engine=InnoDB default charset=utf8 comment '类型';

drop table if exists p39_attribute;
create table p39_attribute
(  
    id mediumint unsigned not null auto_increment comment '属性id',
    attr_name varchar(30) not null comment '属性名称',
    attr_type enum('唯一','可选') not null comment '属性类型',
    attr_option_values varchar(300) not null default '' comment '属性可选值',
    type_id mediumint unsigned not null comment '所属类型id',
    primary key(id), 
    key type_id(type_id)   
)engine=InnoDB default charset=utf8 comment '属性表';

drop table if exists p39_goods_attr;
create table p39_goods_attr
(  
    id mediumint unsigned not null auto_increment comment 'id',
    attr_value varchar(30) not null comment '属性值',
    attr_id mediumint unsigned not null comment '属性id',
    goods_id mediumint unsigned not null comment '商品id',    
    primary key(id), 
    key goods_id(goods_id),
    key attr_id(attr_id)   
)engine=InnoDB default charset=utf8 comment '商品属性表';

drop table if exists p39_goods_number;
create table p39_goods_number
(  
    goods_id mediumint unsigned not null auto_increment comment 'id',
    goods_number mediumint unsigned not null default '0' comment '库存量',     
    goods_attr_id varchar(150) not null comment '商品属性表的ID, 如果有多个,就用程序拼成字符串存到这个字段中',   
    key goods_id(goods_id)   
)engine=InnoDB default charset=utf8 comment '库存量表';

/******************** RBAC权限控制表(5张表) ***********************/
drop table if exists p39_privilege;
create table p39_privilege
(  
    id smallint unsigned not null auto_increment comment 'Id',
    pri_name varchar(30) not null comment '权限名称', 
    module_name varchar(30) not null comment '模块名称',    
    controller_name varchar(30) not null comment '控制器名称',    
    action_name varchar(30) not null comment '方法名称',  
    parent_id smallint unsigned not null default '0' comment '上级权限Id, 0: 代表顶级权限',
    primary key (id)
)engine=InnoDB default charset=utf8 comment '权限表';

//权限表所需数据
INSERT INTO `p39_privilege` (`id`, `pri_name`, `module_name`, `controller_name`, `action_name`, `parent_id`) VALUES
(1, '商品模块', '', '', '', 0),
(2, '商品列表', 'Admin', 'Goods', 'lst', 1),
(3, '添加商品', 'Admin', 'Goods', 'add', 2),
(4, '修改商品', 'Admin', 'Goods', 'edit', 2),
(5, '删除商品', 'Admin', 'Goods', 'delete', 2),
(6, '分类列表', 'Admin', 'Category', 'lst', 1),
(7, '添加分类', 'Admin', 'Category', 'add', 6),
(8, '修改分类', 'Admin', 'Category', 'edit', 6),
(9, '删除分类', 'Admin', 'Category', 'delete', 6),
(10, 'RBAC', '', '', '', 0),
(11, '权限列表', 'Admin', 'Privilege', 'lst', 10),
(12, '添加权限', 'Privilege', 'Admin', 'add', 11),
(13, '修改权限', 'Admin', 'Privilege', 'edit', 11),
(14, '删除权限', 'Admin', 'Privilege', 'delete', 11),
(15, '角色列表', 'Admin', 'Role', 'lst', 10),
(16, '添加角色', 'Admin', 'Role', 'add', 15),
(17, '修改角色', 'Admin', 'Role', 'edit', 15),
(18, '删除角色', 'Admin', 'Role', 'delete', 15),
(19, '管理员列表', 'Admin', 'Admin', 'lst', 10),
(20, '添加管理员', 'Admin', 'Admin', 'add', 19),
(21, '修改管理员', 'Admin', 'Admin', 'edit', 19),
(22, '删除管理员', 'Admin', 'Admin', 'delete', 19),
(23, '类型列表', 'Admin', 'Type', 'lst', 1),
(24, '添加类型', 'Admin', 'Type', 'add', 23),
(25, '修改类型', 'Admin', 'Type', 'edit', 23),
(26, '删除类型', 'Admin', 'Type', 'delete', 23),
(27, '属性列表', 'Admin', 'Attribute', 'lst', 23),
(28, '添加属性', 'Admin', 'Attribute', 'add', 27),
(29, '修改属性', 'Admin', 'Attribute', 'edit', 27),
(30, '删除属性', 'Admin', 'Attribute', 'delete', 27),
(31, 'ajax删除商品属性', 'Admin', 'Goods', 'ajaxDelGoodsAttr', 4),
(32, 'ajax删除商品相册图片', 'Admin', 'Goods', 'ajaxDelImage', 4),
(33, '会员管理', '', '', '', 0),
(34, '会员级别列表', 'Admin', 'MemberLevel', 'lst', 33),
(35, '添加会员级别', 'Admin', 'MemberLevel', 'add', 34),
(36, '修改会员级别', 'Admin', 'MemberLevel', 'edit', 34),
(37, '删除会员级别', 'Admin', 'MemberLevel', 'delete', 34),
(38, '品牌列表', 'Admin', 'Brand', 'lst', 1);


drop table if exists p39_role_pri;
create table p39_role_pri
(  
   pri_id smallint unsigned not null comment '权限ID',
   role_id smallint unsigned not null comment '角色id',
   key pri_id(pri_id),
   key role_id(role_id)
)engine=InnoDB default charset=utf8 comment '角色权限中间表';

drop table if exists p39_role;
create table p39_role
(  
    id smallint unsigned not null auto_increment comment 'Id',
    role_name varchar(30) not null comment '角色名称',     
    primary key (id)
)engine=InnoDB default charset=utf8 comment '角色表';

drop table if exists p39_admin_role;
create table p39_admin_role
(  
   admin_id tinyint unsigned not null comment '管理员的id',
   role_id smallint unsigned not null comment '角色的id',
   key admin_id(admin_id),
   key role_id(role_id)
)engine=InnoDB default charset=utf8 comment '管理员角色中间表';

drop table if exists p39_admin;
create table p39_admin
(  
    id tinyint unsigned not null auto_increment comment 'Id',
    username varchar(30) not null comment '用户名',
    password char(32) not null comment '密码', 
    is_use tinyint unsigned not null default '1' comment '是否启用 1:启用 0:禁用',    
    primary key (id)
)engine=InnoDB default charset=utf8 comment '管理员表';

INSERT INTO p39_admin VALUES(1,'root','677764a9975b7aed734adc9654b6582d',1);



