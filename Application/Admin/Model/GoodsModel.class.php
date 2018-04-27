<?php
namespace Admin\Model;
use Think\Model;
class GoodsModel extends Model
{
    //添加时调用create方法允许接收的表单字段
    protected $insertFields = 'goods_name,market_price,shop_price,is_on_sale,goods_desc,brand_id,cat_id,type_id,promote_price,promote_start_date,promote_end_date,is_new,is_best,is_hot,sort_num,is_floor';
    //修改时调用create方法允许接收的字段
    protected $updatetFields = 'id,goods_name,market_price,shop_price,is_on_sale,goods_desc,brand_id,cat_id,type_id,promote_price,promote_start_date,promote_end_date,is_new,is_best,is_hot,sort_num,is_floor';



    //定义验证规则
    protected $_validate = array(
        array('cat_id', 'require', '必须选择主分类!', 1),
        array('goods_name', 'require', '商品名称不能为空!', 1),
        array('market_price', 'currency', '市场价格必须是货币类型!', 1),
        array('shop_price', 'currency', '本地价格必须是货币类型!', 1),
    );   

    //这个方法在添加之前会自动被调用 >>>钩子方法
    //第一个参数: 表单中即将要插入到数据库中的数据 ->数组
    //&按引用传递:函数内部要修改函数外部传进来的变量必须按引用传递,除非传递是对象,对象默认用引用传递
    //插入之前 钩子函数
    protected function _before_insert(&$data, $option)
    {
        /*************** 处理LOGO ***********************/       
       //判断有没有选择图片
       if ($_FILES['logo']['error'] == 0) 
       {
           $ret = uploadOne('logo', 'Goods', array(
               array(700, 700),
               array(350, 350),
               array(130, 130),
               array(50, 50),
           ));
           $data['logo'] = $ret['images'][0];
           $data['mbig_logo'] = $ret['images'][1];
           $data['big_logo'] = $ret['images'][2];
           $data['mid_logo'] = $ret['images'][3];
           $data['sm_logo'] = $ret['images'][4];
       }
        //获取当前时间并添加到表单中这样就会插入到数据库中
       $data['addtime']=date('Y-m-d H:i:s', time());
       //用我们自己的函数来过滤这个表单字段
       $data['goods_desc'] = removeXSS($_POST['goods_desc']);
       
    }

    //修改之前 钩子函数
    protected function _before_update(&$data, $option)
    {        
        $id = $option['where']['id']; //要修改的商品的ID  $id = I('post.id');         

        /****************** 修改商品属性 **********************/
        $gaid = I('post.goods_attr_id');
        $attrValue = I('post.attr_value');
        $gaModel = D("goods_attr");
        $_i =0;
        foreach($attrValue as $k => $v)
        {
            foreach($v as $k1 => $v1)
            {
                //找这个属性值是否有id
                if($gaid[$_i] =='')
                {
                    $gaModel->add(array(
                        'goods_id' => $id,
                        'attr_id' => $k,
                        'attr_value' => $v1,
                    ));
                }
                else
                {
                    $gaModel->where(array(
                        'id' => array('eq', $gaid[$_i]),                       
                    ))->setField('attr_value', $v1);
                }

                $_i++;
            }
        }

         /***************** 处理扩展分类 *********************/
         $ecid = I('post.ext_cat_id');
         $gcModel = D('goods_cat');
         //先删除原分类数据
         $gcModel->where(array(
             'goods_id' => array('eq', $id),
         ))->delete();
         if(ecid)
         {
             $gcModel = D('goods_cat');
             foreach ($ecid as $k => $v)            
             {
                 if(empty($v))
                     continue;
 
                 $gcModel-> add(array(
                     'cat_id' => $v,
                     'goods_id' => $id,
                     )
                 );
             }
         }
 

         /*************** 处理LOGO ***********************/       
       //判断有没有选择图片
       if ($_FILES['logo']['error'] == 0) 
       {
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize   =     1024*1024 ;// 设置附件上传大小不超过 1M
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath  =     './Public/Uploads/'; // 设置附件上传根目录
            $upload->savePath  =     'Goods/'; // 设置附件上传（子）目录
            // 上传文件 
            $info   =   $upload->upload();
            if(!$info) {
                //获取失败原因把错误信息保存到 模型的error属性中,然后在控制器里会调用$Model->getError()获取到错误信息并由控制器打印
                $this->error = $upload->getError();
                return FALSE;
            }
            else
            {
                /****************** 生成缩略图 ****************/
                //先拼成原图上的路径
                $logo = $info['logo']['savepath'] . $info['logo']['savename'];
                //拼出缩略图的路径和名称
                $mbiglogo = $info['logo']['savepath'] . 'mbig_' .  $info['logo']['savename'];
                $biglogo = $info['logo']['savepath'] . 'big_' .  $info['logo']['savename'];
                $midlogo = $info['logo']['savepath'] . 'mid_' .  $info['logo']['savename'];
                $smlogo = $info['logo']['savepath'] . 'sm_' .  $info['logo']['savename'];

                $image = new \Think\Image();
                //打开要生成缩略图的图片
                $image->open('./Public/Uploads/'.$logo);
                //生成缩略图
                $image->thumb(700, 700)->save('./Public/Uploads/'.$mbiglogo);
                $image->thumb(350, 350)->save('./Public/Uploads/'.$biglogo);
                $image->thumb(130, 130)->save('./Public/Uploads/'.$midlogo);
                $image->thumb(50, 50)->save('./Public/Uploads/'.$smlogo);
               /* 把路径放到表单中 */
               $data['logo'] = $logo;
               $data['mbig_logo'] = $mbiglogo;
               $data['big_logo'] = $biglogo;
               $data['mid_logo'] = $midlogo;
               $data['sm_logo'] = $smlogo;

               /*************** 删除原来的图片 *******************/
               //先查询出原来的图片的路径
               $oldLogo = $this->field('logo,mbig_logo,big_logo,mid_logo,sm_logo')->find($id);
               //从硬盘上删除
               unlink('./Public/Uploads/'.$oldLogo['logo']);
               unlink('./Public/Uploads/'.$oldLogo['mbig_logo']);
               unlink('./Public/Uploads/'.$oldLogo['big_logo']);
               unlink('./Public/Uploads/'.$oldLogo['mid_logo']);
               unlink('./Public/Uploads/'.$oldLogo['sm_logo']);

            }    
       }

       //用我们自己的函数来过滤这个表单字段
       $data['goods_desc'] = removeXSS($_POST['goods_desc']);
        
    }

    //取出一个分类下的所有商品的ID(既考虑了主分类也考虑了扩展分类)
    public function getGoodsIdByCatId($catId)
    {
        //先取出所有子分类的ID
        $catModel = D('Admin/Category');
        $children = $catModel->getChildren($catId);
        //和子分类放在一起
        $children[] = $catId;
        
        /* 取出主分类或者扩展分类在这些分类中的商品 */

        //取出主分类下的商品ID        
        $gids = $this->field('id')->where(array(
            'cat_id' => array('in', $children), //主分类下的商品
        ))->select();   

        //取出扩展分类下的商品ID        
        $gcModel1 = D('goods_cat');
        $gids1 = $gcModel1->field('DISTINCT goods_id id')->where(array(
            'cat_id' => array('IN', $children)
        ))->select();       
        //把主分类的ID和扩展分类下的商品ID合并成一个二维数组 
        //两个都不为空时合并,否则取出不为空的数组
        if($gids && $gids1)    
             $gids = array_merge($gids, $gids1);
        elseif($gids1)
             $gids = $gids1;      
        //二维转一维
        $id = array();
        foreach ($gids as $k => $v)
        {
            if(!in_array($v['id'], $id))
            $id[] = $v['id'];
        } 
        
        return $id;
    }

    /**
    *实现翻页, 搜索, 排序
    *
    * @param  [type] $perPage [每页显示几条数据]
    * @return [type]      [description]
    */
    public function search($perPage = 5)
    {
        /***************** 搜索 *****************/
        $where = array();
        //按商品名称搜索
        $gn = I('get.gn');
        if($gn)
        {
            $where['a.goods_name'] = array('like', "%$gn%"); //WHERE goods_name LIKE '%$gn%'
        }

        //按价格搜索
        $fp = I('get.fp');
        $tp = I('get.tp');
        if($fp && $tp)
        {
            $where['a.shop_price'] = array('between', array($fp, $tp)); //WHERE shop_price BETWEEN $fp AND $tp
        }
        elseif($fp)
        {
            $where['a.shop_price'] = array('egt', $fp); //WHERE shop_price >= $fp
        }
        elseif($tp)
        {
            $where['a.shop_price'] = array('elt', $tp); //WHERE shop_price <= $fp
        }

        //按是否上架搜索
        $ios = I('get.ios');
        if($ios)
        {
            $where['a.is_on_sale'] = array('eq', $ios); //WHERE is_on_sale = $ios
        }

        //按添加时间搜索
        $fa = I('get.fa');
        $ta = I('get.ta');
        if($fa && $ta)
        {
            $where['a.addtime'] = array('between', array($fa, $ta)); //WHERE addtime BETWEEN $fa AND $ta
        }
        elseif($fa)
        {
            $where['a.addtime'] = array('egt', $fa); //WHERE addtime >= $fa
        }
        elseif($ta)
        {
            $where['a.addtime'] = array('elt', $ta); //WHERE addtime <= $ta
        }

        //按品牌搜索
        $brandId = I('get.brand_id');
        if($brandId)
        {
            $where['a.brand_id'] = array('eq', $brandId);
        }

        //按主分类搜索
        $catId = I('get.cat_id');
        if($catId)
        {
            //先查询出这个分类的ID下的所有的商品ID
            $gids = $this->getGoodsIdByCatId($catId);
            //应用到取数据的WHERE上
            $where['a.id'] = array('in', $gids);

        }



        /***************** 翻页 ****************/
        //取出总的记录数
        $count = $this->alias('a')->where($where)->count();
        //生成翻页类的对象
        $pageObj = new \Think\Page($count, $perPage);
        //设置样式
        $pageObj->setConfig('next','下一页');
        $pageObj->setConfig('prev','上一页');
        //生成页面下面显示的上一页,下一页的字符串
        $pageString = $pageObj->show();  
        
        /***************** 排序 ****************/
        $orderby = 'a.id'; //默认的排序字段
        $orderway = 'desc'; //默认的排序方式
        $odby = I('get.odby');

        if($odby)
        {
            if($odby == 'id_asc')
                { $orderway = 'asc'; }
            elseif($odby == 'price_desc')
                { $orderby = shop_price; }
            elseif($odby == 'price_asc')
                { $orderby = shop_price; 
                  $orderway = 'asc'; 
                }
        }

        /***************** 取某一页的数据 ****************/
        $data = $this->order("$orderby $orderway")                  //排序
        ->field('a.*,b.brand_name,c.cat_name,GROUP_CONCAT(e.cat_name SEPARATOR "<br />") ext_cat_name')
        ->alias('a')                                                    
        ->join('LEFT JOIN __BRAND__ b ON a.brand_id=b.id         
                LEFT JOIN __CATEGORY__ c ON a.cat_id=c.id
                LEFT JOIN __GOODS_CAT__ d ON a.id=d.goods_id
                LEFT JOIN __CATEGORY__ e ON d.cat_id=e.id')       
        ->where($where)                                             //搜索
        ->limit($pageObj->firstRow.','.$pageObj->listRows)          //翻页
        ->group('a.id')
        ->select();

        /***************** 返回数据 ****************/
        return array(
            'data' => $data,   //数据
            'page' => $pageString, //翻页字符串
        );
    }

    //删除之前 钩子函数
    protected function _before_delete($option)    
    {      

        $id = $option['where']['id']; //要删除的商品的ID

        /************ 删除商品库存量 ******************/
        $gnModel = D('goods_number');
        $gnModel->where(array(
            'goods_id' => array('eq', $id),
        ))->delete();

        /* 删除商品属性 */
        $gcModel = D('goods_attr');
        $gcModel->where(array(
            'goods_id' => array('eq', $id),
        ))->delete();
        
        /* 删除扩展分类 */
        $gcModel = D('goods_cat');
        $gcModel->where(array(
            'goods_id' => array('eq', $id),
        ))->delete();

    /*************** 删除原来的图片 *******************/
        //先查询出原来的图片的路径
        $oldLogo = $this->field('logo,mbig_logo,big_logo,mid_logo,sm_logo')->find($id);
        
        deleteImage($oldLogo);
        
    }

    //添加之后的钩子函数
    /**
    *商品添加之后会调用这个方法, 其中$data['id']就是新添加的商品的ID
    * 
    */
    protected function _after_insert($data, $option)
    {
        
        /**************** 处理商品属性的代码 *******************/
        $attrValue = I('post.attr_value');
        
        $gaModel = D('goods_attr');
        
        foreach ($attrValue as $k => $v)
        {
            //把属性值得数组去重
            $v = array_unique($v);
            foreach ($v as $k1 => $v1)
            {
                $gaModel->add(array(
                    'goods_id' => $data['id'],
                    'attr_id' =>$k,
                    'attr_value' => $v1,
                ));
            }
        }

        /***************** 处理扩展分类 *********************/
        $ecid = I('post.ext_cat_id');
        if(ecid)
        {
            $gcModel = D('goods_cat');
            foreach ($ecid as $k => $v)            
            {
                if(empty($v))
                    continue;

                $gcModel-> add(array(
                    'cat_id' => $v,
                    'goods_id' => $data['id'],
                    )
                );
            }
        }




         $mp = I('post.member_price');
         $mpModel = D('member_price');         
         foreach ($mp as $k => $v)
         {
             $_v = (float)$v;             
             if($_v > 0)
             {
                $mpModel->add(array(
                        'price' => $v,
                        'level_id' => $k,
                        'goods_id' => $data['id'],
                    )
                );

             }
           
         }
    
    }

    /* 取出当前正在促销的商品(疯狂抢购) */
    public function getPromoteGoods($limit = 5)
    {
        $today = date('Y-m-d H:i');
        return $this->field('id,goods_name,mid_logo,promote_price')
        ->where(array(
            'is_on_sale' => array('eq', '是'),
            'promote_price' => array('gt', 0),
            'promote_start_date' => array('elt', $today),
            'promote_end_date' => array('egt', $today),
        ))
        ->limit($limit)
        ->order('sort_num ASC')
        ->select();
    }

    /* 取出三种推荐的数据 */
    public function getRecGoods($recType, $limit = 5)
    {        
        return $this->field('id,goods_name,mid_logo,shop_price')
        ->where(array(
            'is_on_sale' => array('eq', '是'),
            //此处必须使用双引号才能识别变量$recType
            "$recType" => array('eq', '是'),
            
        ))
        ->limit($limit)
        ->order('sort_num ASC')
        ->select();
    }

}