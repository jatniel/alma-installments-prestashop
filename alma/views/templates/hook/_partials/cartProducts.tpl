{*
 * 2018-2024 Alma SAS
 *
 * THE MIT LICENSE
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated
 * documentation files (the "Software"), to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and
 * to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the
 * Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF
 * CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
 * IN THE SOFTWARE.
 *
 * @author    Alma SAS <contact@getalma.eu>
 * @copyright 2018-2024 Alma SAS
 * @license   https://opensource.org/licenses/MIT The MIT License
 *}
{capture assign='productRegularPriceInCent'}{$product.price_without_reduction|escape:'htmlall':'UTF-8' * 100}{/capture}
{capture assign='cmsReference'}{almaCmsReference product_id=$product.id_product product_attribute_id=$product.id_product_attribute regular_price=$product.price_without_reduction}{/capture}

<div class="row py-1">
    <div class="col-md-11">
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="product-line-grid-left col-md-5 offset-md-1 col-xs-6">
                        <span class="product-image media-middle">
                            {if $product.default_image}
                                <img src="{$product.default_image.bySize.cart_default.url}" alt="{$product.name|escape:'quotes'}" loading="lazy">
                            {else}
                                <img src="{$urls.no_picture_image.bySize.cart_default.url}" loading="lazy"/>
                            {/if}
                          </span>
                    </div>

                    <div class="product-line-grid-left col-xs-6">
                        <div class="product-line-info">
                            <span class="label">
                                {$product.name}
                            </span>
                        </div>

                        <div class="product-line-info product-price h5">
                            <div class="current-price">
                                <span class="price">{$product.price}</span>
                                {if $product.unit_price_full}
                                    <div class="unit-price-cart">{$product.unit_price_full}</div>
                                {/if}
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {if $hasInsurance == '1'}
                <div class="col-md-6">
                    <div class="row item-alma-insurance">
                        <div class="product-line-grid-left col-md-5 col-xs-6">
                            <span class="product-image media-middle">
                                <img src="{$associatedInsurances[$idAlmaInsuranceProduct]['urlImage']}" alt="{$associatedInsurances[$idAlmaInsuranceProduct]['name']}" loading="lazy">
                            </span>
                        </div>
                        <div class="product-line-grid-left col-md-7 col-xs-6">
                            <div class="product-line-info">
                                <span class="label">
                                    {$associatedInsurance.insuranceProduct->getFieldByLang('name', $idLanguage)|escape:'htmlall':'UTF-8'}
                                    <strong>
                                        {$associatedInsurance.insuranceProductAttribute->reference|escape:'htmlall':'UTF-8'}
                                    </strong>
                                </span>
                            </div>
                            <div class="product-line-info product-price h5">
                                <div class="current-price">
                                    <span class="price">{Context::getContext()->currentLocale->formatPrice($associatedInsurance.price, $currency.iso_code)}</span>
                                </div>
                            </div>
                            <div class="alma-action-item-insurance text-right">
                                <a data-alma-association-id="{$idAlmaInsuranceProduct}"
                                   data-action="remove-insurance-product"
                                   data-token='{\Tools::getToken(false)|escape:'htmlall':'UTF-8'}'
                                   href="#"
                                   class="alma-remove-insurance-product"
                                   data-link='{$ajaxLinkRemoveInsuranceProduct|escape:'htmlall':'UTF-8'}'
                                >
                                    {l s='Remove insurance' mod='alma'}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            {else}
                {if $insuranceSettings.isInCartWidgetActivated}
                <div class="col-md-6">
                    <div class="row item-alma-insurance">
                        <div class="product-line-grid-left col-md-2 col-xs-3">
                            <span class="product-image media-middle">
                                <img src="/modules/alma/views/img/logos/shield.svg" alt="{l s='Alma insurance' mod='alma'}">
                            </span>
                        </div>
                        <div class="product-line-grid-left col-md-10 col-xs-9">
                            <div class="product-line-info">
                                <span class="label">
                                    {l s='Protect your product with' mod='alma'}
                                    <strong>
                                        Alma
                                    </strong>
                                </span>
                            </div>
                            <div class="alma-action-item-insurance">
                                <a data-product-id="{$product.id_product|escape:'htmlall':'UTF-8'}"
                                   data-product-attribute-id="{$product.id_product_attribute|escape:'htmlall':'UTF-8'}"
                                   data-product-price="{$productRegularPriceInCent}"
                                   data-product-customization-id="{$product.id_customization|intval}"
                                   data-id-iframe="product-alma-iframe-{$cmsReference}"
                                   data-action="add-insurance-product"
                                   data-token='{\Tools::getToken(false)|escape:'htmlall':'UTF-8'}'
                                   href="#"
                                   id="add-insurance-product-{$cmsReference}"
                                   class="alma-add-insurance-product"
                                   data-link='{$ajaxLinkAddInsuranceProduct|escape:'htmlall':'UTF-8'}'
                                >
                                    {l s='See available insurance policies' mod='alma'}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                {/if}
            {/if}
        </div>

    </div>

    {if $hasInsurance == '1'}
        <div class="col-md-1 text-xs-right">
            <a data-alma-association-id="{$idAlmaInsuranceProduct}"
               data-action="remove-product-with-insurance"
               data-token='{\Tools::getToken(false)|escape:'htmlall':'UTF-8'}'
               href="#"
               class="alma-remove-association"
               data-link='{$ajaxLinkAlmaRemoveAssociation|escape:'htmlall':'UTF-8'}'
            >
                <i class="material-icons float-xs-left">delete</i>
            </a>
        </div>
    {else}
        <div class="col-md-1 text-xs-right">
            <a data-product-id="{$product.id_product|escape:'javascript'}"
               data-product-attribute-id="{$product.id_product_attribute|escape:'javascript'}"
               data-product-customization-id="{$product.id_customization|escape:'javascript'}"
               data-action="remove-product-without-insurance"
               data-token='{\Tools::getToken(false)|escape:'htmlall':'UTF-8'}'
               data-link='{$ajaxLinkAlmaRemoveProduct|escape:'htmlall':'UTF-8'}'
               href="#"
               class="alma-remove-product"
            >
                <i class="material-icons float-xs-left">delete</i>
            </a>
        </div>
    {/if}
</div>