<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-user" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-user" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab">ПОТРЕБИТЕЛ - данни</a></li>
            <!-- if dealer - go to his clients -->
            <?php if($user_group_id == 11){ ?>
              <li><a href="#tab-clients-list" data-toggle="tab">Списък с клиенти</a></li>
            <?php } ?>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
              <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-username"><?php echo $entry_username; ?></label>
            <div class="col-sm-10">
              <input type="text" name="username" value="<?php echo $username; ?>" placeholder="<?php echo $entry_username; ?>" id="input-username" class="form-control" />
              <?php if ($error_username) { ?>
              <div class="text-danger"><?php echo $error_username; ?></div>
              <?php } ?>
            </div>
              </div>
              <div class="form-group">
            <label class="col-sm-2 control-label" for="input-user-group"><?php echo $entry_user_group; ?></label>
            <div class="col-sm-10">
              <select name="user_group_id" id="input-user-group" class="form-control">
                <?php foreach ($user_groups as $user_group) { ?>
                <?php if ($user_group['user_group_id'] == $user_group_id) { ?>
                <option value="<?php echo $user_group['user_group_id']; ?>" selected="selected"><?php echo $user_group['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $user_group['user_group_id']; ?>"><?php echo $user_group['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
              </div>
              <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-firstname"><?php echo $entry_firstname; ?></label>
            <div class="col-sm-10">
              <input type="text" name="firstname" value="<?php echo $firstname; ?>" placeholder="<?php echo $entry_firstname; ?>" id="input-firstname" class="form-control" />
              <?php if ($error_firstname) { ?>
              <div class="text-danger"><?php echo $error_firstname; ?></div>
              <?php } ?>
            </div>
              </div>
              <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-lastname"><?php echo $entry_lastname; ?></label>
            <div class="col-sm-10">
              <input type="text" name="lastname" value="<?php echo $lastname; ?>" placeholder="<?php echo $entry_lastname; ?>" id="input-lastname" class="form-control" />
              <?php if ($error_lastname) { ?>
              <div class="text-danger"><?php echo $error_lastname; ?></div>
              <?php } ?>
            </div>
              </div>
              <div class="form-group">
            <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_email; ?></label>
            <div class="col-sm-10">
              <input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
            </div>
              </div>
              <div class="form-group">
            <label class="col-sm-2 control-label" for="input-image"><?php echo $entry_image; ?></label>
            <div class="col-sm-10"><a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
              <input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image" />
            </div>
              </div>
              <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-password"><?php echo $entry_password; ?></label>
            <div class="col-sm-10">
              <input type="password" name="password" value="<?php echo $password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" autocomplete="off" />
              <?php if ($error_password) { ?>
              <div class="text-danger"><?php echo $error_password; ?></div>
              <?php  } ?>
            </div>
              </div>
              <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-confirm"><?php echo $entry_confirm; ?></label>
            <div class="col-sm-10">
              <input type="password" name="confirm" value="<?php echo $confirm; ?>" placeholder="<?php echo $entry_confirm; ?>" id="input-confirm" class="form-control" />
              <?php if ($error_confirm) { ?>
              <div class="text-danger"><?php echo $error_confirm; ?></div>
              <?php  } ?>
            </div>
              </div>
              <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="status" id="input-status" class="form-control">
                <?php if ($status) { ?>
                <option value="0"><?php echo $text_disabled; ?></option>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <?php } else { ?>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <option value="1"><?php echo $text_enabled; ?></option>
                <?php } ?>
              </select>
            </div>
              </div>
            </div>
            <!-- end tab general -->
            <?php if($user_group_id == 11){ ?>
            <!-- if dealer - dealer`s clients list -->
            <div class="tab-pane" id="tab-clients-list">
              <div class="table-responsive">
                <table id="dealer-customers" class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <td>#</td>
                      <td class="text-left">Фирма - МОЛ - E-mail</td>
                      <td></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $special_row = 1; ?>
                    <?php if( count($user_customers) > 0 ) {?>
                      <?php foreach ($user_customers as $uc) { ?>
                      <tr id="row-<?= $uc['customer_id'] ?>"> 
                        <td><?= $special_row ?></td>                     
                        <td class="text-left" data-user-customer = "<?= $uc['customer_id'] ?>">                          
                          <?php if( trim($uc['company']) != '' ){ echo $uc['company']; } else { echo '<span style="color:#940505" class="bg-danger"> Не е въведено име на фирма! </span>'; }?> 
                          - <?= $uc['firstname']?> <?= $uc['lastname']?> - <?= $uc['email']?>
                        </td>                                             
                        <td class="text-left">
                          <button type="button" data-customer="<?= $uc['customer_id']?>" data-toggle="tooltip" title="Премахни клиент" class="btn btn-danger remove">
                            <i class="fa fa-minus-circle"></i>
                          </button>
                        </td>
                      </tr>
                      <?php $special_row++; ?>
                    <?php } ?>
                    <?php } else { ?>
                    <tr id="special-row<?php echo $special_row; ?>">   
                      <td colspan="3" class="text-center">Този дилър няма клиенти!</td>                   
                    </tr>
                  <?php } ?>
                  <?php if( !empty($user_new_customers) ) { ?>
                    <?php foreach( $user_new_customers as $unc ) { ?>
                      <tr>
                        <td></td>
                        <td>
                          <select name="user_new_customers[]" id="" class="form-control">';
                            <?php foreach( $all_customers as $ac){ ?>
                              <option value="<?= $ac['customer_id'] ?>" <?php if( $ac['customer_id'] == $unc){ echo 'selected'; }?>> <?= $ac['company'] ?> - <?= $ac['name'] ?> - <?= $ac['email'] ?> </option>';
                            <?php } ?>
                          </select>
                        </td>
                        <td>
                          <button type="button" title="Премахни" class="btn btn-danger remove-new-row">
                          <i class="fa fa-minus-circle"></i>
                          </button>
                        </td>                    
                    </tr>
                    <?php } ?>
                  <?php } ?> 
                  </tbody>
                  <tfoot>
                    <tr>
                      <td colspan="2"></td>
                      <td class="text-left">
                        <button type="button" data-toggle="tooltip" title="Добави клиент" class="btn btn-primary add"><i class="fa fa-plus-circle"></i>
                        </button>
                      </td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
            <!-- end dealer`s clients list -->
          <?php } ?>
          </div>
          <!-- end nav-tabs -->
        </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  $('body').on('click', '.remove', function(){
    var customerToRemove = $(this).data('customer');    
    $.ajax({
      url: 'index.php?route=user/user/removeDealerCustomer&token=<?php echo $token; ?>&customer=' +  encodeURIComponent(customerToRemove),
      dataType: 'json',     
      success: function(json) {
        $('tr#row-'+ json.customer).remove(); 
      }
    });
  });

  $('.add').on('click', function(){
    $.ajax({
      url: 'index.php?route=customer/customer/getCustomers&token=<?php echo $token; ?>',
      dataType: 'json',     
      success: function(json) {
        let customers = json.customers;
        let parent = $('#dealer-customers tbody'), html, customerCompany;
        html = '<tr><td>';
        html += '</td><td><select name="user_new_customers[]" id="" class="form-control">';
        for( var ind in customers){
          if( customers[ind].company ){
            customerCompany = customers[ind].company;
          } else {
            customerCompany = '<span style="background-color: #F0B6B6; color: #A50A0A">Не е въведено име на фирма!</span>';
          }
          html += '<option value="' + customers[ind].customer_id + '">' + customerCompany +' - '+ customers[ind].name + ' - '+ customers[ind].email +'</option>';
        }
        html += '</select></td>';
        html += '<td>';
        html += '<button type="button" title="Премахни" class="btn btn-danger remove-new-row">';
        html += '<i class="fa fa-minus-circle"></i>';
        html += '</button>';
        html += '</td></tr>'
        parent.append(html);
      }
    });
  });

  $('body').on('click', '.remove-new-row', function(){
    $(this).parents('tr').remove();    
  });
</script>
<?php echo $footer; ?> 