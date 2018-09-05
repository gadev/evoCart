//<?php
/**
 * evoCart
 *
 * main router for ajax-based evoCart actions
 *
 * @category	plugin
 * @version 	1.0.1
 * @author	Artyom Kremen
 * @internal    @properties &chunk=Чанк товара в корзине;text;evoCartRow&tvs=TV к выводу в корзине;text;img,price,brand&availability=Проверка доступности (tvname или пустое, если не проверять);text;&nds=Налог на итог;text;0
 * @internal    @events OnPageNotFound
 * @internal    @modx_category evoCart
 * @internal    @installset base
 * @lastupdate  11/04/2018
 */

require(MODX_BASE_PATH. 'assets/snippets/evoCart/evoCart.plugin.php');
