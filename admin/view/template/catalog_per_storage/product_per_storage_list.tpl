<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_copy; ?>" class="btn btn-default" onclick="$('#form-product').attr('action', '<?php echo $copy; ?>').submit()"><i class="fa fa-copy"></i></button>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-product').submit() : false;"><i class="fa fa-trash-o"></i></button>
      </div>
      <h1><?php echo $heading_title; ?></h1>      
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
                <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-model"><?php echo $entry_model; ?></label>
                <input type="text" name="filter_model" value="<?php echo $filter_model; ?>" placeholder="<?php echo $entry_model; ?>" id="input-model" class="form-control" />
              </div>
            </div>
            <div class="col-sm-4">              
              <div class="form-group">
                <label class="control-label" for="input-quantity"><?php echo $entry_quantity; ?></label>
                <input type="text" name="filter_quantity" value="<?php echo $filter_quantity; ?>" placeholder="<?php echo $entry_quantity; ?>" id="input-quantity" class="form-control" />
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
                <select name="filter_status" id="input-status" class="form-control">
                  <option value="*"></option>
                  <?php if ($filter_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <?php } ?>
                  <?php if (!$filter_status && !is_null($filter_status)) { ?>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-product">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                   <td class="text-left"><?php if ($sort == 'pd.name') { ?>
                    <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'p.model') { ?>
                    <a href="<?php echo $sort_model; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_model; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_model; ?>"><?php echo $column_model; ?></a>
                    <?php } ?></td>  
                  <td class="text-right"><?php echo $column_quantity; ?> СОФИЯ</td>                    
                  <td class="text-right"><?php echo $column_quantity; ?> ДОБРИЧ</td> 
                  <td class="text-right">ОБЩО</td> 
                  <td class="text-center">ПАРТИДИ <span data-toggle="tooltip" title="Количества по партиди в различните обекти"></span></td>
                  <td class="text-left"><?php if ($sort == 'p.status') { ?>
                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                    <?php } ?></td>
                </tr>
              </thead>
              <tbody>                
                <?php if ($products) { ?>
                <?php foreach ($products as $product) { ?>
                <tr id="<?= $product['product_id'] ?>">
                  <td class="text-center"><?php if (in_array($product['product_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" />
                    <?php } ?></td>
                  <td class="text-left"><?php echo $product['name']; ?></td>
                  <td class="text-left"><?php echo $product['model']; ?></td> 
                  <td class="text-right"><?php if ($products_sf[$product['product_id']]['quantity'] <= 0) { ?>
                    <span class="label label-warning"><?php echo $products_sf[$product['product_id']]['quantity']; ?></span>
                    <?php } elseif ($products_sf[$product['product_id']]['quantity'] <= 5) { ?>
                    <span class="label label-danger"><?php echo $products_sf[$product['product_id']]['quantity']; ?></span>
                    <?php } else { ?>
                    <span class="label label-success"><?php echo $products_sf[$product['product_id']]['quantity']; ?></span>
                    <?php } ?></td>
                  <td class="text-right"><?php if ($products_dobr[$product['product_id']]['quantity'] <= 0) { ?>
                    <span class="label label-warning"><?php echo $products_dobr[$product['product_id']]['quantity']; ?></span>
                    <?php } elseif ($product['quantity'] <= 5) { ?>
                    <span class="label label-danger"><?php echo $products_dobr[$product['product_id']]['quantity']; ?></span>
                    <?php } else { ?>
                    <span class="label label-success"><?php echo $products_dobr[$product['product_id']]['quantity']; ?></span>
                    <?php } ?></td>
                    <td class="text-right"><?php if ($product['quantity'] <= 0) { ?>
                    <span class="label label-warning"><?php echo $product['quantity']; ?></span>
                    <?php } elseif ($product['quantity'] <= 5) { ?>
                    <span class="label label-danger"><?php echo $product['quantity']; ?></span>
                    <?php } else { ?>
                    <span class="label label-success"><?php echo $product['quantity']; ?></span>
                    <?php } ?></td>
                    <td class="text-center"><a href="#" data-product="<?= $product['product_id'] ?>" class="options-toggle">
                            <i class="fa fa-arrow-left"></i> 
                            <span data-toggle="tooltip" title="Кликни за детайлна информация!"><i class="fa fa-info-circle"></i></span>                          
                          </a></td>
                  <td class="text-left"><?php echo $product['status']; ?></td>                  
                </tr>
                <!-- if product option -->
                <?php if(!empty($product['options_data'])) {?>                	
                <!-- foreach product options in each store -->
                <?php foreach($product['options_data'] as $options_data) { ?>
                 
                  <?php if( !empty($options_data['product_option_value']) ) { ?>
                    <?php foreach( $options_data['product_option_value'] as $option ) { ?>
                        <tr class="product-options-<?= $product['product_id'] ?> option-list-hidden">
                          <td></td>
                          <td class="text-right" colspan="2"><?= 'партида - ' . $option['option_value_name'];?></td>
                          <td class="text-right" colspan=""><?= $option['quantity_sofia'];?></td>     
                          <td class="text-right" colspan=""><?= $option['quantity_dobrich'];?></td>   
                          <td class="text-right"><?= $option['quantity_total'];?></td>
                          <td></td>
                          <td></td>
                          <td></td>
                        </tr>
                    <?php } ?>
                  <?php } else { ?>
                  <tr class="product-options-<?= $product['product_id'] ?> option-list-hidden">
                    <td></td>
                    <td colspan="7" class="text-center">Липсват данни за партиди</td>
                  </tr>
                <?php } ?>
                <?php } ?>
                <?php } else { ?>
                  <tr class="product-options-<?= $product['product_id'] ?> option-list-hidden">
                    <td></td>
                    <td colspan="7" class="text-center">Липсват данни за партиди</td>
                  </tr>
                <?php } ?>
                
                <!-- end if product options -->
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </form>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('.options-toggle').on('click', function(e){
    e.preventDefault();
    if( $(this).children('i').attr('class') == 'fa fa-arrow-left'){      
      $(this).children('i').removeClass('fa-arrow-left')
        .addClass('fa-arrow-down');
      let ident = $(this).data('product');     
      $('.product-options-' + ident).removeClass('option-list-hidden');
    } else if( $(this).children('i').attr('class') == 'fa fa-arrow-down' ){
      $(this).children('i').removeClass('fa-arrow-down')
        .addClass('fa-arrow-left');
      let ident = $(this).data('product');
      $('.product-options-' + ident).addClass('option-list-hidden')
    }
    
  });
$('#button-filter').on('click', function() {
	var url = 'index.php?route=catalog_per_storage/product_per_storage&token=<?php echo $token; ?>';

	var filter_name = $('input[name=\'filter_name\']').val();

	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}

	var filter_model = $('input[name=\'filter_model\']').val();

	if (filter_model) {
		url += '&filter_model=' + encodeURIComponent(filter_model);
	}

	
	var filter_quantity = $('input[name=\'filter_quantity\']').val();

	if (filter_quantity) {
		url += '&filter_quantity=' + encodeURIComponent(filter_quantity);
	}

	var filter_status = $('select[name=\'filter_status\']').val();

	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}

	location = url;
});
//--></script>
  <script type="text/javascript"><!--
$('input[name=\'filter_name\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['product_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_name\']').val(item['label']);
	}
});

$('input[name=\'filter_model\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_model=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['model'],
						value: item['product_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_model\']').val(item['label']);
	}
});
//--></script></div>
<?php echo $footer; ?>