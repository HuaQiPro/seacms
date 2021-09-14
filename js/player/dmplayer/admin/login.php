<?php
require_once("../../../../include/common.php");
require_once("../../../../include/check.admin.php");
$dsql->safeCheck = false;
$dsql->SetLongLink();
//检验用户登录状态
$cuserLogin = new userLogin();
CheckPurview();
?>