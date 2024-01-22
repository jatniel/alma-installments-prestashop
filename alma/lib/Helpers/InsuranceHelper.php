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

namespace Alma\PrestaShop\Helpers;

use Alma\PrestaShop\Repositories\CartProductRepository;
use Alma\PrestaShop\Repositories\ProductRepository;
use PrestaShop\PrestaShop\Adapter\Shop\Context;

if (!defined('_PS_VERSION_')) {
    exit;
}

class InsuranceHelper
{
    /**
     * @var CartProductRepository
     */
    public $cartProductRepository;

    /**
     * @var ProductRepository
     */
    public $productRepository;

    /**
     *
     */
    public function __construct()
    {
        $this->cartProductRepository = new CartProductRepository();
        $this->productRepository = new ProductRepository();
    }

    /**
     * @return bool
     */
    public function isInsuranceAllowedInProductPage()
    {
        return (bool) version_compare(_PS_VERSION_, '1.7', '>=')
            && (bool) (int) SettingsHelper::get(ConstantsHelper::ALMA_SHOW_INSURANCE_WIDGET_PRODUCT, false)
            && (bool) (int) SettingsHelper::get(ConstantsHelper::ALMA_ALLOW_INSURANCE, false)
            && (bool) (int) SettingsHelper::get(ConstantsHelper::ALMA_ACTIVATE_INSURANCE, false);
    }

    /**
     * @return bool
     */
    public function isInsuranceActivated()
    {
        return  (bool) version_compare(_PS_VERSION_, '1.7', '>=')
            && (bool) (int) SettingsHelper::get(ConstantsHelper::ALMA_ALLOW_INSURANCE, false)
            && (bool) (int) SettingsHelper::get(ConstantsHelper::ALMA_ACTIVATE_INSURANCE, false);
    }

    /**
     * @return bool
     */
    public function hasInsuranceInCart()
    {
        $idInsuranceProduct = $this->productRepository->getProductIdByReference(ConstantsHelper::ALMA_INSURANCE_PRODUCT_REFERENCE);

        if (!$idInsuranceProduct) {
            return false;
        }

        /**
         * @var \ContextCore $context
         */
        $context = \Context::getContext();
        $idProduct = $this->cartProductRepository->hasProductInCart($idInsuranceProduct, $context->cart->id);

        return (bool)$idProduct;
    }

}
