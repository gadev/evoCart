/**
 * evoCartRow
 *
 * rowTpl for evoCart
 *
 * @category	chunk
 * @version 	1.0.0
 * @author	Artyom Kremen
 * @internal    @modx_category evoCart
 * @internal    @installset base
 * @lastupdate  09/04/2018
 * @overwrite   false
 */

<div class="cart__row row-[+dl.iteration+] js-ec-item" id="[+key+]">
	<div class="item-remove">
		<a href="javascript:;" class="evoShop_remove js-ec-remove" data-key="[+key+]">×</a>
	</div>
	<div class="item-image">
		<img src="[+img+]">
	</div>
	<div class="item-name"><a href="[+url+]">[+pagetitle+]</a></div>
	<div class="item-pricer">
		<div class="item-price">[+f.tv_price+]&nbsp;руб</div>
		<div class="item-discount">[+f.dsq.size+]&nbsp;руб</div>
	</div>
	<div class="item-decrement">
		<a href="javascript:;" class="js-ec-decrement" data-key="[+key+]">-</a>
	</div>
	<div class="item-quantity js-ec-count">[+count+]</div>
	<div class="item-increment">
		<a href="javascript:;" class="js-ec-increment" data-key="[+key+]">+</a>
	</div>
	<div class="item-itogo">
		<div class="item-total">[+f.total+]&nbsp;руб</div>
		<div class="item-discount">[+f.dsq.total+]&nbsp;руб</div>
	</div>
</div>
