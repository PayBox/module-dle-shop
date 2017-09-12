<div style="padding: 5px; border: 1px #ccc dashed; border-bottom: none; width: 150px;"><span style="font-size: 14px; font-weight: bold;">Перечень товаров</span></div>
<div style="padding: 5px; border: 1px #ccc dashed;">
{cartitems}
</div>
<div style="padding: 5px;">
<form action="" method="post">
<table width="100%" style="padding: 5px;">
  <tr>
	<td colspan="2"><span style="font-size: 14px; font-weight: bold;">Форма заказа</b><hr /><br /></td>
  <tr>
  <tr>
	<td>Метод оплаты</td>
	<td><select name="gateway">
	<option value="webmoney">WebMoney</option>
	<option value="platron">Platron</option>
	<option value="robox">Robox</option>
	<option value="order" selected>Заказ</option>
	</select>
	</td>
  <tr>
  <tr>
	<td>Ф.И.О. *</td>
	<td><input type="text" name="fio" value="" /></td>
  <tr>
  <tr>
	<td>Компания</td>
	<td><input type="text" name="company" value="" /></td>
  <tr>
  <tr>
	<td>Email  *</td>
	<td><input type="text" name="email" value="" /></td>
  <tr>
  <tr>
	<td>Телефон  *</td>
	<td><input type="text" name="phone" value="" /></td>
  <tr>
  <tr>
	<td>Адрес</td>
	<td><textarea name="adres" cols="22" rows="4"></textarea></td>
  <tr>
  <tr>
	<td>Примечание:</td>
	<td><textarea name="comments" cols="37" rows="6"></textarea></td>
  <tr>
  <tr>
	<td colspan="2">
	<input type="hidden" name="currentid" value="{cartid}" />
	<input type="hidden" name="sent" value="yes" />
	<input type="submit" name="submit" value="отправить" />
	</td>
  <tr>
</table>
</form>
</div>



