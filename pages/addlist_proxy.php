<?php
    if (isset($_POST["proxies"]) && $_POST["proxies"]) {
        // If we have post request "proxies" it validates list of proxy and adding proxies in main proxy table
        $proxyChecker->validateListExplodeAddListProxies($_POST["proxies"], 0);
    }
?>
<?php // Form that send proxy list in post request ?>
<form action="/index.php?addlist_proxy" method="post">
    <textarea name="proxies"></textarea>
    <input class="button" type="submit">
</form>