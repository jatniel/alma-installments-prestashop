{*
 * 2018-2019 Alma SAS
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
 * @copyright 2018-2019 Alma SAS
 * @license   https://opensource.org/licenses/MIT The MIT License
 *}

<div id="alma-excluded">
    <p>
        {capture name='intro1'}
            {l s='Some products (gift cards, license keys, software, weapons, ...) cannot be sold with Alma, as per %sour terms%s (see Exclusions paragraph).' mod='alma' sprintf=array('<a href="https://getalma.eu/legal/terms/payment" target="_blank">', '</a>')}
        {/capture}
        {$smarty.capture.intro1|unescape:'html'}
    </p>

    <p>{l s='If you are selling such products on your shop, you need to configure Alma so that it is not enabled when customers view or shop them.' mod='alma'}</p>

    <p style="margin: 20px 0;">
        {capture name='intro2'}
            {l s='Use the %1$s%2$scategory exclusions page%3$s%4$s to comply with these restrictions.' sprintf=array('<strong>', "<a href='$exludedLink'>", '</a>', '</strong>') mod='alma'}
        {/capture}
        {$smarty.capture.intro2|unescape:'html'}
    </p>
    <p>
        <strong>{l s='Categories currently excluded : ' mod='alma'}</strong>
        {$excludedCategories}
    </p>
</div>
