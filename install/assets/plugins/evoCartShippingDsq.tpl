//<?php
/**
 * evoCartShippingDsq
 *
 * работа с расчетом стоимости доставки и общей скидки
 *
 * @category	plugin
 * @version 	1.0.1
 * @author	Artyom Kremen
 * @internal    @disabled 1
 * @internal    @properties
 * @internal    @events OnBeforeEvoCartRender
 * @internal    @modx_category evoCart
 * @internal    @installset base
 * @lastupdate  09/09/2018
 */

 $e = &$modx->event;
 switch ($e->name){
 	case 'OnBeforeEvoCartRender':
 		$shipping = 0;

        //примеры
 		if($e->params['delivery'] == 'Курьер') {
 			$shipping = 5;

 			if($e->params['total'] - $e->params['dsq'] > 100) {
 				$shipping = 0;
 			} elseif($e->params['total'] - $e->params['dsq'] > 50) {
 				$shipping = 3;
 			}

 			if($e->params['qty'] >= 10) {
 				$shipping = 0;
 			}
 		}


 		$e->output($shipping);
 		break;
 }
