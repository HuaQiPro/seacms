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
	$dblink=@mysql_connect($dblocalhost,$dbusername,$dbpassword);
	if(!$dblink)
	{
		eDbConnectError();
	}
	//����
	if($phome_db_ver>='4.1')
	{
		//����
		DoSetDbChar($phome_db_char);
		if($phome_db_ver>='5.0')
		{
			@mysql_query("SET sql_mode=''");
		}
	}
	if($dbname)
	{
		@mysql_select_db($dbname,$dblink);
	}
	return $dblink;
}

function do_dbclose(){
	global $link;
	if($link)
	{
		@mysql_close($link);
	}
}

//���ñ���
function do_DoSetDbChar($dbchar){
	@mysql_query('set character_set_connection='.$dbchar.',character_set_results='.$dbchar.',character_set_client=binary;');
}

//ȡ��mysql�汾
function do_eGetDBVer($selectdb=0){
	global $empire;
	if($selectdb&&$empire)
	{
		$getdbver=$empire->egetdbver();
	}
	else
	{
		$getdbver=@mysql_get_server_info();
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
	$dblink=@mysql_connect($dblocalhost,$dbusername,$dbpassword);
	return $dblink;
}

function do_dbquery_common($query,$ecms=0){
	if($ecms==0)
	{
		$sql=@mysql_query($query);
	}
	else
	{
		$sql=mysql_query($query);
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
		$usedb=@mysql_select_db($dbname,return_dblink(''));
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
		$this->sql=mysql_query($query) or die(mysql_error()."<br>".$query);
		return $this->sql;
	}
	//ִ��mysql_query()���2
	function query1($query)
	{
		$this->sql=mysql_query($query);
		return $this->sql;
	}
	//ִ��mysql_fetch_array()
	function fetch($sql)//�˷����Ĳ�����$sql����sql���ִ�н��
	{
		$this->r=mysql_fetch_array($sql);
		return $this->r;
	}
	//ִ��fetchone(mysql_fetch_array())
	//�˷�����fetch()��������:1���˷����Ĳ�����$query����sql��� 
	//2���˷�������while(),for()���ݿ�ָ�벻���Զ����ƣ���fetch()�����Զ����ơ�
	function fetch1($query)
	{
		$this->sql=$this->query($query);
		$this->r=mysql_fetch_array($this->sql);
		return $this->r;
	}
	//ִ��mysql_num_rows()
	function num($query)//����Ĳ�����$query����sql���
	{
		$this->sql=$this->query($query);
		$this->num=mysql_num_rows($this->sql);
		return $this->num;
	}
	//ִ��numone(mysql_num_rows())
	//�˷�����num()�������ǣ�1���˷����Ĳ�����$sql����sql����ִ�н����
	function num1($sql)
	{
		$this->num=mysql_num_rows($sql);
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
		mysql_free_result($sql);
	}
	//ִ��seek(mysql_data_seek())
	//�˷����Ĳ�����$sql����sql����ִ�н��,$pitΪִ��ָ���ƫ����
	function seek($sql,$pit)
	{
		mysql_data_seek($sql,$pit);
	}
	//ִ��id(mysql_insert_id())
	function lastid()//ȡ�����һ��ִ��mysql���ݿ�id��
	{
		$this->id=mysql_insert_id();
		return $this->id;
	}
	//ִ��escape_string()����
	function EDbEscapeStr($str){
		$str=mysql_real_escape_string($str);
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