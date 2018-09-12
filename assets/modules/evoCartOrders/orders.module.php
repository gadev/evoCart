<?php

include_once (MODX_BASE_PATH . 'assets/snippets/DocLister/lib/DLTemplate.class.php');
include_once (MODX_BASE_PATH . 'assets/lib/APIHelpers.class.php');


//
$modx->tpl = \DLTemplate::getInstance($modx);
$modx->tpl->setTemplateExtension('tpl');
$modx->tpl->setTemplatePath('assets/modules/evoCartOrders/tpl/');

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
		$dl = [];
		$fields = ['id', 'phone', 'email', 'status'];
		$filters = [];
		$is_ajax = false;
		if($_POST) {
			$is_ajax = true;

			if((int)$_POST['page'] > 1) {
				$_GET['list_page'] = (int)$_POST['page'];
				unset($_POST['page']);
			}
			if(!empty($_POST['orderby'])) {
				$dl['orderBy'] = str_replace(':', ' ', $modx->db->escape($modx->stripTags($_POST['orderby'])));
			}
			if((int)$_POST['display'] > 1) {
				$dl['display'] = (int)$_POST['display'];
				unset($_POST['display']);
			}

			foreach($_POST as $k => $v) {
				if(in_array($k, $fields, true) && !empty($v)) {
					switch($k) {
						case 'id':
						case 'status':
							$filters[] = $k.' = '.$modx->db->escape($modx->stripTags($v));
							break;
						default:
							$filters[] = $k.' LIKE "%'.$modx->db->escape($modx->stripTags($v)).'%"';
					}
				}
			}
		}

		$params = [
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
			'addWhereList' => implode(' AND ', $filters),
			'id' => 'list',
			'display' => 10,
			'debug' => 0,
			'TplWrapPaginate' => '@CODE: <ul class="[+class+]">[+wrap+]</ul>',
			'TplPage' => '@CODE: <li class="page-item"><a href="[+link+]" class="page-link page" data-page="[+num+]">[+num+]</a></li>',
			'TplCurrentPage' => '@CODE: <li class="page-item active">
		      <span class="page-link">[+num+]</span>
		    </li>',
			'TplNextP' => '@CODE:',
			'TplPrevP' => '@CODE:',
			'noRedirect' => 1
		];

		$data['list'] = $modx->runSnippet('DocLister', array_merge($params, $dl));

		$data['pages'] = str_replace(['0?', '0.html?'], 'manager/index.php?', $modx->getPlaceholder('list.pages'));
		if($is_ajax) {
			echo json_encode($data);
			exit;
		}

		//забираем статистику
		/*
		$o_total = $modx->db->getRow($modx->db->query("SELECT COUNT(*) AS cnt, SUM(price) AS price FROM ".$modx->getFullTableName('evocart_orders')));
        $o_price = number_format($o_total['price']+$o_total_old['price'], 2, ',', ' ');
		$o_avg = round(($o_total['price']+$o_total_old['price']) / ($o_total['cnt']+$o_total_old['cnt']));
		*/


		$inner = $modx->tpl->parseChunk('@FILE:list', $data);
}


$header = $modx->tpl->parseChunk('@FILE:header',$data);
$footer = $modx->tpl->parseChunk('@FILE:footer',$data);

$output = $header . $inner . $footer;
echo $output;
