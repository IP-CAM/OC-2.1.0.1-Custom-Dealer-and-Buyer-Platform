<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if (isset($success)) { ?>
  <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <?php if (isset($error_warning)) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="row">
    <div class="col-sm-12">
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <div class="row">
        <h2 class="col-sm-12"><?php echo $heading_title; ?> </h2>        
      </div>      

      <!-- filter     -->
      <div class="well">
          <div class="row">
            <div class="col-sm-2">
              <div class="form-group">
                <label class="control-label" for="input-order-id">Поръчка No</label>
                <input type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>" placeholder="Поръчка No" id="input-order-id" class="form-control" />
              </div>
            </div>
            <div class="col-sm-2">
              <div class="form-group">
                <label class="control-label" for="input-date-added"> От дата</label>
                <div class="input-group date">
                  <input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" placeholder="ГГГГ-ММ-ДД" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
            </div>
            <div class="col-sm-2">
              <div class="form-group">
                <label class="control-label" for="input-email">От e-mail</label>
                <input type="text" name="filter_email" value="<?php echo $filter_email; ?>" placeholder="От e-mail" id="input-email" class="form-control" />
              </div>
            </div>
            <div class="col-sm-2">
              <div class="form-group">
                <label class="control-label" for="input-product-name">Продукт</label>
                <input type="text" name="filter_product_name" value="<?php echo $filter_product_name; ?>" placeholder="Продукт" id="input-product-name" class="form-control" />
              </div>
            </div>
            <div class="col-sm-2">
              <div class="form-group">
                <label class="control-label" for="input-model">Код</label>
                <input type="text" name="filter_model" value="<?php echo $filter_model; ?>" placeholder="Код" id="input-model" class="form-control" />
              </div>
            </div>
            <div class="col-sm-2">
              <div class="form-group">
                <label class="control-label" for="input-option-value">Партида</label>
                <input type="text" name="filter_option_value" value="<?php echo $filter_option_value; ?>" placeholder="Партида" id="input-option-name" class="form-control" />
              </div>
            </div>
            <div class="col-sm-3">              
              <button type="button" id="button-filter" class="btn btn-primary"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
            <div class="col-sm-2 col-sm-offset-7">              
              <button type="button" id="button-filter-remove" class="btn btn-warning"><i class="fa fa-remove"></i> Изчисти</button>
            </div>
          </div>
        </div>
        <?php if ($orders) { ?>
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <td></td>
              <td class="text-left">
                <?php if ($sort == 'o.order_id') { ?>
                <a href="<?php echo $sort_order_id; ?>" class="<?php echo strtolower($order); ?>">Поръчка No</a>
                <?php } else { ?>
                <a href="<?php echo $sort_order_id; ?>">Поръчка No</a>
                <?php } ?>
              </td>
              <!-- линк към поръчката -->
              <td class="text-left">
                <?php if ($sort == 'o.date_added') { ?>
                  <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>">Дата</a>
                  <?php } else { ?>
                  <a href="<?php echo $sort_date_added; ?>">Дата</a>
                  <?php } ?></td>
              <td class="text-left">
                <?php if ($sort == 'o.email') { ?>
                  <a href="<?php echo $sort_email; ?>" class="<?php echo strtolower($order); ?>">Email</a>
                  <?php } else { ?>
                  <a href="<?php echo $sort_email; ?>">Email</a>
                  <?php } ?></td>
              <td class="text-left">
                <?php if ($sort == 'op.name') { ?>
                  <a href="<?php echo $sort_product_name; ?>" class="<?php echo strtolower($order); ?>">Продукт</a>
                  <?php } else { ?>
                  <a href="<?php echo $sort_product_name; ?>">Продукт</a>
                  <?php } ?></td>
              <td class="text-left">
                <?php if ($sort == 'op.model') { ?>
                  <a href="<?php echo $sort_model; ?>" class="<?php echo strtolower($order); ?>">Код</a>
                  <?php } else { ?>
                  <a href="<?php echo $sort_model; ?>">Код</a>
                  <?php } ?></td>
              <td class="text-left">
                <?php if ($sort == 'op2.value') { ?>
                  <a href="<?php echo $sort_option_value; ?>" class="<?php echo strtolower($order); ?>">Партида</a>
                  <?php } else { ?>
                  <a href="<?php echo $sort_option_value; ?>">Партида</a>
                  <?php } ?></td>
              <td class="text-right">Кол.</td>              
              <td class="text-right">Цена</td>
              <?php if( isset($is_dealer) ){ ?>
                <?php if( $is_dealer == 1 ){ ?>
                <td>Нова цена</td>
                <td>Ново кол.</td>             
                <td>Нова партида</td>
                <?php } ?>
              <?php } ?>
              <!-- <td class="text-right">Общо</td> -->
              <td class="text-center">
                <!-- opens modal with input and submit button
                 -->
                 <!-- submit button returns info of all product purcases for this client ordered by price ascending and sortable options for quantity, date and price -->

                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target=".bd-product-modal-lg"><i class="fa fa-search"></i></button>
              </td>            
            </tr>
          </thead>
          <tbody>
            <?php $row = 1;  ?>
            <?php foreach( $orders as $order ) { ?>
              <tr class="prod-<?= $order['product_id'] ?>" id="<?= $order['product_id'] ?>-row-<?= $row ?>">
                <td style="width: 1px;" class="text-center"><input type="checkbox"  name="bulk-add-<?= $order['product_id'] ?>" data-product="<?= $order['product_id'] ?>-row-<?= $row++ ?>"/></td>
                <!-- <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td> -->

                <td class="col-md-1"><?= $order['order_id']?></td>
                <?php $timestamp = strtotime($order['date_added']); ?>
                <td class="col-md-1"><?= date('d-m-Y', $timestamp)?></td>
                <td class="col-md-1"><?= $order['email']?></td>
                <td class="text-left col-md-2"><?= $order['product_name']; ?></td>
                <td class="text-left col-md-1"><?= $order['model']; ?></td>
                <td class="text-left old-lot col-md-1" data-option-id="<?= $order['product_option_id']?>" data-product-option-id="<?= $order['product_option_value_id']?>"><?= $order['value']; ?></td>
                <td class="text-right old-quant col-md-1"><?= $order['quantity']; ?></td>
                <td class="text-right old-price col-md-1"><?= $order['price']; ?></td>
                <?php if( isset($is_dealer) ){ ?>
                  <?php if( $is_dealer == 1 ){ ?>
                    <?php if( $order['status'] == 1 ){ ?>
                      <td class="new-price col-md-1"></td>
                      <td class="new-quant col-md-1"></td>
                      <td class="new-lot col-md-1"></td>
                    <?php } ?>
                  <?php } ?> 
                <?php } ?> 
                <?php if( $order['status'] == 1 ){ ?>
                  <td class="text-right" class="col-md-1" style="white-space: nowrap;">
                    <?php if( isset($is_dealer) ){ ?>
                      <?php if( $is_dealer == 1 ){ ?>
                        <?php if( !empty( $products_in_cart ) ){ ?>
                          <?php if( in_array($order['product_id'], $products_in_cart) ){?>
                           <span data-toggle="tooltip" title="Продуктът е добавен в количката!<?= PHP_EOL ?><?php foreach($products_in_cart_info[$order['product_id']] as $pci) {
                            echo $pci['quantity'] . 'x' . $pci['price'] . 'лв. - ';foreach( $pci['option'] as $option ){echo $option['name'] . ' ' . $option['value'] . '/' . PHP_EOL;
                              }} ?>" class="price-edit-<?= $order['product_id'] ?> red-tooltip" data-placement="left"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
                          
                        <?php } else {?> 
                            <span data-toggle="tooltip" class="price-edit-<?= $order['product_id'] ?> red-tooltip" data-placement="left" style="display: none"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
                        <?php } ?>                          
                        <?php } else { ?>
                          <span data-toggle="tooltip" class="price-edit-<?= $order['product_id'] ?> red-tooltip" data-placement="left" style="display: none"><i class="fa fa-info-circle" aria-hidden="true"></i></span>
                        <?php } ?>
                        <a href="" data-toggle="tooltip" data-prod="<?= $order['product_id'] ?>" title="Редактирай цена и/или количество" class="btn btn-warning edit-info"><i class="fa fa-edit"></i></a>                        
                      <?php } ?>
                    <?php } ?>
                    <a href="#" data-toggle="tooltip" data-prod="<?= $order['product_id'] ?>" title="Поръчай отново" class="btn btn-success reorder"><i class="fa fa-shopping-cart"></i></a>                    
                  </td>
                <!-- ако продукта не е забранен за продажба -->
                <?php } else { ?>
                  <td class="text-center" colspan="4">
                    Спрян от продажба
                  </td>
                <?php }?>
              </tr>              
            <?php } ?>
            
          </tbody>          
        </table>
      </div>
      <div class="text-right"><?php echo $pagination; ?></div>
      <?php } else { ?>
      <p> Няма резултати от търсенето! </p>
      <?php } ?>
      <div class="buttons clearfix">
        <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
      </div>
      <?php echo $content_bottom; ?></div>
    </div>
</div>
<!-- product order history modal -->
<div class="modal fade bd-product-modal-lg" id="productModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title col-sm-11" id="productModalLabel"><span>Въведете част от код/име на продукт.</span> История на поръчките</h5>
        <button type="button" class="close col-sm-1 modal-close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      		<div class="container-fluid">
        		<form id="modal-form">
        			<div class="row">
        				<div class="col-sm-10">
        		  			<div class="form-group">
        		  			  	<label for="input-product-data" class="col-form-label col-sm-1">Продукт</label>
        		  			  	<div class="col-sm-11">
        		  			  		<input type="text" class="form-control" id="input-product-data">
        		  				</div>
        		  			</div> 
        		  		</div>
        		  		<div class="col-sm-2">
        					<button type="button" class="btn btn-primary" id="order-history-search">
        						<i class="fa fa-search"> Търсeне</i>
        					</button>        
        				</div>
        			</div>
        		</form>
        		<div class="row" id="product-suggestion"></div>
      		</div>
  		</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary modal-close" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- modal end -->
<script src="catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<link href="catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
  <script type="text/javascript"><!--
     $('[data-toggle="tooltip"]').tooltip();
  $('body').on('click', '.sort-product-history', function(e){
    e.preventDefault();
    var data = [], newArrowClass;
    data['product_id'] = $(this).parents('table').data('product-history');
    data['order'] = $(this).data('order');
    if($(this).hasClass('desc')){      
      data['sort'] = 'DESC';
      newArrowClass = 'asc';
    }else if( $(this).hasClass('asc')){      
      data['sort'] = 'ASC';
      newArrowClass = 'desc';
    } else {
      data['sort'] = 'ASC';
      newArrowClass = 'desc';
    }
    // console.log(data);
    $.ajax({
            url: 'index.php?route=account/orders_in_details/product_order_history',
            type: 'get',
            data: 'product_id='+data['product_id'] +'&order='+data['order'] +'&sort_order='+data['sort'],
            dataType: 'json',      
            success: function(json) {
              let orderHistory = json.order_history, elToApend = $('#product-suggestion'), html='';
              //create sortable table for product
              if(orderHistory){
                html+= '<table class="table" data-product-history="'+data['product_id']+'">';               
                html+='<thead><tr><th><a href="#" class="sort-product-history ';
                if(data['order']=='o.order_id'){
                  html+= newArrowClass;
                }
                html+='" data-order="o.order_id">Поръчка No</a></th>';
                html+= '<th><a href="#" class="sort-product-history ';
                if(data['order']=='o.date_added'){
                  html+= newArrowClass;
                }
                html+='" data-order="o.date_added">Дата</a></th>';
                html+= '<th><a href="#" class="sort-product-history ';
                if(data['order']=='op.quantity'){
                  html+= newArrowClass;
                }
                html+='" data-order="op.quantity">Количество</a></th>';
                html+= '<th><a href="#" class="sort-product-history ';
                if(data['order']=='op.price'){
                  html+= newArrowClass;
                }
                html+='" data-order="op.price">Цена</a></td></tr></thead>';
                for(var ind in orderHistory){
                  html+= '<tr><td>'+orderHistory[ind].order_id+'</td>';
                  //remove hour
                  html+= '<td>'+orderHistory[ind].date_added+'</td>';
                  html+= '<td>'+orderHistory[ind].quantity+'</td>';
                  html+= '<td>'+orderHistory[ind].price+'</td></tr>'
                }
                html+='</table>'
              } else {
                html += '<div class="col-sm-6 col-offset-sm-4"><a data-product-suggestion="" href="#">Продуктът няма история на поръчките!</a></div>';
              }

              elToApend.html(html);
              // $('#modal-form').append(elToApend);
            },
           error: function(xhr, ajaxOptions, thrownError) {
               alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
           }
        }); 
  });
  $('body').on('click', 'button.modal-close', function(){
    $('#product-suggestion').html('');
    $("#input-product-data").val('');
  })
  $('body').on('click', 'a[data-product-suggestion]', function(e){
    e.preventDefault();
    let producstForHistoryId = $(this).data('product-suggestion');
    $('#product-suggestion').html('');
    $('#input-product-data').val($(this).html());
    $.ajax({
            url: 'index.php?route=account/orders_in_details/product_order_history',
            type: 'get',
            data: 'product_id=' + producstForHistoryId,
            dataType: 'json',      
            success: function(json) {
              let orderHistory = json.order_history, elToApend = $('#product-suggestion'), html='';
              //create sortable table for product
              if(orderHistory){
                html+= '<table class="table" data-product-history="'+producstForHistoryId+'">';               
                html+='<thead><tr><th><a href="#" class="sort-product-history asc" data-order="o.order_id">Поръчка No</a></th>';
                html+= '<th><a href="#" class="sort-product-history" data-order="o.date_added">Дата</a></th>';
                html+= '<th><a href="#" class="sort-product-history" data-order="op.quantity">Количество</a></th>';
                html+= '<th><a href="#" class="sort-product-history" data-order="op.price">Цена</a></td></tr></thead>';
                for(var ind in orderHistory){
                  html+= '<tr><td>'+orderHistory[ind].order_id+'</td>';
                  //remove hour
                  html+= '<td>'+orderHistory[ind].date_added+'</td>';
                  html+= '<td>'+orderHistory[ind].quantity+'</td>';
                  html+= '<td>'+orderHistory[ind].price+'</td></tr>'
                }
                html+='</table>'
              } else {
                html += '<div class="col-sm-6 col-offset-sm-4"><a data-product-suggestion="" href="#">Продуктът няма история на поръчките!</a></div>';
              }

              elToApend.html(html);
              // $('#modal-form').append(elToApend);
            },
           error: function(xhr, ajaxOptions, thrownError) {
               alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
           }
        }); 
    
  });
	$('body').on('input', '#input-product-data', function(){
		var prodSearchData = $('#input-product-data').val();
		$('#product-suggestion').html('');
		if(prodSearchData.length > 3){

			$.ajax({
    		  	url: 'index.php?route=account/orders_in_details/products_for_order_history',
    		  	type: 'get',
    		  	data: 'product_data=' + prodSearchData,
    		  	dataType: 'json',      
    		  	success: function(json) {
    		 //  		<div class="row">
      	// 		<div class="col-md-4">.col-md-4</div>
      	// 		<div class="col-md-4 ml-auto">.col-md-4 .ml-auto</div>
    			// </div>
    		    	let products = json.products, elToApend = $('#product-suggestion'), html='';
    		    	if(products){
    		    	for( var ind in products ){
    		    		html+= '<div class="col-sm-6 col-sm-offset-1"><a data-product-suggestion="'+products[ind].product_id+'" href="#">'+products[ind].name+'/код: '+products[ind].model+'</a></div>'
    		    		}
    		    	} else {
    		    		html += '<div class="col-sm-6 col-offset-sm-4"><a data-product-suggestion="" href="#">Няма продукти, отговарящи на търсенето ви!</a></div>';
    		    	}

    		    	elToApend.html(html);
    		    	// $('#modal-form').append(elToApend);
    		  	},
    		   error: function(xhr, ajaxOptions, thrownError) {
    		       alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    		   }
    		});	
		}
	});
$('.date').datetimepicker({
  pickTime: false
});

$('.reorder').on('click', function(e){
  e.preventDefault();
  //check if bulk reorder has been chosen
  var bulkCheckboxes = $('input[name*="bulk-add-"]');
  var filteredBulkCheckboxes = bulkCheckboxes.filter(":checked");
  if(filteredBulkCheckboxes.length > 0) {
    //proceed to bulk gathring product info and bulk add to cart
    
    var productsData = [], productIds = [];
    filteredBulkCheckboxes.each(function() {
      productIds.push($(this).data('product'));
    });
   
    for (var ind in productIds){
        var currentProduct = {},
            old_price, 
            old_quant, 
            dealer_price, 
            dealer_quant,
            old_product_option,      
            old_option_id,
            dealer_option_id,
            dealer_product_option,
            dealerOption = [],
            price,
            quant,
            prodId,
            prodId,
            optionQuery;
            prodIdArr = productIds[ind].split('-row-');
            prodId = prodIdArr[0];
            // search by sibling
            old_price = $('tr#' + productIds[ind] + ' .old-price').html(); 
            old_quant = $('tr#' + productIds[ind] + ' .old-quant').html();
            dealer_price = $('tr#' + productIds[ind] + ' input[name=new-price-'+ prodId +']').val(); 
            dealer_quant = $('tr#' + productIds[ind] + ' input[name=new-quant-'+ prodId +']').val();
          
            old_product_option = $('tr#' + productIds[ind] + ' .old-lot').data('product-option-id'); 
            dealer_product_option = $('tr#' + productIds[ind] + ' select[name="new-lot-'+ prodId +'"]').val();
            // console.log($('tr#' + productIds[ind] + ' select[name="new-lot-'+ prodId +'"]'));
            old_option_id = $('tr#' + productIds[ind] + ' .old-lot').data('option-id');
            dealer_option_id = $('tr#' + productIds[ind] + ' select[name="new-lot-'+ prodId +'"]').data('option-id');
            // console.log(dealer_option_id);

            if( dealer_product_option && dealer_option_id ){
              optionQuery = dealer_option_id+":" +dealer_product_option;
            } else if( old_product_option && old_option_id ){
              optionQuery = old_option_id+":" + old_product_option;        
            } else {
              optionQuery = '';
            }

            if(dealer_price){
              price = dealer_price;
            } else {
              price = old_price;
            }

            if(dealer_quant){
              quant = dealer_quant;
            } else {
              quant = old_quant;
            }

            currentProduct = {
              product_id: prodId,
              quantity: (typeof(quant) != 'undefined' ? quant : 1),
              dealer_price: price,
              option: optionQuery
            }
            productsData.push(currentProduct);
            
          }

          $.ajax({
            url: 'index.php?route=checkout/cart/bulk_add',
            type: 'post',
            data: {products: productsData},
            dataType: 'json',      
            success: function(json) {
              let titleNew;
              $('.alert, .text-danger').remove();
              if( json['error']){
                if (json['error']['not_enough_quantity_in_storage']) {
                 alert(json['error']['not_enough_quantity_in_storage']);
                }
                if (json['error']['same_product_in_bulk']) {
                  console.log(json['error']['same_product_in_bulk'])
                  let message = 'Дублиране на продукт/и - ', prods_in_bulk_error = json['error']['same_product_in_bulk'];

                  for( let ind in prods_in_bulk_error ){                    
                    message += prods_in_bulk_error[ind] + ' // ';
                  }
                  message += '! Не добавяйте в количката еднакви продукти в група! Добавен е първия продукт с избраната партида!';
                 alert(message);
                }
              }
             
              if (json['bulk_success']) {
              let productCartData = json['bulk_success'];
                for(let indProd in productCartData){
                    $('.price-edit-'+indProd).css('display', 'inline');
                    let product_data = productCartData[indProd].product_cart_data;
                    // console.log(product_data);
                    let titleNew = 'Продуктът е добавен в количката! '; 
                    for( let ind in product_data){
                    titleNew+=  product_data[ind].quantity +'x'+ product_data[ind].price + ' лв. - ';           
                      if(product_data[ind].option){
                        let options = product_data[ind].option;
                          for(let optInd in options){              
                            titleNew+= options[optInd].name +' '+ options[optInd].value + '/';              
                          }
                      }
                    }
                $('.price-edit-'+ indProd).removeData('original-title');
                $('.price-edit-'+ indProd).attr('data-original-title', titleNew);
              }
            }  

              //remove checked from checkboxes
                
              //   // Need to set timeout otherwise it wont update the total - YET NOT CORRECT
                setTimeout(function () {
                  $('#cart > button').html('<span id="cart-total"><i class="fa fa-shopping-cart"></i> ' + json['total'] + '</span>');
                }, 100);
              // }
          $('html, body').animate({ scrollTop: 0 }, 'slow');        
          $('#cart > ul').load('index.php?route=common/cart/info ul li');
        // }
      },
          // error: function(xhr, ajaxOptions, thrownError) {
          //     alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
          // }
    });
      
  } else{
  var old_price, 
      old_quant, 
      dealer_price, 
      dealer_quant,
      old_product_option,      
      old_option_id,
      dealer_option_id,
      dealer_product_option,
      dealerOption = [],
      price,
      quant,
      prodId,
      optionQuery;

      prodId = $(this).data('prod');
      old_price = $(this).parents('td').siblings('.prod-' + prodId + ' .old-price').html(); 
      old_quant = $(this).parents('td').siblings('.prod-' + prodId + ' .old-quant').html();
     
      dealer_price = $('input[name="new-price-'+ prodId +'"]').val(); 
      dealer_quant = $('input[name="new-quant-'+ prodId +'"]').val();
      old_product_option = $(this).parents('td').siblings('.prod-' + prodId + ' .old-lot').data('product-option-id'); 
      dealer_product_option = $('select[name="new-lot-'+ prodId +'"]').val();
      old_option_id = $(this).parents('td').siblings('.prod-' + prodId + ' .old-lot').data('option-id');
      dealer_option_id = $('select[name="new-lot-'+ prodId +'"]').data('option-id');
      
      if( dealer_product_option && dealer_option_id ){
        optionQuery = "option["+dealer_option_id+"]=" + dealer_product_option;
      } else if( old_product_option && old_option_id ){
        optionQuery = "option["+ old_option_id+"]=" + old_product_option;        
      } else {
        optionQuery = '';
      }

      if(dealer_price){
        price = dealer_price;
      } else {
        price = old_price;
      }

      if(dealer_quant){
        quant = dealer_quant;
      } else {
        quant = old_quant;
      }
      $.ajax({
      url: 'index.php?route=checkout/cart/add',
      type: 'post',
      data: 'product_id=' + prodId + '&quantity=' + (typeof(quant) != 'undefined' ? quant : 1) + '&dealer_price=' + price + '&'+ optionQuery,
      dataType: 'json',      
      success: function(json) {
        let titleNew;
        $('.alert, .text-danger').remove();
        if( json['error']){
          if (json['error']['not_enough_quantity_in_storage']) {
           alert(json['error']['not_enough_quantity_in_storage'])
          }
        }
        if (json['success']) {          
          $('.price-edit-'+json['product_id']).css('display', 'inline');
          let product_data = json.product_cart_data;
          titleNew = 'Продуктът е добавен в количката! '; 
          for( var ind in product_data){
            titleNew+=  product_data[ind].quantity +'x'+ product_data[ind].price + ' лв. - ';           
            if(product_data[ind].option){
            let options = product_data[ind].option;
            for(var optInd in options){              
              titleNew+= options[optInd].name +' '+ options[optInd].value + '/';              
            }
          }
          }         
          
          $('.price-edit-'+json['product_id']).removeData('original-title');
          $('.price-edit-'+json['product_id']).attr('data-original-title', titleNew);
          
          // Need to set timeout otherwise it wont update the total
          setTimeout(function () {
            $('#cart > button').html('<span id="cart-total"><i class="fa fa-shopping-cart"></i> ' + json['total'] + '</span>');
          }, 100);
        }

          $('html, body').animate({ scrollTop: 0 }, 'slow');
          $('#cart > ul').load('index.php?route=common/cart/info ul li');
        // }
      },
          // error: function(xhr, ajaxOptions, thrownError) {
          //     alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
          // }
    });
     }//end single add product to cart 

})
$('.edit-info').on('click', function(e){
  e.preventDefault(); 
  var currProdId = $(this).parents('tr').attr('class'), currParent;
  currParent = $(this).parents('tr');
    currProdId = currProdId.replace('prod-', '');
    //send get request for all product`s lots
    $.ajax({
      url: 'index.php?route=product/product/get_product_lots',
      type: 'post',
      data: 'product_id=' + currProdId,
      dataType: 'json',      
      success: function(json) {
        var selectHtml, productLots, productLotsId, productLotsData;
        productLots = json.product_options;
        prodId = json.product_id;
        // currParent = $('#prod-' + prodId);
        
        if( productLots.length ){
          productLotsId = productLots[0].product_option_id;
          productLotsData = productLots[0].product_option_value;             
          
          selectHtml = '<select name="new-lot-'+ prodId +'" class="form-control" data-option-id = "' + productLotsId + '"id="">';
          for(var product_option in productLotsData){
            // console.log(product_option)
            selectHtml += '<option value="'+ productLotsData[product_option].product_option_value_id +'">' + productLotsData[product_option].name + '</option>';
          }
          selectHtml += '</select>';
        } else {
          selectHtml = '';
        }
        
        currParent.find('.new-price').html('<input type="text" class="form-control" name="new-price-'+ prodId +'"/>');
        currParent.find('.new-quant').html('<input type="text" class="form-control" name="new-quant-'+ prodId +'"/>');   
        currParent.find('.new-lot').html(selectHtml);          
      },
          // error: function(xhr, ajaxOptions, thrownError) {
          //     alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
          // }
    });

})

</script>
<?php echo $footer; ?>
<script type="text/javascript">
$('#button-filter').on('click', function() {
  url = 'index.php?route=account/orders_in_details&token=<?php echo $token; ?>';
  
  var filter_order_id = $('input[name=\'filter_order_id\']').val();
  
  if (filter_order_id) {
    url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
  }
  
  var filter_email = $('input[name=\'filter_email\']').val();
  
  if (filter_email) {
    url += '&filter_email=' + encodeURIComponent(filter_email);
  }
  
  var filter_product_name = $('input[name=\'filter_product_name\']').val();
  
  if (filter_product_name != '*') {
    url += '&filter_product_name=' + encodeURIComponent(filter_product_name);
  } 

  // // ARIEL
  var filter_model = $('input[name=\'filter_model\']').val();
  
  if (filter_model != '*') {
    url += '&filter_model=' + encodeURIComponent(filter_model);
  } 
  // ARIEL END
  
  var filter_option_value = $('input[name=\'filter_option_value\']').val();
  
  if (filter_option_value != '*') {
    url += '&filter_option_value=' + encodeURIComponent(filter_option_value); 
  } 
  
     
  var filter_date_added = $('input[name=\'filter_date_added\']').val();
  
  if (filter_date_added) {
    url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
  }
  
  location = url;
});

$('#button-filter-remove').on('click', function() {
  url = 'index.php?route=account/orders_in_details&token=<?php echo $token; ?>';
  location = url;
});
</script> 