{if isset($layout) || $oldPSVersion == 1}
<div class="alma-widget-insurance"  id="alma-widget-insurance-product-page" {$addToCartLink}>
    <iframe id="alma-widget-insurance" src="https://protect.staging.almapay.com/almaProductInPageWidget.html"></iframe>
    <div id="alma-insurance-modal"></div>
</div>
<!-- TODO : Need to load the asset in registerJavascript() with type module -->
<script type="module" src="https://protect.staging.almapay.com/openInPageModal.js"></script>
{/if}