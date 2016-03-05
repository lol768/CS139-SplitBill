<?php


/**
 * Safe function for echoing potentially dangerous input in a way that mitigates XSS attacks.
 *
 * @param string $content User content to make safe.
 */
function se($content) {
    echo htmlentities($content, ENT_QUOTES, "UTF-8");
}

/**
 * @return \BillSplitter\Security\IAntiRequestForgery
 */
function getAntiForgeryManager() {
    $container = \BillSplitter\Application::getInstance()->getContainer();
    /** @var \BillSplitter\Security\IAntiRequestForgery $antiForgery */
    $antiForgery = $container->resolveClassInstance("\\BillSplitter\\Security\\IAntiRequestForgery");
    return $antiForgery;
}

function csrf_input() {
    $antiForgery = getAntiForgeryManager();
    echo "<input type=\"hidden\" value=\"" . $antiForgery->getCurrentCsrfToken() . "\" name=\"" . "__csrf_token" . "\">";
}


function csrf_meta() {
    $antiForgery = getAntiForgeryManager();
    echo "<meta name=\"X-Csrf-Token\" content=\"" . $antiForgery->getCurrentCsrfToken() . "\" id=\"csrf-token\">";
}

