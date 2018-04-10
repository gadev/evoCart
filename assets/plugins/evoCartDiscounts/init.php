<?php
include_once MODX_BASE_PATH . 'assets/plugins/evoCartDiscounts/core.php';

$e = &$modx->event;

switch ($e->name) {
	case 'OnWebPageInit': {
		$uid = $modx->getLoginUserID('web');
		if($uid > 0) {
			$_SESSION['discounts'] = (new evoCartSales($modx))->render(['user_id' => $uid]);
		}
	}

	case 'OnManagerPageInit': {
		(new evoCartSales($modx))->install();
        return;
	}

    case 'OnWUsrFormRender': {
        $e->output((new evoCartSales($modx))->renderForm());
        return;
    }

    case 'OnWUsrFormSave': {
        (new evoCartSales($modx))->save();
        return;
    }

    case 'OnWUsrFormDelete': {
        (new evoCartSales($modx))->delete();
        return;
    }


}
