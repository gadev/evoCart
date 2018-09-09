//<?php
/**
 * evoCartDiscounts
 *
 * activate discounts tab on web user profile page
 *
 * @category	plugin
 * @version 	1.0.1
 * @internal    @disabled 1
 * @author	Artyom Kremen
 * @internal    @properties &tabName=Tab name;text;Скидки &addType=Add type;menu;dropdown;dropdown &placement=Placement;menu;content,tab;tab &order=Default container ordering;text;0
 * @internal    @events OnWebPageInit,OnManagerPageInit,OnWUsrFormRender,OnWUsrFormSave,OnWUsrFormDelete
 * @internal    @modx_category evoCart
 * @internal    @installset base
 * @lastupdate  09/09/2018
 */

require(MODX_BASE_PATH.'assets/plugins/evoCartDiscounts/init.php');
