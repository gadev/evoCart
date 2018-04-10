<?php
if (empty($_SESSION['cart'])) {
	return false;
}
include_once(MODX_BASE_PATH.'assets/snippets/evoCart/core/evoCart.class.php');
$cart = new evoCart($modx);

if (count($cart->total()) > 0){

	//работаем с uid
	$uid = $modx->getLoginUserID('web');
	if($uid) {
		$user = $modx->getWebUserInfo($uid);
	}
	//Подготовка к выводу в почте
	$items = $cart->getFullData();
	$emailItems = '<h3>Состав заказа:</h3><table>';
	foreach($items['cart'] as $key => $item) {
		$emailItems .='<tr><td><b>'.$item['pagetitle'].'</b></td><td> x <b>'.$item['count'].'</b> шт</td><td><b>'.($item['f.total']).'</b> руб</td></tr>';
	}
	$emailItems .= '</table>'.
	'<h4>Сумма заказа: <b>'.$items['total'].'</b> руб</h4>'.
	'<h4>Скидка: <b>'.$items['dsq'].'</b> руб</h4>'.
	'<h4>Доставка: <b>'.$items['shipping'].'</b> руб</h4>'.
	'<h4>Итого: <b>'.$items['itogo'].'</b> руб</h4>';
	//Запись в базу данных
	$neworder = [
		'short_txt'  => json_encode($data, JSON_UNESCAPED_UNICODE),
		'content' => json_encode($items, JSON_UNESCAPED_UNICODE),
		'price' => $items['itogo'],
		'currency'  => 'руб',
		'date'  => date('Y-m-d H:i:s'),
		'sentdate'  => date('Y-m-d H:i:s'),
		'note'  => $FormLister->getField('message'),
		'email'  => $user['email'],
		'phone'  => $user['phone'],
		'payment'  => $FormLister->getField('payment'),
		'delivery'  => $FormLister->getField('delivery'),
		'address'  => $FormLister->getField('address'),
		'status'  => 1,
		'userid'  => $uid
	];
	$orderId = $modx->db->insert($neworder, $modx->getFullTableName( 'evocart_orders' ));
	$modx->db->insert([
			'timestamp'     => time(),
			'managerid'     => '',
			'action'        => '1',
			'orderid'       => $orderId,
			'message'       => 'Добавлен новый заказ',
		], $modx->getFullTableName('evocart_log')
	);

	$cart->clear();
	unset($_POST);
}


$FormLister->setField('name', $user['fullname'] );
$FormLister->setField('username', $user['username'] );
$FormLister->setField('phone', $user['phone'] );
$FormLister->setField('email', $user['email'] );
$FormLister->setField('evoCart_items', $emailItems );
