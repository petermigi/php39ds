<?php
namespace Admin\Controller;

class AdminController extends BaseController 
{
    public function add()
    {
    	if(IS_POST)
    	{
    		$model = D('Admin');
    		if($model->create(I('post.'), 1))
    		{
    			if($id = $model->add())
    			{
    				$this->success('添加成功！', U('lst?p='.I('get.p')));
    				exit;
    			}
    		}
    		$this->error($model->getError());
    	}

		//取出所有的角色
		$roleModel = M('Role');
		$roleData = $roleModel->select();
		$this->assign('roleData', $roleData);

		// 设置页面中的信息
		$this->assign(array(
			'_page_title' => '添加管理员',
			'_page_btn_name' => '管理员列表',
			'_page_btn_link' => U('lst'),
		));
		$this->display();
    }
    public function edit()
    {
		//要修改的管理员ID
		$id = I('get.id');
		//先判断是否有权修改
		$adminId = session('id'); //取出当前管理员的ID
		//如果是普通管理员要修改其他管理员的信息提示无权
		if($adminId > 1 && $adminId != $id)
		{
			$this->error('无权修改! ');
		}

    	if(IS_POST)
    	{
    		$model = D('Admin');
    		if($model->create(I('post.'), 2))
    		{
    			if($model->save() !== FALSE)
    			{
    				$this->success('修改成功！', U('lst', array('p' => I('get.p', 1))));
    				exit;
    			}
    		}
    		$this->error($model->getError());
    	}
    	$model = M('Admin');
    	$data = $model->find($id);
		$this->assign('data', $data);
		
		//取出所有的角色
		$roleModel = M('Role');
		$roleData = $roleModel->select();
		$this->assign('roleData', $roleData);

		//取出当前管理员所在角色的ID
		$arModel = M('AdminRole');
		$rid=$arModel->field('GROUP_CONCAT(role_id) role_id')->where(array('admin_id' => array('eq', $id)))->find();
		$this->assign('rid', $rid['role_id']);


		// 设置页面中的信息
		$this->assign(array(
			'_page_title' => '修改管理员',
			'_page_btn_name' => '管理员列表',
			'_page_btn_link' => U('lst'),
		));
		$this->display();
    }
    public function delete()
    {
    	$model = D('Admin');
    	if($model->delete(I('get.id', 0)) !== FALSE)
    	{
    		$this->success('删除成功！', U('lst', array('p' => I('get.p', 1))));
    		exit;
    	}
    	else 
    	{
    		$this->error($model->getError());
    	}
    }
    public function lst()
    {
    	$model = D('Admin');
    	$data = $model->search();
    	$this->assign(array(
    		'data' => $data['data'],
    		'page' => $data['page'],
    	));

		// 设置页面中的信息
		$this->assign(array(
			'_page_title' => '管理员列表',
			'_page_btn_name' => '添加管理员',
			'_page_btn_link' => U('add'),
		));
    	$this->display();
	}
	
	public function ajaxUpdateIsuse()
	{
		$adminId = I('get.id');
		$model = M('Admin');
		$info = $model->find($adminId);
		if($info['is_use'] == 1)
		{
			$model->where(array('id'=>array('eq', $adminId)))->setField('is_use', 0);
			echo 0;
		}
		else 
		{
			$model->where(array('id'=>array('eq', $adminId)))->setField('is_use',1);
			echo 1;
		}
	}
}