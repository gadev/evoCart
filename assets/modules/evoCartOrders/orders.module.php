<?php

include_once (MODX_BASE_PATH . 'assets/snippets/DocLister/lib/DLTemplate.class.php');
include_once (MODX_BASE_PATH . 'assets/lib/APIHelpers.class.php');
include_once (MODX_BASE_PATH . 'assets/modules/orders/core/prepareDL.class.php');


//
$modx->tpl = \DLTemplate::getInstance($modx);
$modx->tpl->setTemplateExtension('tpl');
$modx->tpl->setTemplatePath('assets/modules/orders/tpl/');

//
$moduleurl = 'index.php?a=112&id='.$_GET['id'].'&';
$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$data = [
	'tpl'=>$tpl,
	'tvs'=>$tvs,
	'moduleurl'=>$moduleurl,
	'manager_theme'=>$modx->config['manager_theme'],
	'manager_path'=>$modx->getManagerPath(),
	'base_url'=>$modx->config['base_url'],
	'session'=>$_SESSION,
	'get'=>$_GET,
	'action'=>$action,
	'selected'=>array($action=>'selected')
];

switch($action) {
	case 'status':
		$order = (int)$modx->stripTags($modx->db->escape($_GET['order']));
		$status = (int)$modx->stripTags($modx->db->escape($_GET['status']));
		if($order > 0 && $status > 0) {
			$modx->db->update(['status' => $_GET['status']], $modx->getFullTableName('evocart_orders'), 'id='.$_GET['order']);
			$res = [
				'result' => 'Статус обновлен'
			];
		} else {
			$res = [
				'error' => 'Ошибка передачи данных'
			];
		}
		exit(json_encode($res, true));
		break;
	case 'list':
	default:
		$data['list'] = $modx->runSnippet('DocLister', [
			'controller' => 'onetable',
			'table' => 'evocart_orders',
			'idField' => 'id',
			'tpl' => '@CODE:'.$modx->tpl->getChunk('@FILE: list.row'),
			'orderBy'=>'id ASC',
			'showParent' => '-1',
			'idType' => 'documents',
			'ignoreEmpty' => '1',
			'prepare' => 'prepareEvoCartOrders',
			'orderBy' => 'id DESC',
			'paginate' => 'pages',
			'id' => 'list',
			'display' => 10,
			'debug' => 0
		]);
		$modx->setPlaceholder('list.pages', str_replace('0?', 'manager/index.php?', $modx->getPlaceholder('list.pages')));
		$inner = $modx->tpl->parseChunk('@FILE:list', $data);
}


$header = $modx->tpl->parseChunk('@FILE:header',$data);
$footer = $modx->tpl->parseChunk('@FILE:footer',$data);

$output = $header . $inner . $footer;
echo $output;
