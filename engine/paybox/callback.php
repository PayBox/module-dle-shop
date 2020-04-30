<?
define('DATALIFEENGINE', '1');
require_once('../classes/modulus.class.php');
require_once('../../upgrade/mysqli.class.php');
require_once('../data/dbconfig.php');
require_once('../modules/paybox/PG_Signature.php');
require_once('../data/shop_config.php');


$arrRequest = array();
if(!empty($_POST))
	$arrRequest = $_POST;
else
	$arrRequest = $_GET;

$CurrArr = array(
	"unconf" => "pending",
	"paid" => "ok",
	"canceld" => "failed",
);

$thisScriptName = PG_Signature::getOurScriptName();
if (empty($arrRequest['pg_sig']) || !PG_Signature::check($arrRequest['pg_sig'], $thisScriptName, $arrRequest, $ShopConfig['pl_secret_key']['value']))
	die("Wrong signature");

$MyModule = new Modulus();
$arrNotParsedOrder = $MyModule->select(PREFIX . "_shop_orders", array('ordernum' => $arrRequest['pg_order_id']));
$arrOrder = $arrNotParsedOrder[0];

if (!isset($arrRequest['pg_payment_date'])) {
	$bCheckResult = 0;
	if(empty($arrOrder) || $arrOrder['status'] != 'unconf')
		$error_desc = "Товар не доступен. Либо заказа нет, либо его статус " . @$arrOrder['status'];
	elseif($arrRequest['pg_amount'] != $arrOrder['totalsum'])
		$error_desc = "Неверная сумма";
	else
		$bCheckResult = 1;

	$arrResponse['pg_salt']              = $arrRequest['pg_salt']; // в ответе необходимо указывать тот же pg_salt, что и в запросе
	$arrResponse['pg_status']            = $bCheckResult ? 'ok' : 'error';
	$arrResponse['pg_error_description'] = $bCheckResult ?  ""  : $error_desc;
	$arrResponse['pg_sig']				 = PG_Signature::make($thisScriptName, $arrResponse, $ShopConfig['pl_secret_key']['value']);

	$objResponse = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><response/>');
	$objResponse->addChild('pg_salt', $arrResponse['pg_salt']);
	$objResponse->addChild('pg_status', $arrResponse['pg_status']);
	$objResponse->addChild('pg_error_description', $arrResponse['pg_error_description']);
	$objResponse->addChild('pg_sig', $arrResponse['pg_sig']);

}
else{
	$bResult = 0;
	if(empty($arrOrder) || ($arrOrder['status'] != "canceld" && $arrRequest['pg_result'] == 0) || ($arrOrder['status'] != "paid" && $arrOrder['status'] != "unconf" && $arrRequest['pg_result'] == 1))
		$error_desc = "Товар не доступен. Либо заказа нет, либо его статус " . @$arrOrder['status'];
	elseif($arrRequest['pg_amount'] != $arrOrder['totalsum'])
		$strResponseDescription = "Неверная сумма";
	else {
		$bResult = 1;
		$strResponseStatus = 'ok';
		$strResponseDescription = "Оплата принята";
		if ($arrRequest['pg_result'] == 1){
			// Установим статус оплачен
			$arrOrder['status'] = 'paid';
			$MyModule->update(PREFIX . "_shop_orders", $arrOrder, array('ordernum' => $arrRequest['pg_order_id']));
		}
		else{
			// Установим отказ
			$arrOrder['status'] = 'canceld';
			$MyModule->update(PREFIX . "_shop_orders", $arrOrder, array('ordernum' => $arrRequest['pg_order_id']));
		}
	}
	if(!$bResult)
		if($arrRequest['pg_can_reject'] == 1)
			$strResponseStatus = 'rejected';
		else
			$strResponseStatus = 'error';

	$objResponse = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><response/>');
	$objResponse->addChild('pg_salt', $arrRequest['pg_salt']); // в ответе необходимо указывать тот же pg_salt, что и в запросе
	$objResponse->addChild('pg_status', $strResponseStatus);
	$objResponse->addChild('pg_description', $strResponseDescription);
	$objResponse->addChild('pg_sig', PG_Signature::makeXML($thisScriptName, $objResponse, $ShopConfig['pl_secret_key']['value']));
}

header("Content-type: text/xml");
echo $objResponse->asXML();
