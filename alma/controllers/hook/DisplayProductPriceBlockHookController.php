<?php
/**
 * 2018-2023 Alma SAS.
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
 * @copyright 2018-2023 Alma SAS
 * @license   https://opensource.org/licenses/MIT The MIT License
 */

namespace Alma\PrestaShop\Controllers\Hook;

if (!defined('_PS_VERSION_')) {
    exit;
}

use Alma\PrestaShop\Builders\CustomFieldHelperBuilder;
use Alma\PrestaShop\Builders\LocaleHelperBuilder;
use Alma\PrestaShop\Builders\PriceHelperBuilder;
use Alma\PrestaShop\Builders\SettingsHelperBuilder;
use Alma\PrestaShop\Forms\ExcludedCategoryAdminFormBuilder;
use Alma\PrestaShop\Helpers\CustomFieldsHelper;
use Alma\PrestaShop\Helpers\LinkHelper;
use Alma\PrestaShop\Helpers\LocaleHelper;
use Alma\PrestaShop\Helpers\PriceHelper;
use Alma\PrestaShop\Helpers\ProductHelper;
use Alma\PrestaShop\Helpers\SettingsHelper;
use Alma\PrestaShop\Hooks\FrontendHookController;

class DisplayProductPriceBlockHookController extends FrontendHookController
{
    /**
     * @var LocaleHelper
     */
    protected $localeHelper;

    /**
     * @var PriceHelper
     */
    protected $priceHelper;

    /**
     * @var SettingsHelper
     */
    protected $settingsHelper;

    /**
     * @var CustomFieldsHelper
     */
    protected $customFieldsHelper;

    /**
     * HookController constructor.
     *
     * @codeCoverageIgnore
     *
     * @param $module Alma
     */
    public function __construct($module)
    {
        parent::__construct($module);

        $localeHelperBuilder = new LocaleHelperBuilder();
        $this->localeHelper = $localeHelperBuilder->getInstance();

        $priceHelperBuilder = new PriceHelperBuilder();
        $this->priceHelper = $priceHelperBuilder->getInstance();

        $settingsHelperBuilder = new SettingsHelperBuilder();
        $this->settingsHelper = $settingsHelperBuilder->getInstance();

        $customFieldHelperBuilder = new CustomFieldHelperBuilder();
        $this->customFieldsHelper = $customFieldHelperBuilder->getInstance();
    }

    public function canRun()
    {
        return parent::canRun()
            && \Tools::strtolower($this->currentControllerName()) == 'product'
            && SettingsHelper::showProductEligibility()
            && SettingsHelper::getMerchantId() != null;
    }

    public function run($params)
    {
        if (array_key_exists('type', $params)) {
            if (version_compare(_PS_VERSION_, '1.7', '>')) {
                $skip = $params['type'] === 'price' || (!in_array($params['type'], ['price', 'after_price']));
            } elseif (version_compare(_PS_VERSION_, '1.6', '>')) {
                $skip = $params['type'] !== 'after_price';
            } else {
                $skip = !in_array($params['type'], ['price', 'after_price']);
            }

            if ($skip) {
                return null;
            }
        }

        /* @var \Product $product */
        if (isset($params['product']) && $params['product'] instanceof \Product) {
            $product = $params['product'];
            $price = $this->priceHelper->convertPriceToCents($product->getPrice(true));
            $productId = $product->id;

            // Since we don't have access to the combination ID nor the wanted quantity, we should reload things from
            // the frontend to make sure we're displaying something relevant
            $refreshPrice = true;
        } else {
            $productParams = isset($params['product']) ? $params['product'] : [];

            $productId = isset($productParams['id_product'])
                ? $productParams['id_product']
                : \Tools::getValue('id_product');

            $productAttributeId = isset($productParams['id_product_attribute'])
                ? $productParams['id_product_attribute']
                : null;

            $productHelper = new ProductHelper();
            $quantity = $productHelper->getQuantity($productParams);

            $price = $this->priceHelper->convertPriceToCents(
                $productHelper->getPriceStatic($productId, $productAttributeId, $quantity)
            );

            // Being able to use `quantity_wanted` here means we don't have to reload price on the front-end
            $price *= $quantity;
            $refreshPrice = $productAttributeId === null;
        }

        if (\Tools::getValue('id_product') != $productId) {
            return null;
        }

        $psVersion = 'ps15';
        if (version_compare(_PS_VERSION_, '1.7', '>=')) {
            $psVersion = 'ps17';
        } elseif (version_compare(_PS_VERSION_, '1.6', '>=')) {
            $psVersion = 'ps16';
        }

        $activePlans = $this->settingsHelper->activePlans();

        $locale = $this->localeHelper->getLocaleByIdLangForWidget($this->context->language->id);

        if (!$activePlans) {
            return;
        }

        $isEligible = true;

        if (!SettingsHelper::showProductWidgetIfNotEligible()) {
            $feePlans = json_decode(SettingsHelper::getFeePlans());

            $isEligible = false;
            foreach ($feePlans as $feePlan) {
                if (1 == $feePlan->enabled) {
                    if ($price >= $feePlan->min && $price <= $feePlan->max) {
                        $isEligible = true;
                        break;
                    }
                }
            }
        }
        if (!SettingsHelper::showCategoriesWidgetIfNotEligible() && $this->settingsHelper->isProductExcluded($productId)) {
            $isEligible = false;
        }
        if ($isEligible) {
            $this->context->smarty->assign([
            'productId' => $productId,
            'psVersion' => $psVersion,
            'logo' => LinkHelper::getSvgDataUrl(_PS_MODULE_DIR_ . $this->module->name . '/views/img/logos/logo_alma.svg'),
            'isExcluded' => $this->settingsHelper->isProductExcluded($productId),
            'exclusionMsg' => $this->customFieldsHelper->getBtnValueByLang(
                $this->context->language->id,
                ExcludedCategoryAdminFormBuilder::ALMA_NOT_ELIGIBLE_CATEGORIES
            ),
            'settings' => [
                'merchantId' => SettingsHelper::getMerchantId(),
                'apiMode' => SettingsHelper::getActiveMode(),
                'amount' => $price,
                'plans' => $activePlans,
                'refreshPrice' => $refreshPrice,
                'decimalSeparator' => LocaleHelper::decimalSeparator(),
                'thousandSeparator' => LocaleHelper::thousandSeparator(),
                'showIfNotEligible' => SettingsHelper::showProductWidgetIfNotEligible(),
                'locale' => $locale,
                ],
            'widgetQuerySelectors' => json_encode([
                'price' => SettingsHelper::getProductPriceQuerySelector(),
                'attrSelect' => SettingsHelper::getProductAttrQuerySelector(),
                'attrRadio' => SettingsHelper::getProductAttrRadioQuerySelector(),
                'colorPick' => SettingsHelper::getProductColorPickQuerySelector(),
                'quantity' => SettingsHelper::getProductQuantityQuerySelector(),
                'isCustom' => SettingsHelper::isWidgetCustomPosition(),
                'position' => SettingsHelper::getProductWidgetPositionQuerySelector(),
                ]),
            ]);

            return $this->module->display($this->module->file, 'displayProductPriceBlock.tpl');
        }
    }
}
