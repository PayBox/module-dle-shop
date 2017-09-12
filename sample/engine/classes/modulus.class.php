<?
// Modulus Universalis - универсальнфый класс модуля для быстрого создания функционала 
// добавления/изменения/удаления/просмотра записей в БД

// Русский текст
class Modulus
{
	var $Db = null;
	var $table = null;
	var $dbstructure = array();
	var $templates = array();
	var $MyConfig = array();
	var $MyConfigFile = null;
	var	$MyTitle = null;
	var	$MyStorage = null; 
	var $MyClasses = array();
	var $MyPages = array();
	var $MyLinks = array();
	var $Lang = array();
	var $SelectLists = array();
	var $MyConfigTxt = null;
	var $Plugins = array();
	var $SearchData = array();
	var $CaptchaList = array();
	var $ModerSets = array();
	var $DBStorage = array();
	var $Mushka = array();
	var $CommentCfg = array();
	var $BBParsed = array();
	var $MultiFields = array();
	
	var $SqlList = array();
	// empty constructor function
	public function __construct() {	
			
	}
	// empty destructor function
	public function __destruct() {
		
	}
	
	// DB Functions - фнкции по работе с базой данных, с подключением к источнику БД из CMS или класса
	
	function insert($table, $entries)
	{
		global $db;
		// make query string :)
		$Qry = "INSERT INTO $table ";
		$Values = "(";
		$Fields = "(";
		$Count = count($entries);
		$i=1;
		foreach($entries as $key=>$value)
		{
			$value = $db->safesql($value);
			$Fields .= "`$key`";
			$Values .= "'$value'";
			if(($i+1)<=$Count)
			{
				$Fields .= ", ";
				$Values .= ", ";
			}
			$i++;
		}
		$Fields .= ")";
		$Values .= ")";
		$Qry .= $Fields." VALUES ".$Values;
		//echo "".$Qry;
		$this->query($Qry);
	}
	function update( $table, $entries, $where=array()) 
	{
		global $db;
		// make where string
		$WhereList = "";
		if(is_array($where))
		{
			$WhereList = " WHERE ";
			$Count = count($where);
			$i=1;
			foreach($where as $key=>$value)
			{
				$value = $db->safesql($value);
				$WhereList .= "`$key` = '$value'";
				if(($i+1)<=$Count)
				{
					$WhereList .= " AND ";
				}
			}
		}
		// make query string :)
		$Qry = "UPDATE $table SET ";
		$Fields = "";
		$Count = count($entries);
		$i=1;
		foreach($entries as $key=>$value)
		{
			$value = $db->safesql($value);
			$Fields .= "`$key` = '$value'";
			if(($i+1)<=$Count)
			{
				$Fields .= ", ";
			}
			$i++;
		}
		$Qry .= $Fields.$WhereList;
		
		$this->query($Qry);
	}
	function select($table, $where=array(), $orderby=array(), $limit="", $cols=array())
	{
		global $db;
		// make cols streen
		$ColsList = "";
		if(!empty($cols))
		{
			$Count = count($cols);
			$i=1;
			foreach($cols as $col)
			{
				if(strstr($col, "count"))
				{
					$ColsList .= "$col";
				}
				else
				{
					$ColsList .= "`$col`";
				}
				if(($i+1)<=$Count)
				{
					$ColsList .= ", ";
				}
			}
		}
		else
		{
			$ColsList = " * ";
		}
		// make where string
		$WhereList = "";
		if(!empty($where))
		{
			$WhereList = " WHERE ";
			$Count = count($where);
			$i=1;
			foreach($where as $key=>$value)
			{
				if(is_array($value))
				{
					if(!isset($value['sign']))
					{
						
						$Cnt = 1;
						foreach($value as $hrundel)
						{
							if(is_array($hrundel)&&isset($hrundel['sign'])&&$hrundel['sign']!="")
							{
								$hrundel['value'] = $db->safesql($hrundel['value']);
								$WhereList .= "`$key` ".$hrundel['sign']." '".$hrundel['value']."'";
								$Cnt++;
								if($Cnt<=count($value))
								{
									$WhereList .= " AND ";
								}
							}
							else
							{
								$hrundel = $db->safesql($hrundel);
								$WhereList .= "`$key` = '$hrundel'";
								$Cnt++;	
								if($Cnt<=count($value))
								{
									$WhereList .= " OR ";
								}
							}
						}	
					}
					else
					{
						$value['value'] = $db->safesql($value['value']);
						$WhereList .= "`$key` ".$value['sign']." '".$value['value']."'";
					}
				}
				else
				{
					$value = $db->safesql($value);
					$WhereList .= "`$key` = '$value'";
				}
				if(($i+1)<=$Count)
				{
					$WhereList .= " AND ";
				}
				$i++;
			}
		}
		// make orderby string
		$OrderByList = "";
		if(!empty($orderby))
		{
			$OrderByList = " ORDER BY ";
			$Count = count($orderby);
			$i=1;
			foreach($orderby as $key=>$value)
			{
				$OrderByList .= "`$key` $value";
				if(($i+1)<=$Count)
				{
					$OrderByList .= ", ";
				}
				$i++;
			}
		}
		// Limit string 
		$limitStr = "";
		if($limit!="")
		{
			$limitStr = "LIMIT ".$limit;
		}
		$Qry = "SELECT $ColsList FROM $table $WhereList $OrderByList $limitStr";
		$result = $this->query($Qry);
		$RetArr = array();
		if(is_array($result))
		{
			$RetArr = $result;
		}
		else
		{
			while($row = $db->get_row($result))
			{
				$RetArr[] = $row;
			}
		}
		return $RetArr;
	}
	function delete($table, $where)
	{
		global $db;	// make where string
		$WhereList = "";
		if(is_array($where))
		{
			$WhereList = " WHERE ";
			$Count = count($where);
			$i=1;
			foreach($where as $key=>$value)
			{
				$value = $db->safesql($value);
				$WhereList .= "`$key` = '$value'";
				if(($i+1)<=$Count)
				{
					$WhereList .= " AND ";
				}
			}
		}
		$Qry = "DELETE FROM $table $WhereList";
		$result = $this->query($Qry);
	}
	function query($query)
	{
		global $db;
		$this->SqlList[] = $query;
		// set hook on query
		// first get table from it
		$SQLArr = $this->ParseSql($query);
		$TableText = trim($SQLArr['table']);
		if(isset($this->DBStorage[''.$TableText.'']))
		{
			// ok we have kinds of request now lets try to get elements
			// first thing is to define subarray of aproprite variables it is restricted by where
			$result = $this->ArraySQLSearch($SQLArr);
			if($result!="zooza"&&!empty($result))
			{
				return $result;
			}
		}		
		$result = $db->query($query);
		return $result;
	}
	function ProcessQuery($res)
	{
		global $db;
		$RetArr = array();
		if(is_array($res))
		{
			$RetArr = $res;
		}
		else
		{
			while($row = $db->get_row($res))
			{
				$RetArr[] = $row;
			}
		}
		return $RetArr;
	}
	
	// Init functions - функции инициализации модуля
	function Initiate($title, $filePath, $cnfFile, $cnfArr, $templates=array(), $cnfTxt="", $plugins=array())
	{
		$this->MyConfig = $cnfArr;
		$this->MyConfigFile = $cnfFile;
		$this->MyTitle = $title;
		$this->MyStorage = $filePath;
		$this->templates = $templates;
		$this->MyConfigTxt = $cnfTxt;
		$this->Plugins = $plugins;
	}
	function LoadDataClass($title="", $table="", $DbStructure=array(), $tpl=array(), $lang=array(), $FormArr = array(), $CheckDataArr=array(), $BreadCrumbsArr=array(), $LinkinArr=array(), $cacheit=false, $seovar="")
	{
		$this->MyClasses[$title]['title'] = $title;
		$this->MyClasses[$title]['table'] = $table;
		$this->MyClasses[$title]['db'] = $DbStructure;
		$this->MyClasses[$title]['tpl'] = $tpl;
		$this->MyClasses[$title]['lang'] = $lang;
		$this->MyClasses[$title]['form'] = $FormArr;
		$this->MyClasses[$title]['fieldcheck'] = $CheckDataArr;
		$this->MyClasses[$title]['breadcrumbs'] = $BreadCrumbsArr;
		$this->MyClasses[$title]['links'] = $LinkinArr;
		$this->MyClasses[$title]['cache'] = $cacheit;
		$this->MyClasses[$title]['seo'] = $seovar;
		// check if cache is and load database to an array
		if($cacheit==true)
		{
			// load database
			$AllItems = $this->GetRecords($title);
			$this->DBStorage[''.$table.''] = $AllItems;
		}
	}
	function CreatePage($title, $langTitle, $Configs, $TagList, $UserRights, $UrlPath=array(), $tplFile="", $Meta=array())
	{
		$this->MyPages[$title]['lang'] = $langTitle;
		$this->MyPages[$title]['tags'] = $TagList;
		$this->MyPages[$title]['rights'] = $UserRights;
		$this->MyPages[$title]['url'] = $UrlPath;
		$this->MyPages[$title]['tpl'] = $tplFile;
		$this->MyPages[$title]['config'] = $Configs;
		$this->MyPages[$title]['meta'] = $Meta;
	}
	function LoadPage($PageTitle, $plugins=array())
	{
		global $metatags;
		if(isset($this->MyPages[$PageTitle]))
		{
			// start loading page 
			if($this->UserCheckRights($this->MyPages[$PageTitle]['rights']))
			{	
				$metatags['title'] = $this->MyPages[$PageTitle]['meta']['title'];
				$metatags['keywords'] = $this->MyPages[$PageTitle]['meta']['keywords'];
				$metatags['description'] = $this->MyPages[$PageTitle]['meta']['description'];
				
				switch($this->MyPages[$PageTitle]['config']['type'])
				{	
					case "static": // this is a static page which don't need form processing'
					{
						$MyTags = array();
						foreach($plugins as $key=>$value)
						{
							$MyTags = array_merge($MyTags, $this->LoadPlugin($key, $value));
						}
						$MyTags = $this->GenerateTagData($this->MyPages[$PageTitle]['tags']);
					//	$MyTags = array_merge($MyTags, $this->PrepareDbTags($class, "static"));
						return $this->MakeTemplate($this->MyPages[$PageTitle]['tpl'], $MyTags, "content");
						break;
					}
					case "form": // this is a form page which needs to have the form processed and reacted
					{
						if($_REQUEST['sent']=='yes')
						{
							$this->ProcessData($this->MyPages[$PageTitle]);	
						}
						else
						{	
							$MyTags = $this->GenerateTagData($this->MyPages[$PageTitle]['tags']);
							return $this->MakeTemplate($this->MyPages[$PageTitle]['tpl'], $MyTags, "content");
						}
						break;
					}
				}
			}
			else
			{
				return $this->MakeTemplate($this->templates['message'], array("msg"=>$this->GetLangVar("msg_restricted")), "content");	
			}
		}
	}
	
	function GenerateTagData($TagArray = array())
	{
		$RetArr = array();
		foreach($TagArray as $key=>$value)
		{
			$SpecVal = $this->GetTrueValue("", $value);
			if(is_array($SpecVal))
			{
				foreach($SpecVal as $ert=>$jinx)
				{
					$RetArr[$ert] = $jinx;
				}	
			}
			else
			{
				$RetArr[$key] = $this->GetTrueValue("", $value);
			}
		}	
		return $RetArr;
	}
	
	// Show function - функции отображения данных
	function ShowList($class, $ParamArr=array())
	{
		global $config;
		$Page = "1";
		if(isset($_REQUEST['page']))
		{
			if($_REQUEST['page']!="")
			{
				$Page = $_REQUEST['page'];
			}	
		}
		$offset = (int)((int)$Page-1)*(int)$this->MyConfig['limitpage']['value'];
		$Ordering = $ParamArr['order'];
		$where = $ParamArr['where'];
		
		
		$CountIt = 	$this->GetCount($class, $where);
		$limiting = $ParamArr['limit'];
		if($limiting=="")
		{
			if(isset($this->MyConfig['limitpage']['value']))
			{
				$limits = "$offset, ".$this->MyConfig['limitpage']['value'];
			}
			else
			{
				$limits = "$offset, 10";
			}
		}
		else
		{
			$limits = $limiting;
		}
		$Items = $this->GetRecords($class, $where, $Ordering, $limits);
		// We have our items now we need to build a list
		if(isset($ParamArr['tpl']))
		{
			$TemplateLoad = $ParamArr['tpl'];
		}
		else
		{
			$TemplateLoad = $this->MyClasses[$class]['tpl']['item'];
		}
		
		if(isset($ParamArr['tpllist']))
		{
			$TemplateWhole = $ParamArr['tpllist'];
		}
		else
		{
			$TemplateWhole = $this->MyClasses[$class]['tpl']['list'];
		}
		
		$pager = "1";
		if(isset($ParamArr['pager']))
		{
			$pager = $ParamArr['pager'];
		}
		
		$zebra = "";
		if(isset($ParamArr['zebra']))
		{
			$zebra = $ParamArr['zebra'];
		}
		
		$MyList = $this->MakeItemList($class, $Items, $CountIt, $TemplateLoad, $pager, $zebra);
		
		// Load main template and insert extra tags there
		if(!file_exists("templates/".$config['skin']."/".$TemplateWhole))
		{
			$BodyOf =file_get_contents($TemplateWhole);
		}
		else
		{
			$BodyOf =file_get_contents("templates/".$config['skin']."/".$TemplateWhole);
		}
		$BodyOf = str_replace("{items}", $MyList, $BodyOf);
		$TotalPages = ceil((int)$CountIt/$this->MyConfig['limitpage']['value']);
		if($pager=="1")
		{
			if(isset($_GET['page']))
			{
				$Current = $_GET['page'];
			}
			else
			{
				$Current = "1";
			}
			if($pager!="0")
			{
				$PageStr = $this->MakePages($class, $TotalPages, $Current);
				$BodyOf = str_replace("{pagination}", $PageStr, $BodyOf);
			}
			else
			{
				$BodyOf = str_replace("{pagination}", "", $BodyOf);
			}
		}
		else
		{
			$BodyOf = str_replace("{pagination}", "", $BodyOf);
		}
		
		$BodyOf = str_replace("{item_count}", $CountIt, $BodyOf);
		
		return $BodyOf;
	}
	// Record functions - функции управления записями
	function AddRecord($class, $data)
	{
		// Get through the record data get fields we have in dbstructure make an array to add and write it
		$TotalArr = array();
		$CheckArr = array();
		foreach($this->MyClasses[$class]['db'] as $key=>$value)
		{
			if(isset($data[$key]))
			{
				$TotalArr[$key] = $data[$key];
				// check array forming
				if($value=="varchar")
				{
					$CheckArr[$key] = $data[$key];
				}
			}
		}
		// Uniquality check
		$ResultArr = $this->select($this->MyClasses[$class]['table'], $CheckArr);
		if(isset($ResultArr[0]["id"]))
		{
			return "exists";
		}
		// So we need to add our record into offlin
		$this->insert($this->MyClasses[$class]['table'], $TotalArr);
		if($this->MyClasses[$class]['cache']==true)
		{
			unset($this->DBStorage[''.$this->MyClasses[$class]['table'].'']);
			$AllItems = $this->GetRecords(''.$this->MyClasses[$class]['title'].'');
			$this->DBStorage[''.$this->MyClasses[$class]['table'].''] = $AllItems;
		}
		// now lets see our last record ID ;) a little trick from Uncle Boris
		$ResultArr = $this->select($this->MyClasses[$class]['table'], array(), array("id"=>"DESC"), "", array());
		return $ResultArr[0]["id"];
	}
	function EditRecord($class, $id, $data)
	{
		// Get through the record data get fields we have in dbstructure make an array to add and write it
		$TotalArr = array();
		foreach($this->MyClasses[$class]['db'] as $key=>$value)
		{
			if(isset($data[$key]))
			{
				$TotalArr[$key] = $data[$key];
			}
		}
		$this->update($this->MyClasses[$class]['table'], $TotalArr, array("id"=>$id));	
	}
	function DeleteRecord($class, $id)
	{
		$this->delete($this->MyClasses[$class]['table'],  array("id"=>$id));
	}
	function GetRecord($class, $id)
	{
		$where = array("id"=>$id);
		$Redult = $this->select($this->MyClasses[$class]['table'], $where, array(), "1", array());
		return $Redult;
	}
	function GetRecords($class, $Data=array(), $OrderBy=array(), $limit="")
	{
		if(isset($this->ModerSets[''.$class.'']))
		{
			if(!isset($Data['published']))
			{	
				$Data['published'] = "1";
			}
		}
		$Redult = $this->select($this->MyClasses[$class]['table'], $Data, $OrderBy, $limit, array());
		return $Redult;
	}
	function GetCount($class, $Data=array())
	{
		if(isset($this->ModerSets[''.$class.'']))
		{
			if(!isset($Data['published']))
			{
				$Data['published'] = "1";
			}
		}
		$Redult = $this->select($this->MyClasses[$class]['table'], $Data, array(), "", array("count(`id`)"));
		return $Redult[0]["count(`id`)"];
	}
	
	// Show function - функции отображения данных
	function ShowItemList($class, $ParamArr=array())
	{		
			$Page = "1";
			if(isset($_REQUEST['page']))
			{
				$Page = $_REQUEST['page'];	
			}
			if(isset($_REQUEST['moder']))
			{
				$Moder = $_REQUEST['moder'];	
			}
			$offset = (int)((int)$Page-1)*(int)$this->MyConfig['limitpage']['value'];
			$Ordering = $ParamArr['order'];
			$where = $ParamArr['where'];
			if($Moder=="1")
			{
				$where['published'] = "0";
			}
			$CountIt = 	$this->GetCount($class, $where);
			$limits = "$offset, ".$this->MyConfig['limitpage']['value'];
			$Items = $this->GetRecords($class, $where, $Ordering, $limits);
			if($_GET["mod"]=="shop" && $_GET["class"]=="categories" && $_GET["op"]=="showlist")
			$Items = $this->GetRecords($class, $where, $Ordering, $CountIt);
			//echo "<pre>";
			//print_r($Items);	
			//echo "</pre>";
			$pager = "1";
			if(isset($ParamArr['pager']))
			{
				$pager = $ParamArr['pager'];
			}
			
			// We have our items now we need to build a list
			if(isset($ParamArr['tpl']['item']))
			{
				$TplItem = $ParamArr['tpl']['item'];
			}
			else
			{
				$TplItem = $this->MyClasses[$class]['tpl']['item'];
			}
			//echo $class;
			$MyList = $this->MakeItemList($class, $Items, $CountIt, $TplItem);
			//print_r($MyList);	
			if(isset($ParamArr['tpl']['list']))
			{
				$TplList = $ParamArr['tpl']['list'];
			}
			else
			{
				$TplList = $this->MyClasses[$class]['tpl']['list'];
			}
			$TotalPages = ceil((int)$CountIt/$this->MyConfig['limitpage']['value']);
			if($pager=="1")
			{
				if(isset($_GET['page']))
				{
					$Current = $_GET['page'];
				}
				else
				{
					$Current = "1";
				}
				if($pager!="0")
				{
					$PageStr = $this->MakePages($class, $TotalPages, $Current);
				}
				else
				{
					$PageStr = "";
				}
			}
			else
			{
				$PageStr = "";
			}
			if($_GET["mod"]=="shop" && $_GET["class"]=="categories" && $_GET["op"]=="showlist")
			$PageStr="Страниц: <b>1</b>";
			$SetArr = array("items_all"=>$MyList, "pagination"=>$PageStr, "item_count"=>$CountIt);
			
			if(isset($ParamArr['extra']))
			{
				$SetArr = array_merge($SetArr, $ParamArr['extra']);
			}
			//print_r($SetArr);
			return $this->MakeTemplate($TplList, $SetArr, "content");
	}
	function ShowItem($class, $ParamArr = array())
	{	
		// Shows one particular record
		global $member_id, $metatags;
		
		if(isset($_GET['id'])&&$_GET['id']!="")
		{
			$IId = $_GET['id'];
			$Items =  $this->GetRecord($class, $IId);
		}
		else
		{
			$MesData = "Неправильный запрос вы будете перенаправлены на главну страницу <br /><a href=\"".$this->GetLinkTag("main")."\">На главную</a>";	
			return $this->MakeTemplate($this->templates['message'], array("msg"=>$MesData), "content");
		}
		
		$CurItem = $Items[0];
		if(isset($CurItem['id'])&&$CurItem['id']!="")
		{
		}
		else
		{
			$MesData = "Неправильный запрос вы будете перенаправлены на главну страницу <br /><a href=\"".$this->GetLinkTag("main")."\">На главную</a>";	
			return $this->MakeTemplate($this->templates['message'], array("msg"=>$MesData), "content");
		}
		$metatags['title'] 	= $CurItem['title']." :: ".$this->MyConfig['title']['value'];
		if(isset($CurItem['description']))
		{
			$metatags['description'] 	= $CurItem['description'];
		}	
		$ArrVar = array();
		
		$ArrVar['breadcrumbs'] = $this->GenerateCrumbs($class, $CurItem);
		
		//$ArrVar = $this->ShowComments($CurItem);
		foreach($CurItem as $key=>$value)
		{
			$CurItem[$key] = $this->GetTrueValue($value, $this->MyClasses[$class]['db'][$key]);
			$CurItem[$key."_raw"] = $value;
			if($this->MyClasses[$class]['db'][$key]=="img")
			{
				$CurItem["".$key."_thumb"] = $this->GetTrueValue($value, $this->MyClasses[$class]['db'][$key]."_thumb");
				$CurItem["".$key."_thumb_raw"] = $this->GetTrueValue($value, $this->MyClasses[$class]['db'][$key]."_thumb_raw");
				$CurItem["".$key."_raw"] = $this->GetTrueValue($value, $this->MyClasses[$class]['db'][$key]."_raw");
				$CurItem["".$key."_complete"] = $this->GetTrueValue($value, $this->MyClasses[$class]['db'][$key]."_complete");
			}
			if($this->MyClasses[$class]['db'][$key]=="unlimg")
			{
				$CurItem["".$key."_thumb"] = $this->GetTrueValue($value, $this->MyClasses[$class]['db'][$key]."_thumb");
				$CurItem["".$key."_complete"] = $this->GetTrueValue($value, $this->MyClasses[$class]['db'][$key]."_complete");
			}
			if($this->MyClasses[$class]['db'][$key]=="multifield")
			{
				$ArrFields = $this->GetTrueValue($value, $this->MyClasses[$class]['db'][$key]);
				foreach($ArrFields as $gre=>$fee)
				{
					$CurItem["".$gre.""] = $fee;
				}
			}
		}
		if(isset($this->MyClasses[$class]['commcl'])&&$this->MyClasses[$class]['commcl']!="")
		{
			if(isset($CurItem['commpub'])&&$CurItem['commpub']==$this->GetLangVar("checkbox_yes"))
			{
				$CurItem['comments'] = $this->ShowComments($class, $CurItem['id']);
			}
			else
			{
				$CurItem['comments'] = "";
			}
		}
		else
		{
			$CurItem['comments'] = "";
		}
		if($member_id['user_group']=="1")
		{
			$CurItem["adm_links"] = "<a href=\"".$this->GetLink($class, "edit", array("id"=>$IId))."\">".$this->GetLangVar("Edit")."</a> | <a href=\"".$this->GetLink($class, "delete", array("id"=>$IId))."\">".$this->GetLangVar("Delete")."</a>";
			if(isset($this->ModerSets[''.$class.'']))
			{
				// moderation links go here
				if($CurItem['published']=="1")
				{
					$CurItem["adm_links"] .= " | <a href=\"".$this->GetLink($class, "unpublish", array("id"=>$IId))."\">".$this->GetLangVar("unpubl")."</a>"; 
				}
				else
				{
					$CurItem["adm_links"] .= " | <a href=\"".$this->GetLink($class, "publish", array("id"=>$IId))."\">".$this->GetLangVar("publ")."</a>";
				}
				
			}
		}
		else
		{
			$CurItem["adm_links"] = "";
		}
		$ArrVar = array_merge($ArrVar, $CurItem);
		$PlugDataArr = array();
		foreach($this->Plugins as $key=>$value)
		{
			$PlugDataArr = $this->LoadPlugin($key, "ShowFull", $ArrVar);
		}
		$ArrVar = array_merge($ArrVar, $PlugDataArr);
		
		if(isset($ParamArr['tpl']))
		{
			$TemplateLoad = $ParamArr['tpl'];
		}
		else
		{
			$TemplateLoad = $this->MyClasses[$class]['tpl']['full'];
		}
			
		//$ArrVar = array_merge($ArrVar, $this->GetAdminLinks($class, "ShowItem"));
		return $this->MakeTemplate($TemplateLoad, $ArrVar, "content");
	}
	function ShowItemPrint($class, $ParamArr = array())
	{	
		// Shows one particular record
		global $member_id, $metatags;
		if(isset($_GET['id']))
		{
			$IId = $_GET['id'];
			$Items =  $this->GetRecord($class, $IId);
		}
		else
		{
			$MesData = "Неправильный запрос вы будете перенаправлены на главну страницу <br /><a href=\"".$this->GetLinkTag("main")."\">На главную</a>";	
			return $this->MakeTemplate($this->templates['message'], array("msg"=>$MesData), "content");
		}
		$CurItem = $Items[0];
		$metatags['title'] 			= $CurItem['title']." :: ".$this->MyConfig['title']['value'];
		if(isset($CurItem['description']))
		{
			$metatags['description'] 	= $CurItem['description'];
		}	
		$ArrVar = array();
		
		$ArrVar['breadcrumbs'] = $this->GenerateCrumbs($class, $CurItem);
		
		//$ArrVar = $this->ShowComments($CurItem);
		foreach($CurItem as $key=>$value)
		{
			$CurItem[$key] = $this->GetTrueValue($value, $this->MyClasses[$class]['db'][$key]);
			if($this->MyClasses[$class]['db'][$key]=="img")
			{
				$CurItem["".$key."_thumb"] = $this->GetTrueValue($value, $this->MyClasses[$class]['db'][$key]."_thumb");
				$CurItem["".$key."_thumb_raw"] = $this->GetTrueValue($value, $this->MyClasses[$class]['db'][$key]."_thumb_raw");
				$CurItem["".$key."_raw"] = $this->GetTrueValue($value, $this->MyClasses[$class]['db'][$key]."_raw");
				$CurItem["".$key."_complete"] = $this->GetTrueValue($value, $this->MyClasses[$class]['db'][$key]."_complete");
			}
			if($this->MyClasses[$class]['db'][$key]=="unlimg")
			{
				$CurItem["".$key."_thumb"] = $this->GetTrueValue($value, $this->MyClasses[$class]['db'][$key]."_thumb");
				$CurItem["".$key."_complete"] = $this->GetTrueValue($value, $this->MyClasses[$class]['db'][$key]."_complete");
			}
		}
		if(isset($this->MyClasses[$class]['commcl'])&&$this->MyClasses[$class]['commcl']!="")
		{
			$CurItem['comments'] = $this->ShowComments($class, $CurItem['id']);
		}
		else
		{
			$CurItem['comments'] = "";
		}
		if($member_id['user_group']=="1")
		{
			$CurItem["adm_links"] = "<a href=\"".$this->GetLink($class, "edit", array("id"=>$IId))."\">".$this->GetLangVar("Edit")."</a> | <a href=\"".$this->GetLink($class, "delete", array("id"=>$IId))."\">".$this->GetLangVar("Delete")."</a>";
			if(isset($this->ModerSets[''.$class.'']))
			{
				// moderation links go here
				if($CurItem['published']=="1")
				{
					$CurItem["adm_links"] .= " | <a href=\"".$this->GetLink($class, "unpublish", array("id"=>$IId))."\">".$this->GetLangVar("unpubl")."</a>"; 
				}
				else
				{
					$CurItem["adm_links"] .= " | <a href=\"".$this->GetLink($class, "publish", array("id"=>$IId))."\">".$this->GetLangVar("publ")."</a>";
				}
				
			}
		}
		else
		{
			$CurItem["adm_links"] = "";
		}
		
		$ArrVar = array_merge($ArrVar, $CurItem);
		$PlugDataArr = array();
		foreach($this->Plugins as $key=>$value)
		{
			$PlugDataArr = $this->LoadPlugin($key, "ShowFull", $ArrVar);
		}
		$ArrVar = array_merge($ArrVar, $PlugDataArr);
		
		if(isset($ParamArr['tpl']))
		{
			$TemplateLoad = $ParamArr['tpl'];
		}
		else
		{
			//$TemplateLoad = $this->MyClasses[$class]['tpl']['full'];
			$TemplateLoad = "templates/artprint.tpl";//$this->MyClasses[$class]['tpl']['fullprint'];
		}
			
		//$ArrVar = array_merge($ArrVar, $this->GetAdminLinks($class, "ShowItem"));
		return $this->MakeTemplate($TemplateLoad, $ArrVar, "content", true);
	}
	function AddItem($class)
	{
		global $metatags, $lang, $config;
		
				if(isset($_GET['action']))
				{
					$action = $_GET['action'];
				}
				else
				{
					$action = "";
				}
			
			if ($action != "add") 
			{
				// Make form link more universal
				if ($config['allow_alt_url'] == "yes"&&!strstr($this->selfURL(), '='))
				{
					$FormLink = $this->selfURL()."/add";
				}
				else
				{
					$FormLink = $this->selfURL()."&action=add";
				}
				$ArrVar = array("form"=>"<form name=\"addform\" id=\"addform\" action=\"{$FormLink}\" enctype=\"multipart/form-data\" method=\"post\">", "/form"=>"<input type=\"hidden\" name=\"fromsent\" value=\"yes\" /></form>", "input_submit"=>"<input type=\"submit\" size=\"40\" class=\"inputstyle_03\" value=\"Добавить\">");
				$ArrVar = array_merge($ArrVar, $this->GenerateAddFields($class));
				
				$metatags['title'] = "Добавление ".$this->GetLangVar($class)." :: ".$this->MyConfig['title']['value'];
				// Now as we have our add fields
				/*foreach($this->MyClasses[$class]['form'] as $key=>$value)
				{
					/ here we may define title and description for fields and specify certain fields to display
				}*/
				$PlugDataArr = array();
				foreach($this->Plugins as $key=>$value)
				{
					$PlugDataArr = $this->LoadPlugin($key, "AddItem", $ArrVar);
				}
				$ArrVar = array_merge($ArrVar, $PlugDataArr);
				
				return $this->MakeTemplate($this->MyClasses[$class]['tpl']['add'], $ArrVar, "content");
			}
			else
			{
				
				$VarData = $this->ProcessData($class, "add");
				
				if(!is_array($VarData)||!isset($VarData['id'])||$VarData['id']=="")
				{
					$MesData = $VarData."<br><a href=\"javascript:history.go(-1)\">Назад</a>";
					return $this->MakeTemplate($this->templates['message'], array("msg"=>$MesData), "content");
				}
				else
				{
					$MesData = $this->GetLangVar("msg_".$class."_add");
					$MesData .= "<br><a href=\"javascript:history.go(-1)\">Назад</a> ";
					if(is_array($this->MyClasses[$class]['links']['list']))
					{
						if ($config['allow_alt_url'] == "yes")
						{
							$LinkStr = $this->MyClasses[$class]['links']['list']['sef'];
						}
						else
						{
							$LinkStr = $this->MyClasses[$class]['links']['list']['std'];
						}
					}
					else
					{
						$LinkStr = $this->GetLink($class, "itemshow", array("id"=>$VarData['id']));
					}
					foreach($VarData['data'] as $yel=>$rel)
					{
						$LinkStr = str_replace("{".$yel."}", $rel, $LinkStr);
					}
					$MesData .= " | <a href=\"".$LinkStr."\">Просмотреть</a>";
					
					return $this->MakeTemplate($this->templates['message'], array("msg"=>$MesData), "content");
				}
			}
	}
	function EditItem($class)
	{
		global $metatags;
		if($this->UserCheckRights("admin"))
			{
			$action = $_GET['action'];
			if ($action !== "edit") 
			{
				if(!isset($_GET['id'])||$_GET['id']=="")
				{
					$MesData = "Неправильный запрос вы будете перенаправлены на главну страницу <br /><a href=\"".$this->GetLinkTag("main")."\">На главную</a>";	
					return $this->MakeTemplate($this->templates['message'], array("msg"=>$MesData), "content");
				}
				// Make form link more universal
				if ($config['allow_alt_url'] == "yes")
				{
					$FormLink = $this->selfURL()."/edit";
				}
				else
				{
					$FormLink = $this->selfURL()."&action=edit";
				}
				
				$metatags['title'] = "Изменение ".$this->GetLangVar($class)." :: ".$this->MyConfig['title']['value'];
				$ArrVar = array("form"=>"<form name=\"addform\" id=\"addform\" action=\"$FormLink\" enctype=\"multipart/form-data\" method=\"post\">", "/form"=>"<input type=\"hidden\" name=\"fromsent\" value=\"yes\" /></form>", "input_submit"=>"<input type=\"submit\" size=\"40\" class=\"inputstyle_03\" value=\"Изменить\">");
				$ArrVar = array_merge($ArrVar, $this->GenerateEditFields($class, $_GET['id']));
				///$ArrVar = $this->GenerateEditFields();
				$PlugDataArr = array();
				foreach($this->Plugins as $key=>$value)
				{
					$PlugDataArr = $this->LoadPlugin($key, "EditItem", $ArrVar);
				}
				$ArrVar = array_merge($ArrVar, $PlugDataArr);
				return $this->MakeTemplate($this->MyClasses[$class]['tpl']['edit'], $ArrVar, "content");
			}
			else
			{
				$VarData = $this->ProcessData($class, "edit", $_GET['id']);
			
				if(!isset($VarData['id'])||$VarData['id']=="")
				{
					$MesData = "<br><a href=\"javascript:history.go(-1)\">Назад</a>";
					return $this->MakeTemplate($this->templates['message'], array("msg"=>$MesData), "content");
				}
				else
				{
					$MesData = $this->GetLangVar("msg_".$class."_edit");
					$MesData .= "<br><a href=\"javascript:history.go(-1)\">Назад</a> ";
					if(is_array($this->MyClasses[$class]['links']['list']))
					{
						if ($config['allow_alt_url'] == "yes")
						{
							$LinkStr = $this->MyClasses[$class]['links']['list']['sef'];
						}
						else
						{
							$LinkStr = $this->MyClasses[$class]['links']['list']['std'];
						}
					}
					else
					{
						$LinkStr = $this->GetLink($class, "itemshow", array("id"=>$VarData['id']));
					}
					if(is_array($VarData['data']))
					{
						foreach($VarData['data'] as $yel=>$rel)
						{
							$LinkStr = str_replace("{".$yel."}", $rel, $LinkStr);
						}
					}
					$MesData .= " | <a href=\"".$LinkStr."\">Просмотреть</a>";
					
					return $this->MakeTemplate($this->templates['message'], array("msg"=>$MesData), "content");
				}
			//	header ("Location: ".$this->GetLink($class, "showlist"));
			}
			}
			else
			{
				return $this->MakeTemplate($this->templates['message'], array("msg"=>$this->GetLangVar("msg_restricted")), "content");	
			}
	}
	
	function DeleteItem($class)
	{
		global $metatags;
			if($this->UserCheckRights("admin"))
			{
			$action = $_GET['action'];
			if ($action !== "delete") 
			{
				if(!isset($_GET['id'])||$_GET['id']=="")
				{
					$MesData = "Неправильный запрос вы будете перенаправлены на главну страницу <br /><a href=\"".$this->GetLinkTag("main")."\">На главную</a>";	
					return $this->MakeTemplate($this->templates['message'], array("msg"=>$MesData), "content");
				}
				// Make form link more universal
				if ($config['allow_alt_url'] == "yes")
				{
					$FormLink = $this->selfURL()."/delete";
				}
				else
				{
					$FormLink = $this->selfURL()."&action=delete";
				}
				
				$metatags['title'] = "Удаление ".$this->GetLangVar($class)." :: ".$this->MyConfig['title']['value'];
				$ArrVar = array("form"=>"<form name=\"addform\" id=\"addform\" action=\"{$FormLink}\" method=\"post\">", "/form"=>"</form>", "input_submit"=>"<input type=\"submit\" size=\"40\" class=\"inputstyle_03\" value=\"Удалить\">");
				$ArrVar = array_merge($ArrVar, $this->GenerateDeleteFields($class, $_GET['id']));
				return $this->MakeTemplate($this->MyClasses[$class]['tpl']['delete'], $ArrVar, "content");
			}
			else
			{
				
				$VarData = $this->ProcessData($class, "delete", $_GET['id']);
				
				$MesData = $this->GetLangVar("msg_".$class."_del");
				if(is_array($this->MyClasses[$class]['links']['list']))
				{
					if ($config['allow_alt_url'] == "yes")
					{
						$LinkStr = $this->MyClasses[$class]['links']['list']['sef'];
					}
					else
					{
						$LinkStr = $this->MyClasses[$class]['links']['list']['std'];
					}
					foreach($VarData['data'] as $yel=>$rel)
					{
						$LinkStr = str_replace("{".$yel."}", $rel, $LinkStr);
					}
					$MesData .= "<br><a href=\"".$LinkStr."\">Просмотреть</a>";
				}
				else
				{
					$MesData .= "<br><a href=\"".$this->GetLink($class, "showlist")."\">Просмотреть</a>";
				}
				return $this->MakeTemplate($this->templates['message'], array("msg"=>$MesData), "content");
			}
			}
			else
			{
				return $this->MakeTemplate($this->templates['message'], array("msg"=>$this->GetLangVar("msg_restricted")), "content");	
			}
	}
	
	function MakeTemplate($template, $data, $block, $notMain=false)
	{
		global $tpl;
		if(!empty($tpl)&&$notMain==false)
		{
			$tpl->load_template($template);
			if(is_array($data))
			{
				foreach($data as $key=>$value)
				{
					$tpl->set("{".$key."}", stripslashes($value));
					if(stripslashes($value)==""||stripslashes($value)=="0")
					{
					    $tpl->set_block("'\\[".$key."\\](.*?)\\[/".$key."\\]'si","");
			        }
			        else
			        {
			            $tpl->set_block("'\\[".$key."\\](.*?)\\[/".$key."\\]'si","\\1");
					}
				}
			}
			$tpl->compile($block);
			$tpl->clear();
		}
		else
		{
			return $this->MakeStTemplate($template, $data);
		}
	}
	function MakeStTemplate($template, $data)
	{
		global $config;
		// Get template fileg
		if(!file_exists("templates/".$config['skin']."/".$template))
		{
			$LeftItem =file_get_contents($template);
		}
		else
		{
			$LeftItem =file_get_contents("templates/".$config['skin']."/".$template);
		}
			
		if(is_array($data))
		{
			foreach($data as $key=>$value)
			{
				$LeftItem = str_replace("{".$key."}", $value, $LeftItem);
				if(stripslashes($value)==""||stripslashes($value)=="0")
				{
					$IntStart = strpos($LeftItem, "[".$key."]");
					$IntEdn =  strpos($LeftItem, "[/".$key."]");
					$ToRemove = substr($LeftItem, $IntStart, $IntEdn-$IntStart);
					$ToRemove .= "[/".$key."]";
					
					$LeftItem = str_replace($ToRemove, "", $LeftItem);
			    }
			    else
			    {
			    	$LeftItem = str_replace("[".$key."]", "", $LeftItem);
					$LeftItem = str_replace("[/".$key."]", "", $LeftItem);
				}
			}
		}
		return $LeftItem;
	}
	 function translitIt($str) 
{
    $tr = array(
        "А"=>"a","Б"=>"b","В"=>"v","Г"=>"g",
        "Д"=>"d","Е"=>"e","Ж"=>"j","З"=>"z","И"=>"i",
        "Й"=>"y","К"=>"k","Л"=>"l","М"=>"m","Н"=>"n",
        "О"=>"o","П"=>"p","Р"=>"r","С"=>"s","Т"=>"t",
        "У"=>"u","Ф"=>"f","Х"=>"h","Ц"=>"ts","Ч"=>"ch",
        "Ш"=>"sh","Щ"=>"sch","Ъ"=>"","Ы"=>"yi","Ь"=>"",
        "Э"=>"e","Ю"=>"yu","Я"=>"ya","а"=>"a","б"=>"b",
        "в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"j",
        "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
        "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
        "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
        "ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y",
        "ы"=>"yi","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya", 
        " "=> "_", "."=> "", "/"=> "_"
    );
    return strtr($str,$tr);
}




	function MakeItemList($class, $items, $total, $tpl, $pager="1", $zebra="")
	{
			global $config, $member_id;
			
			// Get template fileg
			if(!file_exists("templates/".$config['skin']."/".$tpl))
			{
				$LeftItem =file_get_contents($tpl);
			}
			else
			{
				$LeftItem =file_get_contents("templates/".$config['skin']."/".$tpl);
			}
			$TotalList = "";
			$TotalPages = ceil((int)$total/$this->MyConfig['limitpage']['value']);
			if(count($items)>0)
			{
				$Cnt = 0;
				
			foreach($items as $row)
			{
				if(!is_array($row))
				{
					continue;
				}				
				// first make all the db aware tags
				$TplChange = $LeftItem;
				//print_r($TplChange);
				// Now load plugin functions for this function and get extra tgs and changes
				if($_GET["op"]=="category" and $row["maindata"]!="") $TplChange = str_replace("{addinfo}", "", $TplChange);
				foreach($this->Plugins as $key=>$value)
				{
					$TplChange = $this->LoadPlugin($key, "MakeItemList", array("text" => $TplChange, "row"=>$row));
				}
				foreach($row as $key=>$value)
				{
					//check value with it's type
					$TplChange = str_replace("{".$key."}", $this->GetTrueValue($value, $this->MyClasses[$class]['db'][$key]), $TplChange);
					$TplChange = str_replace("{".$key."_raw}", $value, $TplChange);
					//print_r($TplChange);
					if(stripslashes($value)==""||stripslashes($value)=="0")
					{
					    $TplChange = preg_replace("'\\[".$key."\\](.*?)\\[/".$key."\\]'si", "", $TplChange);
			        }
			        else
			        {
			        	$TplChange = preg_replace("'\\[".$key."\\](.*?)\\[/".$key."\\]'si","\\1", $TplChange);
					}
				
					
					if($this->MyClasses[$class]['db'][$key]=="img")
					{
						$TplChange = str_replace("{".$key."_thumb}", $this->GetTrueValue($value, $this->MyClasses[$class]['db'][$key]."_thumb"), $TplChange);
						$TplChange = str_replace("{".$key."_thumb_raw}", $this->GetTrueValue($value, $this->MyClasses[$class]['db'][$key]."_thumb_raw"), $TplChange);
						$TplChange = str_replace("{".$key."_raw}", $this->GetTrueValue($value, $this->MyClasses[$class]['db'][$key]."_raw"), $TplChange);
						$TplChange = str_replace("{".$key."_complete}", $this->GetTrueValue($value, $this->MyClasses[$class]['db'][$key]."_complete"), $TplChange);
					}
					
					if($this->MyClasses[$class]['db'][$key]=="unlimg")
					{
						$TplChange = str_replace("{".$key."_thumb}", $this->GetTrueValue($value, $this->MyClasses[$class]['db'][$key]."_thumb"), $TplChange);
						$TplChange = str_replace("{".$key."_complete}", $this->GetTrueValue($value, $this->MyClasses[$class]['db'][$key]."_complete"), $TplChange);
					}
					if($this->MyClasses[$class]['db'][$key]=="multifield")
					{
						$ArrFields = $this->GetTrueValue($value, $this->MyClasses[$class]['db'][$key]);
						foreach($ArrFields as $gre=>$fee)
						{
							$TplChange = str_replace("{".$gre."}", $fee, $TplChange);
						}
					}
					
				}
				//$url = urldecode($this->GetLink($class, "itemshow", array("id"=>$row['id'])));
				//$url = $this->translitIt($url);
								//echo $url;
				// and load url and other non db records
				///if (preg_match('/[^A-Za-z0-9_\-]/', $url)) {
				//$urlstr = $this->translitIt($this->GetLink($class, "itemshow", array("id"=>$row['id'])));
				///urlstr = preg_replace('/[^A-Za-z0-9_\-]/', '', $urlstr);}
				//echo $this->GetLink($class, "itemshow", array("id"=>$row['id']));
				//print_r($TplChange);
				$TplChange = str_replace("{link}", "<a href=\"".$this->GetLink($class, "itemshow", array("id"=>$row['id']))."\">", $TplChange);
				$TplChange = str_replace("{/link}", "</a>", $TplChange);
				if($row["maindata"]=="") $TplChange = str_replace("{mfield_maindata_text_shortinfo}", "", $TplChange);
				
				if($member_id['user_group']=="1")
				{
					$AdmLinksStr =  "<a href=\"".$this->GetLink($class, "edit", array("id"=>$row['id']))."\">".$this->GetLangVar("Edit")."</a> | <a href=\"".$this->GetLink($class, "delete", array("id"=>$row['id']))."\">".$this->GetLangVar("Delete")."</a>";
					
					if(isset($this->ModerSets[''.$class.'']))
					{
						// moderation links go here
						if($row['published']=="1")
						{
							$AdmLinksStr .= " | <a href=\"".$this->GetLink($class, "unpublish", array("id"=>$row['id']))."\">".$this->GetLangVar("unpubl")."</a>"; 
						}
						else
						{
							$AdmLinksStr .= " | <a href=\"".$this->GetLink($class, "publish", array("id"=>$row['id']))."\">".$this->GetLangVar("publ")."</a>";
						}
						
					}
					$TplChange = str_replace("{adm_links}", $AdmLinksStr, $TplChange);
				}
				else
				{
					$TplChange = str_replace("{adm_links}", "", $TplChange);
				}
				// zebra fearue

				if($zebra!="")
				{
					if($Cnt % 2==0)
					{
						$TplChange = str_replace("{zebr_cl}", $zebra, $TplChange);
					}
					else
					{
						$TplChange = str_replace("{zebr_cl}", "", $TplChange);
					}
				}
				// - цена
				
				

				$TotalList .= $TplChange;
				// add admin links
				
				$Cnt++;
			}
			}
			else
			{
				$TotalList = $this->GetLangVar("msg_record_none");	
			}
			//print_r($TotalList);
			/*echo "<br><br><br>";
			echo "mod - ".$_GET["mod"]."<br>";
			echo "categories - ".$_GET["categories"]."<br>";
			echo "op - ".$_GET["op"]."<br>";
			echo "<br><br><br>";
				*/
			//	echo "<pre>";
			///print_r($items);
			//	echo "</pre>";
				/*print_r($TotalList);
			echo "<br><br><br>";*/
			if($_GET["mod"]=="shop" && $_GET["op"]=="showlist" && $_GET["class"]=="multifields")
			{
			$TotalList="";
			$TotalList="<table border='1' width='100%'>";
			$TotalList.="<tr bgcolor='grey'><td>";
			$TotalList.="id";
			$TotalList.="</td><td>";
			$TotalList.="Имя поля";
			$TotalList.="</td><td>";
			$TotalList.="Название";
			$TotalList.="</td><td width='5%'>";
			$TotalList.="Редактировать";
			$TotalList.="</td><td width='5%'>";
			$TotalList.="Удалить";
			$TotalList.="</td></tr>";
			for($i=0;$i<count($items);$i++)
				{
				$TotalList.="<tr><td>";
				$TotalList.=$items[$i]["id"];
				$TotalList.="</td><td>";
				$TotalList.=$items[$i]["title"];
				$TotalList.="</td><td>";
				$TotalList.=$items[$i]["langtitle"];
				$TotalList.="</td><td width='5%'>";
				preg_match_all("/\/(.*?)\?.*?/",$_SERVER['REQUEST_URI'],$mas);
				$lnk = $mas[1][0];
				$TotalList.="<a href='/".$lnk."?mod=shop&class=multifields&op=edit&id=".$items[$i]["id"]."'>Редактировать</a>";
				$TotalList.="</td><td width='5%'>";
				$TotalList.="<a href='/".$lnk."?mod=shop&class=multifields&op=delete&id=".$items[$i]["id"]."'>Удалить</a>";
				$TotalList.="</td></tr>";
				}
			$TotalList.="</table>";
			}
			if($_GET["mod"]=="shop" && $_GET["op"]=="showlist" && $_GET["class"]=="orders")
			{
			$CurrArr = array(
				"unconf" => "В ожидании",
				"paid" => "Оплачен",
				"canceld" => "Отменен",
				);

			$TotalList="";
			$TotalList="<table border='0' width='100%'>";
			for($i=0;$i<count($items);$i++)
				{
				$TotalList.="<tr><td>";
				$TotalList.="<table border='1' width='100%'>";
				$TotalList.="<tr><td>";
				$TotalList.="Заказ №";
				$TotalList.="</td><td>";
				$TotalList.="ФИО";
				$TotalList.="</td><td>";
				$TotalList.="Телефон";
				$TotalList.="</td><td>";
				$TotalList.="eMail";
				$TotalList.="</td><td>";
				$TotalList.="Адрес";
				$TotalList.="</td><td>";
				$TotalList.="Дата";
				$TotalList.="</td><td>";
				$TotalList.="Сумма заказа";
				$TotalList.="</td></tr>";
				$TotalList.="<tr><td>";
				$TotalList.=$items[$i]["ordernum"];
				$TotalList.="</td><td>";
				$TotalList.=$items[$i]["fio"];
				$TotalList.="</td><td>";
				$TotalList.=$items[$i]["telephone"];
				$TotalList.="</td><td>";
				$TotalList.=$items[$i]["email"];
				$TotalList.="</td><td>";
				$TotalList.=$items[$i]["address"];
				$TotalList.="</td><td>";
				$dat = explode(" ",$items[$i]["date"]);
				$TotalList.=$dat[0];
				$TotalList.="</td><td>";
				$TotalList.=$items[$i]["totalsum"];
				$TotalList.="</td></tr>";
				$TotalList.="<tr><td colspan='4'>";
				$TotalList.=$items[$i]["items"];
				$TotalList.="</td><td>";
				$TotalList.=$items[$i]["addinfo"];
				$TotalList.="</td><td>";
				$TotalList.="Статус: " . $CurrArr[$items[$i]["status"]];
				$TotalList.="</td><td>";
				preg_match_all("/\/(.*?)\?.*?/",$_SERVER['REQUEST_URI'],$mas);
				$lnk = $mas[1][0];
				$TotalList.="<a href='/".$lnk."?mod=shop&class=orders&op=delete&id=".$items[$i]["id"]."'>Заказ выполнен</a>";
				$TotalList.="</td></tr>";
				$TotalList.="</table>";
				$TotalList.="<tr><td>&nbsp;</td></tr>";
				$TotalList.="</td></tr>";
				}
			$TotalList.="</table>";
			}
			if($_GET["mod"]=="shop" && $_GET["op"]=="showlist" && $_GET["class"]=="products")
			{
			$TotalList="";
			$TotalList="<table border='1' width='100%'>
			<tr  bgcolor='grey'><td width='6%'>
			id
			</td><td width='25%'>
			Наименование 
			</td><td width='25%'>
			Артикул
			</td><td width='20%'>
			Тайтл
			</td><td width='10%'>
			Цена
			</td><td width='7%'>
			Редактировать
			</td><td width='7%'>
			Удалить
			</td></tr>
			";
			
			for($i=0;$i<count($items);$i++)
			{
			$TotalList.="<tr><td width='6%'>";
			$TotalList.=$items[$i]["id"];
			$TotalList.="</td><td width='25%'>";
			$TotalList.=$items[$i]["title"];
			$TotalList.="</td><td width='25%'>";
			$TotalList.=$items[$i]["artikul"];
			$TotalList.="</td><td width='20%'>";
			//echo $items[$i]["maindata"];
			preg_match_all("/(.*?)\|::\|/",$items[$i]["maindata"],$mas);
			//print_r($mas);
			$lnk = $mas[1][1];
			//echo $lnk;
			$mas = explode("|++|", $lnk);
			//print_r($mas);
			$lnk = $mas[2];	
			$TotalList.=$lnk;
			$TotalList.="</td><td width='10%'>";
			$TotalList.=$items[$i]["price"];
			$TotalList.="</td><td width='7%'>";
			preg_match_all("/\/(.*?)\?.*?/",$_SERVER['REQUEST_URI'],$mas);
						//print_r($mas);
						$lnk = $mas[1][0];
						$TotalList.="<a href='/".$lnk."?mod=shop&class=products&op=edit&id=".$items[$i]["id"]."'>Редактировать</a>";
			$TotalList.="</td><td width='7%'>";
			$TotalList.="<a href='/".$lnk."?mod=shop&class=products&op=delete&id=".$items[$i]["id"]."'>Удалить</a>";
			$TotalList.="</td></tr>";
			
			}
			}
			if($_GET["mod"]=="shop" && $_GET["op"]=="showlist" && $_GET["class"]=="categories")
			{
			$TotalList="";
			$TotalList="<table border='1' width='100%'>
			<tr  bgcolor='grey'><td width='5%'>
			id
			</td><td width='25%'>
			Категория
			</td><td width='25%'>
			Родительская категория
			</td><td width='35%'>
			Описание
			</td><td width='5%'>
			Редактировать
			</td><td width='5%'>
			Удалить
			</td></tr>
			";
			for($i=0;$i<count($items);$i++)
			{
				if($items[$i]["parent"]==0)
				{
				$TotalList.="<tr><td width='5%'>";
				$TotalList.=$items[$i]["id"];
				$TotalList.="</td><td width='25%'>";
					$TotalList.=$items[$i]["title"];
					$TotalList.="</td><td width='25%'>";
					$TotalList.="---";
					$TotalList.="</td><td width='35%'>";
					$TotalList.=$items[$i]["description"];
					$TotalList.="</td><td width='5%'>";
						preg_match_all("/\/(.*?)\?.*?/",$_SERVER['REQUEST_URI'],$mas);
						//print_r($mas);
						$lnk = $mas[1][0];
						$TotalList.="<a href='/".$lnk."?mod=shop&class=categories&op=edit&id=".$items[$i]["id"]."'>Редактировать</a>";
						$TotalList.="</td><td width='5%'>";
						$TotalList.="<a href='/".$lnk."?mod=shop&class=categories&op=delete&id=".$items[$i]["id"]."'>Удалить</a>";
					$TotalList.="</td></tr>";
					for($j=$i+1;$j<count($items);$j++)
					{
						if($items[$i]["id"]==$items[$j]["parent"])
						{
						$TotalList.="<tr><td width='5%'>";
				$TotalList.=$items[$j]["id"];
						$TotalList.="</td><td width='25%'>";
						$TotalList.=$items[$j]["title"];
						$TotalList.="</td><td width='25%'>";
						$TotalList.=$items[$i]["title"];
						$TotalList.="</td><td width='35%'>";
						$TotalList.=$items[$j]["description"];
						$TotalList.="</td><td width='5%'>";
						preg_match_all("/\/(.*?)\?.*?/",$_SERVER['REQUEST_URI'],$mas);
						//print_r($mas);
						$lnk = $mas[1][0];
						$TotalList.="<a href='/".$lnk."?mod=shop&class=categories&op=edit&id=".$items[$j]["id"]."'>Редактировать</a>";
						$TotalList.="</td><td width='5%'>";
						$TotalList.="<a href='/".$lnk."?mod=shop&class=categories&op=delete&id=".$items[$j]["id"]."'>Удалить</a>";
						$TotalList.="</td></tr>";
						}
					}
				}
			}
			$TotalList.="</table>";
			}
			return $TotalList;
	}
	function MakeItemPage($class, $items, $tpl)
	{
		global $config;
		// Get template fileg
		if(!file_exists("templates/".$config['skin']."/".$tpl))
		{
			$TplChange =file_get_contents($tpl);
		}
		else
		{
			$TplChange =file_get_contents("templates/".$config['skin']."/".$tpl);
		}
		foreach($items as $key=>$value)
		{
			if(isset($this->MyClasses[$class]['db'][$key]))
			{
				$TplChange = str_replace("{".$key."}", $this->GetTrueValue($value, $this->MyClasses[$class]['db'][$key]), $TplChange);
			}
			else
			{
				$TplChange = str_replace("{".$key."}", $value, $TplChange);
			}
		}
		
		return $TplChange;
	}
	
	function GetTrueValue($value, $type="")
	{
		// here go special functions	
			if(strstr($type, "Class:"))
			{
				// we have class invokation function, lets break it into pieces
				$Parts = explode(":", $type);
				$ClassTitle = $Parts[1];
				$Function = $Parts[2];
				$Params = "";
				
				if(strstr($Function, '(')==true)
				{
					$OldFunc = $Function;
					$Function = substr($Function, 0, strpos($Function, "("));
					
					$Params = substr($OldFunc, strpos($OldFunc, "(")+1);
					$Params = substr($Params, 0, strlen($Params)-1);
				}
				if($Params!="")
				{
					eval("\$ParamArr = ".$Params.";");
				}
				if($Function=="ItemSelect")
				{
					$SomeRet = $this->ItemSelectGet($ClassTitle, $value);
					return $SomeRet;
				}
				// as we know what we need lets invoke that function of the class
				if($Function!="")
				{
					eval("\$SomeRet = \$this->".$Function."(\$ClassTitle, \$ParamArr);");
					return $SomeRet;
				}
				else
				{
					return false;
				}
			}
			else if(strstr($type, "Plugin:"))
			{
				$Parts = explode(":", $type);
				$ClassTitle = $Parts[1];
				$Function = $Parts[2];
				$RetData = $this->LoadPlugin($ClassTitle, $Function, array("value"=>$value, "mode"=>"data"));
				return $RetData;
			}
			else if(strstr($type, "Select:"))
			{
				$Parts = explode(":", $type);
				$SelectTitle = $Parts[1];
				$Function = $Parts[2];
				
				$RetVar = $this->GetSelectListItem($SelectTitle, $value);
				return $RetVar;
			}
			else if(strstr($type, "Raw:"))
			{
				$Parts = explode(":", $type);
				$SelectTitle = $Parts[1];
				return $Parts[1];
			}
		else
		{	
			// if it's a banal string do it
			switch($type)
			{
				case "varchar":
				{
					return $this->GetSimpleData($value);
					break;
				}
				case "text":
				{
					return $this->GetTextData($value);
					break;
				}
				case "decimal":
				{
					return $this->GetDecimalData($value);
					break;
				}
				case "datetime":
				{
					return $this->GetDateTime($value, "Y-m-d G:i:s");
					break;
				}
				case "date":
				{
					return $this->GetDateTime($value, "Y-m-d");
					break;
				}
				case "unlimg":
				{
					return $this->GetUnlImg($value);
					break;
				}
				case "unlimg_thumb":
				{
					return $this->GetUnlImgThumb($value);
					break;
				}
				case "unlimg_complete":
				{
					return $this->GetUnlImgThumb($value, "complete");
					break;
				}
				case "file":
				{
					return $this->GetFile($value);
					break;
				}
				case "img":
				{
					return $this->GetImg($value);
					break;
				}
				case "img_thumb":
				{
					return $this->GetImgThumb($value);
					break;
				}
				case "img_thumb_raw":
				{
					return $this->GetImgThumb($value, "raw");
					break;
				}
				case "img_complete":
				{
					return $this->GetImgThumb($value, "complete");
					break;
				}
				case "img_raw":
				{
					return $this->GetImg($value, "raw");
					break;
				}
				case "checkbox":
				{
					return $this->GetCheckBox($value);
					break;
				}
				case "multifield":
				{
					return $this->GetMultifield($value);
					break;
				}
				default:
				{
					return $value;
				}
				
			}
		}
	}
	function GetCheckBox($value)
	{
		if($value=="1")
		{
			return $this->GetLangVar("checkbox_yes"); 
		}
		else
		{
			return $this->GetLangVar("checkbox_no"); 
		}
	}
	function GetMultifield($value)
	{
		// here we need to analyze our string, form an array with fields and return it
		$ValsArr = explode("|::|", $value);
		$RetArr = array();
		foreach($ValsArr as $field)
		{
			if($field!="")
			{
				$DataField = explode("|++|", $field);
				$FTitle = $DataField[0]; // field title
				$FTtype = $DataField[1]; // field type
				$FValue = $DataField[2]; // field value
				$FinalVal = $this->GetTrueValue($FValue, $FTtype);
				$RetArr[''.$FTitle.''] = $FinalVal;
			}
		}
		return $RetArr;
	}
	function GetSimpleData($value)
	{
		return $value;
	}
	function GetTextData($value)
	{
		// replace \n to <br>
		$value = str_replace("\n", "<br>", $value);
		$value = stripslashes($value);
		
		return $value;
	}
	function GetDecimalData($value)
	{
		// replace \n to <br>
		$value = str_replace(".00", "", $value);
		// Now insert spaces into value
		$ToDot = $value;
		if(strstr($value, "."))
		{
			$ToDot = substr($value, 0, strpos($value, "."));
		}
		
		if(strlen($ToDot)>3)
		{
			$Intrer = strlen($ToDot)-1;
			$Cnt = 1;
			$FinalStr = "";
			
			for($i=$Intrer; $i>=0; $i--)
			{
				$FinalStr = $ToDot[$i].$FinalStr;
				if($Cnt==3)
				{
					$FinalStr = " ".$FinalStr;
					$Cnt = 1;
				}
				else
				{
					$Cnt++;
				}
			}
			$value = $FinalStr;
		}
		return $value;
	}
	function GetDateTime($value, $format)
	{
		$TimeStr = strtotime($value);
		return date($format, $TimeStr);
	}
	function GetFile($value, $type="")
	{
		if($value=="")
		{
			return "";
		}
		else
		{
			return $this->MyStorage."files/".$value;
		}
	}
	function GetImg($value, $type="")
	{
		if($value=="")
		{
			return "";
		}
		else
		{
			if($type=="raw")
			{
				return $this->MyStorage."images/".$value;
			}
			else
			{
				return "<img src=\"".$this->MyStorage."images/".$value."\">";
			}
			
		}
	}
	function GetImgThumb($value, $type="")
	{
		if($value=="")
		{
			return "";
		}
		else
		{
			$ImgParts = explode("/", $value);
			if($type=="raw")
			{
				return $this->MyStorage."images/".$ImgParts[0]."/".$ImgParts[1]."/thumb/".$ImgParts[2];
			}
			else if($type=="complete")
			{
				return "<a href=\"".$this->MyStorage."images/".$value."\" onClick=\"return hs.expand(this)\"><img border=\"0\" src=\"".$this->MyStorage."images/".$ImgParts[0]."/".$ImgParts[1]."/thumb/".$ImgParts[2]."\"></a>";
			}
			else
			{
				return "<img src=\"".$this->MyStorage."images/".$ImgParts[0]."/".$ImgParts[1]."/thumb/".$ImgParts[2]."\">";
			}
		}
	}
	function GetUnlImg($value, $type="complete")
	{
		if($value=="")
		{
			$RetStr =array();
			if($type=="complete")
			{
				return "<img src=\"".$this->MyStorage."images/no_full_photo.gif\">";
			}
			else
			{
				$RetStr[] = "<img src=\"".$this->MyStorage."images/no_photo.gif\">";
			}
			return $RetStr;
		}
		else
		{
			$ImgArr = explode(":|:", $value);
			$RetStr = array();
			$Cnt = "0";
			foreach($ImgArr as $img)
			{
				$ImgParts = explode("/", $img);
				if($type=="raw")
				{
					$RetStr[] = $this->MyStorage."images/".$ImgParts[0]."/".$ImgParts[1]."/thumb/".$ImgParts[2];
				}
				else if($type=="complete")
				{
					if($Cnt=="0")
					{
						$ImgExer = explode(".", $ImgParts[2]);
						$ImgParts[2] = $ImgExer[0]."_ext_".$this->MyConfig['multiimg_width']['value'].".".$ImgExer[1];
						$RetStr[] = "<div class=\"mainunlph\"><a href=\"".$this->MyStorage."images/".$img."\" onClick=\"return hs.expand(this)\"><img border=\"0\" src=\"".$this->MyStorage."images/".$ImgParts[0]."/".$ImgParts[1]."/thumb/".$ImgParts[2]."\"></a></div>";
					}
					else
					{
							$RetStr[] = "<a href=\"".$this->MyStorage."images/".$img."\" onClick=\"return hs.expand(this)\"><img border=\"0\" src=\"".$this->MyStorage."images/".$ImgParts[0]."/".$ImgParts[1]."/thumb/".$ImgParts[2]."\"></a>";
					}
					
				
				}
				else
				{
					$RetStr[] = "<img src=\"".$this->MyStorage."images/".$ImgParts[0]."/".$ImgParts[1]."/thumb/".$ImgParts[2]."\">";
				}
				$Cnt++;
			}
			$RetFuck = implode(" ", $RetStr);
			return $RetFuck;
		}
	}
	function GetUnlImgThumb($value, $type="")
	{
		if($value=="")
		{
			return "<img src=\"".$this->MyStorage."images/no_photo.gif\">";
		}
		else
		{
			$ImgArr = explode(":|:", $value);
			$value = $ImgArr[0];
			$ImgParts = explode("/", $value);
			if($type=="raw")
			{
				return $this->MyStorage."images/".$ImgParts[0]."/".$ImgParts[1]."/thumb/".$ImgParts[2];
			}
			else if($type=="complete")
			{
				$ImgExer = explode(".", $ImgParts[2]);
				$ImgParts[2] = $ImgExer[0]."_ext_".$this->MyConfig['multiimg_width']['value'].".".$ImgExer[1];
				return "<div class=\"mainunlph\"><a href=\"".$this->MyStorage."images/".$ImgArr[0]."\" onClick=\"return hs.expand(this)\"><img border=\"0\" src=\"".$this->MyStorage."images/".$ImgParts[0]."/".$ImgParts[1]."/thumb/".$ImgParts[2]."\"></a></div>";
			}
			else
			{
				return "<img src=\"".$this->MyStorage."images/".$ImgParts[0]."/".$ImgParts[1]."/thumb/".$ImgParts[2]."\">";
			}
		}
	}
	function MakePages($class, $TotalPages, $CurPage, $kind="teams")
	{
		global  $config;
		// count our pages 
		$TotalPagesS = ceil(((int)$TotalPages/(int)$this->MyConfig['limitpage']['value']));
		// Get page styling
		$BodyOf =file_get_contents("templates/".$config['skin']."/".$this->MyClasses[$class]['tpl']['pager']);
		// get template parts
		$ItemTpl = $this->GetBetween("[item]", "[/item]", $BodyOf);
		$SelTpl = $this->GetBetween("[sel]", "[/sel]", $BodyOf);
		$BodyTpl = $this->GetBetween("[body]", "[/body]", $BodyOf);
		
		
		
		if((int)$TotalPages>1)
		{
	    	$CurPage = (int)$CurPage-1;
	    }
	    else
	    {
	        $CurPage = '0';
	    }
	    $CurOffset = (int)$CurPage*(int)$this->MyConfig['limitpage']['value'];
		// теперь сделаем массив наших страничек
		$PagesArr = array();
		
		for($k=1; $k<=(int)$TotalPages; $k++)
		{
		        $PagesArr[] = $k;
		}
		$PageStr = "";
		
		foreach($PagesArr as $page)
		{
			if((int)$page==(int)$CurPage+1)
		    {
				$PageStr .= str_replace("{page}", $page, $SelTpl);
			}
			else
			{	
				$rsTpl = $ItemTpl;
				$rsTpl = str_replace("{page}", $page, $rsTpl);
				$pageArr = array();
				// get all GET vars and add page
				foreach($_GET as $reso=>$kolu)
				{
					if($reso!="do"&&$reso!="class"&&$reso!="page"&&$reso!="op")
					{
						$pageArr[''.$reso.''] = $kolu;
					}
				}
				$pageArr['page'] = $page;
				$rsTpl = str_replace("{link}", $this->GetLink($_GET['class'], $_GET['op'], $pageArr), $rsTpl);
				$PageStr .= $rsTpl;
			}
		}
		
		if($PageStr!="")
		{
			
		    $PageStr = str_replace("{pages}", $PageStr, $BodyTpl);
		}
		
		return $PageStr;
	}
	// Get admin settings
	function AdminPage()
	{
		// 
	}
	function GetAdminLinks($class, $params)
	{
		global $config;
		if($this->UserCheckRights("admin"))
		{			
			// we need to get all the classes and adding links to them
			$LinkTags['adm_cfg_lnk'] = "<a href=".$this->GetLinkTag("adm_cfg_lnk").">".$this->GetLangVar("CfgAdmin")."</a>";
			// Now get each class and build class blocks
			foreach($this->MyClasses as $MyClass)
			{
				// Block consists of Add|ShowList links of the class
				$ClassBlock = $MyClass['lang']['title'].": ";
				$ClassBlock .= "<a href=".$this->GetLink($MyClass['title'], "additem").">".$this->GetLangVar("AddLink")."</a> | ";
				$ClassBlock .= "<a href=".$this->GetLink($MyClass['title'], "showlist").">".$this->GetLangVar("ShowList")."</a>";
				if(isset($this->ModerSets[''.$MyClass['title'].'']))
				{
					$ClassBlock .= " | <a href=".$this->GetLink($MyClass['title'], "showlist", array("page"=>"1", "moder"=>"1")).">".$this->GetLangVar("ModerList")."</a>";
				}
				
				$ClassBlock .= "<hr />";
				
				$LinkTags['class_block'] .= $ClassBlock; 
				
			}
			
			//echo "<a href=\"MyLink.htm\">Админ</a> | <a href=\"Cool.htm\">Круто</a> |";
		}
		else
		{
			$LinkTags['adm_cfg_lnk'] = "";
			$LinkTags['class_block'] = "";
		}
		$RetStr = $this->MakeItemPage("base", $LinkTags, $this->templates['cfg_lnk']);
		return $RetStr;
	}
	function PrepareDbTags($class, $type="static", $id="")
	{
		global $config, $lang;
		$RetArr = array();
		switch($type)
		{
			case "static":
			{
				$ItemData = $this->GetRecord($class, $id);
				$ItemData = $ItemData[0];
				
				foreach($ItemData as $key=>$value)
				{
					$RetArr["".$key.""] = $this->GetTrueValue($value, $this->MyClasses[$class]['db'][$key]);
					if($this->MyClasses[$class]['db'][$key]=="img")
					{
						$RetArr["".$key."_thumb"] = $this->GetTrueValue($value, $this->MyClasses[$class]['db'][$key]."_thumb");
						$RetArr["".$key."_thumb_raw"] = $this->GetTrueValue($value, $this->MyClasses[$class]['db'][$key]."_thumb_raw");
						$RetArr["".$key."_raw"] = $this->GetTrueValue($value, $this->MyClasses[$class]['db'][$key]."_raw");
						$RetArr["".$key."_complete"] = $this->GetTrueValue($value, $this->MyClasses[$class]['db'][$key]."_complete");
					}
					if($this->MyClasses[$class]['db'][$key]=="unlimg")
					{
						$RetArr["".$key."_thumb"] = $this->GetTrueValue($value, $this->MyClasses[$class]['db'][$key]."_thumb");
					}
				}
				break;	
			}
			case "form":
			{
				if($id=="")
				{
					$ItemData = array();
				}
				else
				{
					$ItemData = $this->GetRecord($class, $id);
				}
				if(isset($ItemData[0]))
				{
					$ItemData = $ItemData[0];
				}
				
				// For comments implementation
				if($class=="comments")
				{
					if(!isset($ItemData['autor'])||$ItemData['autor']=="")
					{
						global $member_id;
						$ItemData['autor'] = $member_id['name'];
						$ItemData['email'] = $member_id['email'];
					}
				}
				
				foreach($this->MyClasses[$class]['db'] as $key=>$value)
				{
					if(isset($ItemData[$key]))
					{
						$dataval = $ItemData[$key];
					}
					else
					{
						$dataval = "";
					} 
					$NecVal = $this->GetFormValue($key, $dataval, $value);
					
					if($NecVal!="")
					{
						$RetArr["input_".$key.""] = $NecVal;
					}
					
				}
				// and extra data
				
				// captcha!
				if (($this->MyConfig['captcha']['value']==1)&&(in_array($class, $this->CaptchaList)==true)) 
				{
		            $path = parse_url($config['http_home_url']);
		            
		            $CaptArr["captcha"] = "<span id=\"dle-captcha\"><img src=\"".$path['path']."engine/modules/antibot.php\" alt=\"{$lang['sec_image']}\" border=\"0\" /><br /><a onclick=\"reload(); return false;\" href=\"#\">{$lang['reload_code']}</a></span>";
		            
		            $CaptArr["captcha"] .= <<<HTML
<script language='JavaScript' type="text/javascript">
function reload () {

        var rndval = new Date().getTime();

        document.getElementById('dle-captcha').innerHTML = '<img src="{$path['path']}engine/modules/antibot.php?rndval=' + rndval + '" border="0" width="120" height="50"><br /><a onclick="reload(); return false;" href="#">{$lang['reload_code']}</a>';

};
</script>
HTML;
					$RetArr["captcha"] =  $this->MakeItemPage($class, $CaptArr, $this->templates['captcha']);
		        } 
				else 
				{
		        	$RetArr["captcha"] = "";
		        }
				break;
			}
		}
		return $RetArr;
	}
	function GetFormValue($title, $value, $type="")
	{
		// here go special functions
		if(strstr($type, "Class:"))
		{
				// we have class invokation function, lets break it into pieces
				$Parts = explode(":", $type);
				$ClassTitle = $Parts[1];
				$Function = $Parts[2];
				$Params = "";
				
				if(strstr($Function, '(')==true)
				{
					$OldFunc = $Function;
					$Function = substr($Function, 0, strpos($Function, "("));
					
					$Params = substr($OldFunc, strpos($OldFunc, "(")+1);
					$Params = substr($Params, 0, strlen($Params)-1);
				}
				if($Params!="")
				{
					eval("\$ParamArr = ".$Params.";");
				}
				if($Function=="ItemSelect")
				{
					eval("\$Somewhat = \$this->".$Function."(\$ClassTitle, \$title, \$value);");
					return $Somewhat;
				}
				// as we know what we need lets invoke that function of the class
				if($Function!="")
				{
					if($ParamArr!="")
					{	
						eval("\$Somewhat = \$this->".$Function."(\$ClassTitle, \$ParamArr);");
					}
					else
					{
						eval("\$Somewhat = \$this->".$Function."(\$ClassTitle, \"\");");
					}
					return $Somewhat;
				}
				else
				{
					return false;
				}
			}
		else if(strstr($type, "Plugin:"))
		{
			$Parts = explode(":", $type);
			$ClassTitle = $Parts[1];
			$Function = $Parts[2];
			return $this->LoadPlugin($ClassTitle, $Function, array("value"=>$value, "mode"=>"form"));
		}
		else if(strstr($type, "RSelect:"))
		{
			$Parts = explode(":", $type);
			$SelectTitle = $Parts[1];
			$Function = $Parts[2];
			
			return $this->GetRSelectList($SelectTitle, $title, $value);
		}
		else if(strstr($type, "MSelect:"))
		{
			$Parts = explode(":", $type);
			$SelectTitle = $Parts[1];
			$Function = $Parts[2];
			
			return $this->GetMSelectList($SelectTitle, $title, $value);
		}
		else if(strstr($type, "Select:"))
		{
			$Parts = explode(":", $type);
			$SelectTitle = $Parts[1];
			$Function = $Parts[2];
			
			return $this->GetSelectList($SelectTitle, $title, $value);
		}
		else
		{	
			// if it's a banal string do it
			switch($type)
			{
				case "varchar":
				{
					return $this->GetInputField($title, $value);
					break;
				}
				case "decimal":
				{
					$value = str_replace(".00", "", $value);
					$value = str_replace(" ", "", $value);
					return $this->GetInputField($title, $value);
					break;
				}
				case "text":
				{
					return $this->GetTextarea($title, $value);
					break;
				}
				case "bigtext":
				{
					return $this->GetBigTextarea($title, $value);
					break;
				}
				case "datetime":
				{
					return $this->GetDateTimeField($title, $value, "Y-m-d G:i:s");
					break;
				}
				case "date":
				{
					return $this->GetDateField($title, $value, "Y-m-d");
					break;
				}
				case "img":
				{
					return $this->GetImgField($title, $value);
					break;
				}
				case "file":
				{
					return $this->GetFileField($title, $value);
					break;
				}
				case "unlimg":
				{
					return $this->GetUnlimImgField($title, $value);
					break;
				}
				case "range":
				{
					return $this->GetRangeField($title, $value);
				}
				case "checkbox":
				{
					return $this->GetCheckBoxField($title, $value);
				}
				case "multifield":
				{
					return $this->GetMultiFieldField($title, $value);
				}
				default:
				{
					
				}
				
			}
		}
	}
	
	// Field making functions
	function GetInputField($title, $value)
	{
		return "<input name=\"$title\" type=\"text\" value=\"$value\" size=\"40\">";
	}
	function GetMultiFieldField($title, $value)
	{
		global $config;
		if($value=="")
		{
			$value = $this->MultiFields[''.$title.''];
		}
		$RetStr = "";
		// Get templae for item
		$TemplateWhole = $this->templates['formitem'];
		if(!file_exists("templates/".$config['skin']."/".$TemplateWhole))
		{
			$ItemTpl =file_get_contents($TemplateWhole);
		}
		else
		{
			$ItemTpl =file_get_contents("templates/".$config['skin']."/".$TemplateWhole);
		}
		// get list of fields for current item
		$MyFields = $this->GetRecords("multifields", array("ftitle"=>$title));
		$grArr = array();
		foreach($MyFields as $field)
		{
			$grArr[''.$field['title'].''] = $field;
		}
		
		$ValsArr = explode("|::|", $value);
		$RetArr = array();
		foreach($ValsArr as $field)
		{
			$RfStr = $ItemTpl;
			$DataField = explode("|++|", $field);
			$FTitle = $DataField[0]; // field title
			$FTtype = $DataField[1]; // field type
			$FValue = $DataField[2]; // field value
			$FormCode = $this->GetFormValue($FTitle, $FValue, $FTtype);
			$RfStr = str_replace("{field}", $FormCode, $RfStr);
			$RgTitle = substr($FTitle, strripos($FTitle, "_")+1);
			if(is_array($grArr[''.$RgTitle.'']))
			{
				$RfStr = str_replace("{lang_title}", $grArr[''.$RgTitle.'']['langtitle'], $RfStr);
				$RetStr .= $RfStr;
			}
			
		}
		return $RetStr;
	}
	// here we need to analyze our string, form an array with fields and return it
		
	function GetCheckBoxField($title, $value)
	{
		$AddStr = "";
		if($value=="1")
		{
			$AddStr = "checked";
		}
		return "<input name=\"$title\" type=\"checkbox\" $AddStr value=\"1\" size=\"40\">";
	}
	
	function GetTextarea($title, $value)
	{
		include_once ENGINE_DIR.'/classes/parse.class.php';
		$parse = new ParseFilter(Array(), Array(), 1, 1);
		
		if(in_array($title, $this->BBParsed))
		{
			$value = $parse->decodeBBCodes($value, true, "yes");
		}
		
		return "<textarea name=\"$title\" id=\"$title\" cols=\"30\" rows=\"7\">$value</textarea>";
	}
	function GetBigTextarea($title, $value)
	{
		return "<textarea name=\"$title\" id=\"$title\" cols=\"50\" rows=\"14\">$value</textarea>";
	}
	function GetDateTimeField($title, $value)
	{
		global $lang;
		// Fucking checkboxes
		$CheckBoxes = "";
		$Rien = false;
		if($value=="")
		{
			$Rien = true;
			$value = date("Y-m-d G:i");
		}
		
		if($Rien == true)
		{
			$CheckBoxes = '&nbsp;<input type="checkbox" name="allow_date" value="yes" onChange="javascript:document.getElementById(\''.$title.'_c\').value = \''.date("Y-m-d G:i").'\';" checked>&nbsp;'.$lang['edit_jdate'].'<a href="#" class="hintanchor" onMouseover="showhint('.$lang['hint_calendar'].', this, event, \'320px\')">[?]</a>';	
		}
		else
		{
			$CheckBoxes = '&nbsp;<input type="checkbox" id="'.$title.'_allow_date" name="'.$title.'_allow_date" value="yes" onChange="javascript:document.getElementById(\''.$title.'_allow_now\').disabled = this.checked";" checked>&nbsp;'.$lang['edit_ecal'].'&nbsp;<input type="checkbox" disabled="true" name="'.$title.'_allow_now" id="'.$title.'_allow_now" value="yes" onChange="javascript:document.getElementById(\''.$title.'_c\').value = \''.date("Y-m-d G:i").'\';">&nbsp;'.$lang['edit_jdate'].'<a href="#" class="hintanchor" onMouseover="showhint('.$lang['hint_calendar'].', this, event, \'320px\')">[?]</a>';	
		}
		$CheckBoxes = "";
		return '<input type="text" name="'.$title.'" id="'.$title.'_c" size="20" value="'.$value.'"  class=edit>
<img src="engine/skins/images/img.gif"  align="absmiddle" id="'.$title.'_trigger_c" style="cursor: pointer; border: 0" title="'.$lang['edit_ecal'].'"/> '.$CheckBoxes.'
<script type="text/javascript">
    Calendar.setup({
        inputField     :    "'.$title.'_c",     // id of the input field
        ifFormat       :    "%Y-%m-%d %H:%M",      // format of the input field
        button         :    "'.$title.'_trigger_c",  // trigger for the calendar (button ID)
        align          :    "Br",           // alignment
		timeFormat     :    "24",
		showsTime      :    true,
        singleClick    :    true
    });
</script>';
	/*	return "<input name=\"$title\" id=\"$title\" type=\"text\" size=\"40\"><a href=\"javascript:void(0)\" onclick=\"if(self.gfPop)gfPop.fPopCalendar(document.addform.$title);return false;\" ><img name=\"popcal\" align=\"absmiddle\" src=\"/engine/classes/cal/calbtn.gif\" width=\"34\" height=\"22\" border=\"0\" alt=\"Календарь\"></a>
		
		<iframe width=188 height=166 name=\"gToday:datetime:agenda.js:gfPop:plugins_time.js\" id=\"gToday:datetime:agenda.js:gfPop:plugins_time.js\" src=\"/engine/classes/cal/ipopeng.htm\" scrolling=\"no\" frameborder=\"0\" style=\"visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;\">
</iframe>
		";*/
	}
	function GetDateField($title, $value)
	{
		return "<input name=\"$title\" id=\"$title\" type=\"text\" size=\"40\"><a href=\"javascript:void(0)\" onclick=\"if(self.gfPop)gfPop.fPopCalendar(document.addform.$title);return false;\" ><img name=\"popcal\" align=\"absmiddle\" src=\"/engine/classes/cal/calbtn.gif\" width=\"34\" height=\"22\" border=\"0\" alt=\"Календарь\"></a>
		
		<iframe width=188 height=166 name=\"gToday:normal:agenda.js\" id=\"gToday:normal:agenda.js\" src=\"/engine/classes/cal/ipopeng.htm\" scrolling=\"no\" frameborder=\"0\" style=\"visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;\">
</iframe>
		";
	}
	function GetImgField($title, $value)
	{
		$RetStr = "";
		if($value!="")
		{
			$RetStr .= $this->GetImgThumb($value);
			$RetStr .= " <input type=\"checkbox\" name=\"{$title}_deleteimg\" /> Удалить?";
		}
		$RetStr .= "<br><input name=\"$title\" type=\"file\" size=\"27\">";
		return $RetStr;
	}
	function GetFileField($title, $value)
	{
		$RetStr = "";
		if($value!="")
		{
			// Get file name and size
			$RetStr .= "Текущий файл";
			$RetStr .= " <input type=\"checkbox\" name=\"{$title}_deleteimg\" /> Удалить?";
		}
		$RetStr .= "<br><input name=\"$title\" type=\"file\" size=\"27\">";
		return $RetStr;
	}
	function GetUnlimImgField($title, $value)
	{
		$RetStr = "
		<script type=\"text/javascript\">
		function AddField{$title}()
		{
			var notationS = document.getElementById(\"{$title}imgcontainer\");
			 
			 var el = document.createElement('input');
		     el.setAttribute('type', 'file');
		     el.setAttribute('name', '{$title}[]');
		     el.setAttribute('size', '27');
		     el.setAttribute('value', '');
		     notationS.appendChild(el);
			var eld = document.createElement('br');
			notationS.appendChild(eld);
			
		}
		</script>
		";
		if($value!="")
		{
			$RetStr .= "<hr />";
			// get all images and make a list of iamges
			$ImagesArr = explode(":|:", $value);
			$frt = 0;
			foreach($ImagesArr as $imgthumb)
			{
				$RetStr .= $this->GetImgThumb($imgthumb);
				$RetStr .= " <input type=\"checkbox\" name=\"{$title}_deleteimg_{$frt}\" /> Удалить? <br />";
				$frt++;
			}
			$RetStr .= "<hr />";
			$RetStr .= "<input name=\"{$title}_storage\" type=\"hidden\" value=\"$value\">";
		}
		$RetStr .= "<br><input name=\"{$title}[]\" type=\"file\" size=\"27\">";
		 
		$RetStr .= "<div id=\"{$title}imgcontainer\">";
		$RetStr .= "</div>"; 
		$RetStr .= "<br><input type=\"button\" value=\"".$this->GetLangVar("addimgfield")."\" onClick=\"javascript:AddField{$title}();return false;\">";
		return $RetStr;
		
	}
	function ProcessData($class, $type, $id="")
	{
		global $member_id, $db, $member_db;
		// get each post element and make up input
		$DataArr = array();
		include_once ENGINE_DIR.'/classes/parse.class.php';
		$parse = new ParseFilter(Array(), Array(), 1, 1);
		
		foreach($_POST as $key=>$value)
		{
			// make up an array of items to do with records
			if(isset($this->MyClasses[$class]['db'][$key]))
			{
				// if we haveplugin item than get it's value by plugin
				if(strstr($this->MyClasses[$class]['db'][$key], "Plugin"))
				{
					$Parts = explode(":", $this->MyClasses[$class]['db'][$key]);
					$ClassTitle = $Parts[1];
					$Function = $Parts[2];
					$RetData = $this->LoadPlugin($ClassTitle, $Function, array("value"=>$value, "mode"=>"data"));
					if(is_array($RetData))
					{
						$DataArr = array_merge($DataArr, $RetData);
					}
				}
				else if($this->MyClasses[$class]['db'][$key]=="decimal")
				{
					$value = str_replace(",", ".", $value);
					$value = str_replace(" ", "", $value);
					$value = str_replace(".00", "", $value);
					$DataArr[$key] = $value;
				}
				else if(strstr($this->MyClasses[$class]['db'][$key], "MSelect"))
				{
					$DataArr[$key] = json_encode($value);
				}
				else if(in_array($key, $this->BBParsed))
				{
					$DataArr[$key] = $parse->BB_Parse($value);
				}
				else
				{
					$DataArr[$key] = $value;
				}
			}
			else if(strstr($key, "mfield_"))
			{
					// we have a mulifield
					$RealKeyArr = str_replace("mfield_", "", $key);
					$FieldTitle = substr($RealKeyArr, 0, strpos($RealKeyArr, "_"));
					$RealKeyArr = str_replace($FieldTitle."_", "", $RealKeyArr);
					$TypeField = substr($RealKeyArr, 0, strpos($RealKeyArr, "_"));
					$ClearTile = substr($RealKeyArr, 0, strripos($key, "_")+1);
					// now make final string
					$StrField = $key."|++|".$TypeField."|++|".$value."|::|";
				/*	echo $StrField;
					$dbserver="localhost";
$dbname="testing";
$dbuser="testing";
$dbpass="alltesting";
$chandle = mysql_connect($dbserver, $dbuser, $dbpass) or die("Connection Failure to Database");
mysql_select_db($dbname, $chandle) or die ($dbname . " Database not found." . $dbuser);
					$result = mysql_query('SELECT id FROM dle_shop WHERE artikul='.$_POST["artikul"]);
					$row = mysql_fetch_assoc($result);
				echo $row["id"]."<br>";
					//print_r($_POST["artikul"]);
					echo $_POST["mfield_maindata_text_shortinfo"];*/
					if(!isset($DataArr[$FieldTitle]))
					{
						$DataArr[''.$FieldTitle.''] = $StrField;
					}
					else
					{
						$DataArr[''.$FieldTitle.''] .= $StrField;
					}
			}
		}
		foreach($_FILES as $key=>$value)
		{
			if($this->MyClasses[$class]['db'][$key]=="img")
			{
				if((int)$value['size']>1024*(int)$this->MyConfig['max_img_size']['value'])
				{
					return "Размер загружаемой картинки (".$value['name'].") превышает допустимый размер файла";
				}
			}
		}
		if($type!="delete")
		{
			// check thr e fileds 
			$CheckRes = $this->FieldsCheck($class, $DataArr);
			if($CheckRes!="")
			{
				return $CheckRes;
			}
		}
		if($type=="add"||$type=="edit")
		{
			foreach($this->MyClasses[$class]['db'] as $key=>$value)
			{
				if(!isset($DataArr[$key]))
				{
					if($this->MyClasses[$class]['db'][$key]=="checkbox")
					{
						if(isset($_POST[$key])&&$_POST[$key]!="")
						{
							$DataArr[$key] = "1";
						}
						else
						{
							$DataArr[$key] = "0";
						}
					}
				}
			}
		}
		if($type=="add")
		{
			// Now add fields which are not in the 
			foreach($this->MyClasses[$class]['db'] as $key=>$value)
			{
				if(!isset($DataArr[$key]))
				{
					switch($this->MyClasses[$class]['db'][$key])
					{
						case "CurrentUser":
						{
							if(isset($member_db[10])&&$member_db[10]!="")
							{
								
							}
							else
							{
								$member_db[10] = $member_id['user_id'];
							}
							$DataArr[$key] = $member_db[10];
							break;
						}
						case "date":
						{
							$DataArr[$key] = date("Y-m-d H:i:s");
							break;
						}
						case "ip":
						{
							$DataArr[$key] = $_SERVER['REMOTE_ADDR'];
							break;
						}
					}
				}
			}
		}
		if($type=="delete")
		{
			$SetDataArr = $this->GetRecord($class, $id);
			$SetDataArr = $SetDataArr[0];
		}
		switch($type)
		{
			case "add":
			{
				$id = $this->AddRecord($class, $DataArr);
				break;
			}
			case "edit":
			{
				$this->EditRecord($class, $id, $DataArr);
				break;
			}
			case "delete":
			{
				$this->DeleteRecord($class, $id);
				break;
			}
		}
		if($type!="delete")
		{
			$SetDataArr = $this->GetRecord($class, $id);
			$SetDataArr = $SetDataArr[0];
		}
		if($id=="exists")
		{
			return $this->GetLangVar("msg_record_exists");
		}
		
		if($type!="delete")
		{
			foreach($_FILES as $key=>$value)
			{
				// make up an array of items to do with records
				if(isset($this->MyClasses[$class]['db'][$key]))
				{
					$TmpImg = $this->MakeProcessor($class, $key, $value, $id, $_POST);
					if($TmpImg!="")
					{
						$DataArr[$key] = $TmpImg;
					}
					else
					{
						// we need to check delete flag, if it is there, add empty string and del the file
						if($_POST[$key."_deleteimg"])
						{
							// delet
							$DataArr[$key] = "";
							$this->DeleteFile($class, $key, $id);
						}
						if($this->MyClasses[$class]['db'][$key]=="unlimg")
						{
							$DataArr[$key] = $TmpImg;
						}
						
					}
					// search for deleting checkpoints
				}	
			}
			
			$this->EditRecord($class, $id, $DataArr);
		}
		// if isset ID - get it
		
		$RetArr = array(
		"id" => $id,
		"data" => $SetDataArr
		);
		return $RetArr;
		
	}
	function IfUserAllowed($rights)
	{
		if($rights['allowed'] == "all")
		{
			return true;
		}
	}
	function ShowSearchForm()
	{
		return "";
	}	
	function AddLink($tag, $url, $sefurl, $rights)
	{
		$this->MyLinks[$tag]['url'] = $url;
		$this->MyLinks[$tag]['sefurl'] = $sefurl;
		$this->MyLinks[$tag]['rights'] = $rights;
	}
	function GetLinkTag($tag)
	{
		global $config;
		if ($config['allow_alt_url'] == "yes")
		{
			return $this->MyLinks[$tag]['sefurl'];
		}
		else
		{
			return $this->MyLinks[$tag]['url'];
		}
	}
	function GetLink($class, $type, $data=array(), $kind="")
	{
		
		global $config;
		if($kind=="")
		{
			if(strstr($_SERVER['REQUEST_URI'], $config['admin_path']))
			{
				$kind="admin";
			}
			else
			{
				$kind="index";
			}
		}
		if ($config['allow_alt_url'] == "yes")
		{
			if($kind=="admin")
			{
				$RetStr = "/".$config['admin_path']."?mod=".$this->MyTitle."&class=$class&op=$type";
				foreach($data as $key=>$value)
				{
					if($value!="")
					{
						$RetStr .= 	"&$key=$value";
					}
				}
			}
			else
			{
				
				$RetStr = "/".$this->MyTitle;
				if($class!="")
				{
					$RetStr .= "/$class";
				}
				if($type!="")
				{					
					$RetStr .= "/$type";
				}
				if(is_array($data))
				{
					foreach($data as $key=>$value)
					{
						
						if($this->MyClasses[$class]['seo']!="")
						{
							if($key=="id")
							{
								if($type=="itemshow")
								{
									// Get record data
									$UnsereItem = $this->GetRecord($class, $value);
									$UnsereItem = $UnsereItem[0];
									$SEOString = $this->MyClasses[$class]['seo'];
									if(is_array($UnsereItem))
									{
										foreach($UnsereItem as $rey=>$ralit)
										{
											$SEOString = str_replace('{'.$rey.'}', $this->GetTrueValue($ralit, $this->MyClasses[$class]['db'][$rey]), $SEOString);
										}
									}
									
									$value = $value."-".urlencode($SEOString);
								}
							}
						}
						if($value!=""||$value=="0")
						{
							$RetStr .= 	"/$value";
						}
					}
				}
			}
		}
		else
		{
			if($kind=="admin")
			{
				$RetStr = "/".$config['admin_path']."?mod=".$this->MyTitle."&class=$class&op=$type";
			}
			else
			{
				$RetStr = "/index.php?do=".$this->MyTitle."&class=$class&op=$type";
			}
			//if we have our data array, simply add it next
			foreach($data as $key=>$value)
			{
				$RetStr .= 	"&$key=$value";
			}	
		}
		return $RetStr;
	}
	// admin page functions
	function AdminShowConfig()
	{ 
		global $lang, $db;
		// Now process data if it was sent
		if($_GET['action']=="save")
		{
			$content = "<?PHP\n\n";
			
			// make list of configs in POST with empty values but IS
			foreach($this->MyConfig as $key=>$value)
			{
				if(!isset($_POST[''.$key.'']))
				{
					$_POST[''.$key.''] = "";
				}
			}
			
			foreach($_POST as $key=>$value)
			{
				if(isset($this->MyConfig[$key]['value']))
				{
					if(is_array($value))
					{
						$value = json_encode($value);
					}
					else
					{
						$value = $db->safesql($value);
					}
					// if we have such config make a string with it
					$content .= "\$".$this->MyConfigTxt."['".$key."'] = array(\"value\"=>'".$db->safesql($value)."', \"type\"=>\"".$this->MyConfig[$key]['type']."\", \"title\"=>\"".$this->MyConfig[$key]['title']."\", \"descr\"=>\"".$this->MyConfig[$key]['descr']."\");\n\n";
				}
			}
		    $content .= "?>";
		    $filename = $this->MyConfigFile;
		    if ($file = fopen($filename, "w")) {
		       fwrite($file, $content);
		        fclose($file);
		    } else {
		        echo "не удалось записать";
		     //   exit();
		    }
		    header ("Location: ".$this->GetLinkTag("adm_cfg_lnk"));
		}
		else
		{
			if ($config['allow_alt_url'] == "yes"&&!strstr($this->selfURL(), '='))
			{
				$FormLink = $this->selfURL()."/save";
			}
			else
			{
				$FormLink = $this->selfURL()."&action=save";
			}
		// at first load basic template for admin config
		$CfgPage = <<< HTML
		<form action="{$FormLink}" method="post" name="addform">
<table width="100%">
HTML;
// Now lets make the list of items
	foreach($this->MyConfig as $key=>$value)
	{
		switch($value['type'])
		{
			case "varchar":
			{
				$CfgPage .= <<< HTML
		<tr>
	        <td class="option" style="padding:4px;"><b>{$value['title']}:</b>
			<br><span class=small>{$value['descr']}</span>
			</td>
			<td width="350" style="padding-left:2px;" align="center"><input class="edit" style="text-align: center;" type="text" name="{$key}" value="{$value['value']}" /></td>
		</tr>
HTML;
	
				break;
			}
			case "text":
			{
				$value['value'] = stripslashes(stripslashes(stripslashes($value['value'])));
				$CfgPage .= <<< HTML
		<tr>
	        <td class="option" style="padding:4px;"><b>{$value['title']}:</b>
			<br><span class=small>{$value['descr']}</span>
			</td>
			<td width="350" style="padding-left:2px;" align="center"><textarea name="{$key}">{$value['value']}</textarea></td>
		</tr>
HTML;
	
				break;
			}
			case "yesno":
			{
				$CfgPage .= <<< HTML
				<tr>
        <td class="option" style="padding:4px;"><b>{$value['title']}</b>
		<br><span class=small>{$value['descr']}</span>
		</td>
        <td style="padding-left:2px;" align="center">
HTML;
 $CfgPage .= $this->makeDropDown(array("1" => "Да", "0" => "Нет"),
        "$key", "{$value['value']}");
$CfgPage .= <<< HTML
			</td>
    </tr>
HTML;
				
				break;
			}
			default:
			{
				if(strstr($value['type'], "MSelect") == true)
				{
					// Make and show the list
					$CfgPage .= <<< HTML
				<tr>
        <td class="option" style="padding:4px;"><b>{$value['title']}</b>
		<br><span class=small>{$value['descr']}</span>
		</td>
        <td style="padding-left:2px;" align="center">
HTML;
$LstParts = explode(":", $value['type']);
$ListTitle = $LstParts[1];
$CfgPage .= $this->GetMSelectList($ListTitle, "$key", "{$value['value']}");

$CfgPage .= <<< HTML
			</td>
    </tr>
HTML;
				}	
				else if(strstr($value['type'], "Select") == true)
				{
					// Make and show the list
					$CfgPage .= <<< HTML
				<tr>
        <td class="option" style="padding:4px;"><b>{$value['title']}</b>
		<br><span class=small>{$value['descr']}</span>
		</td>
        <td style="padding-left:2px;" align="center">
HTML;
$LstParts = explode(":", $value['type']);
$ListTitle = $LstParts[1];
$CfgPage .= $this->GetSelectList($ListTitle, "$key", "{$value['value']}");

$CfgPage .= <<< HTML
			</td>
    </tr>
HTML;
				}
			}
		}
		$CfgPage .= <<< HTML
		<tr>
        	<td background="engine/skins/images/mline.gif" height="1" colspan="2"></td>
	    </tr>
HTML;

	}
    $CfgPage .= <<< HTML
    <tr>
        <td colspan="2"><br>
<input type="hidden" name="user_hash" value="ebde4ded39a808727e303d43dd84f0c5" />
<input type="submit" class="buttons" value="Сохранить" style="width:150px;">
	<br><br></td>
    </tr>
</table>
</form>
HTML;
		return $CfgPage;
		}
		return $CfgPage;
	}
	function AdminShowItemList($class, $ParamArr)
	{
			
	}
	
	function makeDropDown($options, $name, $selected)
	{
		$output = "<select name=\"$name\">\r\n";
	    foreach ($options as $value => $description) {
	        $output .= "<option value=\"$value\"";
	        if ($selected == $value) {
	            $output .= " selected ";
	        }
	        $output .= ">$description</option>\n";
	    }
	    $output .= "</select>";
	    return $output;
	}
	function makeRadioList($options, $name, $selected)
	{
		$output = "";
		foreach ($options as $value => $description) {
			$output .= '<input type="radio" name="'.$name.'" value="'.$value.'" ';
	        if ($selected == $value) {
	            $output .= " checked ";
	        }
	        $output .= '/>'.$description.' ';
	    }
	    return $output;
	}
	function makeMultiSelectList($options, $name, $selected)
	{
		if($selected!="")
		{
		$selected = json_decode(stripslashes($selected));
		}
		else
		{
			$selected = array();
		}
		$output = "<select multiple name=\"{$name}[]\">\r\n";
	    foreach ($options as $value => $description) {
	        $output .= "<option value=\"$value\"";
	        if (in_array($value, $selected)) {
	            $output .= " selected ";
	        }
	        $output .= ">$description</option>\n";
	    }
	    $output .= "</select>";
	    return $output;
	}
	
	function AddSelectList($ListTitle, $valarr=array(), $params=array())
	{
		$this->SelectLists[$ListTitle]['list'] = $valarr;
		$this->SelectLists[$ListTitle]['params'] = $params;
	}
	function GetSelectList($ListTitle, $name, $selected)
	{
		if(isset($this->SelectLists[$ListTitle]))
		{
			$ListAdd = $this->SelectLists[$ListTitle]['list'];
			if(isset($this->SelectLists[$ListTitle]['params']['zeritem']))
			{
				if(isset($_GET['op']))
				{
					if(strstr($this->SelectLists[$ListTitle]['params']['zeritem'], $_GET['op']))
					{
						unset($ListAdd['0']);
					}
				}
			}
			return $this->makeDropDown($ListAdd, $name, $selected);
		}
		else
		{
			return false;
		}
	}
	function GetRSelectList($ListTitle, $name, $selected)
	{
		if(isset($this->SelectLists[$ListTitle]))
		{
			$ListAdd = $this->SelectLists[$ListTitle]['list'];
			if(isset($this->SelectLists[$ListTitle]['params']['zeritem']))
			{
				if(isset($_GET['op']))
				{
					if(strstr($this->SelectLists[$ListTitle]['params']['zeritem'], $_GET['op']))
					{
						unset($ListAdd['0']);
					}
				}
			}
			return $this->makeRadioList($ListAdd, $name, $selected);
		}
		else
		{
			return false;
		}
	}
	function GetMSelectList($ListTitle, $name, $selected)
	{
		if(isset($this->SelectLists[$ListTitle]))
		{
			$ListAdd = $this->SelectLists[$ListTitle]['list'];
			if(isset($this->SelectLists[$ListTitle]['params']['zeritem']))
			{
				if(isset($_GET['op']))
				{
					if(strstr($this->SelectLists[$ListTitle]['params']['zeritem'], $_GET['op']))
					{
						unset($ListAdd['0']);
					}
				}
			}
			return $this->makeMultiSelectList($ListAdd, $name, $selected);
		}
		else
		{
			return false;
		}
	}	
	function GetSelectListItem($ListTitle, $selected)
	{
		if(isset($this->SelectLists[$ListTitle]))
		{
			return $this->SelectLists[$ListTitle]['list'][$selected];
		}
		else
		{
			return false;
		}
	}
	function SetLangVar($lang, $title, $value)
	{
		$this->Lang[$lang][$title] = $value;
	}
	function GetLangVar($title)
	{
		$lang = "ru";
		if(isset($this->Lang[''.$lang.''][''.$title.'']))
		{
			return $this->Lang[''.$lang.''][''.$title.''];
		}
		else
		{
			return $title;
		}
	}
	function LoadPlugin($file, $function, $params="")
	{
		if($params=="")
		{
			eval("\$SomeRet = \$this->Plugins[\"".$file."\"]->".$function.";");
		}
		else
		{
			eval("\$SomeRet = \$this->Plugins[\"".$file."\"]->".$function."(\$params);");
		}
		return $SomeRet;
	}
	function GenerateAddFields($class)
	{
		return $this->PrepareDbTags($class, "form");
	}
	function GenerateEditFields($class, $id)
	{
		return $this->PrepareDbTags($class, "form", $id);
	}
	function GenerateDeleteFields($class, $id)
	{
		return $this->PrepareDbTags($class, "static", $id);
	}
	
	function ItemSelect($class, $name, $selected="")
	{
		// we need just to get all the items and put selected one if exists
		if(isset($this->MyClasses[$class]['db']['title']))
		{
			$Order = array("title"=>"ASC");
		}
		else
		{
			$Order = array();
		}
		$Items = $this->GetRecords($class, array(), $Order, "");
		// make options from data
		$Options['0'] = "...";
		// check if we have parent_id, make list with children
		if(isset($this->MyClasses[$class]['db']['parent']))
		{
			$finarr = array();
			$finarr = $this->GetListRecursive($class, "0", $finarr, "");
			foreach($finarr as $burat)
			{
				$BoriArr = explode("|:|", $burat);
				$Options[$BoriArr[0]] = $BoriArr[1];
			}
		}
		else
		{
			foreach($Items as $item)
			{
				$Options[$item['id']] = $item['title'];
			}
		}
		
		return $this->makeDropDown($Options, $name, $selected);
	}
	function GetListRecursive($CatTitle, $curID, $finarr, $sep)
	{
		$AlItems = $this->GetRecords($CatTitle, array("parent"=>$curID));
		if(count($AlItems)>0)
		{
			foreach($AlItems as $freak)
			{
				$tmparr = array();
				$finarr[] = $freak['id']."|:|".$sep." ".$freak['title'];
				$tmparr = $this->GetListRecursive($CatTitle, $freak['id'], $tmparr, "--".$sep);
				$finarr = array_merge($finarr, $tmparr);
				unset($tmparr);
			}
		}
		unset($AlItems);
		return $finarr;
	}
	function ItemSelectGet($class, $selected="")
	{
		// we need just to get all the items and put selected one if exists
		$Items = $this->GetRecord($class, $selected);
		return $Items[0]['title'];
	}
	function MakeProcessor($class, $key, $value, $id, $curPost)
	{
		switch($this->MyClasses[$class]['db'][$key])
		{
			case "img":
			{
				// we need to load file and check it's size
				return $this->LoadImage($class, $value, $id);
				break;
			}
			case "file":
			{
				// we need to load file and check it's size
				return $this->LoadFile($class, $value, $id);
				break;
			}
			case "unlimg":
			{
				// checkout deletions from the list
				if(isset($curPost[$key.'_storage'])&&$curPost[$key.'_storage']!="")
				{
					$DelItems = array();
					foreach($_POST as $tk=>$vl)
					{
						if(strstr($tk, $key."_deleteimg_"))
						{
							$DelItems[] = substr($tk, strlen($key."_deleteimg_"));
						}
					}
					$CurItems = explode(":|:", $curPost[$key.'_storage']);
					foreach($DelItems as $del)
					{
						
					 	$CurItems[$del] = "";
					}
					$NewItemArr = array();
					foreach($CurItems as $chitem)
					{
						if($chitem!="")
						{
							$NewItemArr[] = $chitem;	
						}
					}
					$curPost[$key.'_storage'] = implode(":|:", $NewItemArr);
				}
				// Now loop through our array and load each image
					
				$ImagesArr = array();
				$KeyArr = array();
				$KeyArr[] = "name";
				$KeyArr[] = "type";
				$KeyArr[] = "tmp_name";
				$KeyArr[] = "error";
				$KeyArr[] = "size";
				$ktr = 0;
				foreach($value as $row)
				{
					for($i=0; $i<count($row); $i++)
					{
						$ImagesArr[$i][$KeyArr[$ktr]] = $row[$i]; 
					}
					$ktr++;
				}
				
				$TitlesArr = array();
				$Cnt = 0;
				foreach($ImagesArr as $setimg)
				{
					if($setimg['name']!="")
					{
						if($Cnt==0&&isset($this->MyConfig['multiimg_width']['value']))
						{
							$TitlesArr[] = $this->LoadImage($class, $setimg, $id, $this->MyConfig['multiimg_width']['value']);
						}
						else
						{
							$TitlesArr[] = $this->LoadImage($class, $setimg, $id);
						}
						$Cnt++;
					}
					
				}
				$retStr = implode(":|:", $TitlesArr);
				
				if(isset($curPost[$key.'_storage'])&&$curPost[$key.'_storage']!="")
				{
					if($retStr!="")
					{
						$retStr = $curPost[$key.'_storage'].":|:".$retStr;
					}
					else
					{
						$retStr = $curPost[$key.'_storage'];
					}
				}
				else
				{
					if($retStr=="")
					{
						$retStr = "";
					}
				}
				return $retStr;
				break;
			}
		}
	}
	function DeleteFile($class, $key, $id)
	{
		// so we need to get full path and delete this shit
		switch($this->MyClasses[$class]['db'][$key])
		{
			case "img":
			{
				$Reek = $this->GetRecord($class, $id);
				$Reek = $Reek[0];
				 
				$upload_dir = ROOT_DIR.$this->GetImg($Reek[$key], "raw");
			    $upload_dir_thumb = ROOT_DIR.$this->GetImgThumb($Reek[$key], "raw");
				if(file_exists($upload_dir))
			    {
					unlink($upload_dir);
				}
				if(file_exists($upload_dir_thumb))
			    {
					unlink($upload_dir_thumb);
				}
			}
			case "unlimg":
			{
				
			}
		}
	}
	function LoadImage($class, $ImageFile, $id, $exSize="")
	{
		if(!file_exists(ENGINE_DIR.'/inc/makethumb.php'))
		{
			require_once ENGINE_DIR.'/classes/thumb.class.php';
		}
		else
		{
			require_once ENGINE_DIR.'/inc/makethumb.php';
			
		}
	    $StrFileName = "";
	    
	    if(!empty($ImageFile['name']))
	    {
	        $allowed_extensions_thumb = $this->MyConfig['allowed_screen']['value'];
	        $FILE_EXTS_THUMB  = explode(",", $allowed_extensions_thumb);
	        foreach($FILE_EXTS_THUMB as $haggard) $FILE_EXTS_THUMB[] = ".".$haggard;
	        
	        $upload_dir = ROOT_DIR.$this->MyStorage."/images/$class/$id/";
	        $upload_dir_thumb = ROOT_DIR.$this->MyStorage."/images/$class/$id/thumb/";
	        
	        if(!is_dir($upload_dir))
	        {
		        if($this->rmkdir($upload_dir)==false)
		        {
					return "duralex";
				}
			}
			
	      	if(!is_dir($upload_dir_thumb))
	        {
		        if($this->rmkdir($upload_dir_thumb)==false)
		        {
					return "sedlex";
				}
			}
	          $file_type_thumb = $ImageFile['type'];
	          $file_name_thumb = $ImageFile['name'];
	          $file_name_arr_thumb = explode(".",$file_name_thumb);
	          $type_thumb = end($file_name_arr_thumb);
	          $file_name_thumb = time()."_".totranslit (stripslashes($file_name_arr_thumb[0]));
			  $file_name_thumb .= ".".totranslit($type_thumb);
			  if($exSize!="")
	          {
	          	$file_name_thumb_ex = time()."_".totranslit (stripslashes($file_name_arr_thumb[0]));
	          	$file_name_thumb_ex .= "_ext_".$exSize;	
			  	$file_name_thumb_ex .= ".".totranslit($type_thumb);
			  }	
	          $file_ext_thumb = strtolower(substr($file_name_thumb,strrpos($file_name_thumb,".")));
	          
	          if (!in_array($file_ext_thumb, $FILE_EXTS_THUMB)){
	          	
	              $stop .= "{$file_ext_thumb} ".'Извините, но такой тип изображения не разрешён для загрузки. Поддерживаются только .jpg, gif и .png изображения.<br>';
	          }else {
	            $temp_name_thumb = $ImageFile['tmp_name'];
	            
	            
	            $file_path = $upload_dir.$file_name_thumb;
	            if($exSize!="")
	          	{
	          		$file_path_ex = $upload_dir.$file_name_thumb_ex;
				}	
	
	            $StrFileName = $file_name_thumb;
	            }
	            
	        if (is_uploaded_file($ImageFile['tmp_name'])){
	            //Download screenshot
	            if (!$stop){
	            
	                move_uploaded_file($temp_name_thumb, $file_path);
	                
	                $NewFilePath = str_replace("images/$class/$id/", "images/$class/$id/thumb/", $file_path);
	                if($exSize!="")
	                {
	                	$NewFilePathEx = str_replace("images/$class/$id/", "images/$class/$id/thumb/", $file_path_ex);
	                }
	                
	                $thumb=new thumbnail($file_path);
	                if($exSize!="")
	                {
						if ($thumb->size_auto($exSize)) 
		                {
		                          $thumb->jpeg_quality($this->MyConfig['jpeg_quality']['value']);
		                          $thumb->save($NewFilePathEx);
		                          @chmod($NewFilePathEx, 0777);
		                }
		                else
		                {
							$thumb->jpeg_quality($this->MyConfig['jpeg_quality']['value']);
		                    $thumb->save($NewFilePathEx);
		                    @chmod($NewFilePathEx, 0777);
						}
					}
					$thumb=new thumbnail($file_path);
		            if ($thumb->size_auto($this->MyConfig['width_photo']['value'])) 
		            {
		                          $thumb->jpeg_quality($this->MyConfig['jpeg_quality']['value']);
		                          $thumb->save($NewFilePath);
		                          @chmod($NewFilePath, 0777);
		            }
		            else
		            {
							$thumb->jpeg_quality($this->MyConfig['jpeg_quality']['value']);
		                    $thumb->save($NewFilePath);
		                    @chmod($NewFilePath, 0777);
					}
	            }
	        }
	    }
	    if($StrFileName!="")
	    {
	    	$StrFileName = "$class/$id/".$StrFileName;
	    }
	    return $StrFileName;
	}
	function LoadFile($class, $fileFile, $id)
	{
	    $StrFileName = "";    
	    if(!empty($fileFile['name']))
	    {
	        $upload_dir = ROOT_DIR.$this->MyStorage."/files/$class/$id/";
	        
	        if(!is_dir($upload_dir))
	        {
		        if($this->rmkdir($upload_dir)==false)
		        {
					return "duralex";
				}
			}
	        $file_type_thumb = $fileFile['type'];
	        $file_name_thumb = $fileFile['name'];
	        $file_name_arr_thumb = explode(".",$file_name_thumb);
	        $type_thumb = end($file_name_arr_thumb);
	        $file_name_thumb = time()."_".totranslit (stripslashes($file_name_arr_thumb[0])).".".totranslit($type_thumb);
	        $file_ext_thumb = strtolower(substr($file_name_thumb,strrpos($file_name_thumb,".")));
	          
	        $temp_name_thumb = $fileFile['tmp_name'];
	        $file_path = $upload_dir.$file_name_thumb;
	        $StrFileName = $file_name_thumb;
	            
	        if (is_uploaded_file($fileFile['tmp_name'])){
	            //Download screenshot
	            if (!$stop){
	                @move_uploaded_file($temp_name_thumb, $file_path);
	            }
	        }
	    }
	    if($StrFileName!="")
	    {
	    	$StrFileName = "$class/$id/".$StrFileName;
	    }
	    return $StrFileName;
	}
	function rmkdir($path, $mode = 0777) 
	{
	    $path = rtrim(preg_replace(array("/\\\\/", "/\/{2,}/"), "/", $path), "/");
	    $path = trim($path, '/');
	    if(strstr($path, ":"))
	    {}
	    else
	    {
	    	$path = "/".$path;
	    }
	    return mkdir($path, $mode);
	}
	function SearchItem($class)
	{
		// we need to get all specified search forms for the class
		
		$TagsArr = array("form"=>"<form name=\"addform\" action=\"/index.php?do={$this->MyTitle}&op=search&class={$class}&action=search\" enctype=\"multipart/form-data\" method=\"post\">", "/form"=>"<input type=\"hidden\" name=\"fromsent\" value=\"yes\" /></form>", "input_submit"=>"<input type=\"submit\" size=\"40\" class=\"inputstyle_03\" value=\"Найти\">");
		foreach($this->SearchData[$class] as $key=>$values)
		{
			foreach($values as $value)
			{
				// Now get form value for the field
				if(is_array($value))
				{
					// if it is not just field, take it
					$TagsArr["input_".$value['item']] =  $this->GetFormValue($value['item'], "", $value['type']);
				}
				else
				{
					$TagsArr["input_".$value] = $this->GetFormValue($value, "", $this->MyClasses[$class]['db'][$value]);	
				}
			}
		}
		
		//$ArrVar = array_merge($ArrVar, $this->GetAdminLinks($class, "ShowItem"));
		//$this->MakeTemplate($this->MyClasses[$class]['tpl']['search'], $TagsArr, "content");
		$MyPages = $this->MakeItemPage($class, $TagsArr, $this->MyClasses[$class]['tpl']['search']);
		
		return $MyPages;
	}
	function SetSearchParams($class, $dataArr)
	{
		$this->SearchData[$class] = $dataArr;
	}
	function GetRangeField($title, $value)
	{
		return "от <input name=\"{$title}_min\" type=\"text\" size=\"20\" class=\"inputstyle_03\"> до <input name=\"{$title}_max\" type=\"text\" size=\"20\" class=\"inputstyle_03\">";	
	}
	function Search($class)
	{
		// We need to form where string from our search query
		$TagsArr = array();
		// First process POST values and get true values for plugins
		foreach($_REQUEST as $key=>$value)
		{
			// make up an array of items to do with records
			if(isset($this->MyClasses[$class]['db'][$key]))
			{
				// if we haveplugin item than get it's value by plugin
				if(strstr($this->MyClasses[$class]['db'][$key], "Plugin"))
				{
					$Parts = explode(":", $this->MyClasses[$class]['db'][$key]);
					$ClassTitle = $Parts[1];
					$Function = $Parts[2];
					$RetData = $this->LoadPlugin($ClassTitle, $Function, array("value"=>$value, "mode"=>"data"));
					$DataArr = array_merge($DataArr, $RetData);
				}
				else
				{
					$DataArr[$key] = $value;
				}
			}
			else
			{
				$DataArr[$key] = $value;
			}
		}
		foreach($DataArr as $key=>$value)
		{
			if($DataArr[$key]!="")
			{
				foreach($this->SearchData[$class] as $keyg=>$values)
				{
					foreach($values as $value)
					{
						if(is_array($value))
						{
							if($key==$value['item'])
							{
								if(($DataArr[$value['item']]!="")&&($DataArr[$value['item']]!="0"))
								{
									$TagsArr[$key] = $DataArr[$value['item']];
								}
							}
							else
							{
								
								if($value['type']=="range")
								{
									if($key==$value['item']."_min")
									{
										$TagsArr[$value['item']][] = array("value"=>$DataArr[$key], "sign"=>">=");
									}
									if($key==$value['item']."_max")
									{
										$TagsArr[$value['item']][] = array("value"=>$DataArr[$key], "sign"=>"<=");
									}
								}
							}
						}
						else
						{
							if($key==$value)
							{
								if(($DataArr[$value]!="")&&($DataArr[$value]!="0"))
								{
									$TagsArr[$key] = array("value"=>"%".$DataArr[$value]."%", "sign"=>"LIKE");
								}
							}
						}
					}
				}
			}
		}
		$this->ShowItemList($class, array("order"=>array(),"where"=>$TagsArr, "tpl"=>array("item"=>$this->templates['search_item'], "list"=>$this->templates['search_list']), "extra"=>array("search_field"=>$this->SearchItem($class))));
	}
	function UserCheckRights($uType)
	{
		global $member_id, $member_db;
		if(!isset($member_id['user_group']))
		{ 
			$member_id['user_group'] = $member_db[1];
		}
		switch($uType)
		{	
			case "admin":
			{
				if($member_id['user_group']=="1")
				{
					return true;
				}
				else
				{
					return false;
				}
				break;
			}
			case "all":
			{
				return true;
				break;
			}
			default:
			{
				if($member_id['user_group']<=$uType)
				{
					return true;
				}
				else
				{
					return false;
				}
			}
		}
	}
	function FieldsCheck($class, $data)
	{
		global $lang;
		$CheckFields = $this->MyClasses[$class]['fieldcheck'];
		$ErrorStr = "";
		if(count($CheckFields)>0)
		{
			foreach($CheckFields as $key=>$value)
			{
				if(isset($data[$key]))
				{
					$dataval = $data[$key];
					switch($value['type'])
					{
						case "required":
						{
							if($dataval=="")
							{
								$ErrorStr .= $value['msg']."<br>";
							}
							break;
						}
						case "email":
						{
							if($this->checkmail($dataval)=='-1')
							{
								$ErrorStr .= $value['msg']."<br>";
							}
							break;
						}
					}
				}
			}
		}
	if (($this->MyConfig['captcha']['value']==1)&&(in_array($class, $this->CaptchaList)==true)) 
		{
			if ( $_POST['sec_code'] != $_SESSION['sec_code_session'] OR !$_SESSION['sec_code_session']) {
			           $ErrorStr .= $lang['reg_err_19']."<br>";
			        }
			    $_SESSION['sec_code_session'] = false; 
		}
		if($ErrorStr=="")
		{
			$ErrorStr = "";
		}
		return $ErrorStr;
	}
	public function checkmail($mail) 
	{
		$mail=trim($mail);
		if (strlen($mail)==0) return 1;
		if (!preg_match("/^[a-z0-9_-]{1,20}@(([a-z0-9-]+\.)+(com|net|org|mil|".
		"edu|gov|arpa|info|biz|inc|name|ru|[a-z]{2})|[0-9]{1,3}\.[0-9]{1,3}\.[0-".
		"9]{1,3}\.[0-9]{1,3})$/is",$mail))
		return -1;
		return $mail;
	}
	public function SetCaptchaList($ListArray)
	{
		$this->CaptchaList = $ListArray;
	}
	public function GenerateCrumbs($class, $item)
	{
		$Model = $this->MyClasses[$class]['breadcrumbs'];
		// Make main link
		if(count($Model)>0&&isset($Model['class'])&&$Model['class']!="")
		{
		$PathStr = $this->GetLangVar("msg_yrhere")." <a href=\"/\">Главная</a> ".$this->GetLangVar("brd_sign")." <a href=\"".$this->GetLinkTag("main")."\">".$this->GetLangVar("brd_startup")."</a>";
		// Now by the model parameters make tree
		
		// Get item
		$CatPar = "";
		$CurrentID = $item[''.$Model['field'].''];
		do
		{
			$CurCat = $this->GetRecord($Model['class'], $CurrentID);
			$CurCat = $CurCat[0];
			if(!isset($CurCat['id'])||$CurCat['id']=="")
			{
				break;
			}
			$LinkDummy = $Model['link'];
			
			foreach($CurCat as $key=>$value)
			{
				$LinkDummy= str_replace("{".$key."}", $value, $LinkDummy);
			}
			
			$LinkArr = explode("|", $LinkDummy);
			$ParamArr = array();
			if(strstr($LinkArr[2], "->"))
			{
				// this means we need to build an array
				$ParamArr = explode("->", $LinkArr[2]);
				$ParamArr = array($ParamArr[0]=>$ParamArr[1]);
			}
			
			$CurLink = $this->GetLink($LinkArr[0], $LinkArr[1], $ParamArr);
			$LinkTo = $this->GetLangVar("brd_sign")."<a href=\"".$CurLink."\">".$CurCat['title']."</a>";
			$CatPar = $LinkTo.$CatPar; 
			$CurrentID = $CurCat['parent_id'];
		}
		while($CurrentID!="0");
		
		
		$PathStr = $PathStr.$CatPar;
		return $PathStr;
		}
		return "";
	}
	public function SetModeration($ModArr)
	{
		$this->ModerSets = $ModArr;
	}
	public function PublishItem($class, $publed)
	{
		if(isset($this->ModerSets[''.$class.'']))
		{
			if(isset($_REQUEST['id']))
			{
				$id = $_REQUEST['id'];
				$data = array("published"=>$publed);
				$this->EditRecord($class, $id, $data);
			}
		}
		header ("Location: ".$this->GetLink($class, "showlist"));
	}
	public function GetBetween($start, $end, $text, $params="")
	{
		$start = preg_quote($start);
		$end = preg_quote($end);
		if($params=="")
		{
			$params = "sU";
		}
		$pattern = "#$start(.*)$end#$params";
		$num_match = preg_match_all($pattern, $text, $result, PREG_SET_ORDER);
		if($num_match>1)
		{
			return $result;
		}
		else if($num_match==1)
		{
			return $result[0][1];
		}
		return "";
	}
	public function ParseSql($string)
	{
		// SQL string parser
		$Patterns = array(
		"#SELECT (.*?) FROM (.*?) WHERE (.*?) ORDER BY (.*?) LIMIT (.*)#",
		"#SELECT (.*?) FROM (.*?) WHERE (.*?) ORDER BY (.*)#",
		"#SELECT (.*?) FROM (.*?) WHERE (.*?) LIMIT (.*)#",
		"#SELECT (.*?) FROM (.*?) ORDER BY (.*?) LIMIT (.*)#",
		"#SELECT (.*?) FROM (.*?) WHERE (.*)#",
		"#SELECT (.*?) FROM (.*?) ORDER BY (.*)#",
		"#SELECT (.*?) FROM (.*?) LIMIT (.*)#",
		"#SELECT (.*?) FROM (.*)#"
		);
		
		$SelectPms = false;
		$WherePms = false;
		$LimitPms = false; 
		$OrderPms = false; 
		$ResArr = array();
		
		if(strstr($string, "SELECT")==true)
		{
			$SelectPms = true; 
		}
		if(strstr($string, "WHERE")==true)
		{
			$WherePms = true; 
		}
		if(strstr($string, "LIMIT")==true)
		{
			$LimitPms = true; 
		}
		if(strstr($string, "ORDER BY")==true)
		{
			$OrderPms = true; 
		}
		$matches = array();
		$TypeStr = "0";
		if($SelectPms==true&&$WherePms==true&&$LimitPms==true&&$OrderPms==true)
		{
			$TypeStr = "0";
			$Result = preg_match($Patterns[0], $string, $matches);
		}
		else if($SelectPms==true&&$WherePms==true&&$LimitPms==false&&$OrderPms==true)
		{
			$TypeStr = "1";
			$Result = preg_match($Patterns[1], $string, $matches);
		}
		else if($SelectPms==true&&$WherePms==true&&$LimitPms==true&&$OrderPms==false)
		{
			$TypeStr = "2";
			$Result = preg_match($Patterns[2], $string, $matches);
		}
		else if($SelectPms==true&&$WherePms==false&&$LimitPms==true&&$OrderPms==true)
		{
			$TypeStr = "3";
			$Result = preg_match($Patterns[3], $string, $matches);
		}
		else if($SelectPms==true&&$WherePms==true&&$LimitPms==false&&$OrderPms==false)
		{
			$TypeStr = "4";
			$Result = preg_match($Patterns[4], $string, $matches);
		}
		else if($SelectPms==true&&$WherePms==false&&$LimitPms==false&&$OrderPms==true)
		{
			$TypeStr = "5";
			$Result = preg_match($Patterns[5], $string, $matches);
		}
		else if($SelectPms==true&&$WherePms==false&&$LimitPms==true&&$OrderPms==false)
		{
			$TypeStr = "6";
			$Result = preg_match($Patterns[6], $string, $matches);
		}
		else if($SelectPms==true&&$WherePms==false&&$LimitPms==false&&$OrderPms==false)
		{
			$TypeStr = "7";
			$Result = preg_match($Patterns[7], $string, $matches);
		}
		$RetArr = array();
		// Now make our type array
		switch($TypeStr)
		{
			case "0":
			{
				$RetArr['table'] = trim($matches[2]);
				$RetArr['cols']  = trim($matches[1]);
				$RetArr['where'] = trim($matches[3]);
				$RetArr['order'] = trim($matches[4]);
				$RetArr['limit'] = trim($matches[5]);
				break;
			}
			case "1":
			{
				$RetArr['table'] = trim($matches[2]);
				$RetArr['cols']  = trim($matches[1]);
				$RetArr['where'] = trim($matches[3]);
				$RetArr['order'] = trim($matches[4]);
				$RetArr['limit'] = "";
				break;
			}
			case "2":
			{
				$RetArr['table'] = trim($matches[2]);
				$RetArr['cols']  = trim($matches[1]);
				$RetArr['where'] = trim($matches[3]);
				$RetArr['order'] = "";
				$RetArr['limit'] = trim($matches[4]);
				break;
			}
			case "3":
			{
				$RetArr['table'] = trim($matches[2]);
				$RetArr['cols']  = trim($matches[1]);
				$RetArr['where'] = "";
				$RetArr['order'] = trim($matches[3]);
				$RetArr['limit'] = trim($matches[4]);
				break;
			}
			case "4":
			{
				$RetArr['table'] = trim($matches[2]);
				$RetArr['cols']  = trim($matches[1]);
				$RetArr['where'] = trim($matches[3]);
				$RetArr['order'] = "";
				$RetArr['limit'] = "";
				break;
			}
			case "5":
			{
				$RetArr['table'] = trim($matches[2]);
				$RetArr['cols']  = trim($matches[1]);
				$RetArr['where'] = "";
				$RetArr['order'] = trim($matches[3]);
				$RetArr['limit'] = "";
				break;
			}
			case "6":
			{
				$RetArr['table'] = trim($matches[2]);
				$RetArr['cols']  = trim($matches[1]);
				$RetArr['where'] = "";
				$RetArr['order'] = "";
				$RetArr['limit'] = trim($matches[3]);
				break;
			}
			case "7":
			{
				$RetArr['table'] = trim($matches[2]);
				$RetArr['cols']  = trim($matches[1]);
				$RetArr['where'] = "";
				$RetArr['order'] = "";
				$RetArr['limit'] = "";
				break;
			}
			default:
			{
				$RetArr['table'] = "empty";
				$RetArr['cols']  = "empty";
				$RetArr['where'] = "empty";
				$RetArr['order'] = "empty";
				$RetArr['limit'] = "empty";
				break;
			}
		}			
		return $RetArr;
	}
	public function ArraySQLSearch($SQLArr)
	{
		// First we need to ge our records acording to whee request
		$Records = array();
		if($SQLArr['where']!="")
		{
			// separate search params
			$SParams = array();
			$FParams = array();
			if(strstr($SQLArr['where'], ","))
			{
				$SParams = explode(",", $SQLArr['where']);
			}
			else
			{
				$SParams[0] = $SQLArr['where'];	
			}
			// now separate field titles from values
			foreach($SParams as $item)
			{
				$itemArr = explode("=", $item);
				$FParams[''.str_replace("`", "", trim($itemArr[0])).''] = str_replace("'", "", trim($itemArr[1]));
			}
			// So we have our params now lets go through an array and collect only appropriate items
			if(is_array($this->DBStorage[''.$SQLArr['table'].'']))
			{
				foreach($this->DBStorage[''.$SQLArr['table'].''] as $tremor)
				{
					$FlagS = true; 
					foreach($FParams as $key=>$value)
					{
						if($tremor[''.$key.'']!=$value)
						{
							$FlagS =false;
						}
					}
					if($FlagS ==true)
					{
						$Records[] = $tremor; 	
					}
				}
			}
		}
		else
		{
			$Records = $this->DBStorage[''.$SQLArr['table'].''];
		}
		if(count($Records)>0)
		{
			// Now lets sort our array according to ORDER BY params
			if($SQLArr['order']!="")
			{
				$params = explode(" ", $SQLArr['order']);
				$Filed = str_replace("`", "", trim($params[0]));
				$Records = $this->array_key_multi_sort($Records, $Filed, trim($params[1]));
			}
			// And now finally limit
			if($SQLArr['limit']!="")
			{
				$FinalArr = array();
				for($i=0; $i<(int)$SQLArr['limit']; $i++)
				{
					$FinalArr[] = $Records[$i];
				}
				$Records = $FinalArr;
			}
			return $Records;
		}
		else
		{
			return "zooza";
		}
		
		return "zooza";
	}
	public function array_key_multi_sort($arr, $l, $type="DESC", $f='strnatcasecmp')
	{
		if($type=="DESC")
		{
			$StrIs = '$b, $a';
		}
		else
		{
			$StrIs = '$a, $b';
		}
	    usort($arr, create_function($StrIs, "return $f(\$a['$l'], \$b['$l']);"));
	    return($arr);
	}
	
	public function SetComments($CommArr=array())
	{
		foreach($CommArr as $key=>$value)
		{
			$this->MyClasses[$key]['commcl'] = $value;
		}
	}
	public function ShowComments($class, $id, $params=array())
	{
		$this->ProcessCommentForm($class, $id, $params);
		// first we need to make the list of current comments
		$Comm['list'] = $this->ShowCommentList($class, $id, $params);
		// and the form
		$Comm['form'] = $this->ShowCommentForm($class, $id, $params);
		// make item template nd return it
		$RetStr = $this->MakeItemPage("base", $Comm, $this->MyClasses[$this->MyClasses[$class]['commcl']]['tpl']['main']);
		return $RetStr;
	}
	public function ShowCommentList($class, $id, $params)
	{
		$where = array();
		$where['post_id'] = $id;
		$where['dataclass'] = $class;
		return $this->ShowList($this->MyClasses[$class]['commcl'],  array("tpl"=>$this->MyClasses[$this->MyClasses[$class]['commcl']]['tpl']['item'], "where"=>$where, "order"=>array("date"=>"DESC"), "pager"=>"0", "limit"=>"20"));
	}
	public function ShowCommentForm($class, $id, $params)
	{
		$CurClass = $this->MyClasses[$class]['commcl'];
		$ArrVar = array("form"=>"<form name=\"addform\" action=\"\" enctype=\"multipart/form-data\" method=\"post\">", "/form"=>"</form>", "input_submit"=>"<input type=\"hidden\" name=\"commsent\" value=\"yes\" /><input type=\"submit\" size=\"40\" class=\"inputstyle_03\" value=\"Добавить\">");
		$ArrVar = array_merge($ArrVar, $this->GenerateAddFields($CurClass));
				
		return $this->MakeItemPage($CurClass, $ArrVar, $this->MyClasses[$this->MyClasses[$class]['commcl']]['tpl']['add']);
	}
	public function ProcessCommentForm($class, $id, $params)
	{
		if(isset($_POST['commsent'])&&$_POST['commsent']=="yes")
		{
			// Get form data if everythign is ok, ass
			$_POST['post_id'] = $id;
			$_POST['dataclass'] = $class;
			// Remove all links from text form
			$_POST['text'] = preg_replace('@(http(\s?)://([-\w\.]+)+(:\d+)?(/([\w/_\.]*(\?\S+)?)?)?)@', '', $_POST['text']);
			$VarData = $this->ProcessData($this->MyClasses[$class]['commcl'], "add");
			if(is_array($VarData))
			{
				foreach($this->Plugins as $key=>$value)
				{
					$this->LoadPlugin($key, "AddComment", array("row"=>$VarData['data']));
				}
			}
			header ("Location: ".$this->selfURL());
		}
	}
	
	public function selfURL() { $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : ""; $protocol = $this->strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s; $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]); return $protocol."://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']; } 
	public function strleft($s1, $s2) { return substr($s1, 0, strpos($s1, $s2)); }
	
	public function SendEmails($WhomArr, $What, $From)
	{
		global $config;
		include_once ENGINE_DIR.'/classes/mail.class.php';
		$mail = new dle_mail($config);
		foreach($WhomArr as $person)
		{	
			$mail->from = $From;
		    $mail->send($person, $What['subject'], $What['message']);
		}
	}
	function SetBBfields($darr)
	{
		$this->BBParsed = $darr;
	}
}
?>