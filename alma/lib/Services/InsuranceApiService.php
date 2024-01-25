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

use Alma\API\Entities\Insurance\Contract;
use Alma\API\Entities\Insurance\File;
use Alma\API\Exceptions\MissingKeyException;
use Alma\API\Exceptions\ParametersException;
use Alma\API\Exceptions\ParamsException;
use Alma\API\Exceptions\RequestException;
use Alma\API\RequestError;
use Alma\PrestaShop\Exceptions\InsuranceSubscriptionException;
use Alma\PrestaShop\Helpers\CartHelper;
use Alma\PrestaShop\Helpers\ClientHelper;
use Alma\API\Client;
use Alma\PrestaShop\Logger;

class InsuranceApiService
{
    /**
     * @var Client|mixed|null
     */
    protected $almaApiClient;

    /**
     * @var \ContextCore
     */
    protected $context;

    /**
     * @var CartHelper
     */
    protected $cartHelper;

    /**
     *
     */
    public function __construct()
    {
        $this->almaApiClient = ClientHelper::defaultInstance();
        $this->context = \Context::getContext();
        $this->cartHelper = new CartHelper();
    }

    /**
     * @param int $insuranceContractId
     * @param string $cmsReference
     * @param int $productPrice
     * @param string $type
     * @return File|null
     */
    public function getInsuranceContractFileByType($insuranceContractId, $cmsReference, $productPrice, $type = 'ipid-document')
    {
        try {
            return $this->almaApiClient->insurance->getInsuranceContract(
                $insuranceContractId,
                $cmsReference,
                $productPrice,
                $this->context->session->getId(),
                $this->cartHelper->getCartIdFromContext()
            )->getFileByType($type);
        } catch (\Exception  $e) {
            Logger::instance()->error(
                sprintf(
                    '[Alma] Impossible to retrieve insurance contract file, message "%s", trace "%s"',
                    $e->getMessage(),
                    $e->getTraceAsString()
                )
            );
        }

        return null;
    }

    /**
     * @param int $insuranceContractId
     * @param string $cmsReference
     * @param int $productPrice
     * @return Contract|null
     */
    public function getInsuranceContract($insuranceContractId, $cmsReference, $productPrice)
    {
        try {
            return $this->almaApiClient->insurance->getInsuranceContract(
                $insuranceContractId,
                $cmsReference,
                $productPrice,
                $this->context->session->getId(),
                $this->cartHelper->getCartIdFromContext()
            );
        } catch (\Exception $e) {
            Logger::instance()->error(
                sprintf(
                    '[Alma] Impossible to retrieve insurance contract, message "%s", trace "%s"',
                    $e->getMessage(),
                    $e->getTraceAsString()
                )
            );
        }

        return null;
    }


    /**
     * @param array $subscriptionData
     * @param int $idTransaction
     * @return array
     * @throws InsuranceSubscriptionException
     */
    public function subscribeInsurance($subscriptionData, $idTransaction)
    {
        try {
          $result = $this->almaApiClient->insurance->subscription(
              $subscriptionData,
              $idTransaction,
              $this->context->session->getId(),
              $this->cartHelper->getCartIdFromContext()
          );

          if(isset($result['subscriptions'])) {
              return $result['subscriptions'];
          }
        } catch (\Exception  $e) {
            Logger::instance()->error(
                sprintf(
                    '[Alma] Error when subscribing insurance contract, message "%s", trace "%s", subscriptionData : "%s", idTransaction : "%s"',
                    $e->getMessage(),
                    $e->getTraceAsString(),
                    json_encode($subscriptionData),
                    $idTransaction
                )
            );

        }

        throw new InsuranceSubscriptionException();
    }
}
