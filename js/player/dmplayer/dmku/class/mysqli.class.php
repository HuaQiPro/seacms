<?php
function RemoveXSS($val) {  
   // remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed  
   // this prevents some character re-spacing such as <java\0script>  
   // note that you have to handle splits with \n, \r, and \t later since they *are* allowed in some inputs  
   $val = preg_replace('/([\x00-\x08,\x0b-\x0c,\x0e-\x19])/', '', $val);  
     
   // straight replacements, the user should never need these since they're normal characters  
   // this prevents like <IMG SRC=@avascript:alert('XSS')>  
   $search = 'abcdefghijklmnopqrstuvwxyz'; 
   $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';  
   $search .= '1234567890!@#$%^&*()'; 
   $search .= '~`";:?+/={}[]-_|\'\\'; 
   for ($i = 0; $i < strlen($search); $i++) { 
      // ;? matches the ;, which is optional 
      // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars 
    
      // @ @ search for the hex values 
      $val = preg_replace('/(&#[xX]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val); // with a ; 
      // @ @ 0{0,7} matches '0' zero to seven times  
      $val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ; 
   } 
    
   // now the only remaining whitespace attacks are \t, \n, and \r 

   $ra1 = Array('_GET','_POST','_COOKIE','_REQUEST','if:','javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base', 'eval', 'passthru', 'exec', 'assert', 'system', 'chroot', 'chgrp', 'chown', 'shell_exec', 'proc_open', 'ini_restore', 'dl', 'readlink', 'symlink', 'popen', 'stream_socket_server', 'pfsockopen', 'putenv', 'cmd','base64_decode','fopen','fputs','replace','input','contents'); 
   $ra2 = Array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload'); 
   $ra = array_merge($ra1, $ra2); 
    
   $found = true; // keep replacing as long as the previous round replaced something 
   while ($found == true) { 
      $val_before = $val; 
      for ($i = 0; $i < sizeof($ra); $i++) { 
         $pattern = '/'; 
         for ($j = 0; $j < strlen($ra[$i]); $j++) { 
            if ($j > 0) { 
               $pattern .= '(';  
               $pattern .= '(&#[xX]0{0,8}([9ab]);)'; 
               $pattern .= '|';  
               $pattern .= '|(&#0{0,8}([9|10|13]);)'; 
               $pattern .= ')*'; 
            } 
            $pattern .= $ra[$i][$j]; 
         } 
         $pattern .= '/i';  
         $replacement = substr($ra[$i], 0, 2).'|*|'.substr($ra[$i], 2); // add in <> to nerf the tag  
         $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags  
         if ($val_before == $val) {  
            // no replacements were made, so exit the loop  
            $found = false;  
         }  
      }  
   } 
	$val = str_ireplace('<', "（", $val);
	$val = str_ireplace('>', "）", $val);
	$val = str_ireplace('"', "“", $val);
	$val = str_ireplace('\'', "‘", $val);
	$val = str_ireplace(',', "，", $val);
	$val = str_ireplace('(', "（", $val);
	$val = str_ireplace(')', "）", $val);
   return $val;  
}
function secsql($str)  
{  
if (empty($str)) return false;  
	$str = htmlspecialchars($str);  
	$str = str_ireplace('/', "", $str);
	$str = str_ireplace('[', "", $str);
	$str = str_ireplace(']', "", $str);	
	$str = str_ireplace('>', "", $str);  
	$str = str_ireplace('<', "", $str);  
	$str = str_ireplace('?', "", $str);
	$str = str_ireplace('&', "", $str);
	$str = str_ireplace('|', "", $str);
	$str = str_ireplace('{', "", $str);
	$str = str_ireplace('}', "", $str);
	$str = str_ireplace('%', "", $str);
	$str = str_ireplace('=', "", $str);
	$str = str_ireplace(':', "", $str);
	$str = str_ireplace(';', "", $str);
	$str = str_ireplace('*', "", $str);
    $str = str_ireplace('@', "", $str);	
	$str = str_ireplace('--', "", $str);
	$str = str_ireplace('//', "", $str);
	$str = str_ireplace('\\', "", $str);
	$str = str_ireplace('#', "", $str);
return $str;
}


class sql
{
    public static $sql;

    function __construct()
    {
        global $_config;
        self::数据库连接($_config['数据库']['地址'], $_config['数据库']['用户名'], $_config['数据库']['密码'], $_config['数据库']['名称'], $_config['数据库']['端口']);
    }

    private static function 数据库连接($hostname, $username, $password, $db, $port)
    {
        $sql = new mysqli($hostname, $username, $password, $db, $port);;
        if ($sql->connect_error) {
            showmessage(-1, '数据库错误:' . $sql->connect_errno . "\n" . $sql->connect_error);
        }
        self::$sql = $sql;
    }

    public static function 插入_弹幕($data)
    {
        $data['id']=secsql($data['id']);
		$data['type']=secsql($data['type']);
		$data['text']=RemoveXSS($data['text']);
		$data['color']=secsql($data['color']);
		$data['size']=secsql($data['size']);
		$data['time']=secsql($data['time']);
		$data['author']=RemoveXSS($data['author']);
		$_SERVER['REMOTE_ADDR']=secsql($_SERVER['REMOTE_ADDR']);
		try {
            $stmt = self::$sql->prepare("INSERT IGNORE INTO sea_danmaku_list (id, type, text, color, size, videotime, ip, time, user) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            @$stmt->bind_param('sssssssss', $data['id'], $data['type'], $data['text'], $data['color'], $data['size'], $data['time'], $_SERVER['REMOTE_ADDR'], time(), $data['author']);
            if ($stmt->execute() == false) {
                throw new Exception($stmt->error_list);
            }
            $stmt->close();
        } catch (Exception $e) {
            showmessage(-1, $e->getMessage());
        }
    }

    public static function 插入_发送弹幕次数($ip)
    {
        $ip=secsql($ip);
		try {
            $stmt = self::$sql->prepare("INSERT IGNORE INTO sea_danmaku_ip (ip, time) VALUES (?, ?)");
            @$stmt->bind_param('si', $ip, time());
            if ($stmt->execute() == false) {
                throw new Exception($stmt->error_list);
            }
            $stmt->close();
        } catch (Exception $e) {
            showmessage(-1, $e->getMessage());
        }
    }

    public static function 查询_弹幕池($id)
    {
        $id=secsql($id);
		try {
            $stmt = self::$sql->prepare("SELECT * FROM sea_danmaku_list WHERE id=?");
            $stmt->bind_param('s', $id);
            if ($stmt->execute() == false) {
                throw new Exception($stmt->error_list);
            }
            $data = self::fetchAll($stmt->get_result());
            $stmt->close();
            return $data;
        } catch (Exception $e) {
            showmessage(-1, $e->getMessage());
        }
    }

    public static function 查询_发送弹幕次数($ip)
    {
        $ip=secsql($ip);
		try {
            $stmt = self::$sql->prepare("SELECT * FROM sea_danmaku_ip WHERE ip = ? LIMIT 1");
            $stmt->bind_param('s', $ip);
            if ($stmt->execute() == false) {
                throw new Exception($stmt->error_list);
            }
            $data = self::fetchAll($stmt->get_result());
            $stmt->close();
            return $data;
        } catch (Exception $e) {
            showmessage(-1, $e->getMessage());
        }
    }
	
	public static function 搜索_弹幕池($key)
    {
        try {
            $stmt = self::$sql->prepare("SELECT * FROM sea_danmaku_list WHERE text like '%$key%' or id like '%$key%' ORDER BY time DESC");
         
            if ($stmt->execute() == false) {
                throw new Exception($stmt->error_list);
            }
            $data = self::fetchAll($stmt->get_result());
            return $data;
        } catch (PDOException $e) {
            showmessage(-1, '数据库错误:' . $e->getMessage());
        }
    }

    public static function 更新_发送弹幕次数($ip, $time = 'time')
    {
        $ip=secsql($ip);
		try {
            $query = "UPDATE sea_danmaku_ip SET c=c+1,time=$time WHERE ip = ?";
            if (is_int($time)) $query = "UPDATE sea_danmaku_ip SET c=1,time=$time WHERE ip = ?";
            $stmt = self::$sql->prepare($query);
            $stmt->bind_param('s', $ip);
            if ($stmt->execute() == false) {
                throw new Exception($stmt->error_list);
            }
            $stmt->close();
        } catch (Exception $e) {
            showmessage(-1, $e->getMessage());
        }
    }
	
	public static function 举报_弹幕($ip)
    {
		$_GET['title']=secsql($_GET['title']);
		$_GET['cid']=secsql($_GET['cid']);
		$_GET['text']=secsql($_GET['text']);
		$_GET['type']=secsql($_GET['type']);
		$_SERVER['REMOTE_ADDR']=secsql($_SERVER['REMOTE_ADDR']);
		try {
            $stmt = self::$sql->prepare("INSERT IGNORE INTO sea_danmaku_report (id,cid,text,type,time,ip) VALUES (?,?,?,?,?,?)");
            @$stmt->bind_param('ssssss', $_GET['title'],$_GET['cid'],$_GET['text'],$_GET['type'],time(),$_SERVER['REMOTE_ADDR']);
            if ($stmt->execute() == false) {
                throw new Exception($stmt->error_list);
            }
            $stmt->close();
        } catch (Exception $e) {
            showmessage(-1, $e->getMessage());
        }
	}
	public static function 显示_弹幕列表()
    {
        try {
            global $_config;
            $page = 1;
            if (isset($_GET['page'])) {
                $page = $_GET['page'];
            }
            $limit = $_GET['limit'];
			$conn = @new mysqli($_config['数据库']['地址'], $_config['数据库']['用户名'], $_config['数据库']['密码'], $_config['数据库']['名称'], $_config['数据库']['端口']);
            $conn->set_charset('utf8');
            $sql = "select count(*) from sea_danmaku_list ORDER BY time DESC";
            $res = $conn->query($sql);
            $length = $res->fetch_row();
            $count = $length[0];
            $index = ($page - 1) * $limit;	
            $stmt = self::$sql->prepare("SELECT * FROM sea_danmaku_list ORDER BY time DESC limit $index,$limit"); 		
            if($stmt->execute() == false) {
                throw new Exception($stmt->error_list);
            }
            $data = self::fetchAll($stmt->get_result());
            $stmt->close();
            return $data;

        } catch (PDOException $e) {
            showmessage(-1, '数据库错误:' . $e->getMessage());
        }
    }
    public static function 显示_举报列表()
    {
        try {
            global $_config;
            $page = 1;
            if (isset($_GET['page'])) {
                $page = $_GET['page'];
            }
            $limit = $_GET['limit'];
			$conn = @new mysqli($_config['数据库']['地址'], $_config['数据库']['用户名'], $_config['数据库']['密码'], $_config['数据库']['名称'], $_config['数据库']['端口']);
            $conn->set_charset('utf8');
            $sql = "select count(*) from sea_danmaku_report ORDER BY time DESC";
            $res = $conn->query($sql);
            $length = $res->fetch_row();
            $count = $length[0];
            $index = ($page - 1) * $limit;	
            $stmt = self::$sql->prepare("SELECT * FROM sea_danmaku_report ORDER BY time DESC limit $index,$limit"); 		
            if($stmt->execute() == false) {
                throw new Exception($stmt->error_list);
            }
            $data = self::fetchAll($stmt->get_result());
            $stmt->close();
            return $data;

        } catch (PDOException $e) {
            showmessage(-1, '数据库错误:' . $e->getMessage());
        }
    }
   public static function 删除_弹幕数据($id)
    {
        try {
            global $_config;
            $conn = @new mysqli($_config['数据库']['地址'], $_config['数据库']['用户名'], $_config['数据库']['密码'], $_config['数据库']['名称'], $_config['数据库']['端口']);
            $conn->set_charset('utf8');
            if ($_GET['type'] == "list") {
                $sql = "DELETE FROM sea_danmaku_report WHERE cid={$id}";
                $result = "DELETE FROM sea_danmaku_list WHERE cid={$id}";
                $conn->query($sql);
                $conn->query($result);
            } else if ($_GET['type'] == "report") {
                $sql = "DELETE FROM sea_danmaku_report WHERE cid={$id}";
                $conn->query($sql);
            }
        } catch (PDOException $e) {
            showmessage(-1, '数据库错误:' . $e->getMessage());
        }
    }
    public static function 编辑_弹幕($cid)
    {
        try {
            global $_config;
            $text = $_POST['text'];
            $color = $_POST['color'];
            $conn = @new mysqli($_config['数据库']['地址'], $_config['数据库']['用户名'], $_config['数据库']['密码'], $_config['数据库']['名称'], $_config['数据库']['端口']);
            
            $sql = "UPDATE sea_danmaku_list SET text='$text',color='$color' WHERE cid=$cid";
            $result = "UPDATE sea_danmaku_report SET text='$text',color='$color' WHERE cid=$cid";
            $conn->query($sql);
            $conn->query($result);
        } catch (PDOException $e) {
            showmessage(-1, '数据库错误:' . $e->getMessage());
        }
    }
   private static function fetchAll($obj)
    {
        $data = [];
        if ($obj->num_rows > 0) {
            while ($arr = $obj->fetch_assoc()) {
                $data[] = $arr;
            }
        }
        $obj->free();
        return $data;
    }
}
