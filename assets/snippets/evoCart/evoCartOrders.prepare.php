<?php
$date = strtotime($data['date']);
$data['date'] = date('d.m.Y', $date);
$data['time'] = date('H:i', $date);

$json = json_decode($data['content'], true);
$evoShopItems = '';
$total = 0;
if(is_array($json)) {
	foreach($json['cart'] as $k => $v){
		$evoShopItems .='<li><b><a target="_blank" href='.$modx->config['site_url'].$v['url'].'>'.$v['pagetitle'].'</a></b> x '.$v['count'].' шт, <b>'.$v['price'].'</b> руб</li>';
		$total = $total + ($v['count']*$v['price']);
	}
	$evoShopItems .= '</ul><hr>';
	$evoShopItems .= '<p>Сумма заказа: <b>'.$json['total'].'</b> руб<br>'.
		'Скидка: <b>'.$json['dsq'].'</b> руб<br>'.
		'Доставка: <b>'.$json['shipping'].'</b> руб<br>'.
		'Итого: <b>'.$json['itogo'].'</b> руб</p>';
}
$data['orderdata'] = $evoShopItems;

$user = [];
if($data['userid'] > 0) {
	$user = $modx->db->getRow($modx->db->select('fullname,phone,email', $modx->getFullTableName('web_user_attributes'), 'internalKey = '.$data['userid'], '', 1));
}

$json2 = json_decode($data['short_txt'], true);
if(is_array($json2)) {
	$data['name'] = $json2['name'] ?: $user['fullname'];
	$data['message'] = !empty($json2['message']) ? '– '.$json2['message'] : '';
}

$statuses = [
	1 => 'Новый',
	10 => 'Обработан',
	20 => 'Выполнен',
	30 => 'Отменен',
];
$sel = [];
foreach($statuses as $k => $v) {
	$selected = ((int)$data['status'] === $k) ? 'selected' : '';
	$sel[] = '<option value="'.$k.'" '.$selected.'>'.$v.'</option>';
}
$data['statuses'] = implode('', $sel);

switch(trim(mb_strtolower($data['payment']))) {
	case 'наличные':
	case 'наличными':
		$i = 'fa-money';
		break;
	case 'безналичными':
	default:
		$i = 'fa-credit-card';
}
$data['payment.icon'] = '<div><span tooltip="'.$data['payment'].'"><i class="fa '.$i.'"></i></span></div>';
return $data;
