
{\frontend\design\Info::addBoxToCss('price-box')}
<div class="price-box order_totals" id="order_totals">
    {foreach $order_totals as $order_total}
        <div class="price-row {$order_total.code} {if $order_total.code=='ot_total'} total{/if} {$order_total.class}{if $order_total.show_line} totals-line{/if}">
            <div class="title">{$order_total.title}</div>
            <div class="price">{$order_total.text}</div>
        </div>
    {/foreach}
</div>