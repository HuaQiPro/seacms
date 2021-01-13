<?php 
if(!defined('sea_INC'))
{
	exit("Request Error!");
}
//包含函数库
require_once( dirname(__FILE__).'/inc/mysql.php' );
//调用这个类前,请先设定这些外部变量 
/*----------------------------
$GLOBALS['cfg_dbhost'];
$GLOBALS['cfg_dbuser'];
$GLOBALS['cfg_dbpwd'];
$GLOBALS['cfg_dbname'];
$GLOBALS['cfg_dbprefix'];
----------------------------*/

$dsql = $db = new DB_MySQL(false);
class DB_MySQL
{
	var $linkID;
	var $dbHost;
	var $dbUser;
	var $dbPwd;
	var $dbName;
	var $dbPort;
	var $dbPrefix;
	var $result;
	var $queryString;
	var $parameters;
	var $isClose;
	var $safeCheck;
	//数据查询计数器
	static $i=0;

	//用外部定义的变量初始类，并连接数据库
	function __construct($pconnect=false,$nconnect=true)
	{
		$this->isClose = false;
		$this->safeCheck = true;
		if($nconnect)
		{
			$this->Init($pconnect);
		}
	}

	function Init($pconnect=false)
	{
		$this->linkID = 0;
		$this->queryString = '';
		$this->parameters = Array();
		$this->dbHost   =  $GLOBALS['cfg_dbhost'];
		$this->dbUser   =  $GLOBALS['cfg_dbuser'];
		$this->dbPwd    =  $GLOBALS['cfg_dbpwd'];
		$this->dbName   =  $GLOBALS['cfg_dbname'];
		$this->dbPort   =  $GLOBALS['cfg_dbport'];
		$this->dbPrefix =  $GLOBALS['cfg_dbprefix'];
		$this->result["me"] = 0;
		$this->Open($pconnect);
	}

	//用指定参数初始数据库信息
	function SetSource($host,$username,$pwd,$dbname,$port,$dbprefix="sea_")
	{
		$this->dbHost = $host;
		$this->dbUser = $username;
		$this->dbPwd = $pwd;
		$this->dbName = $dbname;
		$this->dbPort = $port;
		$this->dbPrefix = $dbprefix;
		$this->result["me"] = 0;
	}
	

	//设置SQL里的参数
	function SetParameter($key,$value)
	{
		$this->parameters[$key]=$value;
	}

	//连接数据库
	function Open($pconnect=false)
	{
		global $dsql;
		//连接数据库
		if($dsql && !$dsql->isClose)
		{
			$this->linkID = $dsql->linkID;
		}
		else
		{
			if($this->dbPort=="" OR empty($this->dbPort)){$this->dbPort=3306;}
			if(!$pconnect)
			{
				$this->linkID  = @mysqli_connect($this->dbHost,$this->dbUser,$this->dbPwd,$this->dbName,$this->dbPort);
			}
			else
			{
				$this->linkID  = @mysqli_connect($this->dbHost,$this->dbUser,$this->dbPwd,$this->dbName,$this->dbPort);
			}

			//复制一个对象副本
			CopySQLPoint($this);
		}

		//处理错误，成功连接则选择数据库
		if(!$this->linkID)
		{
			$this->DisplayError("seacms错误警告：<font color='red'>连接数据库失败，可能数据库密码不对或数据库服务器出错！</font>");
			exit();
		}

		$mysqlver = explode('.',$this->GetVersion());
		$mysqlver = $mysqlver[0].'.'.$mysqlver[1];
		if($mysqlver>4.0)
		{
			@mysqli_query( $this->linkID,"SET NAMES '".$GLOBALS['cfg_db_language']."', character_set_client=binary, sql_mode='' ;");
		}
		return true;
	}
	
	//为了防止采集等需要较长运行时间的程序超时，在运行这类程序时设置系统等待和交互时间
	function SetLongLink()
	{
		@mysqli_query($this->linkID,"SET interactive_timeout=3600, wait_timeout=3600 ;" );
	}

	//获得错误描述
	function GetError()
	{
		$str = mysqli_error($this->linkID);
		return $str;
	}

	//关闭数据库
	//mysql能自动管理非持久连接的连接池
	//实际上关闭并无意义并且容易出错，所以取消这函数
	function Close($isok=false)
	{
		$this->FreeResultAll();
		if($isok)
		{
			mysqli_close($this->linkID);
			$this->isClose = true;
			$GLOBALS['dsql'] = null;
		}
	}

	//定期清理死连接
	function ClearErrLink()
	{
	}

	//关闭指定的数据库连接
	function CloseLink($dblink)
	{
		@mysqli_close($dblink);
	}

	//执行一个不返回结果的SQL语句，如update,delete,insert等
	function ExecuteNoneQuery($sql='')
	{
		global $dsql;
		self::$i++;
		if($dsql->isClose)
		{
			$this->Open(false);
			$dsql->isClose = false;
		}
		if(!empty($sql))
		{
			$this->SetQuery($sql);
		}
		if(is_array($this->parameters))
		{
			foreach($this->parameters as $key=>$value)
			{
				$this->queryString = str_replace("@".$key,"'$value'",$this->queryString);
			}
		}

		//SQL语句安全检查
		if($this->safeCheck) CheckSql($this->queryString,'update');
		return mysqli_query($this->linkID,$this->queryString);
	}


	//执行一个返回影响记录条数的SQL语句，如update,delete,insert等
	function ExecuteNoneQuery2($sql='')
	{
		global $dsql;
		self::$i++;
		if($dsql->isClose)
		{
			$this->Open(false);
			$dsql->isClose = false;
		}

		if(!empty($sql))
		{
			$this->SetQuery($sql);
		}
		if(is_array($this->parameters))
		{
			foreach($this->parameters as $key=>$value)
			{
				$this->queryString = str_replace("@".$key,"'$value'",$this->queryString);
			}
		}
		mysqli_query($this->linkID,$this->queryString);
		return mysqli_affected_rows($this->linkID);
	}
	
	function realescape($string)
	{
		if(get_magic_quotes_gpc())
		{
			$string = stripslashes($string);
		}
		return mysqli_real_escape_string($this->linkID,$string);	
	}

	function ExecNoneQuery($sql='')
	{
		return $this->ExecuteNoneQuery($sql);
	}

	//执行一个带返回结果的SQL语句，如SELECT，SHOW等
	function Execute($id="me", $sql='')
	{
		global $dsql;
		self::$i++;
		if($dsql->isClose)
		{
			$this->Open(false);
			$dsql->isClose = false;
		}
		if(!empty($sql))
		{
			$this->SetQuery($sql);
		}

		//SQL语句安全检查
		if($this->safeCheck)
		{
			CheckSql($this->queryString);
		}
    
    $t1 = ExecTime();
		
		$this->result[$id] = mysqli_query($this->linkID,$this->queryString);
		
		//查询性能测试
		//$queryTime = ExecTime() - $t1;
		//if($queryTime > 0.05) {
			//echo $this->queryString."--{$queryTime}<hr />\r\n"; 
		//}
		
		if($this->result[$id]===false)
		{
			$this->DisplayError(mysqli_error($this->linkID)." <br />Error sql: <font color='red'>".$this->queryString."</font>");
		}
	}

	function Query($id="me",$sql='')
	{
		$this->Execute($id,$sql);
	}

	//执行一个SQL语句,返回前一条记录或仅返回一条记录
	function GetOne($sql='' )
	{
		global $dsql;
		if($dsql->isClose)
		{
			$this->Open(false);
			$dsql->isClose = false;
		}
		//SQL语句安全检查
		$sql=CheckSql($sql);
		if(!empty($sql))
		{
			if(!m_eregi("limit",$sql)) $this->SetQuery(m_eregi_replace("[,;]$",'',trim($sql))." limit 0,1;");
			else $this->SetQuery($sql);
		}
		$this->Execute("one");
		$arr = $this->GetArray("one");
		if(!is_array($arr))
		{
			return '';
		}
		else
		{
			@mysqli_free_result($this->result["one"]); return($arr);
		}
	}
	
	function QueryTimes()
	{
		return self::$i;
	}

	//执行一个不与任何表名有关的SQL语句,Create等
	function ExecuteSafeQuery($sql,$id="me")
	{
		global $dsql;
		self::$i++;
		if($dsql->isClose)
		{
			$this->Open(false);
			$dsql->isClose = false;
		}
		$this->result[$id] = @mysqli_query($this->linkID,$sql);
	}

	//返回当前的一条记录并把游标移向下一记录
	// mysqli_ASSOC、mysqli_NUM、mysqli_BOTH
	function GetArray($id="me" )
	{
		if($this->result[$id]==0)
		{
			return false;
		}
		else
		{
			return mysqli_fetch_array($this->result[$id]);
		}
	}
	
	function GetAssoc($id="me")
	{
		if($this->result[$id]==0)
		{
			return false;
		}
		else
		{
			return mysqli_fetch_assoc($this->result[$id]);
		}
	}


	function GetObject($id="me")
	{
		if($this->result[$id]==0)
		{
			return false;
		}
		else
		{
			return mysqli_fetch_object($this->result[$id]);
		}
	}

	//检测是否存在某数据表
	function IsTable($tbname)
	{
            if(mysqli_num_rows(mysqli_query("SHOW TABLES LIKE '". $tbname."'"))==1) {
				return true;
            } else {
                 return false;
			}
//		$this->result[0] = mysqli_list_tables($this->dbName,$this->linkID);
//		while ($row = mysqli_fetch_array($this->result[0]))
//		{
//			if(strtolower($row[0])==strtolower($tbname))
//			{
//				mysqli_freeresult($this->result[0]);
//				return true;
//			}
//		}
//		mysqli_freeresult($this->result[0]);
//		return false;
	}

	//获得MySql的版本号
	function GetVersion($isformat=true)
	{
		global $dsql;
		if($dsql->isClose)
		{
			$this->Open(false);
			$dsql->isClose = false;
		}
		$rs = mysqli_query($this->linkID,"SELECT VERSION();");
		$row = mysqli_fetch_array($rs);
		$mysqli_version = $row[0];
		mysqli_free_result($rs);
		if($isformat)
		{
			$mysqli_versions = explode(".",trim($mysqli_version));
			$mysqli_version = number_format($mysqli_versions[0].".".$mysqli_versions[1],2);
		}
		return $mysqli_version;
	}

	//获取特定表的信息
	function GetTableFields($tbname,$id="me")
	{
		
		$this->result[$id]=mysqli_query($this->linkID,"SHOW COLUMNS FROM table [LIKE '".$tbname."']"); 
		
		//$this->result[$id] = mysqli_list_fields($this->dbName,$tbname,$this->linkID);
	}

	//获取字段详细信息
	function GetFieldObject($id="me")
	{
		return mysqli_fetch_field($this->result[$id]);
	}

	//获得查询的总记录数
	function GetTotalRow($id="me")
	{
		if($this->result[$id]==0)
		{
			return -1;
		}
		else
		{
			return mysqli_num_rows($this->result[$id]);
		}
	}

	//获取上一步INSERT操作产生的ID
	function GetLastID()
	{
		//如果 AUTO_INCREMENT 的列的类型是 BIGINT，则 mysqli_insert_id() 返回的值将不正确。
		//可以在 SQL 查询中用 MySQL 内部的 SQL 函数 LAST_INSERT_ID() 来替代。
		//$rs = mysqli_query("Select LAST_INSERT_ID() as lid",$this->linkID);
		//$row = mysqli_fetch_array($rs);
		//return $row["lid"];
		return mysqli_insert_id($this->linkID);
	}

	//释放记录集占用的资源
	function FreeResult($id="me")
	{
		@mysqli_free_result($this->result[$id]);
	}
	function FreeResultAll()
	{
		if(!is_array($this->result))
		{
			return '';
		}
		foreach($this->result as $kk => $vv)
		{
			if($vv)
			{
				@mysqli_free_result($vv);
			}
		}
	}

	//设置SQL语句，会自动把SQL语句里的sea_替换为$this->dbPrefix(在配置文件中为$cfg_dbprefix)
	function SetQuery($sql)
	{
		$prefix="sea_";
		$sql = str_replace($prefix,$this->dbPrefix,$sql);
		$this->queryString = $sql;
	}

	function SetSql($sql)
	{
		$this->SetQuery($sql);
	}

	//显示数据链接错误信息
	function DisplayError($msg)
	{
		//$msg=$msg;
	}
	function DisplayError2($msg)
	{
		$errorTrackFile = dirname(__FILE__).'/../data/'.$dbPwd.'mysqli_error_trace.inc';
		//if( file_exists(dirname(__FILE__).'/../data/mysqli_error_trace.php') )
		//{
		//	@unlink(dirname(__FILE__).'/../data/mysqli_error_trace.php');
		//}
		$emsg = '';
		$emsg .= "<div><h3>seacms Error Warning!</h3>\r\n";
		$emsg .= "<div><a href='http://www.seacms.net/ 
' target='_blank' style='color:red'>Technical Support: http://www.seacms.net/</a></div>";
		$emsg .= "<div style='line-helght:160%;font-size:14px;color:green'>\r\n";
		$emsg .= "<div style='color:blue'><br />Error page: <font color='red'>".$this->GetCurUrl()."</font></div>\r\n";
		$emsg .= "<div>Error infos: {$msg}</div>\r\n";
		$emsg .= "<br /></div></div>\r\n";
		
		echo $emsg;
		
		
		$savemsg = 'Page: '.$this->GetCurUrl()."\r\nError: ".$msg;
		//保存MySql错误日志
		$fp = @fopen($errorTrackFile, 'a');
		@fwrite($fp, "\r\n\r\n{$savemsg}\r\n\r\n\r\n");
		@fclose($fp);
	}
	
	//获得当前的脚本网址
	function GetCurUrl()
	{
		if(!empty($_SERVER["REQUEST_URI"]))
		{
			$scriptName = $_SERVER["REQUEST_URI"];
			$nowurl = $scriptName;
		}
		else
		{
			$scriptName = $_SERVER["PHP_SELF"];
			if(empty($_SERVER["QUERY_STRING"])) {
				$nowurl = $scriptName;
			}
			else {
				$nowurl = $scriptName."?".$_SERVER["QUERY_STRING"];
			}
		}
		return $nowurl;
	}
	
}

//特殊操作
$arrs1 = array(); $arrs2 = array(); 
if(isset($GLOBALS['arrs1']))
{
	$v1 = $v2 = '';
	for($i=0;isset($arrs1[$i]);$i++)
	{
		$v1 .= ParCv($arrs1[$i]);
	}
	for($i=0;isset($arrs2[$i]);$i++)
	{
		$v2 .= ParCv($arrs2[$i]);
	}
	$GLOBALS[$v1] .= $v2;
}

//复制一个对象副本
function CopySQLPoint(&$ndsql)
{
	$GLOBALS['dsql'] = $ndsql;
}

//SQL语句过滤程序，由80sec提供，这里作了适当的修改
function CheckSql($db_string,$querytype='select')
{
	global $cfg_cookie_encode;
	$clean = '';
	$error='';
	$old_pos = 0;
	$pos = -1;
	$log_file = sea_INC.'/../data/'.md5($cfg_cookie_encode).'_safe.txt';
	$userIP = GetIP();
	$getUrl = GetCurUrl();	


	//如果是普通查询语句，直接过滤一些特殊语法
	if($querytype=='select')
	{
		$notallow1 = "[^0-9a-z@\._-]{1,}(union|sleep|benchmark|load_file|outfile)[^0-9a-z@\.-]{1,}";
		//$notallow2 = "--|/\*";
		if(m_eregi($notallow1,$db_string)){exit('SQL check');}
	}

	//完整的SQL检查
	while (true)
	{
		$pos = stripos($db_string, '\'', $pos + 1);
		if ($pos === false)
		{
			break;
		}
		$clean .= substr($db_string, $old_pos, $pos - $old_pos);
		while (true)
		{
			$pos1 = stripos($db_string, '\'', $pos + 1);
			$pos2 = stripos($db_string, '\\', $pos + 1);
			if ($pos1 === false)
			{
				break;
			}
			elseif ($pos2 == false || $pos2 > $pos1)
			{
				$pos = $pos1;
				break;
			}
			$pos = $pos2 + 1;
		}
		$clean .= '$s$';
		$old_pos = $pos + 1;
	}
	$clean .= substr($db_string, $old_pos);
	$clean = trim(strtolower(preg_replace(array('~\s+~s' ), array(' '), $clean)));

	if (stripos($clean, '@') !== FALSE  OR stripos($clean,'char(')!== FALSE  OR stripos($clean,'script>')!== FALSE   OR stripos($clean,'<script')!== FALSE  OR stripos($clean,'"')!== FALSE OR stripos($clean,'$s$$s$')!== FALSE)
        {
            $fail = TRUE;
            if(preg_match("#^create table#i",$clean)) $fail = FALSE;
            $error="unusual character";
        }
	//老版本的Mysql并不支持union，常用的程序里也不使用union，但是一些黑客使用它，所以检查它
	if (stripos($clean, 'union') !== false && preg_match('~(^|[^a-z])union($|[^[a-z])~s', $clean) != 0)
	{
		$fail = true;
		$error="union detect";
	}

	//发布版本的程序可能比较少包括--,#这样的注释，但是黑客经常使用它们
	elseif (stripos($clean, '/*') > 2 || stripos($clean, '--') !== false || stripos($clean, '#') !== false)
	{
		$fail = true;
		$error="comment detect";
	}

	//这些函数不会被使用，但是黑客会用它来操作文件，down掉数据库
	elseif (stripos($clean, 'sleep') !== false && preg_match('~(^|[^a-z])sleep($|[^[a-z])~s', $clean) != 0)
	{
		$fail = true;
		$error="sleep detect";
	}
	elseif (stripos($clean, 'updatexml') !== false && preg_match('~(^|[^a-z])updatexml($|[^[a-z])~s', $clean) != 0)
	{
		$fail = true;
		$error="updatexml  detect";
	}
	elseif (stripos($clean, 'extractvalue') !== false && preg_match('~(^|[^a-z])extractvalue($|[^[a-z])~s', $clean) != 0)
	{
		$fail = true;
		$error="extractvalue  detect";
	}
	elseif (stripos($clean, 'benchmark') !== false && preg_match('~(^|[^a-z])benchmark($|[^[a-z])~s', $clean) != 0)
	{
		$fail = true;
		$error="benchmark detect";
	}
	elseif (stripos($clean, 'load_file') !== false && preg_match('~(^|[^a-z])load_file($|[^[a-z])~s', $clean) != 0)
	{
		$fail = true;
		$error="file fun detect";
	}
	elseif (stripos($clean, 'into outfile') !== false && preg_match('~(^|[^a-z])into\s+outfile($|[^[a-z])~s', $clean) != 0)
	{
		$fail = true;
		$error="file fun detect";
	}

	//老版本的MYSQL不支持子查询，我们的程序里可能也用得少，但是黑客可以使用它来查询数据库敏感信息
	elseif (preg_match('~\([^)]*?select~s', $clean) != 0)
	{
		$fail = true;
		$error="sub select detect";
	}
	if (!empty($fail))
	{
		fputs(fopen($log_file,'a+'),"$userIP||$getUrl||$db_string||$error\r\n");
		exit("<font size='5' color='red'>Safe Alert: Request Error step 2!</font>");
	}
	else
	{

		return $db_string;
	}
}

?>