<?php 
define('InEmpireBAKDbSql',TRUE);

//------------------------- ���ݿ� -------------------------

function do_dbconnect($dbhost,$dbport,$dbusername,$dbpassword,$dbname=''){
	global $phome_db_char,$phome_db_ver;
	$dblocalhost=$dbhost;
	//�˿�
	if($dbport)
	{
		$dblocalhost.=':'.$dbport;
	}
	$dblink=@mysqli_connect($dblocalhost,$dbusername,$dbpassword);
	if(!$dblink)
	{
		eDbConnectError();
	}
	//����
	if($phome_db_ver>='4.1')
	{
		//����
		//DoSetDbChar($phome_db_char);
		@mysqli_query($dblink,'set character_set_connection='.$phome_db_char.',character_set_results='.$phome_db_char.',character_set_client=binary;');
		if($phome_db_ver>='5.0')
		{
			@mysqli_query($dblink,"SET sql_mode=''");
		}
	}
	if($dbname)
	{
		@mysqli_select_db($dblink,$dbname);
	}
	return $dblink;
}

function do_dbclose(){
	global $link;
	if($link)
	{
		@mysqli_close($link);
	}
}

//���ñ���
function do_DoSetDbChar($dbchar){
	@mysqli_query(return_dblink(),'set character_set_connection='.$dbchar.',character_set_results='.$dbchar.',character_set_client=binary;');
}

//ȡ��mysql�汾
function do_eGetDBVer($selectdb=0){
	global $empire,$link;
	if($selectdb&&$empire)
	{
		$getdbver=$empire->egetdbver();
	}
	else
	{
		if($link)
		{
			$getdbver=@mysqli_get_server_info($link);
		}
		else
		{
			$getdbver='';
		}
	}
	return $getdbver;
}

//��ͨ����
function do_dbconnect_common($dbhost,$dbport,$dbusername,$dbpassword,$dbname=''){
	global $phome_db_char,$phome_db_ver;
	$dblocalhost=$dbhost;
	//�˿�
	if($dbport)
	{
		$dblocalhost.=':'.$dbport;
	}
	$dblink=@mysqli_connect($dblocalhost,$dbusername,$dbpassword);
	return $dblink;
}

function do_dbquery_common($query,$ecms=0){
	if($ecms==0)
	{
		$sql=@mysqli_query(return_dblink(),$query);
	}
	else
	{
		$sql=mysqli_query(return_dblink(),$query);
	}
	return $sql;
}

//ѡ�����ݿ�
function do_eUseDb($dbname,$query=0){
	if($query)
	{
		$usedb=do_dbquery_common('use `'.$dbname.'`');
	}
	else
	{
		$usedb=@mysqli_select_db(return_dblink(),$dbname);
	}
	return $usedb;
}



//------------------------- ���ݿ���� -------------------------

class mysqlquery
{
	var $sql;//sql���ִ�н��
	var $query;//sql���
	var $num;//���ؼ�¼��
	var $r;//��������
	var $id;//�������ݿ�id��
	//ִ��mysql_query()���
	function query($query)
	{
		$this->sql=mysqli_query(return_dblink(),$query) or die(mysqli_error(return_dblink())."<br>".$query);
		return $this->sql;
	}
	//ִ��mysql_query()���2
	function query1($query)
	{
		$this->sql=mysqli_query(return_dblink(),$query);
		return $this->sql;
	}
	//ִ��mysql_fetch_array()
	function fetch($sql)//�˷����Ĳ�����$sql����sql���ִ�н��
	{
		$this->r=mysqli_fetch_array($sql);
		return $this->r;
	}
	//ִ��fetchone(mysql_fetch_array())
	//�˷�����fetch()��������:1���˷����Ĳ�����$query����sql��� 
	//2���˷�������while(),for()���ݿ�ָ�벻���Զ����ƣ���fetch()�����Զ����ơ�
	function fetch1($query)
	{
		$this->sql=$this->query($query);
		$this->r=mysqli_fetch_array($this->sql);
		return $this->r;
	}
	//ִ��mysql_num_rows()
	function num($query)//����Ĳ�����$query����sql���
	{
		$this->sql=$this->query($query);
		$this->num=mysqli_num_rows($this->sql);
		return $this->num;
	}
	//ִ��numone(mysql_num_rows())
	//�˷�����num()�������ǣ�1���˷����Ĳ�����$sql����sql����ִ�н����
	function num1($sql)
	{
		$this->num=mysqli_num_rows($sql);
		return $this->num;
	}
	//ִ��numone(mysql_num_rows())
	//ͳ�Ƽ�¼��
	function gettotal($query)
	{
		$this->r=$this->fetch1($query);
		return $this->r['total'];
	}
	//ִ��free(mysql_result_free())
	//�˷����Ĳ�����$sql����sql����ִ�н����ֻ�����õ�mysql_fetch_array���������
	function free($sql)
	{
		mysqli_free_result($sql);
	}
	//ִ��seek(mysql_data_seek())
	//�˷����Ĳ�����$sql����sql����ִ�н��,$pitΪִ��ָ���ƫ����
	function seek($sql,$pit)
	{
		mysqli_data_seek($sql,$pit);
	}
	//ִ��id(mysql_insert_id())
	function lastid()//ȡ�����һ��ִ��mysql���ݿ�id��
	{
		$this->id=mysqli_insert_id(return_dblink());
		return $this->id;
	}
	//ִ��escape_string()����
	function EDbEscapeStr($str){
		$str=mysqli_real_escape_string(return_dblink(),$str);
		return $str;
	}
	//ȡ�����ݿ�汾
	function egetdbver()
	{
		$this->r=$this->fetch1('select version() as version');
		return $this->r['version'];
	}
}
 ?>