//<?php
/**
 * evoCart
 *
 * main snippet with js/css include
 *
 * @category	snippet
 * @version 	1.0.1
 * @author	Artyom Kremen
 * @internal    @modx_category evoCart
 * @internal    @installset base
 * @lastupdate  09/09/2018
 */

/*

страница корзины:

.js-ec-ifempty - блок скрывается при пустой корзине
.js-ec-container - общая обертка корзины
.js-ec-cart - блок вывода товаров в корзине
.js-ec-total - сумма заказа
.js-ec-dsq - сумма скидки
.js-ec-shipping - сумма доставки
.js-ec-itogo - итого
.js-ec-qty - кол-во товаров в корзине
.js-ec-qty-txt - товар/товара/товаров

чанк товара в корзине evoCartRow:

.js-ec-remove // data-key="[+key+]" – удаление товара по ключу
.js-ec-decrement // data-key="[+key+]" – +1 шт товара по ключу
.js-ec-increment // data-key="[+key+]" – -1 шт товара по ключу
.js-ec-count – кол-во товара в корзине (span)
.js-ec-count-input – кол-во товара в корзине (input)

Плейсхолдеры:

[+key+] - ключ в корзине
[+tv_price+] - цена
[+tprice+] - цена
[+total+] - цена * кол-во
[+dsq.price+] - цена со скидкой
[+dsq.size+] - размер скидки
[+dsq.total+] - размер скидки * кол-во

*/

return require MODX_BASE_PATH.'assets/snippets/evoCart/evoCart.snippet.php';
