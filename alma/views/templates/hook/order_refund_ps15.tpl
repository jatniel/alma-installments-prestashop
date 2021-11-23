{*
 * 2018-2021 Alma SAS
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
 * @copyright 2018-2021 Alma SAS
 * @license   https://opensource.org/licenses/MIT The MIT License
 *}

<br>
<div class="alma-success conf" style="display:none"></div>
<div class="alma-danger error" style="display:none"></div>
<fieldset>
    <legend>
        <img src="{$iconPath|escape:'htmlall':'UTF-8'}"/>
        {l s='Alma refund' mod='alma'}
    </legend>
    <form id="alma-refund"  method="POST" action="{$actionUrl|escape:'htmlall':'UTF-8'}" class="defaultForm form-horizontal form-alma {if $refund.percentRefund >= 100}disabled{/if}">
        <input type="hidden" class="alma" name="orderId" required value="{$order.id|escape:'htmlall':'UTF-8'}" />
        <table cellspacing="0" cellpadding="0" class="table tableDnD alma-table-refund" style="float:right; {if !$refund}display:none;{/if}">
            <thead>
            <tr> 
                <th>{l s='Amount refunded' mod='alma'}</th>
                <th>{l s='Total' mod='alma'}</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="refundAmount">
                        {$refund.totalRefundAmount|escape:'htmlall':'UTF-8'}
                    </td>
                    <td>
                        {$order.ordersTotalAmount|escape:'htmlall':'UTF-8'}
                    </td>
                    <td>
                        <img src="../img/admin/money.gif" />
                    </td>
                </tr>
	        </tbody>
	    </table>
        <p>
            {l s='Refund this order thanks to the Alma module. This will be applied in your Alma dashboard automatically. The maximum refundable amount includes client fees.' mod='alma'}
            <a href="https://docs.getalma.eu/docs/prestashop-refund" target="_blank">{l s='See documentation' mod='alma'}</a>
        </p>
        <div class="clear"></div>
        <label>{l s='Refund type:' mod='alma'}</label>
        <div class="margin-form">
            {if $order.ordersId}
                <input type="radio" autocomplete="off" class="refundType" name="refundType" value="partial_multi" />
                <label>{l s='Only this order (%s)' sprintf=$order.maxAmount mod='alma'}</label>
                <div class="clear"></div>
            {/if} 
            <input type="radio" autocomplete="off" name="refundType" value="total" checked="checked"/>
            <label>
                {if $order.ordersId}
                    {l s='Refund the entire order' mod='alma'}
                    <br>
                    <i>{l s='Refund this order (id: %1$d) and all those linked to the same payment (id: %2$s)' sprintf=array($order.id, $order.ordersId) mod='alma'}
                    <br>
                    {l s='Total amount: %s' sprintf=$order.ordersTotalAmount mod='alma'}
                    </i>
                {else}
                    {l s='Total amount' mod='alma'}
                {/if}
            </label>
            <div class="clear"></div>
            <input type="radio" autocomplete="off" name="refundType" value="partial" />
            <label>{l s='Partial' mod='alma'}</label>
            <div class="clear"></div>
        </div>
        <div style="display:none;" id="amountDisplay">
            <label>
                {l s='Amount (Max. %s):' sprintf=$order.ordersTotalAmount mod='alma'}
            </label>
            <div class="margin-form">
                <input type="text" autocomplete="off" class="alma-input-number" id="amount" value="" name="amount" placeholder="{l s='Amount to refund...' mod='alma'}" />
            </div>
        </div>  
        <div class="clear"></div>
        <div class="margin-form">
            <input type="submit" class="button" value="{l s='Proceed the refund' mod='alma'}" />
        </div>
    </form>
</fieldset>

{include file='./_partials/refunds.tpl'}