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


<!-- 搜索 -->
<div class="form-div search_form_div">
    <form action="/index.php/Admin/Admin/lst" method="GET" name="search_form">
		<p>
			用户名：
	   		<input type="text" name="username" size="30" value="<?php echo I('get.username'); ?>" />
		</p>
		<p>
			是否启用：
			<input type="radio" value="-1" name="is_use" <?php if(I('get.is_use', -1) == -1) echo 'checked="checked"'; ?> /> 全部 
			<input type="radio" value="1" name="is_use" <?php if(I('get.is_use', -1) == '1') echo 'checked="checked"'; ?> /> 启用 
			<input type="radio" value="0" name="is_use" <?php if(I('get.is_use', -1) == '0') echo 'checked="checked"'; ?> /> 禁用 
		</p>
		<p><input type="submit" value=" 搜索 " class="button" /></p>
    </form>
</div>
<!-- 列表 -->
<div class="list-div" id="listDiv">
	<table cellpadding="3" cellspacing="1">
    	<tr>
            <th >用户名</th>
            <th >密码</th>
            <th >是否启用</th>
			<th width="60">操作</th>
        </tr>
		<?php foreach ($data as $k => $v): ?>            
			<tr class="tron">
				<td><?php echo $v['username']; ?></td>
				<td><?php echo $v['password']; ?></td>
				<td admin_id="<?php echo ($v["id"]); ?>" class="is_use"><?php echo $v['is_use']==1?'启用':'禁用'; ?></td>
		        <td align="center">
					<a href="<?php echo U('edit?id='.$v['id'].'&p='.I('get.p')); ?>" title="编辑">编辑</a>
					<?php if($v['id'] > 1): ?>
					|
					<a href="<?php echo U('delete?id='.$v['id'].'&p='.I('get.p')); ?>" onclick="return confirm('确定要删除吗？');" title="移除">移除</a> 
					<?php endif; ?>
		        </td>
	        </tr>
        <?php endforeach; ?> 
		<?php if(preg_match('/\d/', $page)): ?>  
        <tr><td align="right" nowrap="true" colspan="99" height="30"><?php echo $page; ?></td></tr> 
        <?php endif; ?> 
	</table>
</div>

<script>
	//为启用的td加一事件
	$(".is_use").click(function(){
		//先获取点击的记录的ID
		var id = $(this).attr("admin_id");
		//如果是超级管理员,就不执行ajax了
		if(id == 1)
		{
			alert("超级管理员不能修改! ");
			return false;
		}
		var _this = $(this);
		$.ajax({
			type: "GET",
			url: "<?php echo U('ajaxUpdateIsuse', '', FALSE); ?>/id/"+id,			
			success: function(data)
			{
				if(data == 0 )
					_this.html("禁用");
				else
					_this.html("启用");		
			}
		});
	});	
</script>

<script src="/Public/Admin/Js/tron.js"></script>

<div id="footer">
    39期</div>
</body>
</html>