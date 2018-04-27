<?php
namespace Admin\Controller;
use Think\Controller;
class BaseController extends Controller {

    public function __construct()
    {
        
        //先调用父类的构造函数
        parent::__construct();

        //获取当前管理员的ID        
        $adminId = session('id');

        //验证登录
        if(!session('id'))
        {
            $this->error('必须先登录! ', U('Admin/Login/login'));            
        }     
        
        //验证当前管理员是否有权限访问这个页面
        //1.先获取当前管理员要访问的页面
        $url = MODULE_NAME .'/'. CONTROLLER_NAME .'/'. ACTION_NAME; 
        //查询数据库判断当前管理员有没有访问这个页面的权限
       $where ='module_name="'.MODULE_NAME.'"AND controller_name="'.CONTROLLER_NAME.'" AND action_name="'.ACTION_NAME.'"';     

       //任何人只要登录了就可以进入后台
       if(CONTROLLER_NAME == 'Index')
        {
            return TRUE;
        }        

       if($adminId == 1)
       {
           // $sql = 'SELECT COUNT(*) has FROM p39_privilege WHERE '.$where;
           $sql = 'SELECT COUNT(*) has FROM p39_privilege';
       }
       else 
       {
            $sql = 'SELECT COUNT(a.pri_id) has
                    FROM p39_role_pri a
                    LEFT JOIN p39_privilege b ON a.pri_id=b.id
                    LEFT JOIN p39_admin_role c ON a.role_id=c.role_id
                    WHERE c.admin_id='.$adminId.' AND '.$where;
       }
         
        $db = M();
        $pri = $db->query($sql);
        //echo $sql;
        //echo '<br/>';
        //echo '<br/>';
        //echo '<br/>';
        //echo mysql_error();
        //echo "<font size=16>$url</font>";
       // p($pri);


         if($pri[0]['has'] < 1)
        {
            $this->error('无权访问! ');
        } 
        
    }
       
}