<?php
/**
 * 2018-2023 Alma SAS
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

namespace Alma\PrestaShop\Tests\Unit\Lib\Model;

use Alma\PrestaShop\Model\CartData;
use Cart;
use PHPUnit\Framework\TestCase;
use Product;

class CartDataTest extends TestCase
{
    public function testGetCartItems()
    {
        $expectedItems = [];
        $cart = $this->createMock(Cart::class);
        $product = $this->createMock(Product::class);
        $productHelper = $this->createMock(ProductHelper::class);
        $productHelper->method('getProductsCombinations')->willReturn( ["1-1" => "Color - White, Size - S"]);
        $product->id = 1;
        $product->id_product = 1;
        $product->name = 'Product Test';
        $product->price_wt = 280.000000;
        $product->price = 280.000000;
        $product->total_wt = 280.000000;
        $product->id_image = 1;
        $product->is_virtual = false;
        $product->cart_quantity = 1;
        $product->id_product_attribute = 1;

        $summaryDetailsMock = ['products' => [(array) $product], 'gift_products'=>[]];
        $cart->method('getSummaryDetails')->willReturn($summaryDetailsMock);
        $returnItems = CartData::getCartItems($cart);
        $this->assertEquals($expectedItems, $returnItems);
    }
}
