<?php
namespace Admin\Controller;

class GoodsController extends BaseController 
{
   //库存量页面
   function goods_number(){
       
       //接收商品ID
       $id = I('get.id');
       $gnModel = D('goods_number');
        
       //处理表单
       if(IS_POST)
       {
           //先删除原库存
           $gnModel->where(array(
               'goods_id' =>array('eq', $id),
           ))->delete();


           $gaid = I('post.goods_attr_id');
           $gn = I('post.goods_number');          

           //先计算商品属性ID和库存量的比例
           $gaidCount = count($gaid);
           $gnCount = count($gn);
           $rate = $gaidCount/$gnCount;           
           

           //循环库存量
           $_i = 0; //取第几个商品属性ID           
          
           foreach($gn as $k => $v)
           {
               $_goodsAttrId = array(); //把下面取出来的ID放在这里
               //从商品属性ID数组中取出 $rate个
                for($i=0; $i<$rate; $i++)
                {
                    $_goodsAttrId[] = $gaid[$_i];
                    $_i++;
                }
                
                //先升序排列
                sort($_goodsAttrId, SORT_NUMERIC); //以数字的形式排序
               
                //把取出来的商品属性ID转化成字符串
                $_goodsAttrId = (string)implode(',', $_goodsAttrId);
               
                $gnModel->add(array(
                    'goods_id' => $id,
                    'goods_attr_id' => $_goodsAttrId,
                    'goods_number' => $v,
                ));
           }
           $this->success('设置成功! ', U('goods_number?id='.I('get.id')));
           exit;

       }

       //根据商品ID取出这件商品所有可选属性的值
       $gaModel = D('goods_attr');
       $gaData = $gaModel->alias('a')
       ->field('a.*,b.attr_name')
       ->join('LEFT JOIN __ATTRIBUTE__ b ON a.attr_id=b.id')
       ->where(array(
           'goods_id' => array('eq', $id),
           'b.attr_type' => array('eq', '可选'),
       ))->select();

       //处理这个二维数组:转化成三维: 把属性相同的放到一起
       foreach($gaData as $k => $v)
       {
            $_gaData[$v['attr_name']][]= $v;
       }     

       //先取出这件商品已经设置过的库存量
       $gnData = $gnModel->where(array(
           'goods_id' => $id,
       ))->select();
       //p($gnData);

       $this->assign(array(
            'gaData' => $_gaData, 
            'gnData' => $gnData,  
       ));

       //设置页面信息
       $this->assign(array(         
        '_page_title' => '库存量',
        '_page_btn_name' => '返回列表',
        '_page_btn_link' => U('lst'),
    ));

    //1.显示表单
    $this->display();
   
   }


    //处理删除属性
    public function ajaxDelAttr()
    {
        $goodsId = addslashes(I('get.goods_id'));
        $gaid = I('get.gaid');
        $gaModel = D('goods_attr');
        $gaModel->delete($gaid);
        //删除相关库存量数据
        $gnModel = D('goods_number');
        $gnModel->where(array(
            'goods_id' => array('EXP', "=$goodsId AND FIND_IN_SET($gaid, attr_list)"),
        ))->delete();

    }

    //处理获取属性的AJAX请求
    public function ajaxGetAttr()
    {
        $typeId = I('get.type_id');
        $attrModel = D('Attribute');
        $attrData = $attrModel->where(array(
            'type_id' => array('eq', $typeId),
        ))->select();
        echo json_encode($attrData);
    }

    //添加 显示和处理表单
    public function add()
    {     

        //判断用户是否提交了表单
        if(IS_POST)
        {            

            $model = D('Goods');
            //2. create方法: a. 接收数据并保存到模型中 b.根据模型中定义的规则验证表单
            /**
             * 第一个参数:要接收的数据默认是$_POST
             * 第二个参数:表单的类型.当前是添加还是修改的表单, 1:添加 2: 修改
             * $_POST: 表单中原始的数据, I('post.'):过滤之后的$_POST的数据,过滤XSS攻击
             */

             if($model->create(I('post.'),1))
             {
                 
                if($model->add())
                {
                    //显示成功信息并等待1秒后之后跳转
                    $this->success('操作成功!', U('lst'));
                    exit;
                }
             }
             //处理失败的请求
             //失败的原因
             $error = $model->getError();
             //由控制器显示错误信息,并在3秒跳回上一个页面
             $this->error($error);

        }

        //取出所有的会员级别
        $mlModel = D('member_level');
        $mlData = $mlModel->select();

        //取出所有的分类做下拉框
        $catModel = D('category');
        $catData = $catModel->getTree();

      
                
         //设置页面信息
         $this->assign(array(
            'catData' => $catData,           
            'mlData' => $mlData,           
            '_page_title' => '添加新商品',
            '_page_btn_name' => '商品列表',
            '_page_btn_link' => U('lst'),
        )
        );

        //1.显示表单
        $this->display();
    } 
    //修改 显示和处理表单
    public function edit()
    {
        $id = I('get.id'); //要修改的商品的ID
        $model = D('Goods');
        
        //判断用户是否提交了表单
        if(IS_POST)
        {
          
            
             if($model->create(I('post.'),2))
             {
                 
                if(FALSE !== $model->save()) //save()的返回值是, 如果失败返回false,如果成功返回受影响的条数[如果修改前和修改后相同就会返回0]
                {
                    //显示成功信息并等待1秒后之后跳转
                    $this->success('操作成功!', U('lst'));
                    exit;
                }
             }
             //处理失败的请求
             //失败的原因
             $error = $model->getError();
             //由控制器显示错误信息,并在3秒跳回上一个页面
             $this->error($error);

        }

        //根据ID取出要修改的商品的原信息
        $data = $model->find($id);
        $this->assign('data', $data);

        //取出所有的分类做下拉框
        $catModel = D('category');
        $catData = $catModel->getTree();  
        
        //取出扩展分类ID
        $gcModel = D('goods_cat');
        $gcData = $gcModel->field('cat_id')->where(array(
            'goods_id' => array('eq', $id),
        ))->select();

        //取出当前类型下所有的属性        
        $attrModel = D('Attribute');
        $attrData = $attrModel->alias('a')
        ->field('a.id attr_id,a.attr_name,a.attr_type,a.attr_option_values,b.attr_value,b.id')
        ->join('LEFT JOIN __GOODS_ATTR__ b ON (a.id=b.attr_id AND b.goods_id='.$id.')')       
        ->where(array(
            'a.type_id' => array('eq',$data['type_id']),
        ))->select();
        
        
         //设置页面信息
         $this->assign(array( 
            'catData' => $catData,
            'gcData' => $gcData,
            'attrData' => $attrData,
            '_page_title' => '修改商品',
            '_page_btn_name' => '商品列表',
            '_page_btn_link' => U('lst'),
        )
        );

        //1.显示表单
        $this->display();
    } 

    //展示 商品列表页
    public function lst()
    {
        $model = D('Goods');

        //返回数据和翻页
        $data = $model->search();
        $this->assign($data);

        //取出所有的分类做下拉框
        $catModel = D('category');
        $catData = $catModel->getTree();
        
        //设置页面信息
        $this->assign(array(
            'catData' => $catData,
            '_page_title' => '商品列表',
            '_page_btn_name' => '添加商品',
            '_page_btn_link' => U('add'),
        )
        );

        $this->display();
    }

    //删除 
    function delete()
    {
        $model = D('Goods');
        if(FALSE !== $model->delete(I('get.id')))
        {
            $this->success('删除成功!', U('lst'));
        }
        else
        {
            $this->error('删除失败! 原因: ' .$model->getError());
        }    
    }

}