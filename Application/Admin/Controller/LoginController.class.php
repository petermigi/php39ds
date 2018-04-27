<?php
namespace Admin\Controller;
use Think\Controller;

class LoginController extends Controller 
{
    public function login()
    {
        if(IS_POST)
        {
            $model = D('Admin');
            //使用validate方法来指定使用模型中的那个数组作为验证规则,默认是使用$_validate
            //我这里把登录的规则和添加修改管理员的规则分成了两个,所以这里要指定使用哪个
            //7我们自己规定,代表登录说明这个表单是登录的表单
            if($model->validate($model->_login_validate)->create('', 7))
            {
                if(TRUE === $model->login())
                    {
                        $this->success('登录成功!', U('Admin/Index/index'));
                        exit;                        
                    }                  
            }
            
           $this->error($model->getError());
        }
        
        $this->display();
    }

    public function logout()
    {
        $model = D('Admin');
        $model->logout();
        redirect(U('Admin/Login/login'));

    }

    //生成验证码的图片
    public function chkcode()
    {
        $Verify = new \Think\Verify(array(
            'length' => 2,
            'useNoise' => false,
        ));
        $Verify->entry();
    }

 
}