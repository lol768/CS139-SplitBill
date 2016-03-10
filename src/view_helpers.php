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
 * @return \SplitBill\Security\IAntiRequestForgery
 */
function getAntiForgeryManager() {
    $container = \SplitBill\Application::getInstance()->getContainer();
    /** @var \SplitBill\Security\IAntiRequestForgery $antiForgery */
    $antiForgery = $container->resolveClassInstance("\\SplitBill\\Security\\IAntiRequestForgery");
    return $antiForgery;
}

/**
 * @return \SplitBill\Session\IFlashSession
 */
function getFlashSession() {
    $container = \SplitBill\Application::getInstance()->getContainer();
    /** @var \SplitBill\Session\IFlashSession $flash */
    $flash = $container->resolveClassInstance("\\SplitBill\\Session\\IFlashSession");
    return $flash;
}

function csrf_input() {
    $antiForgery = getAntiForgeryManager();
    echo "<input type=\"hidden\" value=\"" . $antiForgery->getCurrentCsrfToken() . "\" name=\"" . "__csrf_token" . "\">";
}


function csrf_meta() {
    $antiForgery = getAntiForgeryManager();
    echo "<meta name=\"X-Csrf-Token\" content=\"" . $antiForgery->getCurrentCsrfToken() . "\" id=\"csrf-token\">";
}

function old_input($key) {
    $fs = getFlashSession();
    if ($fs->has("oldData")) {
        $oldData = $fs->get("oldData");
        if (array_key_exists($key, $oldData)) {
            se($oldData[$key]);
        }
    }
}
