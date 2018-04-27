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
    <form name="main_form" method="POST" action="/index.php/Admin/Admin/add.html" enctype="multipart/form-data">
        <table cellspacing="1" cellpadding="3" width="100%">
            <tr>
                <td class="label">所在角色：</td>
                <td>
                    <?php foreach($roleData as $k => $v): ?>
                    <input  type="checkbox" name="role_id[]" value="<?php echo ($v["id"]); ?>" /><?php echo ($v["role_name"]); ?>
                    <?php endforeach; ?>
                </td>
            </tr>
            <tr>
                <td class="label">用户名：</td>
                <td>
                    <input  type="text" name="username" />
                </td>
            </tr>
            <tr>
                <td class="label">密码：</td>
                <td>
                    <input type="password" size="25" name="password" />
                </td>
            </tr>
            <tr>
                <td class="label">确认密码：</td>
                <td>
                    <input type="password" size="25" name="cpassword" />
                </td>
            </tr>
            <tr>
                <td class="label">是否启用</td>
                <td>
                	<input type="radio" name="is_use" value="1" checked="checked" />启用 
                	<input type="radio" name="is_use" value="0"  />禁用 
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
</script>

<div id="footer">
    39期</div>
</body>
</html>