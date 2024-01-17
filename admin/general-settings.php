<?php
$wdvppr_options = get_option('wdvppr_options');
$price_type   = (isset($wdvppr_options['wdvppr_price_type'])) ? $wdvppr_options['wdvppr_price_type'] : 'default';
$label_value  = (isset($wdvppr_options['wdvppr_add_label'])) ? $wdvppr_options['wdvppr_add_label'] : 'From:';
$custom_text  = (isset($wdvppr_options['wdvppr_custom_text'])) ? wp_unslash($wdvppr_options['wdvppr_custom_text']) : '';
$add_from     = (isset($wdvppr_options['add_from'])) ? $wdvppr_options['add_from'] : '';
$add_up_to    = (isset($wdvppr_options['add_up_to'])) ? $wdvppr_options['add_up_to'] : '';
$change_variation_price = (isset($wdvppr_options['wdvppr_change_variation_price'])) ? $wdvppr_options['wdvppr_change_variation_price'] : '';
$hide_default_price = (isset($wdvppr_options['wdvppr_hide_default_price'])) ? $wdvppr_options['wdvppr_hide_default_price'] : '';
$hide_reset_link    = (isset($wdvppr_options['wdvppr_hide_reset_link'])) ? $wdvppr_options['wdvppr_hide_reset_link'] : '';
$wdvppr_sale_price  = (isset($wdvppr_options['wdvppr_sale_price'])) ? $wdvppr_options['wdvppr_sale_price'] : '';
$display_condition  = (isset($wdvppr_options['display_condition'])) ? $wdvppr_options['display_condition'] : 'both';
$sku_with_variation_name = (isset($wdvppr_options['sku_with_variation_name'])) ? $wdvppr_options['sku_with_variation_name'] : '';
$display_discount_badge  = (isset($wdvppr_options['display_discount_badge'])) ? $wdvppr_options['display_discount_badge'] : '';
$disable_price_for_admin = (isset($wdvppr_options['disable_price_for_admin'])) ? $wdvppr_options['disable_price_for_admin'] : '';
$wrapper_class = (isset($wdvppr_options['wrapper_class'])) ? $wdvppr_options['wrapper_class'] : '';
$remove_price_class = (isset($wdvppr_options['remove_price_class'])) ? $wdvppr_options['remove_price_class'] : '';


$up_to_style = $add_from_style = $custom_text_style = $change_label_style = $format_sale_style = $sku_with_variation_style = "";
$display_none = 'display: none;';
if(isset($price_type) && ($price_type != 'max' ))               $up_to_style = $display_none;
if(isset($price_type) && ($price_type != 'min' ))               $add_from_style = $display_none;
if(isset($price_type) && ($price_type != 'custom_text' ))       $custom_text_style = $display_none;
if(isset($price_type) && ($price_type != 'default'))            $change_label_style = $display_none;
if(isset($price_type) && ($price_type != 'list_all_variation'))            $sku_with_variation_style = $display_none;
if(isset($price_type) && ($price_type == 'min_to_max' || $price_type == 'max_to_min' || $price_type == 'default' || $price_type == 'custom_text' )) $format_sale_style = $display_none;
?>
    <div class="wrap">
        <h2><?php _e('Disable Variable Product Price Range Woocommerce', WDVPPR_TEXT_DOMAIN); ?></h2>
        <?php settings_errors(); ?>

        <form method="post" action="options.php">
            <?php settings_fields( 'wdvppr-all-settings' ); ?>

            <div class="wdvppr-price-range">
                <div class="wdvppr-title">
                    <strong><?php _e('Price types', WDVPPR_TEXT_DOMAIN); ?></strong>
                </div>
                <div class="wdvppr-label wdvppr-price-type">
                    <span class="wdvppr-radio-field">
                        <input type="radio" name="wdvppr_options[wdvppr_price_type]" value="default" id="default" <?php if(isset($price_type) && $price_type == 'default'){ esc_attr_e('checked'); } ?>>
                        <label for="default"><?php echo esc_html("Default", WDVPPR_TEXT_DOMAIN); ?> <code><del><?php echo esc_html("Reguler Price", WDVPPR_TEXT_DOMAIN); ?></del> <?php echo esc_html("From: Sale PriceFrom: Sale Price", WDVPPR_TEXT_DOMAIN); ?></code></label>
                    </span>
                    <span class="wdvppr-radio-field">
                        <input type="radio" name="wdvppr_options[wdvppr_price_type]" value="min" id="min" <?php if(isset($price_type) && $price_type == 'min'){ esc_attr_e('checked'); } ?>>
                        <label for="min"><?php echo esc_html("Minimum Price", WDVPPR_TEXT_DOMAIN); ?></label>
                    </span>
                    <span class="wdvppr-radio-field">
                        <input type="radio" name="wdvppr_options[wdvppr_price_type]" value="max" id="max" <?php if(isset($price_type) && $price_type == 'max'){ esc_attr_e('checked'); } ?>>
                        <label for="max"><?php echo esc_html("Maximum Price", WDVPPR_TEXT_DOMAIN); ?></label>
                    </span>
                    <span class="wdvppr-radio-field">
                        <input type="radio" name="wdvppr_options[wdvppr_price_type]" value="min_to_max" id="min_to_max" <?php if(isset($price_type) && $price_type == 'min_to_max'){ esc_attr_e('checked'); } ?>>
                        <label for="min_to_max"><?php echo esc_html("Minimum to Maximum Price", WDVPPR_TEXT_DOMAIN); ?></label>
                    </span>
                    <span class="wdvppr-radio-field">
                        <input type="radio" name="wdvppr_options[wdvppr_price_type]" value="max_to_min" id="max_to_min" <?php if(isset($price_type) && $price_type == 'max_to_min'){ esc_attr_e('checked'); } ?>>
                        <label for="max_to_min"><?php echo esc_html("Maximum to Minimum Price", WDVPPR_TEXT_DOMAIN); ?></label>
                    </span>
                    <span class="wdvppr-radio-field">
                        <input type="radio" name="wdvppr_options[wdvppr_price_type]" value="list_all_variation" id="list_all_variation" <?php if(isset($price_type) && $price_type == 'list_all_variation'){ esc_attr_e('checked'); } ?>>
                        <label for="list_all_variation"><?php echo esc_html("List All Variation", WDVPPR_TEXT_DOMAIN); ?></label>
                    </span>
                    <span class="wdvppr-radio-field">
                        <input type="radio" name="wdvppr_options[wdvppr_price_type]" value="custom_text" id="custom_text" <?php if(isset($price_type) && $price_type == 'custom_text'){ esc_attr_e('checked'); } ?>>
                        <label for="custom_text"><?php echo esc_html("Custom Text", WDVPPR_TEXT_DOMAIN); ?></label>
                    </span>
                </div>
            </div>

            <div class="wdvppr-price-range wdvppr-change-label" style="<?php esc_attr_e($change_label_style); ?>">
                <div class="wdvppr-title">
                    <strong><?php _e('Change Label', WDVPPR_TEXT_DOMAIN); ?></strong> 
                </div>
                <div class="wdvppr-label">
                    <input type="text" name="wdvppr_options[wdvppr_add_label]" value="<?php _e($label_value); ?>">
                    <p><i>you can replce text <strong>"From"</strong> on each Page. Default: <strong>From</strong></i></p>
                </div>
            </div>

            <div class="wdvppr-price-range wdvppr-custom-text" style="<?php esc_attr_e($custom_text_style); ?>">
                <div class="wdvppr-title">
                    <strong><?php _e('Custom Text', WDVPPR_TEXT_DOMAIN); ?></strong>
                </div>
                <div class="wdvppr-label">
                    <input type="text" name="wdvppr_options[wdvppr_custom_text]" value="<?php _e($custom_text); ?>" placeholder="Custom Text">
                    <p><i><b>Some Examples:</b> <code>Starts at {min_price}</code>, <code>Starts {min_price} to {max_price}</code></i></p>
                </div>
            </div>
            
            <div class="wdvppr-price-range wdvppr-add-from" style="<?php esc_attr_e($add_from_style); ?>">
                <div class="wdvppr-title">
                    <strong><?php _e('Add From', WDVPPR_TEXT_DOMAIN); ?></strong> 
                </div>
                <div class="wdvppr-label">
                    <label class="wdvppr-switch">
                        <input type="checkbox" class="wdvppr-checkbox" name="wdvppr_options[add_from]" value="on" <?php if(isset($add_from) && $add_from == 'on'){ esc_attr_e('checked'); } ?>>
                        <span class="wdvppr-slider wdvppr-round"></span>
                    </label>
                    <p><i>Enable it to display <u>From</u> before Minimum Price. <b>For Example:</b> <code>From $1 </code></i></p>
                </div>
            </div>

            <div class="wdvppr-price-range wdvppr-up-to" style="<?php esc_attr_e($up_to_style); ?>">
                <div class="wdvppr-title">
                    <strong><?php _e('Add Up To', WDVPPR_TEXT_DOMAIN); ?></strong> 
                </div>
                <div class="wdvppr-label">
                    <label class="wdvppr-switch">
                        <input type="checkbox" class="wdvppr-checkbox" name="wdvppr_options[add_up_to]" value="on" <?php if(isset($add_up_to) && $add_up_to == 'on'){ esc_attr_e('checked'); } ?>>
                        <span class="wdvppr-slider wdvppr-round"></span>
                    </label>
                    <p><i>Enable it to display <u>Up To</u> before Maximum Price. <b>For Example:</b> <code>Up To $10 </code></i></p>
                </div>
            </div>

            <div class="wdvppr-price-range">
                <div class="wdvppr-title">
                    <strong><?php _e('Variation Price', WDVPPR_TEXT_DOMAIN); ?></strong> 
                </div>
                <div class="wdvppr-label">
                    <label class="wdvppr-switch">
                        <input type="checkbox" class="wdvppr-checkbox" name="wdvppr_options[wdvppr_change_variation_price]" value="on" <?php if(isset($change_variation_price) && $change_variation_price == 'on'){ esc_attr_e('checked'); } ?>>
                        <span class="wdvppr-slider wdvppr-round"></span>
                    </label>
                    <p><i><?php echo esc_html("Change price, based on selected variation(s).", WDVPPR_TEXT_DOMAIN); ?></i></p>
                </div>
            </div>

            <div class="wdvppr-price-range">
                <div class="wdvppr-title">
                    <strong><?php _e('Hide Default Price', WDVPPR_TEXT_DOMAIN); ?></strong> 
                </div>
                <div class="wdvppr-label">
                    <label class="wdvppr-switch">
                        <input type="checkbox" class="wdvppr-checkbox" name="wdvppr_options[wdvppr_hide_default_price]" value="on" <?php if(isset($hide_default_price) && $hide_default_price == 'on'){ esc_attr_e('checked'); } ?>>
                        <span class="wdvppr-slider wdvppr-round"></span>
                    </label>
                    <p><i><?php echo esc_html("Don't display default variation price.", WDVPPR_TEXT_DOMAIN); ?></i></p>
                </div>
            </div>

            <div class="wdvppr-price-range">
                <div class="wdvppr-title">
                    <strong><?php _e('Hide Reset Link', WDVPPR_TEXT_DOMAIN); ?></strong>
                </div>
                <div class="wdvppr-label">
                    <label class="wdvppr-switch">
                        <input type="checkbox" class="wdvppr-checkbox" name="wdvppr_options[wdvppr_hide_reset_link]" value="on" <?php if(isset($hide_reset_link) && $hide_reset_link == 'on'){ esc_attr_e('checked'); } ?>>
                        <span class="wdvppr-slider wdvppr-round"></span>
                    </label>
                    <p><i><?php echo esc_html('Remove "Clear" link on single product page.', WDVPPR_TEXT_DOMAIN); ?></i></p>
                </div>
            </div>

            <div class="wdvppr-price-range wdvppr-format-sale-price" style="<?php esc_attr_e($format_sale_style); ?>">
                <div class="wdvppr-title">
                    <strong><?php _e('Format Sale Price', WDVPPR_TEXT_DOMAIN); ?></strong>
                </div>
                <div class="wdvppr-label">
                    <label class="wdvppr-switch">
                        <input type="checkbox" class="wdvppr-checkbox" name="wdvppr_options[wdvppr_sale_price]" value="on" <?php if(isset($wdvppr_sale_price) && $wdvppr_sale_price == 'on'){ esc_attr_e('checked'); } ?>>
                        <span class="wdvppr-slider wdvppr-round"></span>
                    </label>
                    <p><i><?php echo esc_html("Show Regular Price and Sale Price Format.", WDVPPR_TEXT_DOMAIN); ?> <b>For Example:</b> <code>From <del>$40</del> $38 </code></i></p>
                </div>
            </div>

            <div class="wdvppr-price-range">
                <div class="wdvppr-title">
                    <strong><?php _e('Display Condition', WDVPPR_TEXT_DOMAIN); ?></strong>
                </div>
                <div class="wdvppr-label">
                    <select class="wdvppr-regular-ele-width display_condition" name="wdvppr_options[display_condition]">
                        <option value="shop" <?php selected( $display_condition, 'shop' ); ?>><?php echo esc_html("Shop/Archive Page", WDVPPR_TEXT_DOMAIN); ?> </option>
                        <option value="single" <?php selected( $display_condition, 'single' ); ?>><?php echo esc_html("Single Product/Product Description Page", WDVPPR_TEXT_DOMAIN); ?> </option>
                        <option value="both" <?php selected( $display_condition, 'both' ); ?>><?php echo esc_html("Both Shop and Single Product Page", WDVPPR_TEXT_DOMAIN); ?></option>
                    </select>
                </div>
            </div>

            <div class="wdvppr-price-range wdvppr-sku-with-variation-name" style="<?php esc_attr_e($sku_with_variation_style); ?>">
                <div class="wdvppr-title">
                    <strong><?php _e('SKU with variation name', WDVPPR_TEXT_DOMAIN); ?></strong>
                </div>
                <div class="wdvppr-label">
                    <label class="wdvppr-switch">
                        <input type="checkbox" class="wdvppr-checkbox" name="wdvppr_options[sku_with_variation_name]" value="on" <?php if(isset($sku_with_variation_name) && $sku_with_variation_name == 'on'){ esc_attr_e('checked'); } ?>>
                        <span class="wdvppr-slider wdvppr-round"></span>
                    </label>
                    <p><i><b><?php echo esc_html("For Example:", WDVPPR_TEXT_DOMAIN); ?></b> <code>Hoodie – Blue, Yes (woo-hoodie-blue-logo) – <del>$40.00</del> $38.00</code>.</i></p>
                </div>
            </div>

            <div class="wdvppr-price-range">
                <div class="wdvppr-title">
                    <strong><?php _e('Display discount badge', WDVPPR_TEXT_DOMAIN); ?></strong>
                </div>
                <div class="wdvppr-label">
                    <label class="wdvppr-switch">
                        <input type="checkbox" class="wdvppr-checkbox" name="wdvppr_options[display_discount_badge]" value="on" <?php if(isset($display_discount_badge) && $display_discount_badge == 'on'){ esc_attr_e('checked'); } ?>>
                        <span class="wdvppr-slider wdvppr-round"></span>
                    </label>
                    <p><i><b>Note:</b> <?php echo esc_html("This option will also work with", WDVPPR_TEXT_DOMAIN); ?> <b><?php echo esc_html("List all variation price.", WDVPPR_TEXT_DOMAIN); ?></b></i></p>
                </div>
            </div>

            <div class="wdvppr-price-range">
                <div class="wdvppr-title">
                    <strong><?php _e('Disable Price for Admin', WDVPPR_TEXT_DOMAIN); ?></strong>
                </div>
                <div class="wdvppr-label">
                    <label class="wdvppr-switch">
                        <input type="checkbox" class="wdvppr-checkbox" name="wdvppr_options[disable_price_for_admin]" value="on" <?php if(isset($disable_price_for_admin) && $disable_price_for_admin == 'on'){ esc_attr_e('checked'); } ?>>
                        <span class="wdvppr-slider wdvppr-round"></span>
                    </label>
                    <p><i><b>Note:</b> <?php echo esc_html("By enabling this option, Admin can see the default price range while logged in.", WDVPPR_TEXT_DOMAIN); ?></i></p>
                </div>
            </div>

            <div class="wdvppr-price-range">
                <div class="wdvppr-title">
                    <strong><?php _e('Product Wrapper Class', WDVPPR_TEXT_DOMAIN); ?></strong>
                </div>
                <div class="wdvppr-label">
                    <input type="text" name="wdvppr_options[wrapper_class]" placeholder=".product.product-type-variable" value="<?php _e($wrapper_class); ?>">
                    <p style="font-style: italic; color: red;">Give <code>comma (,)</code> after each target classes. <b>Examples:</b> <code>.product.product-type-variable</code>.</p>
                    <p><i><?php echo esc_html("Keep blank, if you haven't any issues with the price changing. This field is for fixing price changing compatibility issue.", WDVPPR_TEXT_DOMAIN); ?></i></p>
                </div>
            </div>

            <div class="wdvppr-price-range">
                <div class="wdvppr-title">
                    <strong><?php _e('Remove Price Class', WDVPPR_TEXT_DOMAIN); ?></strong>
                </div>
                <div class="wdvppr-label">
                    <input type="text" name="wdvppr_options[remove_price_class]" placeholder=".product-inner-wrap .price" value="<?php _e($remove_price_class); ?>">
                    <p style="font-style: italic; color: red;">Give <code>comma (,)</code> after each target classes. <b>Examples:</b> <code>.df-product-inner-wrap .df-product-price.price, .product-inner-wrap .price</code>.</p>
                    <p><i><?php echo esc_html("Keep blank, if you haven't any issues with the price changing. This field is for fixing price changing compatibility issue in the product description/singe product page.", WDVPPR_TEXT_DOMAIN); ?></i></p>
                </div>
            </div>

            <div class="wdvppr-submit-btn">
                <?php submit_button( 'Save Settings' ); ?>
            </div>
        </form>
    </div>
<?php