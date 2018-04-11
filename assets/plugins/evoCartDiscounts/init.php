<?php
include_once MODX_BASE_PATH . 'assets/plugins/evoCartDiscounts/core.php';

$e = &$modx->event;

switch ($e->name) {
	case 'OnWebPageInit': {
		$uid = $modx->getLoginUserID('web');
		if($uid > 0) {
			$_SESSION['discounts'] = (new evoCartDiscounts($modx))->render(['user_id' => $uid]);
		}
	}

	case 'OnManagerPageInit': {
		(new evoCartDiscounts($modx))->install();
        return;
	}

    case 'OnWUsrFormRender': {
        $e->output((new evoCartDiscounts($modx))->renderForm());
        return;
    }

    case 'OnWUsrFormSave': {
        (new evoCartDiscounts($modx))->save();
        return;
    }

    case 'OnWUsrFormDelete': {
        (new evoCartDiscounts($modx))->delete();
        return;
    }


}
