<?php
$modx->regClientCSS('assets/snippets/evoCart/css/evoCart.css');
$modx->regClientCSS('assets/snippets/evoCart/css/themes/metroui.css');
$modx->regClientScript('assets/snippets/evoCart/js/noty.min.js');
$modx->regClientScript('assets/snippets/evoCart/js/evoCart.min.js?v=1.0.5');

if($modx->documentIdentifier === (int)$cartId) {
    $modx->regClientScript('<script>var isCart = true; window.onload = evoCart.getFullCart();</script>');
}

return;
