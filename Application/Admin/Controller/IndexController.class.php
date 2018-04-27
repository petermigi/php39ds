<?php
namespace Admin\Controller;

class IndexController extends BaseController

{

    public function index(){
        $this->display();
    }
    public function top(){
        $this->display();
    }

    //后台首页左侧菜单
    public function menu(){

        $adminId = session(id);

        /************** 取出当前管理员所拥有的前两级的权限 *****************/

        //取出当前管理员拥有的所有权限

        if($adminId == 1)
       {
           // $sql = 'SELECT COUNT(*) has FROM p39_privilege WHERE '.$where;
           $sql = 'SELECT * FROM p39_privilege';
       }
       else 
       {
            $sql = 'SELECT b.*
                    FROM p39_role_pri a
                    LEFT JOIN p39_privilege b ON a.pri_id=b.id
                    LEFT JOIN p39_admin_role c ON a.role_id=c.role_id
                    WHERE c.admin_id='.$adminId;
       }

       $db = M();
       $pri = $db->query($sql);

       $btn = array();  //放前两级的权限
       //从所有的权限中取出前两级的权限
       foreach ($pri as $k => $v)
       {
           if($v['parent_id'] == 0)
           {
               //再循环把这个顶级权限的子权限
               foreach ($pri as $k1 => $v1)
               {
                   if($v1['parent_id'] == $v['id'])
                   {
                        $v['children'][] = $v1; 
                   }

               }

               $btn[] = $v;
           }

       }   
       
       $this->assign('btn', $btn);
       $this->display();
       
    }
    
    public function main(){
        $this->display();
    }
}