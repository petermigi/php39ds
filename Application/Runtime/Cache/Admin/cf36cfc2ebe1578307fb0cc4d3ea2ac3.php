<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>管理中心 - 商品列表 </title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="/Public/Admin/Styles/general.css" rel="stylesheet" type="text/css" />
<link href="/Public/Admin/Styles/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/Public/umeditor1_2_2-utf8-php/third-party/jquery.min.js"></script>
</head>
<body>
<h1>
    <?php if($_page_btn_name): ?>
    <span class="action-span"><a href="<?php echo $_page_btn_link; ?>"><?php echo $_page_btn_name; ?></a></span>
    <?php endif; ?>
    <span class="action-span1"><a href="#">管理中心</a></span>
    <span id="search_id" class="action-span1"> - <?php echo $_page_title; ?> </span>
    <div style="clear:both"></div>
</h1>

<!-- 内容 -->

<div class="main-div">
    <form name="main_form" method="POST" action="/index.php/Admin/Role/edit/id/18.html" enctype="multipart/form-data">
        <table cellspacing="1" cellpadding="3" width="100%">
            <input type="hidden" name="id" value="<?php echo $data['id']; ?>" />
            <tr>
                <td class="label">角色名称：</td>
                <td>
                    <input  type="text" name="role_name" value="<?php echo $data['role_name']; ?>" />
                </td>
            </tr>
            <tr>
                <td class="label">权限列表：</td>
                <td>
                    <?php foreach($priData as $k => $v): if(strpos(','.$pri_id.',', ','.$v['id'].',') !== FALSE) $check= 'checked="checked"'; else $check = ''; ?>
                    <?php echo str_repeat('-', $v['level'] * 8); ?>
                   <input <?php echo ($check); ?> level="<?php echo ($v["level"]); ?>" type="checkbox" name="pri_id[]" value="<?php echo ($v["id"]); ?>" /><?php echo ($v["pri_name"]); ?><br />
                    <?php endforeach; ?>
                </td>
            </tr>
            <tr>
                <td colspan="99" align="center">
                    <input type="submit" class="button" value=" 确定 " />
                    <input type="reset" class="button" value=" 重置 " />
                </td>
            </tr>
        </table>
    </form>
</div>

<script>
   
    // 为所有的选择框绑定点击事件
    $(":checkbox").click(function(){

        //先取出当前权限的level值是多少
        var cur_level = $(this).attr("level"); 

        //判断是选中还是取消
        if($(this).prop("checked"))
        {          
            var tmplevel = cur_level; //给一个临时的变量后面要进行减操作

            //先取出这个复选框所有前面的复选框
            var allprev = $(this).prevAll(":checkbox");            

            //循环每一个前面的复选框判断是不是上级的
            $(allprev).each(function(k,v){

                //判断是不是上级的权限
                /* 
                根据树形结构层级找直接上级, prevAll()方法得到的是从当前元素前面一个元素开始的所有之前的元素
                直接上级只有一个
                直接上级一定在当前元素的前面,其level值小于当前元素,
                找到直接上级元素后,把判断是否是直接上级的标准提升一级
                这样再往后找的时候,找的是直接上级的直接上级,而与当前元素的直接上级level值相等的兄弟上级不会被找到
                */
                if($(v).attr("level") < tmplevel)
                {                    
                    tmplevel--; //再向上提一级 
                    $(v).prop("checked", "checked");
                }
            });

            //所有的子权限也选中
            //先取出这个复选框所有后面的复选框
            var allnext = $(this).nextAll(":checkbox");          

            //循环每一个后面的复选框判断是不是下级的
            $(allnext).each(function(k,v){
            
                //判断是不是下级的权限               
                if($(v).attr("level") > cur_level)
                {                       
                    $(v).prop("checked", "checked");
                }
                else
                {
                    return false; //遇到一个平级的权限就停止循环后面的不用再判断了
                }
            });

        }
        else 
        {
            //先取出这个复选框所有后面的复选框
            var allnext = $(this).nextAll(":checkbox");          

            //循环每一个后面的复选框判断是不是下级的
            $(allnext).each(function(k,v){
            
                //判断是不是下级的权限               
                if($(v).attr("level") > cur_level)
                {                       
                    $(v).removeAttr("checked");
                }
                else
                {
                    return false; //遇到一个平级的权限就停止循环后面的不用再判断了
                }
            });
        }
    });
</script>

<div id="footer">
    39期</div>
</body>
</html>