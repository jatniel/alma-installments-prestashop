<?php
/**
 * 2018-2020 Alma SAS
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
 * @copyright 2018-2020 Alma SAS
 * @license   https://opensource.org/licenses/MIT The MIT License
 */

namespace Alma\PrestaShop\Controllers\Hook;

if (!defined('_PS_VERSION_')) {
    exit;
}

use Alma\PrestaShop\Hooks\FrontendHookController;
use Alma\PrestaShop\Utils\Settings;

use Product;

final class DisplayProductPriceBlockHookController extends FrontendHookController
{
    public function canRun()
    {
        return parent::canRun() &&
            $this->context->controller->php_self == 'product' &&
            Settings::showProductEligibility() &&
            Settings::getMerchantId() != null;
    }

    public function run($params)
    {
        if (array_key_exists('type', $params)) {
            if (
                (version_compare(_PS_VERSION_, '1.6.0', '>') && $params['type'] === 'price') ||
                (!in_array($params['type'], array("price", "after_price")))
            ) {
                return null;
            }
        }

        /** @var Product $product */
        if ($params['product'] instanceof Product) {
            $product = $params['product'];
            $price = almaPriceToCents($product->getPrice(true));
            $productId = $product->id;

            // Since we don't have access to the combination ID nor the wanted quantity, we should reload things from
            // the frontend to make sure we're displaying something relevant
            $refreshPrice = true;
        } else {
            $productParams = $params['product'];
            $productId = $productParams['id_product'];

            $quantity = max((int)$productParams['minimal_quantity'], (int)$productParams['quantity_wanted']);
            $price = almaPriceToCents(
                Product::getPriceStatic(
                    $productId,
                    true,
                    $productParams['id_product_attribute'],
                    6,
                    null,
                    false,
                    true,
                    $quantity
                )
            );

            // Being able to use `quantity_wanted` here means we don't have to reload price on the front-end
            $price *= $quantity;
            $refreshPrice = false;
        }

        $globalMin = PHP_INT_MAX;
        $globalMax = 0;

        $n = 1;
        while ($n < Settings::installmentPlansMaxN()) {
            ++$n;

            if (!Settings::isInstallmentPlanEnabled($n)) {
                continue;
            } else {
                $min = Settings::installmentPlanMinAmount($n);
                $globalMin = min($min, $globalMin);

                $max = Settings::installmentPlanMaxAmount($n);
                $globalMax = max($max, $globalMax);
            }
        }

        $psVersion = 'ps15';
        if (version_compare(_PS_VERSION_, '1.7', '>=')) {
            $psVersion = 'ps17';
        } else if (version_compare(_PS_VERSION_, '1.6', '>=')) {
            $psVersion = 'ps16';
        }

        $this->context->smarty->assign(
            array(
                'merchantId' => Settings::getMerchantId(),
                'apiMode' => Settings::getActiveMode(),
                'installmentsCounts' => Settings::activeInstallmentsCounts(),
                'productId' => $productId,
                'productPrice' => $price,
                'refreshPrice' => $refreshPrice,
                'logo' => almaSvgDataUrl(_PS_MODULE_DIR_ . $this->module->name . '/views/img/logos/logo_alma.svg'),
                'min' => $globalMin,
                'max' => $globalMax,
                'psVersion' => $psVersion
            )
        );

        return $this->module->display($this->module->file, 'displayProductPriceBlock.tpl');
    }
}
