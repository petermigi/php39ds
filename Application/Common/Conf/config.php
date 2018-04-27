<?php
return array(  

    //调试跟踪页面
    //'SHOW_PAGE_TRACE' => TRUE,

	//PDO专用数据库配置
	'DB_TYPE'   =>  'mysql',     // 数据库类型
    'DB_DSN'    =>  'mysql:host=localhost;dbname=ds39;charset=utf8',
    'DB_USER'   =>  'root',      // 用户名
    'DB_PWD'    =>  '123',          // 密码
    'DB_PORT'   =>  '3306',        // 端口
    'DB_PREFIX' =>  'p39_',    // 数据库表前缀

    //过滤表单数据以防脚本注入攻击,和提交空格字符
    'DEFAULT_FILTER' => 'trim,htmlspecialchars',

    /********************** 图片相关的配置 **************************/
    'IMAGE_CONFIG' => array(
        'maxSize' => 1024 * 1024,
        'exts' => array('jpg', 'gif', 'png', 'jpeg'),
        'rootPath' =>'./Public/Uploads/', //上传图片的保存路径 -> PHP要使用的路径,硬盘上的路径
        'viewPath' =>'/Public/Uploads/', //显示图片的保存路径  -> 浏览器用的路径, 相对网站根目录(http请求URL)
    ),

    /* ND5密钥 用来复杂化md5加密避免被人轻易破解密码 */
    'MD5_KEY' => 'md5abc',


);