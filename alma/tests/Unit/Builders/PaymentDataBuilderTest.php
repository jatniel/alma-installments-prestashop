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

namespace Alma\PrestaShop\Tests\Unit\Builders;

use Alma\PrestaShop\Builders\PaymentDataBuilder;
use Alma\PrestaShop\Factories\ContextFactory;
use Alma\PrestaShop\Helpers\AddressHelper;
use Alma\PrestaShop\Helpers\CarrierHelper;
use Alma\PrestaShop\Helpers\CartHelper;
use Alma\PrestaShop\Helpers\ConfigurationHelper;
use Alma\PrestaShop\Helpers\CountryHelper;
use Alma\PrestaShop\Helpers\CurrencyHelper;
use Alma\PrestaShop\Helpers\CustomerHelper;
use Alma\PrestaShop\Helpers\CustomFieldsHelper;
use Alma\PrestaShop\Helpers\LanguageHelper;
use Alma\PrestaShop\Helpers\LocaleHelper;
use Alma\PrestaShop\Helpers\OrderHelper;
use Alma\PrestaShop\Helpers\OrderStateHelper;
use Alma\PrestaShop\Helpers\PriceHelper;
use Alma\PrestaShop\Helpers\ProductHelper;
use Alma\PrestaShop\Helpers\SettingsHelper;
use Alma\PrestaShop\Helpers\ShopHelper;
use Alma\PrestaShop\Helpers\StateHelper;
use Alma\PrestaShop\Helpers\ToolsHelper;
use Alma\PrestaShop\Helpers\ValidateHelper;
use Alma\PrestaShop\Model\CarrierData;
use Alma\PrestaShop\Model\CartData;
use Alma\PrestaShop\Model\PaymentData;
use Alma\PrestaShop\Model\ShippingData;
use Alma\PrestaShop\Repositories\OrderRepository;
use Alma\PrestaShop\Repositories\ProductRepository;
use PHPUnit\Framework\TestCase;

class PaymentDataBuilderTest extends TestCase
{
    /**
     *
     * @var PaymentDataBuilder $paymentDataBuilder
     */
    protected $paymentDataBuilder;

    /**
     *
     * @var SettingsHelper $settingsHelper
     */
    protected $settingsHelper;

    /**
     *
     * @var PriceHelper $priceHelper
     */
    protected $priceHelper;

    /**
     *
     * @var CarrierHelper $carrierHelper
     */
    protected $carrierHelper;


    public function setUp() {
        $this->paymentDataBuilder = new PaymentDataBuilder();
        $this->settingsHelper = new SettingsHelper(
            new ShopHelper(),
            new ConfigurationHelper()
        );

        $this->priceHelper =  new PriceHelper(
            new ToolsHelper(),
            new CurrencyHelper()
        );

        $this->carrierHelper =  new CarrierHelper(
            new ContextFactory(),
            new CarrierData()
        );
    }


    public function testGetInstance() {
        $this->assertInstanceOf(PaymentData::class, $this->paymentDataBuilder->getInstance());
    }

    public function testGetToolsHelper()
    {
        $this->assertInstanceOf(ToolsHelper::class, $this->paymentDataBuilder->getToolsHelper());
        $this->assertInstanceOf(ToolsHelper::class, $this->paymentDataBuilder->getToolsHelper(
            new ToolsHelper()
        ));
    }

    public function testGetSettingsHelper()
    {
        $this->assertInstanceOf(SettingsHelper::class, $this->paymentDataBuilder->getSettingsHelper());
        $this->assertInstanceOf(SettingsHelper::class, $this->paymentDataBuilder->getSettingsHelper(
            $this->settingsHelper
        ));
    }

    public function testPriceHelper()
    {
        $this->assertInstanceOf(PriceHelper::class, $this->paymentDataBuilder->getPriceHelper());
        $this->assertInstanceOf(PriceHelper::class, $this->paymentDataBuilder->getPriceHelper(
            $this->priceHelper
        ));
    }

    public function testGetCustomFieldsHelper()
    {
        $this->assertInstanceOf(CustomFieldsHelper::class, $this->paymentDataBuilder->getCustomFieldsHelper());
        $this->assertInstanceOf(CustomFieldsHelper::class, $this->paymentDataBuilder->getCustomFieldsHelper(
            new CustomFieldsHelper(
                new LanguageHelper(),
                new LocaleHelper(new LanguageHelper()),
                $this->settingsHelper
            )
        ));
    }

    public function testGetCartData()
    {
        $this->assertInstanceOf(CartData::class, $this->paymentDataBuilder->getCartData());
        $this->assertInstanceOf(CartData::class, $this->paymentDataBuilder->getCartData(
            new CartData(
                new ProductHelper(),
                $this->settingsHelper,
                $this->priceHelper,
                new ProductRepository()
            )
        ));
    }

    public function testGetShippingData()
    {
        $this->assertInstanceOf(ShippingData::class, $this->paymentDataBuilder->getShippingData());
        $this->assertInstanceOf(ShippingData::class, $this->paymentDataBuilder->getShippingData(
            new ShippingData(
                $this->priceHelper,
                $this->carrierHelper
            )
        ));
    }

    public function testGetContextFactory()
    {
        $this->assertInstanceOf(ContextFactory::class, $this->paymentDataBuilder->getContextFactory());
        $this->assertInstanceOf(ContextFactory::class, $this->paymentDataBuilder->getContextFactory(
            new ContextFactory()
        ));
    }

    public function testGetAddressHelper()
    {
        $this->assertInstanceOf(AddressHelper::class, $this->paymentDataBuilder->getAddressHelper());
        $this->assertInstanceOf(AddressHelper::class, $this->paymentDataBuilder->getAddressHelper(
            new AddressHelper(new ToolsHelper())
        ));
    }

    public function testGetCountryHelper()
    {
        $this->assertInstanceOf(CountryHelper::class, $this->paymentDataBuilder->getCountryHelper());
        $this->assertInstanceOf(CountryHelper::class, $this->paymentDataBuilder->getCountryHelper(
            new CountryHelper()
        ));
    }

    public function testGetLocaleHelper()
    {
        $this->assertInstanceOf(LocaleHelper::class, $this->paymentDataBuilder->getLocaleHelper());
        $this->assertInstanceOf(LocaleHelper::class, $this->paymentDataBuilder->getLocaleHelper(
            new LocaleHelper(new LanguageHelper())
        ));
    }
    public function testGetStatesHelper()
    {
        $this->assertInstanceOf(StateHelper::class, $this->paymentDataBuilder->getStateHelper());
        $this->assertInstanceOf(StateHelper::class, $this->paymentDataBuilder->getStateHelper(
            new StateHelper()
        ));
    }

    public function testGetCustomerHelper()
    {
        $this->assertInstanceOf(CustomerHelper::class, $this->paymentDataBuilder->getCustomerHelper());
        $this->assertInstanceOf(CustomerHelper::class, $this->paymentDataBuilder->getCustomerHelper(
            new CustomerHelper(
                new ContextFactory(),
                new OrderHelper(),
                new ValidateHelper()
            )
        ));
    }

    public function testGetCartHelper()
    {
        $this->assertInstanceOf(CartHelper::class, $this->paymentDataBuilder->getCartHelper());
        $this->assertInstanceOf(CartHelper::class, $this->paymentDataBuilder->getCartHelper(
            new CartHelper(
                new ContextFactory(),
                new ToolsHelper(),
                $this->priceHelper,
                new CartData(
                    new ProductHelper(),
                    new SettingsHelper(
                        new ShopHelper(),
                        new ConfigurationHelper()
                    ),
                    $this->priceHelper,
                    new ProductRepository()
                ),
                new OrderRepository(),
                new OrderStateHelper(new ContextFactory()),
                $this->carrierHelper
            )
        ));
    }

    public function testGetCarrierHelper()
    {
        $this->assertInstanceOf(CarrierHelper::class, $this->paymentDataBuilder->getCarrierHelper());
        $this->assertInstanceOf(CarrierHelper::class, $this->paymentDataBuilder->getCarrierHelper(
            $this->carrierHelper
        ));
    }

}
