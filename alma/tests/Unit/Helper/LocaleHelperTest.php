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

namespace Alma\PrestaShop\Tests\Unit\Helper;

use Alma\PrestaShop\Builders\Models\LocaleHelperBuilder;
use Alma\PrestaShop\Helpers\LanguageHelper;
use Alma\PrestaShop\Helpers\LocaleHelper;
use PHPUnit\Framework\TestCase;

class LocaleHelperTest extends TestCase
{
    /**
     * @var LocaleHelper
     */
    protected $localeHelper;

    public function setUp()
    {
        $localeHelperBuilder = new LocaleHelperBuilder();
        $this->localeHelper = $localeHelperBuilder->getInstance();
    }

    /**
     * @return void
     */
    public function testGetLocaleByIdLangForWidgetEn()
    {
        $locale = $this->localeHelper->getLocaleByIdLangForWidget(1);

        $this->assertEquals('en', $locale);
    }

    /**
     * @return void
     */
    public function testGetLocaleByIdLangForWidgetNl()
    {
        $languageHelperMock = \Mockery::mock(LanguageHelper::class);
        $languageHelperMock->shouldReceive('getIsoById')->with(2)->andReturn('nl');

        $localeHelperBuilder = \Mockery::mock(LocaleHelperBuilder::class)->makePartial();
        $localeHelperBuilder->shouldReceive('getLanguageHelper')->andReturn($languageHelperMock);

        $localeHelper = $localeHelperBuilder->getInstance();
        $locale = $localeHelper->getLocaleByIdLangForWidget(2);

        $this->assertEquals('nl-NL', $locale);
    }
}
