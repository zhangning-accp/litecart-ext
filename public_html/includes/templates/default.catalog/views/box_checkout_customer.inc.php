<?php
    /**
     * 订单页，原框架就有
     */
?>
<div id="box-checkout-customer" class="box">
  <?php echo functions::form_draw_hidden_field('customer_details', 'true'); ?>

  <?php if (empty(customer::$data['id'])) { ?>
  <div style="float:right">
    <a href="<?php echo document::ilink('login', array('redirect_url' => document::ilink('checkout'))) ?>" data-toggle="lightbox" data-require-window-width="768"><?php echo language::translate('title_sign_in', 'Sign In'); ?></a>
  </div>
  <?php } ?>

  <h2 class="title"><?php echo language::translate('title_customer_details', 'Customer Details'); ?></h2>

  <div class="address shipping-address">

    <div class="row">
      <div class="form-group col-sm-6">
        <label><?php echo language::translate('title_tax_id', 'Tax ID / VATIN'); ?></label>
        <?php echo functions::form_draw_text_field('tax_id', true); ?>
      </div>

      <div class="form-group col-sm-6">
        <label><?php echo language::translate('title_company', 'Company'); ?></label>
        <?php echo functions::form_draw_text_field('company', true); ?>
      </div>
    </div>

    <div class="row">
      <div class="form-group col-sm-6">
        <label><?php echo language::translate('title_firstname', 'First Name'); ?></label>
        <?php echo functions::form_draw_text_field('firstname', true, 'required="required"'); ?>
      </div>

      <div class="form-group col-sm-6">
        <label><?php echo language::translate('title_lastname', 'Last Name'); ?></label>
        <?php echo functions::form_draw_text_field('lastname', true, 'required="required"'); ?>
      </div>
    </div>

    <div class="row">
      <div class="form-group col-sm-6">
        <label><?php echo language::translate('title_address1', 'Address 1'); ?></label>
        <?php echo functions::form_draw_text_field('address1', true, 'required="required"'); ?>
      </div>

      <div class="form-group col-sm-6">
        <label><?php echo language::translate('title_address2', 'Address 2'); ?></label>
        <?php echo functions::form_draw_text_field('address2', true); ?>
      </div>
    </div>

    <div class="row">
      <div class="form-group col-sm-6">
        <label><?php echo language::translate('title_postcode', 'Postal Code'); ?></label>
        <?php echo functions::form_draw_text_field('postcode', true); ?>
      </div>

      <div class="form-group col-sm-6">
        <label><?php echo language::translate('title_city', 'City'); ?></label>
        <?php echo functions::form_draw_text_field('city', true); ?>
      </div>
    </div>

    <div class="row">
      <div class="form-group col-sm-6">
        <label><?php echo language::translate('title_country', 'Country'); ?></label>
        <?php echo functions::form_draw_countries_list('country_code', true); ?>
      </div>

      <div class="form-group col-sm-6">
        <label><?php echo language::translate('title_zone_state_province', 'Zone/State/Province'); ?></label>
        <?php echo functions::form_draw_zones_list(isset($_POST['country_code']) ? $_POST['country_code'] : '', 'zone_code', true); ?>
      </div>
    </div>

    <div class="row">
      <div class="form-group col-sm-6">
        <label><?php echo language::translate('title_email', 'Email'); ?></label>
        <?php echo functions::form_draw_email_field('email', true, 'required="required"'. (!empty(customer::$data['id']) ? ' readonly="readonly"' : '')); ?>
      </div>

      <div class="form-group col-sm-6">
        <label><?php echo language::translate('title_phone', 'Phone'); ?></label>
        <?php echo functions::form_draw_phone_field('phone', true, 'required="required"'); ?>
      </div>
    </div>
  </div><!--Billing address end -->

  <div class="address billing-address">

      <?php //I want to set another address for my invoice. ?>
    <h3><?php echo functions::form_draw_checkbox('different_billing_address', '1',
            !empty($_POST['different_billing_address']) ? '1' : '', 'style="margin: 0px;"'); ?>
        <?php echo language::translate('title_billing_address', 'I want to set another address for my invoice.'); ?></h3>

    <div id="shipping-address-container"<?php echo (empty($_POST['different_billing_address'])) ? ' style="display: none;"' : false; ?>>

      <div class="row">
        <div class="form-group col-sm-6">
          <label><?php echo language::translate('title_company', 'Company'); ?></label>
          <?php echo functions::form_draw_text_field('billing_address[company]', true); ?>
        </div>
      </div>

      <div class="row">
        <div class="form-group col-sm-6">
          <label><?php echo language::translate('title_firstname', 'First Name'); ?></label>
          <?php echo functions::form_draw_text_field('billing_address[firstname]', true); ?>
        </div>

        <div class="form-group col-sm-6">
          <label><?php echo language::translate('title_lastname', 'Last Name'); ?></label>
          <?php echo functions::form_draw_text_field('billing_address[lastname]', true); ?>
        </div>
      </div>

      <div class="row">
        <div class="form-group col-sm-6">
          <label><?php echo language::translate('title_address1', 'Address 1'); ?></label>
          <?php echo functions::form_draw_text_field('billing_address[address1]', true); ?>
        </div>

        <div class="form-group col-sm-6">
          <label><?php echo language::translate('title_address2', 'Address 2'); ?></label>
          <?php echo functions::form_draw_text_field('billing_address[address2]', true); ?>
        </div>
      </div>

      <div class="row">
        <div class="form-group col-sm-6">
          <label><?php echo language::translate('title_postcode', 'Postal Code'); ?></label>
          <?php echo functions::form_draw_text_field('billing_address[postcode]', true); ?>
        </div>

        <div class="form-group col-sm-6">
          <label><?php echo language::translate('title_city', 'City'); ?></label>
          <?php echo functions::form_draw_text_field('billing_address[city]', true); ?>
        </div>
      </div>

      <div class="row">
        <div class="form-group col-sm-6">
          <label><?php echo language::translate('title_country', 'Country'); ?></label>
          <?php echo functions::form_draw_countries_list('billing_address[country_code]', true); ?>
        </div>

        <div class="form-group col-sm-6">
          <label><?php echo language::translate('title_zone_state_province', 'Zone/State/Province'); ?></label>
          <?php echo functions::form_draw_zones_list(isset($_POST['billingg_address']['country_code']) ? $_POST['billing_address']['country_code'] : $_POST['country_code'], 'billing_address[zone_code]', true); ?>
        </div>
      </div>

      <div class="row">
        <div class="form-group col-sm-6">
          <label><?php echo language::translate('title_phone', 'Phone'); ?></label>
          <?php echo functions::form_draw_phone_field('billing_address[phone]', true); ?>
        </div>
      </div>

    </div>
  </div><!--shipping address end-->
    <!-- 原位置此处是放 Create account 的位置。已删掉-->
  <div>
    <button class="btn btn-block btn-default" name="save_customer_details" type="submit" disabled="disabled"><?php echo language::translate('title_save_changes', 'Save Changes'); ?></button>
  </div>

</div>

<script>
  <?php if (!empty(notices::$data['errors'])) { ?>
  alert("<?php echo functions::general_escape_js(notices::$data['errors'][0]); notices::$data['errors'] = array(); ?>");
  <?php } ?>

// Initiate fields

  if ($('select[name="country_code"] option:selected').data('tax-id-format')) {
    $('input[name="tax_id"]').attr('pattern', $('select[name="country_code"] option:selected').data('tax-id-format'));
  } else {
    $('input[name="tax_id"]').removeAttr('pattern');
  }

  if ($('select[name="country_code"] option:selected').data('postcode-format')) {
    $('input[name="postcode"]').attr('pattern', $('select[name="country_code"] option:selected').data('postcode-format'));
  } else {
    $('input[name="postcode"]').removeAttr('pattern');
  }

  if ($('select[name="country_code"] option:selected').data('phone-code')) {
    $('input[name="phone"]').attr('placeholder', '+' + $('select[name="country_code"] option:selected').data('phone-code'));
  } else {
    $('input[name="phone"]').removeAttr('placeholder');
  }

  if ($('select[name="shipping_address[country_code]"] option:selected').data('postcode-format')) {
    $('input[name="shipping_address[postcode]"]').attr('pattern', $('select[name="shipping_address[country_code]"] option:selected').data('postcode-format'));
  } else {
    $('input[name="shipping_address[postcode]"]').removeAttr('pattern');
  }

  if ($('select[name="shipping_address[country_code]"] option:selected').data('phone-code')) {
    $('input[name="shipping_address[phone]"]').attr('placeholder', '+' + $('select[name="shipping_address[country_code]"] option:selected').data('phone-code'));
  } else {
    $('input[name="shipping_address[phone]"]').removeAttr('placeholder');
  }

  $('input[name="create_account"][type="checkbox"]').trigger('change');

  window.customer_form_changed = false;
  window.customer_form_checksum = $('#box-checkout-customer :input').serialize();
</script>