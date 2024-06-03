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

namespace Alma\PrestaShop\Tests\Unit\Builders\Helpers;

use Alma\PrestaShop\Builders\Helpers\ContextHelperBuilder;
use Alma\PrestaShop\Factories\ContextFactory;
use Alma\PrestaShop\Factories\ModuleFactory;
use Alma\PrestaShop\Helpers\ContextHelper;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Alma\PrestaShop\Builders\Helpers\ContextHelperBuilder
 */
class ContextHelperBuilderTest extends TestCase
{
    /**
     * @var ContextHelperBuilder
     */
    protected $contextHelperBuilder;

    public function setUp()
    {
        $this->contextHelperBuilder = new ContextHelperBuilder();
    }

    public function testGetInstance()
    {
        $this->assertInstanceOf(ContextHelper::class, $this->contextHelperBuilder->getInstance());
    }

    public function testGetContextFactory()
    {
        $this->assertInstanceOf(ContextFactory::class, $this->contextHelperBuilder->getContextFactory());
        $this->assertInstanceOf(ContextFactory::class, $this->contextHelperBuilder->getContextFactory(
            new ContextFactory()
        ));
    }

    public function testGetModuleFactory()
    {
        $this->assertInstanceOf(ModuleFactory::class, $this->contextHelperBuilder->getModuleFactory());
        $this->assertInstanceOf(ModuleFactory::class, $this->contextHelperBuilder->getModuleFactory(
            new ModuleFactory()
        ));
    }
}
