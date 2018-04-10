<?php
$modx->regClientCSS('assets/snippets/evoCart/css/evoCart.css');
$modx->regClientCSS('assets/snippets/evoCart/css/themes/metroui.css');
$modx->regClientScript('assets/snippets/evoCart/js/noty.min.js');
$modx->regClientScript('assets/snippets/evoCart/js/evoCart.js');

if($modx->documentIdentifier === (int)$cartId) {
    $modx->regClientScript('<script>var isCart = true; window.onload = evoCart.getFullCart();</script>');
}

return;
