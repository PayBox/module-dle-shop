<?

if(!defined('DATALIFEENGINE'))
{
  die("Hacking attempt!");
}
//error_reporting(E_ALL);
require_once ENGINE_DIR.'/classes/modulus.class.php';
require_once ENGINE_DIR.'/data/shop_config.php';
if(!file_exists(ENGINE_DIR.'/inc/makethumb.php'))
		{
			require_once ENGINE_DIR.'/classes/thumb.class.php';
		}
		else
		{
			require_once ENGINE_DIR.'/inc/makethumb.php';

		}

$MyModule = new Modulus();
$MyModule->Mushka = "vals";
// init module itself

$TplDef = array(
"item" => "shop/item.tpl",
"list" => "shop/list.tpl",
"full" => "shop/full.tpl",
"cfg_lnk" => "shop/cfg_lnk.tpl",
"captcha" => "shop/captcha.tpl",
"message" => "shop/message.tpl",
"search_item" => "shop/search/item.tpl",
"search_list" => "shop/search/list.tpl",
"formitem" => "shop/multifield.tpl",
"mailadmin" => "shop/mailadmin.tpl",
"mailuser" => "shop/mailuser.tpl",
"ordersuccess" => "shop/ordersuccess.tpl"
);

$MyPlugin = new shopPlugin($MyModule);
$MyModule->Initiate("shop", "/uploads/shop/", "./engine/data/shop_config.php", $ShopConfig, $TplDef, "ShopConfig", array("shopplug"=>$MyPlugin));


//-------------
// -----------------|
// cart class   |
// -----------------|

// Now load all the data parts
$DbArr = array(
"id" => "id",
"user_id" => "varchar",
"date"  => "date",
"contents"  => "text",
);


$TplDef = array(
"item" => "shop/cart/item.tpl",
"add" => "shop/cart/add.tpl",
"list" => "shop/cart/list.tpl",
"edit" => "shop/cart/edit.tpl",
"delete" => "shop/cart/delete.tpl",
"main" => "shop/cart/main.tpl",
"full" 	=> "shop/cart/full.tpl",
"pager" => "shop/cart/pager.tpl"
);

$LangArr = array(
"title" => "�������"
);

$FormArr = array(
"title"=>array("title"=>"��������", "descr"=>"�������� ������")
);

$CheckDataArr = array(
"text"=>array("type"=>"required", "msg"=>"���� '���������' ����������� � ����������")
);

$LinksArr = array(
"list" => array("sef"=>"/shop/products/itemshow/{post_id}", "std"=>"/index.php?do=shop&class=products&op=itemshow&id={post_id}")
);

$MyModule->LoadDataClass("carts", PREFIX . "_shop_carts", $DbArr, $TplDef, $LangArr, $FormArr, $CheckDataArr, array(), $LinksArr, false);
//-------------


$DbArr = array(
 "id" => "id",
 "title" => "varchar",
 "category" => "Class:categories:ItemSelect",
 "price" => "decimal",
 "date" => "date",
 "addinfo" => "text",
 "photo" => "unlimg",
 "mainpub" => "checkbox",
 "byaction"=>"checkbox",
 "oldprice"=>"varchar",
 "maindata"=>"multifield",
 "owner" => "CurrentUser",
 "commpub"=>"checkbox",
 "meta_desc"=>"text",
 "meta_keys"=>"text",
 "artikul"=>"varchar"
);


$TplDef = array(
"item" 		=> "shop/ad/item.tpl",
"full" 		=> "shop/ad/full.tpl",
"add" 		=> "shop/ad/add.tpl",
"list" 		=> "shop/ad/list.tpl",
"edit" 		=> "shop/ad/edit.tpl",
"search" 	=> "shop/ad/search.tpl",
"delete" 	=> "shop/ad/delete.tpl",
"pager" 	=> "shop/ad/pager.tpl"
);

$LangVars = array(
"title" => "����������",
"title_item" => "���������"
);
$LangArr = array(
"title" => "����������",
);

$FormArr = array(
"title"=>array("title"=>"��������", "descr"=>"�������� ������")
);

$CheckDataArr = array(
//"title"=>array("type"=>"required", "msg"=>"���� '��������' ����������� � ����������"),
//"info"=>array("type"=>"required", "msg"=>"���� '��������' ����������� � ����������"),
//"email"=>array("type"=>"email", "msg"=>"����������, ��������� ���� Email ���������")
);

$BrdArr = array("class"=>"products", "field"=>"title", "link"=>"main");

$SeoAddition = " {title}";

$MyModule->LoadDataClass("products", PREFIX . "_shop", $DbArr, $TplDef, $LangArr, $FormArr, $CheckDataArr, $BrdArr, array(), false, $SeoAddition);
//-------------

//-------------
// -----------------|
// Comments class   |
// -----------------|

// Now load all the data parts
$DbArr = array(
"id" => "id",
"post_id" => "varchar",
"user_id"  => "CurrentUser",
"date"  => "date",
"autor"  => "varchar",
"email"  => "varchar",
"text"  => "text",
"ip"  => "ip",
"is_register"  => "varchar",
"published" => "moderation",
"dataclass" => "varchar",
"subscribe"  => "checkbox"
);


$TplDef = array(
"item" => "shop/comments/item.tpl",
"add" => "shop/comments/add.tpl",
"list" => "shop/comments/list.tpl",
"edit" => "shop/comments/edit.tpl",
"delete" => "shop/comments/delete.tpl",
"main" => "shop/comments/main.tpl",
"pager" 	=> "shop/comments/pager.tpl"
);

$LangArr = array(
"title" => "����������� � ���������"
);

$FormArr = array(
"title"=>array("title"=>"��������", "descr"=>"�������� ������")
);

$CheckDataArr = array(
"text"=>array("type"=>"required", "msg"=>"���� '���������' ����������� � ����������")
);

$LinksArr = array(
"list" => array("sef"=>"/shop/products/itemshow/{post_id}", "std"=>"/index.php?do=shop&class=products&op=itemshow&id={post_id}")
);

$MyModule->LoadDataClass("comments", PREFIX . "_shop_comments", $DbArr, $TplDef, $LangArr, $FormArr, $CheckDataArr, array(), $LinksArr, false);
//-------------

// counries
$DbArr = array(
 "id" => "id",
 "title" => "varchar",
 "type" => "varchar",
 "ftitle" => "varchar",
 "data" => "text"
);

$TplDef = array();
$LangArr = array();
$FormArr = array();
$CheckDataArr = array();
$LinksArr = array();

$MyModule->LoadDataClass("multifields", PREFIX . "_shop_fields", $DbArr, $TplDef, $LangArr, $FormArr, $CheckDataArr, array(), $LinksArr, false);
//-------------

// counries
$DbArr = array(
 "id" => "id",
 "title" => "varchar",
 "parent" => "Class:categories:ItemSelect",
 "description" => "text"
);

$TplDef = array(
"item" => "shop/categories/item.tpl",
"full" => "shop/categories/full.tpl",
"add" => "shop/categories/add.tpl",
"list" => "shop/categories/list.tpl",
"edit" => "shop/categories/edit.tpl",
"delete" => "shop/categories/delete.tpl",
"pager" => "shop/categories/pager.tpl"
);

$LangArr = array(
"title" => "�����"
);

$FormArr = array(
"title"=>array("title"=>"��������", "descr"=>"�������� ������")
);

$CheckDataArr = array(
"title"=>array("type"=>"required", "msg"=>"���� '��������' ����������� � ����������")
);

$LinksArr = array(
"list" => array("sef"=>"/shop/", "std"=>"/index.php?do=shop")
);

$MyModule->LoadDataClass("categories", PREFIX . "_shop_categories", $DbArr, $TplDef, $LangArr, $FormArr, $CheckDataArr, array(), $LinksArr, false);
//-------------

// counries
$DbArr = array(
 "id" => "id",
 "cart_id" => "Class:carts:ItemSelect",
 "gateway" => "varchar",
 "status" => "varchar",
 "fio" => "varchar",
 "company" => "varchar",
 "email" => "varchar",
 "telephone" => "varchar",
 "address" => "varchar",
 "addinfo" => "text",
 "ip" => "varchar",
 "date" => "varchar",
 "items" => "text",
 "totalsum" => "varchar",
 "ordernum"=>"varchar"
);

$TplDef = array(
"item" => "shop/orders/item.tpl",
"full" => "shop/orders/full.tpl",
"add" => "shop/orders/add.tpl",
"list" => "shop/orders/list.tpl",
"edit" => "shop/orders/edit.tpl",
"delete" => "shop/orders/delete.tpl",
"pager" => "shop/orders/pager.tpl"
);

$LangArr = array(
"title" => "������"
);

$FormArr = array(
"title"=>array("title"=>"��������", "descr"=>"�����")
);

$CheckDataArr = array(
"cart_id"=>array("type"=>"required", "msg"=>"���� 'ID �������' ����������� � ����������")
);

$LinksArr = array(
"list" => array("sef"=>"/shop/", "std"=>"/index.php?do=shop")
);

$MyModule->LoadDataClass("orders", PREFIX . "_shop_orders", $DbArr, $TplDef, $LangArr, $FormArr, $CheckDataArr, array(), $LinksArr, false);
//-------------


// when we set our data classes we need to make our pages to show
$PageConf = array(
"type"=>"static" // define type of the page - static ot form
);

$TagList = array(
	"cats" => 'Plugin:shopplug:ShowCats();',
	"search" => 'Class:products:SearchItem',
	"breadcrumbs" => "Plugin:shopplug:ShowBreadCrumbs();",
	"add_link" => "Plugin:shopplug:GetAddLink();",
	"current_cat" => "Plugin:shopplug:GetCurrentCat();",
	"items" => 'Plugin:shopplug:ShowItems();' //Class:products:ShowList(array("tpl"=>"shop/mainitem.tpl", "tpllist"=>"shop/mainitemlist.tpl", "order"=>array("date"=>"DESC") '.$AddString.'))
);

$UserRights = "all";

$UrlPath = array(
"sef"=>"/shop/",
"std"=>"/index.php?do=shop"
);

$MetaArr = array(
"title" => $ShopConfig['title']['value'],
"keywords" => $ShopConfig['meta_keys']['value'],
"description" => $ShopConfig['meta_desc']['value']
);

$MyModule->CreatePage("ShowAll", "�������", $PageConf, $TagList, $UserRights, $UrlPath, "shop/main.tpl", $MetaArr);
// -------------------
if($_GET['op']=='category')
$MyModule->CreatePage("ShowAll", "�������", $PageConf, $TagList, $UserRights, $UrlPath, "shop/main1.tpl", $MetaArr);
// when we set our data classes we need to make our pages to show
$PageConf = array(
"type"=>"static" // define type of the page - static ot form
);

if(!isset($_GET['id']))
{
	$_GET['id'] = "";
	$AddString = "";
}


$TagList = array(
	"cartid" => "Raw:".$_GET['id'],
	"cartitems" => "Plugin:shopplug:GetCartItemList",
);

// if user is set get user data into tags
if(isset($member_id['user_id'])&&$member_id['user_id']!="")
{
	$TagList['fullname'] = "Raw:".$member_id['fullname'];
	$TagList['email'] = "Raw:".$member_id['email'];

}
else
{
	$TagList['fullname'] = "";
	$TagList['email'] = "";
}

$UserRights = "all";

$UrlPath = array(
"sef"=>"/shop/",
"std"=>"/index.php?do=shop"
);

$MetaArr = array(
"title" => $ShopConfig['title']['value'],
"keywords" => "�����, ����������, ����, �����",
"description" => "������ ����� ��������� ������ ������!!!"
);

$MyModule->CreatePage("checkout", "���������� ������", $PageConf, $TagList, $UserRights, $UrlPath, "shop/checkout.tpl", $MetaArr);
// ---------------------->

$PageConf = array(
"type"=>"static" // define type of the page - static ot form
);

$TagList = array(
	"main_data" => "Class:general:AdminShowConfig"
);

$UserRights = "admin";

$UrlPath = array(
"sef"=>"/shop/",
"std"=>"/index.php?do=shop"
);


$MetaArr = array(
"title" => "������������ :: ".$ShopConfig['title']['value'],
"keywords" => "�����, ����������, ����, �����",
"description" => "������ ����� ��������� ������ ������!!!"
);

$MyModule->CreatePage("config", "������������ ������", $PageConf, $TagList, $UserRights, $UrlPath, "shop/config.tpl", $MetaArr);

$MyModule->AddLink("main", "/index.php?do=shop", "/shop/", "all");
$MyModule->AddLink("adm_cfg_lnk", "/index.php?do=shop&op=config", "/shop/admin/config", "admin");

// Now define array with classes using captcha
$CaptchaList = array("");
$MyModule->SetCaptchaList($CaptchaList);


if($ShopConfig['moder_not']['value']=="1")
{
$ModerationArr = array(
"products"=>"0"
);
$MyModule->SetModeration($ModerationArr);
}

if($ShopConfig['comment_not']['value']=="1")
{
	$CommArr = array(
		"products"=>"comments",
	);
	$MyModule->SetComments($CommArr);
}



$MyModule->SetLangVar("ru", "checkbox_yes", "����");
$MyModule->SetLangVar("ru", "checkbox_no", "���");
// Initiate languages
$MyModule->SetLangVar("ru", "categories", "���������");
$MyModule->SetLangVar("ru", "cities", "������");
$MyModule->SetLangVar("ru", "products", "����������");
$MyModule->SetLangVar("ru", "countries", "������");


$MyModule->SetLangVar("ru", "CfgAdmin", "������������ ������");
$MyModule->SetLangVar("ru", "AddLink", "��������");
$MyModule->SetLangVar("ru", "ShowList", "������");
$MyModule->SetLangVar("ru", "Edit", "��������");
$MyModule->SetLangVar("ru", "Delete", "�������");
$MyModule->SetLangVar("ru", "ModerList", "���������");
$MyModule->SetLangVar("ru", "publ", "������������");
$MyModule->SetLangVar("ru", "unpubl", "�����");

$MyModule->SetLangVar("ru", "msg_cities_add", "�������, ����� ������� ��������");
$MyModule->SetLangVar("ru", "msg_categories_add", "�������, ��������� ������� ���������");
$MyModule->SetLangVar("ru", "msg_products_add", "�������, ���������� ������� ���������.");
$MyModule->SetLangVar("ru", "msg_countries_add", "�������, ������ ������� ���������.");
$MyModule->SetLangVar("ru", "msg_comments_add", "�������,  ����������� ������� ��������.");

$MyModule->SetLangVar("ru", "msg_cities_edit", "�������, ����� ������� �������");
$MyModule->SetLangVar("ru", "msg_categories_edit", "�������, ��������� ������� ��������");
$MyModule->SetLangVar("ru", "msg_products_edit", "�������, ���������� ������� ��������.");
$MyModule->SetLangVar("ru", "msg_countries_edit", "�������, ������ ������� ��������");
$MyModule->SetLangVar("ru", "msg_comments_edit", "�������, ����������� ������� �������");


$MyModule->SetLangVar("ru", "msg_cities_del", "�������, ����� ������� ������");
$MyModule->SetLangVar("ru", "msg_categories_del", "�������, ��������� ������� �������");
$MyModule->SetLangVar("ru", "msg_products_del", "�������, ���������� ������� �������.");
$MyModule->SetLangVar("ru", "msg_countries_del", "�������, ������ ������� �������.");
$MyModule->SetLangVar("ru", "msg_comments_del", "�������, ����������� ������� ������");

$MyModule->SetLangVar("ru", "msg_record_exists", "����� ����� �� ������ ��� ���������� � ����.");
$MyModule->SetLangVar("ru", "msg_record_none", "������ � ������ ��������� �������� �����������.");

$MyModule->SetLangVar("ru", "msg_restricted", "� ��� ������������ ���� ��� ��������� ���� ��������. <br><a href=\"javascript:history.go(-1)\">�����</a>");

$MyModule->SetLangVar("ru", "brd_startup", $MyModule->MyConfig['title']['value']);
$MyModule->SetLangVar("ru", "msg_yrhere", "");
$MyModule->SetLangVar("ru", "brd_sign", " &rarr; ");

$MyModule->SetLangVar("ru", "addimgfield", "�������� ����");


// Select Lists
$db->query("SELECT id, group_name FROM " . PREFIX . "_usergroups");
$GroupArr = array();
while($row = $db->get_row())
{
  	$GroupArr[$row['id']] = $row['group_name'];
}
$MyModule->AddSelectList("groups",$GroupArr);

$TimeShowList = array(
"week" => "������",
"2week" => "2 ������",
"month" => "�����"
);
$MyModule->AddSelectList("GetTimeShow",$TimeShowList);


$YesNoList = array(
"1" => "��",
"0" => "���"
);
$MyModule->AddSelectList("YesNo",$YesNoList);

$MarksList = array(
"0" => "���",
"1" => "Caro",
"2" => "Oporo",
"3" => "Topclass",
"4" => "Foundain",
"5" => "Eago"
);
$ParamsFR = array(
"zeritem"=>"edititem, additem"
);
$MyModule->AddSelectList("Marks", $MarksList, $ParamsFR);

$MarksList = array(
"0" => "���",
"1" => "���",
"2" => "2",
"3" => "4",
"4" => "6",
"5" => "8",
"6" => "10",
"7" => "12",
"8" => "14",
"9" => "16",
"10" => "18",
"11" => "20"
);
$ParamsFR = array(
"zeritem"=>"edititem, additem"
);
$MyModule->AddSelectList("HMFors", $MarksList, $ParamsFR);

$MarksList = array(
"0" => "���",
"1" => "������",
"2" => "�������",
"3" => "����������� � ������",
);
$ParamsFR = array(
"zeritem"=>"edititem, additem"
);
$MyModule->AddSelectList("Poddons", $MarksList, $ParamsFR);

$MarksList = array(
"0" => "���",
"1" => "���",
"2" => "���������",
"3" => "���������",
);
$ParamsFR = array(
"zeritem"=>"edititem, additem"
);
$MyModule->AddSelectList("Panels", $MarksList, $ParamsFR);


$YearArr = array();
for($i=1970; $i<=(int)date("Y");$i++)
{
	$YearArr[$i] = $i;
}
$YearArr = array_reverse($YearArr, true);
$MyModule->AddSelectList("Years", $YearArr, $ParamsFR);

$CurrArr = array(
"0" => "���",
"usd" => "$",
"jpy" => "JPY",
"eur" => "EUR",
"kzt" => "KZT",
"rub" => "RUB"
);
$ParamsFR = array(
"zeritem"=>"edititem, additem"
);
$MyModule->AddSelectList("Currencies", $CurrArr, $ParamsFR);

$CurrArr = array(
"unconf" => "� ��������",
"paid" => "�������",
"canceld" => "�������",
);

$ParamsFR = array();

$MyModule->AddSelectList("Statuses", $CurrArr, $ParamsFR);


$SearchData = array(
"products" => array("title", "category", array("item"=>"price", "type"=>"range"))
);
$MyModule->SetSearchParams("products", $SearchData);

// multifields load
$MRecs = $MyModule->GetRecords("multifields");
$FArr = array();
foreach($MRecs as $recm)
{
	$FArr[''.$recm['ftitle'].''][] = $recm;
}

foreach($FArr as $sey=>$deer)
{
	$FString = "";
	foreach($deer as $jalue)
	{
		$FString .= "mfield_".$sey."_".$jalue['type']."_".$jalue['title']."|++|".$jalue['type']."|++||::|";
	}
	$MyModule->MultiFields[''.$sey.''] = $FString;
}

global $metatags;
// When we have our pages set, lets make disatcher function to process them all
function Dispatchershop() //gets parameters and invokes necessary function
{
	global $MyModule, $MyPlugin, $metatags;
	$op = "";
	if(isset($_REQUEST['op']))
	{
			$op = $_REQUEST['op'];
	}
	switch ($op) {

	case "shop":
			$MyModule->LoadPage("ShowAll");
	        break;
	case "additem":
			$MyModule->AddItem($_GET['class']);
	        break;
	case "config":
	        $MyModule->LoadPage("config");
	        break;
	case "admlist":
	        $MyModule->LoadPage("admlist");
	        break;
	case "showlist":
	        $MyModule->ShowItemList($_GET['class']);
	        break;
	case "itemshow":
	        $MyModule->ShowItem($_GET['class']);
	        break;
	case "publish":
	        $MyModule->PublishItem($_GET['class'], "1");
	        break;
	case "itembuy":
	        $MyPlugin->AddToCart();
	        break;
	case "clearup":
	        $MyPlugin->ClearCart();
	        break;
	case "caro":
	        $MyModule->ShowItemList($_GET['class'], array(), array(""=>""));
	        break;
	case "checkout":
			if(!isset($_POST['sent']))
			{
				$MyModule->LoadPage("checkout");
			}
	        else
	        {
				$MyPlugin->CheckOutSend();
			}
	        break;
	case "deleteitem":
	        $MyPlugin->RemoveFromCart();
	        break;
	case "unpublish":
	        $MyModule->PublishItem($_GET['class'], "0");
	        break;
	case "edit":
	        $MyModule->EditItem($_GET['class']);
	        break;
	case "delete":
	        $MyModule->DeleteItem($_GET['class']);
	        break;
	case "search":
	        $MyModule->Search($_GET['class']);
	        break;
	case "itemup":
			$MyPlugin->PutItemUp();
	        $MyModule->LoadPage("ShowAll");
	        break;
	case "itemurgent":
			$MyPlugin->MakeItemUrgent();
	        $MyModule->LoadPage("ShowAll");
	        break;
	case "itemvip":
			$MyPlugin->MakeItemVip();
	        $MyModule->LoadPage("ShowAll");
	        break;
	case "itemunurgent":
			$MyPlugin->MakeItemUrgent(false);
	        $MyModule->LoadPage("ShowAll");
	        break;
	case "itemunvip":
			$MyPlugin->MakeItemVip(false);
	        $MyModule->LoadPage("ShowAll");
	        break;
	default:
		{
			$MyModule->LoadPage("ShowAll");
	        break;
	    }
	}

}
if(strstr($_SERVER['REQUEST_URI'], "do=shop")||strstr($_SERVER['REQUEST_URI'], "/shop/")||strstr($_SERVER['REQUEST_URI']."::x", "/shop::x"))
{
	Dispatchershop();
}
$MyPlugin->ShowCart();
$MyPlugin->LoadLatest();
$MyPlugin->ShowFrontItems();
$MyPlugin->LoadCategories();
class shopPlugin
{
	// empty constructor function
	var $VarMod = null;
	function shopPlugin($modobj) {
		$this->VarMod = $modobj;
	}



	function GetAddLink()
	{
		global $is_logged;
		if($this->VarMod->MyConfig['allow_add']['value'])
		{
			if($this->VarMod->UserCheckRights($this->VarMod->MyConfig['min_add_group']['value']))
			{
				return "<a style=\"font-size: 14px;\" href=\"".$this->VarMod->GetLink("products", "additem")."\">�������� ����������</a>";
			}
			else
			{
				$Restr = "�������� ���������� (��� �� ������� ����)";
				if(!$is_logged)
				{
					$Restr .= " <a style=\"font-size: 14px;\" href=\"/index.php?do=register\">�����������</a>";
				}
				return $Restr;
			}
		}
	}

	function LoadLatest()
	{
		global $tpl;
		// Make item list
		$MyList = "";
		$MyList = $this->VarMod->ShowList("products", array("tpl"=>"shop/blockitem.tpl", "tpllist"=>"shop/latest_list.tpl", "order"=>array("date"=>"DESC"), "pager"=>"0", "limit"=>$this->VarMod->MyConfig['block_limitpage']['value']));
		$tpl->load_template('shop/latest.tpl');
		$tpl->set("{items}", $MyList);
		$tpl->compile('shop_latest');
		$tpl->clear();
	}
	function ShowFrontItems()
	{
		global $tpl;
		if($_SERVER['REQUEST_URI']=="/index.php"||$_SERVER['REQUEST_URI']=="/"||$_SERVER['REQUEST_URI']=="")
		{
			$ItemsList = "";
			$ItemsList = $this->VarMod->ShowList("products", array("tpl"=>"shop/mainitem.tpl", "tpllist"=>"shop/mainitemlist.tpl", "where"=>array("mainpub"=>"1"), "order"=>array("date"=>"DESC"), "pager"=>"0", "limit"=>$this->VarMod->MyConfig['main_limitpage']['value']));
			$tpl->load_template('shop/frontpage.tpl');
			$tpl->set("{list}", $ItemsList);
		}
		$tpl->compile('frontshop');
		$tpl->clear();
	}
	function LoadCategories()
	{
		global $tpl;
		if($_SERVER['REQUEST_URI']=="/index.php"||$_SERVER['REQUEST_URI']=="/"||$_SERVER['REQUEST_URI']=="")
		{
			// Make item list
			$MyList = "";
			$MyList = $this->VarMod->ShowList("categories", array("tpl"=>"shop/catitem.tpl", "tpllist"=>"shop/cat_list.tpl", "order"=>array("title"=>"ASC"), "pager"=>"0", "limit"=>"9999"));
			$tpl->load_template('shop/allcats.tpl');
			$tpl->set("{items}", $MyList);
		}
		$tpl->compile('shop_cats');
		$tpl->clear();
	}
	function GenerateArt()
	{

	}
		 function translitIt($str)
{
    $tr = array(
        "�"=>"a","�"=>"b","�"=>"v","�"=>"g",
        "�"=>"d","�"=>"e","�"=>"j","�"=>"z","�"=>"i",
        "�"=>"y","�"=>"k","�"=>"l","�"=>"m","�"=>"n",
        "�"=>"o","�"=>"p","�"=>"r","�"=>"s","�"=>"t",
        "�"=>"u","�"=>"f","�"=>"h","�"=>"ts","�"=>"ch",
        "�"=>"sh","�"=>"sch","�"=>"","�"=>"yi","�"=>"",
        "�"=>"e","�"=>"yu","�"=>"ya","�"=>"a","�"=>"b",
        "�"=>"v","�"=>"g","�"=>"d","�"=>"e","�"=>"j",
        "�"=>"z","�"=>"i","�"=>"y","�"=>"k","�"=>"l",
        "�"=>"m","�"=>"n","�"=>"o","�"=>"p","�"=>"r",
        "�"=>"s","�"=>"t","�"=>"u","�"=>"f","�"=>"h",
        "�"=>"ts","�"=>"ch","�"=>"sh","�"=>"sch","�"=>"y",
        "�"=>"yi","�"=>"","�"=>"e","�"=>"yu","�"=>"ya",
        " "=> "_", "."=> ""
    );
    return strtr($str,$tr);
}
	function MakeItemList($Params)
	{
		//$url = $this->VarMod->GetLink("products", "itemshow", array("id"=>$Params['row']['id']));
		$url = urldecode($this->VarMod->GetLink("products", "itemshow", array("id"=>$Params['row']['id'])));
		$url = $this->translitIt($url);
		//$url = str_replace("_shop_products_itemshow_","product/itemshow/",$url);
		$ItemLink = "<a href=\"".$url."\" class=\"prod_more\">";

		//echo str_replace("<","",$url);
		$BuyLink = "<a href=\"".$this->VarMod->GetLink("products", "itembuy", array("id"=>$Params['row']['id']))."\" class=\"prod_buynow\">";
		$CatLink = "<a href=\"".$this->VarMod->GetLink("main", "category", array("cid"=>$Params['row']['category']))."\" class=\"prod_buynow\">";
		$FormStr = '
		<form method="POST" action="'.$this->VarMod->GetLink("products", "itembuy", array("id"=>$Params['row']['id'])).'">
		<input type="text" value="1" name="prod_qty" class="qtyfield" />
		<input type="submit" class="buybut" value="� �������" />
		</form>
		';
		$FCatLink = "<a href=\"".$this->VarMod->GetLink("main", "category", array("cid"=>$Params['row']['id']))."\">";
		$Params['text'] = str_replace("{link}", $ItemLink, $Params['text']);
		$Params['text'] = str_replace("{blink}", $BuyLink, $Params['text']);
		$Params['text'] = str_replace("{buybut}", $FormStr, $Params['text']);
		$Params['text'] = str_replace("{/blink}", "</a>", $Params['text']);
		$Params['text'] = str_replace("{catlink}", $CatLink, $Params['text']);
		$Params['text'] = str_replace("{/catlink}", "</a>", $Params['text']);

		$Params['text'] = str_replace("{fcatlink}", $FCatLink, $Params['text']);
		$Params['text'] = str_replace("{/fcatlink}", "</a>", $Params['text']);

		return $Params['text'];
	}
	function ShowFull($ItemDataArr)
	{
		global $metatags, $member_id;
		if($_GET['class']=="carts")
		{
			// Check if current cart belongs to current user
			$MyCartId = "";
			if(isset($member_id['user_id'])&&$member_id['user_id']!="")
			{
				$MyCartId = $member_id['user_id'];
			}
			else
			{
				// look of we have a cookie
				if(isset($_COOKIE['elvic_userid']))
				{
					$MyCartId = $_COOKIE['elvic_userid'];
				}
			}
			if($MyCartId=="")
			{}
			else
			{
				if($MyCartId==$ItemDataArr['user_id'])
				{
					$metatags['title'] =  "���� ������� :: ".$this->VarMod->MyConfig['title']['value'];
					// Now make items list
					$Products = $this->VarMod->GetRecords("products");
					$RpRod = array();
					foreach($Products as $item)
					{
						$RpRod[$item['id']] = $item;
					}
					$CartContents = $this->wtl_convert("UTF-8", "cp1251", json_decode($ItemDataArr['contents'], true));
					$ItemDataArr['items'] = "";
					if(count($CartContents)>0)
					{
						foreach($CartContents as $key=>$utin)
						{
							// ge product data
							$MyProd = $RpRod[$utin['prod']];
							$Qty = $utin['qty'];
							$FullPrice = (float)$MyProd['price']*(int)$Qty;
							$FullPrice = $this->VarMod->GetTrueValue($FullPrice, $this->VarMod->MyClasses['products']['db']['price']);
							$BuyLink = $this->VarMod->GetLink("products", "itembuy", array("id"=>$MyProd['id']));

							$ItemDataArr['items'] .= "".$this->VarMod->GetTrueValue($MyProd['category'], $this->VarMod->MyClasses['products']['db']['category'])." - ".$MyProd['title']." ".$Qty."x - ".$FullPrice." ��� - "."<form style=\"display: inline;\" action=\"".$BuyLink."\" method=\"post\"><input type=\"text\" name=\"newqty\" style=\"width: 40px;\" value=\"".$Qty."\" /><input type=\"submit\" value=\"��������\" /></form> <a href=\"".$this->VarMod->GetLink("carts", "deleteitem", array("id"=>$ItemDataArr['id'], "did"=>$key))."\">�������</a>"."<br />";
						}
					}
					if(count($CartContents)>0)
					{

					$ItemDataArr['formlnk'] = '<a href="'.$this->VarMod->GetLink("carts", "checkout", array("id"=>$ItemDataArr['id'])).'">';
					$ItemDataArr['clearlnk'] = '<a href="'.$this->VarMod->GetLink("carts", "clearup", array("id"=>$ItemDataArr['id'])).'">';
					$ItemDataArr['/a'] = "</a>";
					}
					else
					{
						$ItemDataArr['items'] = "���� ��� ������� � �������";
						$ItemDataArr['formlnk'] = '';
					$ItemDataArr['clearlnk'] = '';
					$ItemDataArr['/a'] = "";
					}

				}
				else
				{
					header("location: ".$this->VarMod->GetLinkTag("main"));
				}
			}
		}
		else
		{
		if($ItemDataArr["maindata"]["mfield_maindata_varchar_title"]!="") $metatags['title'] =$ItemDataArr["maindata"]["mfield_maindata_varchar_title"];
		else
			$metatags['title'] = $ItemDataArr['title']." :: ".$ItemDataArr['category']." :: ".$this->VarMod->MyConfig['title']['value']."asdasdas";
			$ItemLink = "<a href=\"".$this->VarMod->GetLink("products", "itemshow", array("id"=>$ItemDataArr['id']))."\" class=\"prod_more\">";
			$BuyLink = "<a href=\"".$this->VarMod->GetLink("products", "itembuy", array("id"=>$ItemDataArr['id']))."\" class=\"prod_buynow\">";
			$ItemDataArr['link'] = $ItemLink;
			$ItemDataArr['catlink'] = "<a href=\"".$this->VarMod->GetLink("main", "category", array("cid"=>$ItemDataArr['category_raw']))."\" class=\"prod_more\">";
			$ItemDataArr['/catlink'] = "</a>";
			$FormStr = '
		<form method="POST" action="'.$this->VarMod->GetLink("products", "itembuy", array("id"=>$Params['row']['id'])).'">
		<input type="text" value="1" name="prod_qty" class="qtyfield" />
		<input type="submit" class="buybut" value="� �������" />
		</form>
		';

			$ItemDataArr['buybut'] = $FormStr;
			$ItemDataArr['blink'] = $BuyLink;
			$ItemDataArr['/blink'] = "</a>";
			$metatags['description'] = strip_tags($ItemDataArr['meta_desc']);
			$metatags['keywords'] = strip_tags($ItemDataArr['meta_keys']);
		}
		return $ItemDataArr;
	}
	function SrochnoFunction($TplText, $rowData)
	{
	//	<span class="express">������!</span>
		if($this->VarMod->UserCheckRights('admin'))
			{
		$TplText = str_replace("{plg_upped}", " | <a href=\"/index.php?do=shop&class=products&op=itemup&id=".$rowData['id']."\">�������</a>", $TplText);
		}
		else
		{
			$TplText = str_replace("{plg_upped}", "", $TplText);
		}
		if($rowData['urgent']=="1")
		{
			$TplText = str_replace("{plg_srochno}", "<span class=\"srochbut\"><b>������</b></span>", $TplText);
		}
		else
		{
			$TplText = str_replace("{plg_srochno}", "", $TplText);
		}
		if($rowData['vip']=="1")
		{
			$TplText = str_replace("{plg_vipcl}", "vipstyle", $TplText);
		}
		else
		{
			$TplText = str_replace("{plg_vipcl}", "", $TplText);
		}
		return $TplText;
	}
	function PutItemUp()
	{
		// get item id
		if(isset($_REQUEST['id'])&&($_REQUEST['id']!=""))
		{
			// check user rights
			if($this->VarMod->UserCheckRights("admin"))
			{
				// up the item up!
				$NewDate = date("Y-m-d G:i:s");
				$this->VarMod->EditRecord("products", $_REQUEST['id'], array("dateupd"=>$NewDate));
			}
		}
	}
	function MakeItemUrgent($set=true)
	{
		// get item id
		if(isset($_REQUEST['id'])&&($_REQUEST['id']!=""))
		{
			// check user rights
			if($this->VarMod->UserCheckRights("admin"))
			{
				// up the item up!
				if($set==true)
				{
					$NewDate = "1";
				}
				else
				{
					$NewDate = "0";
				}
				$this->VarMod->EditRecord("products", $_REQUEST['id'], array("urgent"=>$NewDate));
			}
		}
	}
	function MakeItemVip($set=true)
	{
		// get item id
		if(isset($_REQUEST['id'])&&($_REQUEST['id']!=""))
		{
			// check user rights
			if($this->VarMod->UserCheckRights("admin"))
			{
				// up the item up!
				if($set==true)
				{
					$NewDate = "1";
				}
				else
				{
					$NewDate = "0";
				}
				$this->VarMod->EditRecord("products", $_REQUEST['id'], array("vip"=>$NewDate));
			}
		}
	}
	function EditItem($ItemDataArr)
	{
		//$ItemDataArr['form'] = '<form name="fullform" action="" enctype="multipart/form-data" method="post">';
		return $ItemDataArr;
	}
	function AddItem($ItemDataArr)
	{
		//$ItemDataArr['form'] = '<form name="fullform" action="" enctype="multipart/form-data" method="post">';
		return $ItemDataArr;
	}
	function AddComment($Params)
	{
		// Add comment plugin :) just get it and send notifications to subscribers
	}

	function AddToCart()
	{
		// Ge item ID and add it to cart item, if exists for user, if not create it first
		global $member_id;

		if($this->VarMod->MyConfig['onlyauth']['value']=="1")
		{
			if(isset($member_id['user_id'])&&$member_id['user_id']!="")
			{

			}
			else
			{
				header("location: ".$_SERVER['HTTP_REFERER']);
				exit;
			}
		}

		$CountIT = 1;
		if(isset($_POST['prod_qty'])&&$_POST['prod_qty']!="")
		{
			$CountIT = $_POST['prod_qty'];
		}
		if(isset($member_id['user_id'])&&$member_id['user_id']!="")
		{
			$MyCartId = $member_id['user_id'];
		}
		else
		{
			// look of we have a cookie
			if(isset($_COOKIE['elvic_userid']))
			{
				$MyCartId = $_COOKIE['elvic_userid'];
			}
			else
			{
				$value = $this->wtl_GenerateId();
				setcookie("elvic_userid", $value, time()+3600*24, "/", '.'.$_SERVER['SERVER_NAME']);  /* expire in 24 hour */
				$MyCartId = $value;
			}
		}

		// we have our cart ID try to get cart data
		$MyCart = $this->VarMod->GetRecords("carts", array("user_id"=>$MyCartId));
		if(isset($MyCart)&&count($MyCart)>0)
		{
			$MyCart = $MyCart[0];
			// as we have one lets edit it
			$CartContents = $this->wtl_convert("UTF-8", "cp1251", json_decode($MyCart['contents'], true));
		//	for($u=0; $u<$CountIT; $u++)
		//	{
			$existTrue = false;
			foreach($CartContents as $fey=>$citem)
			{
				if($citem['prod']==$_GET['id'])
				{
					if(!isset($_POST['newqty'])||$_POST['newqty']=="")
					{
						$CartContents[$fey]['qty'] = (int)$citem['qty']+(int)$CountIT;
					}
					else
					{
						$CartContents[$fey]['qty'] = $_POST['newqty'];
					}
					$existTrue = true;
				}
			}
			if($existTrue==false)
			{
				$CartContents[] = array("prod"=>$_GET['id'], "qty"=>$CountIT);
			}
		//	}
			$CartContentsStr = json_encode($this->wtl_convert("cp1251", "utf-8", $CartContents));
			$this->VarMod->EditRecord("carts", $MyCart['id'], array("contents"=>$CartContentsStr));
			$CurCat = $MyCart['id'];
		}
		else
		{
			// we need to create the new cart
			$CartContents = array();
			$CartContents[] = array("prod"=>$_GET['id'], "qty"=>$CountIT);
		/*	for($u=0; $u<$CountIT; $u++)
			{
				$CartContents[] = array("prod"=>$_GET['id']);
			}*/
			$CartContentsStr = json_encode($this->wtl_convert("cp1251", "utf-8", $CartContents));
			$data = array("user_id"=>$MyCartId, "date"=>date("Y-m-d G:i:s"), "contents"=>$CartContentsStr);
			$CurCat = $this->VarMod->AddRecord("carts", $data);
		}
		header("location: ".$_SERVER['HTTP_REFERER']);
	}
	function RemoveFromCart()
	{
		global $member_id;
		$MyCartId = "";
		if(isset($member_id['user_id'])&&$member_id['user_id']!="")
		{
			$MyCartId = $member_id['user_id'];
		}
		else
		{
			// look of we have a cookie
			if(isset($_COOKIE['elvic_userid']))
			{
				$MyCartId = $_COOKIE['elvic_userid'];
			}
		}
		if($MyCartId=="")
		{
			header("location: ".$this->VarMod->GetLinkTag("main"));
		}
		else
		{
			$ItemDataArr = $this->VarMod->GetRecord("carts", $_GET['id']);
			$ItemDataArr = $ItemDataArr[0];

			if($MyCartId==$ItemDataArr['user_id'])
			{
				// remove selected item
				$CartContents = $this->wtl_convert("UTF-8", "cp1251", json_decode($ItemDataArr['contents'], true));
				$CartContentsNew = array();
				foreach($CartContents as $key=>$item)
				{
					if($key!=$_GET['did'])
					{
						$CartContentsNew[] = $item;
					}
				}
				$CartContentsStr = json_encode($this->wtl_convert("cp1251", "utf-8", $CartContentsNew));
				$this->VarMod->EditRecord("carts", $_GET['id'], array("contents"=>$CartContentsStr));
				header("location: ".$this->VarMod->GetLink("carts", "itemshow", array("id"=>$_GET['id'])));
			}
			else
			{
				header("location: ".$this->VarMod->GetLinkTag("main"));
			}
		}

	}
	function ClearCart($redir=true)
	{
		global $member_id;
		if(isset($member_id['user_id'])&&$member_id['user_id']!="")
		{
			$MyCartId = $member_id['user_id'];
		}
		else
		{
			// look of we have a cookie
			if(isset($_COOKIE['elvic_userid']))
			{
				$MyCartId = $_COOKIE['elvic_userid'];
			}
		}
		if($MyCartId=="")
		{
			header("location: ".$this->VarMod->GetLinkTag("main"));
		}
		else
		{
			$ItemDataArr = $this->VarMod->GetRecord("carts", $_GET['id']);
			$ItemDataArr = $ItemDataArr[0];
			$ListOfProducts = $this->VarMod->GetRecords("products");

			if($MyCartId==$ItemDataArr['user_id'])
			{
				$CartContents = $this->wtl_convert("UTF-8", "cp1251", json_decode($ItemDataArr['contents'], true));

				$this->VarMod->EditRecord("carts", $_GET['id'], array("contents"=>""));
				if($redir==true)
				{
					header("location: ".$this->VarMod->GetLink("carts", "itemshow", array("id"=>$_GET['id'])));
				}
			}
			else
			{
				if($redir==true)
				{
					header("location: ".$this->VarMod->GetLinkTag("main"));
				}
			}
		}
	}
	function CheckOutSend()
	{
		global $config;
		// Get POST values form a message get whom to send and send it
		if($_POST['fio']==""||$_POST['phone']==""||$_POST['email']=="")
		{
			$MesData = "���� Email, ��� � ������� ����������� ��� ���������� <br /><a href=\"\">�����</a>";

			return $this->VarMod->MakeTemplate($this->VarMod->templates['message'], array("msg"=>$MesData), "content");
		}

		$FIO = $_POST['fio'];
		$CmpAny = $_POST['company'];
		$StrEmail = $_POST['email'];
		$StrPhone = $_POST['phone'];
		$StrAdres = $_POST['adres'];
		$StrComments = $_POST['comments'];
		$ip = $_SERVER['REMOTE_ADDR'];
		$date = date("Y.m.d G:i:s");
		$_GET['id'] = $_POST['currentid'];
		$ListOfItems = $this->GetCartItemList();
		$GateWay = "";
		switch($_POST['gateway'])
		{
			case "paybox":
			{
				$GateWay = "Paybox";
				break;
			}
			case "robox":
			{
				$GateWay = "RoboxChange";
				break;
			}
			case "webmoney":
			{
				$GateWay = "WebMoney";
				break;
			}
			default:
			{
				$GateWay = "�����";
				break;
			}
		}

		// get full sum -----------------------------------------------
		$Green = $this->VarMod->GetRecords("carts", array("id"=>$_POST['currentid']));
		$Green = $Green[0];
		$CartContents = $this->wtl_convert("UTF-8", "cp1251", json_decode($Green['contents'], true));
		$QtyStr = count($CartContents);
		// Count total summ
		$MyObiavas = $this->VarMod->GetRecords("products");
		$MyObiavasNew = array();
		foreach($MyObiavas as $item)
		{
			$MyObiavasNew[$item['id']] = $item;
		}
		$Sum = 0;
		if(count($CartContents)>0)
		{
			foreach($CartContents as $cont)
			{
			$Sum = (float)$Sum+(float)$MyObiavasNew[$cont['prod']]['price']*$cont['qty'];
			}
		}

		$MessArr = array(
		"cart_id" => $_POST['currentid'],
		"shop_title" => $this->VarMod->MyConfig['title']['value'],
		"fio" => $FIO,
		"company" => $CmpAny,
		"email" => $StrEmail,
		"telephone" => $StrPhone,
		"address" => $StrAdres,
		"addinfo" => $StrComments,
		"ip" => $ip,
		"date" => $date,
		"items" => $ListOfItems,
		"gateway" => $GateWay,
		"totalsum" => $Sum
		);

		$OrderNum = $this->AddNewOrder($MessArr);
		$MessArr['ordernum'] = $OrderNum;
		$this->SendMessage($this->VarMod->MyConfig['site_mail']['value'], $StrEmail, "����� ����� � ".$this->VarMod->MyConfig['title']['value'], $MessArr, "mailadmin");
	    $this->SendMessage($StrEmail, $this->VarMod->MyConfig['site_mail']['value'], "��� ����� � ".$this->VarMod->MyConfig['title']['value'], $MessArr, "mailuser");

	    $this->ClearCart(false);

	    if($_POST['gateway']=="webmoney")
	    {
			$this->WebMoneyForm($MessArr);
		}
		else if($_POST['gateway']=="robox")
		{
			$this->RoboxForm($MessArr);
		}
		else if($_POST['gateway']=="paybox")
		{
			$this->PayboxForm($MessArr);
		}
		else
		{
			// now show success page
	    	$this->VarMod->MakeTemplate($this->VarMod->templates['ordersuccess'], $MessArr, "content");
		}
	}
	function WebMoneyForm($MessArr)
	{
		global $tpl;
		$total_sum_to_pay 	= $MessArr['totalsum'];
		$shop_uri 			= $this->VarMod->MyConfig['wbm_cursite']['value'];
		$url 				= "https://merchant.webmoney.ru/lmi/payment.asp";
		$payee_purse		= $this->VarMod->MyConfig['wbm_purse']['value'];
		$payment_description= $MessArr['ordernum']." - ".$MessArr['email']." - ".$MessArr['fio'];

		$post_variables = Array(
		"LMI_PAYMENT_AMOUNT" 	=> round( $total_sum_to_pay, 2),
		"LMI_PAYMENT_DESC" 		=> $payment_description,
		"LMI_PAYMENT_NO" 		=> $MessArr['ordernum'],
		"LMI_PAYEE_PURSE" 		=> $payee_purse,
		"LMI_SIM_MODE" 			=> "0",
		"LMI_RESULT_URL" 		=> "http://".$_SERVER['SERVER_NAME']."/shop/",
		"LMI_SUCCESS_URL" 		=> "http://".$_SERVER['SERVER_NAME']."/shop/",
		"LMI_SUCCESS_METHOD" 	=> "2",
		"LMI_FAIL_URL" 			=> "http://".$_SERVER['SERVER_NAME']."/shop/",
		"LMI_FAIL_METHOD" 		=> "2"
		);

		$FormStr = "";
		$FormStr .= '<h4>����������� WebMoney Keeper ����� ��������� � �������� �������.</h4>'; // Please remember to activate WebMoney Keeper prior to processing next step
		$FormStr .= '<form action="'.$url.'" method="post">';
		$FormStr .= '<input type="submit" value="�������� �����" name="formSubmit" class="button"/>';

		foreach( $post_variables as $name => $value )
		{
			$FormStr .= '<input type="hidden" name="'.$name.'" value="'.htmlspecialchars($value).'" />';
		}
		$FormStr .= '</form>';

		$this->VarMod->MakeTemplate($this->VarMod->templates['message'], array("msg"=>$FormStr), "content");
	}
	function PayboxForm($MessArr)
	{
		require_once('\engine\modules\paybox\PG_Signature.php');
		global $tpl;
		global $config;

		$form_fields = array(
			'pg_merchant_id'	=> $this->VarMod->MyConfig['pl_merchant_id']['value'],
			'pg_order_id'		=> $MessArr['ordernum'],
	//		'pg_encoding'		=> $config['charset'],
			'pg_user_ip'		=> $MessArr['ip'],
			'pg_user_email'		=> $MessArr['email'],
			'pg_contact_email'	=> $MessArr['email'],
			'pg_amount'			=> number_format($MessArr['totalsum'], 2, '.', ''),
			'pg_lifetime'		=> (!empty($this->VarMod->MyConfig['pl_lifetime']['value']))?$this->VarMod->MyConfig['pl_lifetime']['value']:0,
			'pg_testing_mode'	=> (!empty($this->VarMod->MyConfig['pl_testmode']['value'])) ? 1 : 0,
			'pg_description'	=> '#'.$MessArr['ordernum'],
			'pg_check_url'		=> 'http://'.$_SERVER['SERVER_NAME'].'/engine/paybox/callback.php',
			'pg_result_url'		=> 'http://'.$_SERVER['SERVER_NAME'].'/engine/paybox/callback.php',
			'pg_request_method'	=> 'GET',
			'pg_salt'			=> rand(21,43433), // ��������� ������������ ���������. ���������� ��������� pg_salt � ������� ���������.
		);

		if(!empty($this->VarMod->MyConfig['pg_success_url']['value']))
			$form_fields['pg_success_url'] = $this->VarMod->MyConfig['pg_success_url']['value'];
		if(!empty($this->VarMod->MyConfig['pg_failure_url']['value']))
			$form_fields['pg_failure_url'] = $this->VarMod->MyConfig['pg_failure_url']['value'];

		preg_match_all("/\d/", $MessArr['telephone'], $array);
		$strPhone = implode('',$array[0]);
		$form_fields['pg_user_phone'] = $strPhone;

		$form_fields['pg_sig'] = PG_Signature::make('payment.php', $form_fields, $this->VarMod->MyConfig['pl_secret_key']['value']);
		$query = http_build_query($form_fields);
		header("Location: https://api.paybox.money/payment.php?$query");
	}
	function RoboxForm($MessArr)
	{
		global $tpl;

		$order_number = $MessArr['ordernum'];
		// your registration data
		$mrh_login = $this->VarMod->MyConfig['rbx_login']['value'];
		// your login here
		$mrh_pass1 = $this->VarMod->MyConfig['rbx_pass']['value']; ;
		// merchant pass1 here
		// order properties
		$inv_id = $order_number; // shop's invoice number // (unique for shop's lifetime)
		$inv_desc = ""; // invoice desc
		$out_summ = $MessArr['totalsum']; // invoice summ
		// build CRC value
		$crc = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1");
		// build URL
		$url = "https://merchant.roboxchange.com/Index.aspx?MrchLogin=$mrh_login&"."OutSum=$out_summ&InvId=$inv_id&Desc=$inv_desc&SignatureValue=$crc";

		header("location: ".$url);
	}
	function GetCartItemList()
	{
		global $member_id;
		if(isset($member_id['user_id'])&&$member_id['user_id']!="")
		{
			$MyCartId = $member_id['user_id'];
		}
		else
		{
			// look of we have a cookie
			if(isset($_COOKIE['elvic_userid']))
			{
				$MyCartId = $_COOKIE['elvic_userid'];
			}
		}
		if($MyCartId=="")
		{
			header("location: ".$this->VarMod->GetLinkTag("main"));
		}
		else
		{
			$ItemDataArr = $this->VarMod->GetRecord("carts", $_GET['id']);
			$ItemDataArr = $ItemDataArr[0];
			$Products = $this->VarMod->GetRecords("products");
					$RpRod = array();
					foreach($Products as $item)
					{
						$RpRod[$item['id']] = $item;
					}
			if($MyCartId==$ItemDataArr['user_id'])
			{
					$CartContents = $this->wtl_convert("UTF-8", "cp1251", json_decode($ItemDataArr['contents'], true));
					$ItemDataArr['items'] = "";
					if(count($CartContents)>0)
					{
						$cnt = 1;
						foreach($CartContents as $key=>$utin)
						{
							// ge product data
							$MyProd = $RpRod[$utin['prod']];

							$Qty = $utin['qty'];
							$FullPrice = (float)$MyProd['price']*(int)$Qty;
							$FullPrice = $this->VarMod->GetTrueValue($FullPrice, $this->VarMod->MyClasses['products']['db']['price']);

							$BaseLink = "http://".$_SERVER['SERVER_NAME'];
							$ItemDataArr['items'] .= "<b>".$cnt.".</b> <a href=\"{$BaseLink}".$this->VarMod->GetLink("products", "itemshow", array("id"=>$utin['prod']))."\">".$this->VarMod->GetTrueValue($MyProd['category'], $this->VarMod->MyClasses['products']['db']['category'])." - ".$MyProd['title']."</a> ".$Qty."x - ".$FullPrice." ���. <br />";
							$cnt++;
						}
					}
					return $ItemDataArr['items'];
			}
			else
			{
				header("location: ".$this->VarMod->GetLinkTag("main"));
			}
		}
	}
	function ShowCart()
	{
		$Content = '';
		global $member_id, $tpl;
		$MyCartId = "";
		$Green = "";
		if(isset($member_id['user_id'])&&$member_id['user_id']!="")
		{
			$MyCartId = $member_id['user_id'];
		}
		else
		{
			// look of we have a cookie
			if(isset($_COOKIE['elvic_userid']))
			{
				$MyCartId = $_COOKIE['elvic_userid'];
			}
		}
		// get cart if exists
		if($MyCartId!="")
		{
			$Green = $this->VarMod->GetRecords("carts", array("user_id"=>$MyCartId));
			$Green = $Green[0];
			$CartContents = $this->wtl_convert("UTF-8", "cp1251", json_decode($Green['contents'], true));
			$QtyStr = 0;//count($CartContents);
			// Count total summ
			$MyObiavas = $this->VarMod->GetRecords("products");
			$MyObiavasNew = array();
			foreach($MyObiavas as $item)
			{
				$MyObiavasNew[$item['id']] = $item;
			}
			$Sum = 0;
			if(count($CartContents)>0)
			{
				foreach($CartContents as $cont)
				{
					$Sum = (float)$Sum+(float)$MyObiavasNew[$cont['prod']]['price']*$cont['qty'];
					$QtyStr = (int)$QtyStr+(int)$cont['qty'];
				}

			}
			$MoneyStr = $Sum." ���.";
		}
		else
		{
			$QtyStr = "";
			$MoneyStr = "";
		}
		if($MoneyStr!=""&&$QtyStr!="")
		{
			$ViewCartLnk = $this->VarMod->GetLink("carts", "itemshow", array("id"=>$Green['id']));
			$ClearCartLnk = $this->VarMod->GetLink("carts", "clearup", array("id"=>$Green['id']));
			$ViewOrderLnk = $this->VarMod->GetLink("carts", "checkout", array("id"=>$Green['id']));
			// Make item list
			$tpl->load_template('shop/mycart.tpl');
			$tpl->set("{view_link}", $ViewCartLnk);
			$tpl->set("{order_link}", $ViewOrderLnk);
			$tpl->set("{clear_link}", $ClearCartLnk);
			$tpl->set("{qty}", $QtyStr);
			$tpl->set("{money}", $MoneyStr);
			$tpl->compile('mycart');
			$tpl->clear();
		}
		else
		{
			$tpl->copy_template = "<div align=\"center\"><b>������� �����</b></div>";
			$tpl->compile('mycart');
			$tpl->clear();
		}
	}
	function wtl_convert($from, $to, $var)
	{
	  if (is_array($var))
	  {
	     $new = array();
	     foreach ($var as $key => $val)
	     {
	       $new[$this->wtl_convert($from, $to, $key)] = $this->wtl_convert($from, $to, $val);
	     }
	     $var = $new;
	  }
	  else if (is_string($var))
	  {
	 	 $var = iconv($from, $to, $var);
	  }
	  return $var;
	}
	function wtl_GenerateId()
	{
		return $this->wtl_rand_chars("abcdefghijklmnopqrstyzx1234567890", 10, TRUE);
	}
	function wtl_rand_chars($c, $l, $u = FALSE) {
	 if (!$u) for ($s = '', $i = 0, $z = strlen($c)-1; $i < $l; $x = rand(0,$z), $s .= $c{$x}, $i++);
	 else for ($i = 0, $z = strlen($c)-1, $s = $c{rand(0,$z)}, $i = 1; $i != $l; $x = rand(0,$z), $s .= $c{$x}, $s = ($s{$i} == $s{$i-1} ? substr($s,0,-1) : $s), $i=strlen($s));
	 return $s;
	}
	function ShowCats()
	{
		global $metatags;
		if(isset($_GET['cid']))
		{
			$where = array("parent"=>$_GET['cid']);
			// Current cat info
			$CurCat = $this->VarMod->GetRecord("categories", $_GET['cid']);
			$CurCat = $CurCat[0];
			if($CurCat['title']!="")
			{
				$metatags['title'] = str_replace("\\",'',$CurCat['title']);
			}
		}
		else
		{
			$metatags['title'] = $this->VarMod->MyConfig['title']['value'];
			$where = array("parent"=>"0");
		}
		$RetStr = "";
		$Items = $this->VarMod->GetRecords("categories", $where, array(), "");
		foreach($Items as $item)
		{
			$SubItems = $this->VarMod->GetRecords("categories", array("parent"=>$item['id']), array(), "");
			$TotAAds = $this->VarMod->GetCount("products", array("category"=>$item['id']));
			$TempStr = "";
			foreach($SubItems as $SubItem)
			{
				// count elemebts foreach of subcats
				//print_r($SubItem);
				$TotAds = $this->VarMod->GetCount("products", array("category"=>$SubItem['id']));
				$TempStr .= "<span style='padding-left:20px;'> - <a href=\"".$this->VarMod->GetLink("main", "category", array("cid"=>$SubItem['id']))."\">".$SubItem['title']."</a> (".$TotAds.")</span> <br>";
				if($SubItem["description"]!="") $TempStr .="<span style='padding-left:25px;'>".$SubItem["description"]."</span><br>";
				$TotAAds = $TotAAds +  $TotAds;
			}

			$RetStr .= "<span class=\"catlink\"><a href=\"".$this->VarMod->GetLink("main", "category", array("cid"=>$item['id']))."\">".$item['title']."</a> (".$TotAAds.") </span><br>";

			$RetStr .= $TempStr;
		}
		if($RetStr=="")
		{
			$RetStr = "� ������ ��������� ��� ������������";
		}
		return $RetStr;
	}
	function GetCategoriesRecursive($curID, $finarr)
	{
		$AlItems = $this->VarMod->GetRecords("categories", array("parent"=>$curID));
		if(count($AlItems)>0)
		{
			foreach($AlItems as $freak)
			{
				$tmparr = array();
				$finarr[] = $freak['id'];
				$tmparr = $this->GetCategoriesRecursive($freak['id'], $tmparr);
				$finarr = array_merge($finarr, $tmparr);
				unset($tmparr);
			}
		}
		unset($AlItems);
		return $finarr;
	}
	function ShowItems()
	{
		if(!isset($_GET['cid']))
		{
			$_GET['cid'] = "";
			$where = array();
		}
		else
		{
			$where["category"][] = $_GET['cid'];
            // Current cat info
            // ge subcat ids

            $PrId = $_GET['cid'];
            $AllCats = $this->GetCategoriesRecursive($PrId, $where["category"]);
            $where["category"] = array_merge($where["category"], $AllCats);
         	unset($AllCats);
         }

		return $this->VarMod->ShowList("products", array("tpl"=>"shop/mainitem.tpl", "tpllist"=>"shop/mainitemlist.tpl", "order"=>array("date"=>"DESC"), "where"=>$where));
	}
	function GetCurrentCat()
	{
		if(isset($_GET['cid']))
		{
			$where = array("parent_id"=>$_GET['cid']);
			// Current cat info
			$CurCat = $this->VarMod->GetRecord("categories", $_GET['cid']);
			$CurCat = $CurCat[0];
			return $CurCat['title'];
		}
		else
		{
			return "";
		}
	}

	function ShowBreadCrumbs()
        {
            if(isset($_GET['cid'])&&($_GET['cid']!=""))
                {
                    $PathArr = array();
                    $CurCat = $this->VarMod->GetRecord("categories", $_GET['cid']);
                    $CurCat = $CurCat[0];
                    $PathArr[] = array("cid"=>$CurCat['id'], "title"=>$CurCat['title']);
                    // Get parent
                    $ParCat = $CurCat['parent'];
                    $i=0;
                    if($ParCat!="0")
                    {
                        do
                        {
                            if($i>100)
                            {
                                break;
                            }
                            $NextCat = $this->VarMod->GetRecord("categories", $ParCat);
                            $NextCat = $NextCat[0];
                            $ParCat = $NextCat['parent'];
                            $PathArr[] = array("cid"=>$NextCat['id'], "title"=>$NextCat['title']);
                            $i++;
                        }
                        while($ParCat!="0");
                    }
                }
                else
                {
                    $PathArr = array();
                }
                $PathArr = array_reverse($PathArr, true);
                $PathStr = "<a href='/'>�������</a> &rarr; ";
                $cnt = 1;
                if(count($PathArr)>0)
                {
                	$PathStr .= "<a href=\"/shop/\">".$this->VarMod->MyConfig['title']['value']."</a>";
	                foreach($PathArr as $item)
	                {
	                    if($cnt>=count($PathArr))
	                    {
	                        $PathStr .= " &rarr; ".$item['title']."";
	                    }
	                    else
	                    {
	                    $PathStr .= " &rarr; <a href=\"".$this->VarMod->GetLink("main", "category", array("cid"=>$item['cid']))."\">".$item['title']."</a>";
	                    }
	        			$cnt++;
	                }
                }
                else
                {
					$PathStr .= $this->VarMod->MyConfig['title']['value'];
				}
                return $PathStr;
        }
	function SendMessage($SendTo, $SendFrom, $SubkectRR, $MessArr, $tplname)
	{
		global $config;
		$Varikus = file_get_contents("templates/".$config['skin']."/".$this->VarMod->templates[''.$tplname.'']);

		foreach($MessArr as $key=>$value)
		{
			$Varikus = str_replace("{".$key."}", $value, $Varikus);
		}

		include_once ENGINE_DIR.'/classes/mail.class.php';
		$mail = new dle_mail ($config, true);
		$mail->from = $SendFrom;
	    $mail->send ($SendTo, $SubkectRR, $Varikus);
	}
	function AddNewOrder($OrderData)
	{
		$OrderID = $this->GenerateOrderID();
		$ForAdd = array(
		"cart_id" => $OrderData['cart_id'],
		"gateway" => $OrderData['gateway'],
		"status" => "unconf",
		"fio" => $OrderData['fio'],
		"company" => $OrderData['company'],
		"email" => $OrderData['email'],
		"telephone" => $OrderData['telephone'],
		"address" => $OrderData['address'],
		"addinfo" => $OrderData['addinfo'],
		"ip" => $OrderData['ip'],
		"date" => $OrderData['date'],
		"items" => $OrderData['items'],
		"totalsum" => $OrderData['totalsum'],
		"ordernum" =>$OrderID
		);
		$this->VarMod->AddRecord("orders", $ForAdd);

		return $OrderID;
	}
	function GenerateOrderID()
	{
		return $this->wtl_rand_chars("1234567890", 6, TRUE);
	}

}



?>
