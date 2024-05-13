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

namespace Alma\PrestaShop\Tests\Unit\Factories;

use Alma\PrestaShop\Exceptions\AlmaException;
use Alma\PrestaShop\Factories\ContextFactory;
use PHPUnit\Framework\TestCase;

class ContextFactoryTest extends TestCase
{
    /**
     * @var ContextFactory
     */
    protected $contextFactory;

    public function setUp()
    {
        $this->contextFactory = new ContextFactory();
    }

    public function testGetContext()
    {
        $this->assertInstanceOf(\Context::class, $this->contextFactory->getContext());
    }

    public function testGetContextLink()
    {
        $this->assertInstanceOf(\Link::class, $this->contextFactory->getContextLink());

        $contextFactory = \Mockery::mock(ContextFactory::class)->makePartial();
        $contextFactory->shouldReceive('getContext')->andReturn(null);

        $this->expectException(AlmaException::class);

        $contextFactory->getContextLink();
    }

    public function testGetContextLanguage()
    {
        $this->assertInstanceOf(\Language::class, $this->contextFactory->getContextLanguage());

        $contextFactory = \Mockery::mock(ContextFactory::class)->makePartial();
        $contextFactory->shouldReceive('getContext')->andReturn(null);

        $this->expectException(AlmaException::class);

        $contextFactory->getContextLanguage();
    }

    public function testGetContextLanguageId()
    {
        $this->assertEquals('1', $this->contextFactory->getContextLanguageId());

        $contextFactory = \Mockery::mock(ContextFactory::class)->makePartial();
        $contextFactory->shouldReceive('getContextLanguage')->andReturn(null);

        $this->expectException(AlmaException::class);

        $contextFactory->getContextLanguageId();
    }
}
