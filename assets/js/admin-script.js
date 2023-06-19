jQuery(document).ready(function () {

    var $_change_label, $_add_from, $_up_to, $_format_sale_price, $_custom_txt;

    $_change_label = jQuery(".wdvppr-change-label");
    $_add_from = jQuery(".wdvppr-add-from");
    $_up_to = jQuery(".wdvppr-up-to");
    $_format_sale_price = jQuery(".wdvppr-format-sale-price");
    $_custom_txt = jQuery(".wdvppr-custom-text");
    $_sku_with_variation = jQuery(".wdvppr-sku-with-variation-name");

    jQuery("body").on('change', 'input[name="wdvppr_options[wdvppr_price_type]"]', function () {
        const $priceType = jQuery(this).val();

        $_change_label.hide();
        $_add_from.hide();
        $_up_to.hide();
        $_format_sale_price.hide();
        $_custom_txt.hide();
        $_sku_with_variation.hide();

        switch ($priceType) {

            case 'min':
                $_format_sale_price.show();
                $_add_from.show();
                break;

            case 'max':
                $_format_sale_price.show();
                $_up_to.show();
                break;

            case 'min_to_max':
                break;

            case 'max_to_min':
                break;

            case 'custom_text':
                $_custom_txt.show();
                break;

            case 'list_all_variation':
                $_format_sale_price.show();
                $_sku_with_variation.show();
                break;

            default:
                $_change_label.show();
        }

    });

});