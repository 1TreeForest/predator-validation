{use class="\yii\helpers\Url"}
<div id="suppliers_stock_popup">
<div class="popup-heading">{if $master == 1}&nbsp;{else}{$smarty.const.TEXT_SUPPLIERS_STOCK}{/if}</div>
<form id="suppliers_stock_form" action="{Yii::$app->urlManager->createUrl('categories/suppliers-stock')}" method="post">
{tep_draw_hidden_field('prid', $prid)}
{if \common\helpers\Warehouses::get_warehouses_count() > 1}
<div class="ord_status_filter_row row" style="width: 500px">
  <div class="col-md-3" style="padding-top: 4px">{$smarty.const.TEXT_WAREHOUSE}:</div>
  <div class="col-md-3">{tep_draw_pull_down_menu('warehouse_id', array_merge([['id' => '0', 'text' => $smarty.const.TEXT_ALL]], \common\helpers\Warehouses::get_warehouses()), $warehouse_id, 'class="form-control form-control-small" id="warehouse_id"')}</div>
  <div class="col-md-3" style="padding-top: 4px">{$smarty.const.TEXT_SHOW_EMPTY}:</div>
  <div class="col-md-3"><input name="empty_row" id="empty_row" type="checkbox" value="1"{if $empty_row == 1} checked{/if}></div>
</div>
{else}
    {assign var=warehouse_id value=\common\helpers\Warehouses::get_default_warehouse()}
    {tep_draw_hidden_field('warehouse_id', $warehouse_id)}
{/if}
<div class="warehousesStockPopup">
    <table class="table table-striped table-bordered table-hover table-responsive table-ordering suppliers-stock-datatable double-grid">
      <thead>
        <tr>
          <th>&nbsp;</th>
          <th data-orderable="false">{trim($smarty.const.TEXT_SUPPLIER, ': ')}{if $master == 1} / {$smarty.const.TEXT_WAREHOUSE}{/if}</th>
          <th data-orderable="false">{$smarty.const.TEXT_STOCK_QUANTITY_INFO}</th>
          <th data-orderable="false">{$smarty.const.TEXT_STOCK_ALLOCATED_QUANTITY}</th>
          <th data-orderable="false">{$smarty.const.TEXT_STOCK_TEMPORARY_QUANTITY}</th>
          <th data-orderable="false">{$smarty.const.TEXT_STOCK_WAREHOUSE_QUANTITY}</th>
          <th data-orderable="false">{$smarty.const.TEXT_STOCK_ORDERED_QUANTITY}</th>
        {*if $warehouse_id > 0}
          <th data-orderable="false">{$smarty.const.TEXT_STOCK_QUANTITY_UPDATE}</th>
        {/if*}
        </tr>
      </thead>
      <tbody>
{foreach $suppliers as $Item}
        <tr{if $Item['master'] == 1} style="background-color: rgb(233, 233, 233);"{/if}>
          <td>{$Item['sort_order']}</td>
          <td>{$Item['name']}</td>
          <td>{$Item['products_quantity']}{$Item['actions']}{if $warehouse_id > 0 && $Item['id'] > 0}<a href="{Yii::$app->urlManager->createUrl(['categories/update-stock', 'products_id' => $prid, 'suppliers_id' => $Item['id'], 'warehouse_id' => $warehouse_id])}" class="right-link" data-class="update-stock-popup">{$smarty.const.TEXT_UPDATE_STOCK}</a>{/if}</td>
          <td>{$Item['allocated_quantity']}</td>
          <td>{$Item['temporary_quantity']}</td>
          <td>{$Item['warehouse_quantity']}</td>
          <td>{$Item['ordered_quantity']}</td>
        {*if $warehouse_id > 0}
          <td>
              {if $Item['id'] > 0}
                  <a href="{Yii::$app->urlManager->createUrl(['categories/update-stock', 'products_id' => $prid, 'suppliers_id' => $Item['id'], 'warehouse_id' => $warehouse_id])}" class="btn right-link" data-class="update-stock-popup">{$smarty.const.TEXT_UPDATE_STOCK}</a>
              {/if}
          </td>
        {/if*}
        </tr>
{/foreach}
      </tbody>
    </table>
</div>
<div class="mail-sending noti-btn">
  <div><span class="btn btn-cancel">{$smarty.const.IMAGE_CANCEL}</span></div>
  <div><input class="btn btn-primary" type="submit" value="{$smarty.const.IMAGE_UPDATE}"></div>
</div>
</form>
<script>
  var table;
  (function($){
    table = $('.suppliers-stock-datatable').dataTable( {
        'pageLength': 10,
        'order': [[ 0, 'asc' ]],
        'columnDefs': [ { 'visible': false, 'targets': 0 } ],
    } );
    var oSettings = table.fnSettings();
    oSettings._iDisplayStart = 0;
    table.fnDraw();
    
    $('.right-link').popUp({ 'box_class':'popupCredithistory' });
    
  })(jQuery)

  $('#suppliers_stock_form').on('submit', function() {
    $.post("{Yii::$app->urlManager->createUrl('categories/suppliers-stock')}", $('#suppliers_stock_form').serialize(), function (data, status) {
      if (status == "success") {
        $('#suppliers_stock_popup').replaceWith(data);
      }
    });
    return false;
  });

  $('#empty_row, #warehouse_id').on('change', function() {
      var empty_row = 0;
      if ($('#empty_row').prop('checked')) {
          empty_row = 1;
        }
    $.get('{Yii::$app->urlManager->createUrl('categories/suppliers-stock')}', { 
        prid: '{$prid}', 
        warehouse_id: $('#warehouse_id').val(), 
        empty_row: empty_row,
    }, function(data, status){
      if (status == 'success') {
        $('#suppliers_stock_popup').replaceWith(data);
      }
    });
  });
</script>
</div>