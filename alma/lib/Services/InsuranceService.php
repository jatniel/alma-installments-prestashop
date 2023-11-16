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

namespace Alma\PrestaShop\Services;

use Alma\PrestaShop\Exceptions\InsuranceInstallException;
use Alma\PrestaShop\Helpers\ConstantsHelper;
use Alma\PrestaShop\Logger;
use Alma\PrestaShop\Repositories\AlmaInsuranceProductRepository;
use Alma\PrestaShop\Repositories\AttributeGroupRepository;
use Alma\PrestaShop\Repositories\ProductRepository;

class InsuranceService
{
    /**
     * @var ProductRepository
     */
    private $productRepository;
    /**
     * @var ImageService
     */
    private $imageService;
    /**
     * @var \Context|null
     */
    private $context;
    /**
     * @var AttributeGroupRepository
     */
    private $attributeGroupRepository;
    /**
     * @var AlmaInsuranceProductRepository
     */
    private $almaInsuranceProductRepository;

    public function __construct()
    {
        $this->productRepository = new ProductRepository();
        $this->imageService = new ImageService();
        $this->context = \Context::getContext();
        $this->attributeGroupRepository = new AttributeGroupRepository();
        $this->almaInsuranceProductRepository = new AlmaInsuranceProductRepository();
    }

    /**
     * Create the default Insurance product
     * @return void
     * @throws InsuranceInstallException
     */
    public function createProductIfNotExists()
    {
        $insuranceProduct = $this->productRepository->getProductIdByReference(
            ConstantsHelper::ALMA_INSURANCE_PRODUCT_REFERENCE,
            $this->context->language->id
        );

        if (!$insuranceProduct) {
            try {
                $insuranceProduct = $this->productRepository->createInsuranceProduct();
                $shops = \Shop::getShops(true, null, true);

                $this->imageService->associateImageToProduct(
                    $insuranceProduct->id,
                    $shops,
                    ConstantsHelper::ALMA_INSURANCE_PRODUCT_IMAGE_URL
                );
            } catch (\Exception $e) {
                Logger::instance()->error(
                    sprintf(
                        '[Alma] The insurance product has not been created, message "%s", trace "%s"',
                        $e->getMessage(),
                        $e->getTraceAsString()
                    )
                );

                throw new InsuranceInstallException();
            }
        }
    }

    /**
     * Create the default Insurance attribute group
     * @return void
     * @throws InsuranceInstallException
     */
    public function createAttributeGroupIfNotExists()
    {
        $insuranceAttributeGroup = $this->attributeGroupRepository->getAttributeIdByName(
            ConstantsHelper::ALMA_INSURANCE_ATTRIBUTE_NAME,
            $this->context->language->id
        );

        if (!$insuranceAttributeGroup) {
            try {
                $this->attributeGroupRepository->createInsuranceAttributeGroup();
            } catch (\Exception $e) {
                Logger::instance()->error(
                    sprintf(
                        '[Alma] The insurance attribute group has not been created, message "%s", trace "%s"',
                        $e->getMessage(),
                        $e->getTraceAsString()
                    )
                );

                throw new InsuranceInstallException();
            }
        }
    }

    /**
     * @return void
     * @throws InsuranceInstallException
     */
    public function installDefaultData()
    {
        if (!$this->almaInsuranceProductRepository->createTable()) {
            throw new InsuranceInstallException('The creation of table "alma_insurance_product" has failed');
        }

        $this->createProductIfNotExists();
        $this->createAttributeGroupIfNotExists();
    }
}