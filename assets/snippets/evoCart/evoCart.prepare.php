<?php
//выводы для корзины

$data['price'] = number_format(str_replace(',', '.', $data['tv.price']), 2);

$data['img'] = $modx->runSnippet('phpthumb', ['input' => $data['tv.img'], 'options' => 'w_70,h_70,zc_1']);

//работаем со скидками evoCartDiscounts
if(!empty($_SESSION['discounts'])) {
    $dsq = $_SESSION['discounts'];
    $checkers = [];
    foreach($dsq['rules'] as $rule) {
        if(!empty($rule['brand']) && !empty($rule['cat'])) {
            if($rule['brand'] === $data['tv.brand'] && $rule['cat'] == $data['parent']) {
                unset($checkers);
                $checkers[] = $rule['sale'];
                break;
            }
        } elseif(!empty($rule['brand'])) {
            if($rule['brand'] === $data['tv.brand']) {
                $checkers[] = $rule['sale'];
            }
        } elseif(!empty($rule['cat'])) {
            if($rule['cat'] == $data['parent']) {
                $checkers[] = $rule['sale'];
            }
        }
    }
    if(!$checkers) {
        if(isset($dsq['main'])) {
            $checkers[] = $dsq['main'];
        }
    }
    if($checkers) {
        $max = max($checkers);

    }
    $data['dsq'] = $max > 0 ? $max : 0;
}

return $data;
