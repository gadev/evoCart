<?php
/*
 * !!! important !!!
 * если есть еще плагины на OnPageNotFound,
 * evoCart должен вызываться перед ними
 */

$e = & $modx->event;

if($e->name == 'OnPageNotFound') {
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 	 strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {

        include_once(MODX_BASE_PATH.'assets/snippets/evoCart/core/evoCart.class.php');
        $cart = new evoCart($modx);
        switch($_GET['q']){
            case 'cart_add':
                $id = (int)$_REQUEST['id'];
                if($id === 0) {
                    echo $cart->jsonResult('Ошибка передачи данных');
                }
                $count = (int)$_REQUEST['count'];
                if (!$count || $count < 1) $count = 1;
                $options = [];
                $ignore = ['tooltipped', 'originalTitle'];
                foreach($_POST as $k => $v) {
                    if(in_array($k, array_merge(['id', 'count'], $ignore))) continue;
                    $options[$k] = $v;
                }
                echo $cart->add($id, $count, $options)->jsonResult();
                break;
            case 'cart_remove':
                $key = $_REQUEST['key'];
                echo $cart->remove($key)->jsonResult();
                break;
            case 'cart_update':
                $key = $_REQUEST['key'];
                $count = (int)$_REQUEST['count'];
                echo $cart->update($key,$count)->jsonResult();
                break;
            case 'cart_clear':
                echo $cart->clear()->jsonResult();
                break;
            case 'cart_status':
                $cart->success = true;
                echo $cart->jsonResult();
                break;
            case 'cart_full':
                $cartRowTpl = 'evoCartRow';
                echo $cart->getFullData($params['chunk'], $params['tvs']);
                break;
            default:
                $sql = $modx->db->select('pluginid', $modx->getFullTableName('site_plugin_events'), 'evtid = 1000');
                if($modx->db->getRecordCount($sql) < 2) {
                    die();
                }

        }
    }
}
