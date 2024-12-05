<?php
/*
Plugin Name: Disable Variable Product Price Range Woocommerce
Description: This usually looks like $100-$999. With this snippet you will be able to hide the highest price, plus add a “From: ” in front of the minimum price.
Version: 2.6.0
WC tested up to: 8.9.0
Requires Plugins: woocommerce
Author: Geek Code Lab
Author URI: https://geekcodelab.com/
Text Domain: disable-variable-product-price-range
*/
if (!defined('ABSPATH')) exit;

define("WDVPPR_BUILD","2.6.0");

define("WDVPPR_TEXT_DOMAIN","disable-variable-product-price-range");

if(!defined("WDVPPR_PLUGIN_DIR_PATH"))
    define("WDVPPR_PLUGIN_DIR_PATH", plugin_dir_path(__FILE__));
if(!defined("WDVPPR_PLUGIN_URL"))
    define("WDVPPR_PLUGIN_URL", plugins_url().'/'.basename(dirname(__FILE__)));

if (!defined("WDVPPR_PLUGIN_BASENAME"))
define("WDVPPR_PLUGIN_BASENAME", plugin_basename(__FILE__));

if (!defined("WDVPPR_PLUGIN_DIR"))
	define("WDVPPR_PLUGIN_DIR", plugin_basename(__DIR__));


require_once(WDVPPR_PLUGIN_DIR_PATH . 'updater/updater.php');

add_action('upgrader_process_complete', 'wdvppr_updater_activate'); // remove  transient  on plugin  update


if(!class_exists('wdvppr_disable_price_range')) {
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
    
    class wdvppr_disable_price_range
    {
        public function __construct() {
            $plugin = plugin_basename(__FILE__);
            add_action( 'before_woocommerce_init', array( $this,'wdvppr_before_woocommerce_init' ) );
            add_action( 'admin_enqueue_scripts', array( $this,'wdvppr_enqueue_custom_admin_style' ));
            add_action( 'wp_print_scripts', array( $this,'wdvppr_print_scripts' ) );
            add_filter( "plugin_action_links_$plugin", array( $this,'wdvppr_add_plugin_settings_link' ));
            add_filter( 'woocommerce_variable_sale_price_html', array( $this,'wdvppr_variation_price_format' ), 10, 2 );
            add_filter( 'woocommerce_variable_price_html', array( $this,'wdvppr_variation_price_format' ), 10, 2 );
            add_action( 'admin_menu', array( $this,'plugin_menu_page' ) );
            add_action( 'admin_init', array( $this,'register_settings_callback' ));
            add_filter( 'woocommerce_available_variation', array( $this, 'rewrite_woocommerce_available_variation' ), 99, 3 );
            add_filter( 'woocommerce_reset_variations_link', array( $this, 'wdvppr_remove_reset_link' ), 20, 1 );
        }

        /**
         * Adding HPOS woocommerce support before_woocommerce_init
         */
        function wdvppr_before_woocommerce_init() {
            if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
                \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
            }
        }

        /**
         * Register and enqueue a custom stylesheet in the WordPress admin.
        */
        function wdvppr_enqueue_custom_admin_style( $hook ) {
            if( is_admin() && $hook == 'woocommerce_page_wdvppr-disable-price-range' ) {
                wp_enqueue_style( 'wdvppr-admin-style', WDVPPR_PLUGIN_URL . "/assets/css/admin-style.css", array(), WDVPPR_BUILD );
                wp_enqueue_script( 'wdvppr-admin-script', WDVPPR_PLUGIN_URL . "/assets/js/admin-script.js", array(), WDVPPR_BUILD );
            }
        }

        /**
         * Register and enqueue a custom stylesheet on front.
         */
        function wdvppr_print_scripts() {
            global $wdvppr_options;
            wp_enqueue_style( 'wdvppr-front', WDVPPR_PLUGIN_URL . "/assets/css/front-style.css", array(), WDVPPR_BUILD );
            wp_enqueue_script('wdvppr-public', WDVPPR_PLUGIN_URL . "/assets/js/public.js", array('jquery'), WDVPPR_BUILD, true);
            wp_localize_script( 'wdvppr-public', 'wdvppr_public_object',
                array( 
                    'changeVariationPrice' => isset($wdvppr_options) && isset($wdvppr_options['wdvppr_change_variation_price']) ? $wdvppr_options['wdvppr_change_variation_price'] : '',
                    'hideDefaultPrice' => isset($wdvppr_options) && isset($wdvppr_options['wdvppr_hide_default_price']) ? $wdvppr_options['wdvppr_hide_default_price'] : '',
                    'wrapperClass' => isset($wdvppr_options) && isset($wdvppr_options['wrapper_class']) ? $wdvppr_options['wrapper_class'] : '',
                    'removePriceClass' => isset($wdvppr_options) && isset($wdvppr_options['remove_price_class']) ? $wdvppr_options['remove_price_class'] : '',
                    'priceType' => isset($wdvppr_options) && isset($wdvppr_options['wdvppr_price_type']) ? $wdvppr_options['wdvppr_price_type'] : 'default'
                )
            );
        }

        /**
         * Add pluign settings to plugin list page.
        */
        public function wdvppr_add_plugin_settings_link( $links ) {
            $support_link = '<a href="https://geekcodelab.com/contact/" target="_blank" >' . __( 'Support', 'disable-variable-product-price-range' ) . '</a>';
            array_unshift( $links, $support_link );
        
            $settings_link = '<a href="'. admin_url() .'admin.php?page=wdvppr-disable-price-range">' . __( 'Settings', 'disable-variable-product-price-range' ) . '</a>';
            array_unshift( $links, $settings_link );
        
            return $links;
        }

        /**
         * Change price format to product
         */
        public function wdvppr_variation_price_format( $price, $product ) {
            global $price_type, $label_value, $custom_text, $add_from, $add_up_to, $wdvppr_sale_price, $display_condition, $sku_with_variation_name, $disable_price_for_admin;

            if(!is_user_logged_in() || (is_user_logged_in() && $disable_price_for_admin == '')) {
                if($product->is_type( 'variable' )):
                    switch ($price_type) {

                        case "min":
                            $before_min_price = ( $add_from === 'on' ) ? __('From ', 'disable-variable-product-price-range') : '';
        
                            $min_price = $this->format_price( $wdvppr_sale_price, 'min', $product );
        
                            $prices = apply_filters( 'wdvppr_prefix_min_price', $before_min_price ) . $min_price;                    
                        break;
        
                        case "max":    
                            $before_max_price = ( $add_up_to === 'on' ) ? __('Up To ', 'disable-variable-product-price-range') : '';
        
                            $max_price = $this->format_price( $wdvppr_sale_price, 'max', $product );
        
                            $prices = apply_filters( 'wdvppr_prefix_max_price', $before_max_price ) . $max_price;
                        break;

                        case "max_to_min":    
                            if( $product->get_variation_price( 'max', true ) === $product->get_variation_price( 'min', true ) ){
                                $prices = wc_price( $product->get_variation_price( 'max', true ) );    
                            }
                            else{
                                $discount_percent_html = $this->wdvppr_discount_percentage($product, $product->get_variation_price( 'max', true ), $product->get_variation_price( 'min', true ));
                                $prices = wc_format_price_range($product->get_variation_price( 'max', true ) , $product->get_variation_price( 'min', true ) );
                                if($discount_percent_html != '') {
                                    $prices .= $discount_percent_html;
                                }
                            }
                        break;

                        case "min_to_max":    
                            if( $product->get_variation_price( 'max', true ) === $product->get_variation_price( 'min', true ) ) {
                                $prices = wc_price( $product->get_variation_price( 'min', true ) );
                            }
                            else{
                                $discount_percent_html = $this->wdvppr_discount_percentage($product, $product->get_variation_price( 'max', true ), $product->get_variation_price( 'min', true ));
                                $prices = wc_format_price_range($product->get_variation_price( 'min', true ) , $product->get_variation_price( 'max', true ) );
                                if($discount_percent_html != '') {
                                    $prices .= $discount_percent_html;
                                }
                            }    
                        break;

                        case "list_all_variation":
                            if(!is_product()) {
                                $prices = $price;
                            }else{
                                $list_varaitions = $this->list_all_variation($product, $wdvppr_sale_price, $sku_with_variation_name);
                                $prices = '<ul class="wdvppr-list-variation">'.implode("",$list_varaitions).'</ul>';
                            }
                        break;

                        case "custom_text":
                            $updated_price = '';

                            if(isset($custom_text) && !empty($custom_text)) {
                                $min_price = wc_price( $product->get_variation_price( 'min', true ) );
                                $updated_min_price = apply_filters( 'wdvppr_non_formatted_price', $min_price, 'min', $product );

                                $max_price = wc_price( $product->get_variation_price( 'max', true ) );
                                $updated_max_price = apply_filters( 'wdvppr_non_formatted_price', $max_price, 'max', $product );

                                if ((strpos($custom_text,'{min_price}') !== false) && (strpos($custom_text,'{max_price}') !== false)) { 
                                    if($min_price == $max_price) {  $custom_text = $price; }
                                }
                                
                                $discount_percent_html = $this->wdvppr_discount_percentage($product, (int)$product->get_variation_price( 'max', true ), (int)$product->get_variation_price( 'min', true ));
                                $search_text = [ "{min_price}", "{max_price}" ];
                                $replace_text = [ $updated_min_price, $updated_max_price ];
                                $updated_price = str_replace( $search_text, $replace_text, $custom_text );

                                if($discount_percent_html != '')    $updated_price .= $discount_percent_html;
                            }else{
                                $updated_price = $price;
                            }

                            $prices = html_entity_decode(wp_unslash($updated_price));
                        break;
        
                        default:
                            if(isset($price_type) && $price_type == 'default') {
                                
                                $label_value = (isset($label_value) && !empty($label_value)) ? $label_value.' ': '';
                                // Main Price
                                $prod_prices = array( $product->get_variation_price( 'min', true ), $product->get_variation_price( 'max', true ) );
                                $prod_price = $prod_prices[0] !== $prod_prices[1] ? sprintf( __( '%1$s', 'woocommerce' ), wc_price( $prod_prices[0] ) ) : wc_price( $prod_prices[0] );
                    
                                // Sale Price
                                $prod_prices = array( $product->get_variation_regular_price( 'min', true ), $product->get_variation_regular_price( 'max', true ) );
                                sort( $prod_prices );
                                $saleprice = $prod_prices[0] !== $prod_prices[1] ? sprintf( __( '%1$s', 'woocommerce' ), wc_price( $prod_prices[0] ) ) : wc_price( $prod_prices[0] );
                    
                                $prod_price = ($prod_price !== $saleprice) ? '<del>' . $saleprice . '</del> <ins>' . $label_value . $prod_price . '</ins>': $label_value . $prod_price;
                                
                                $discount_percent_html = $this->wdvppr_discount_percentage($product, $product->get_variation_price( 'max', true ), $product->get_variation_price( 'min', true ));
                                if($discount_percent_html != '') {
                                    $prod_price .= $discount_percent_html;
                                }
                                $prices = $prod_price;
                                
                            }else{
                                $prices = $price;
                            }
                    }
                    $wdvppr_price = apply_filters( 'wdvppr_woocommerce_variable_price_html', $prices . $product->get_price_suffix(), $product, $price, $price_type );

                    // display price conditions
                    if($display_condition == 'shop') {
                        if(is_shop() || is_product_category() || is_product_tag()){
                            return $wdvppr_price;
                        }else{
                            return $price;
                        }
                    }elseif ($display_condition == 'single') {
                        if(is_product()){
                            return $wdvppr_price;
                        }else{
                            return $price;
                        }
                    }else{
                        return $wdvppr_price;
                    }
                    // return $wdvppr_price;
                else:
                    return $price;
                endif;
            }else{
                return $price;
            }
        }

        /*
         * List all variation
        */
        public function list_all_variation( $product, $format = '', $sku_type = ''  ){
            
            $variation_detail = [];
            if($product->get_name())    $product_name = $product->get_name();

            if($product->get_children()) {

                foreach($product->get_children() as $id){
                    $variation_text = $attribute = [];
                    $variationProduct = wc_get_product( $id );
                    $variation = $variationProduct->get_data();
                    if(isset($variation['attributes']) && !empty($variation['attributes'])) {
                        foreach($variation['attributes'] as $key => $attribute_val) {
                            $attribute[] = ucfirst($attribute_val);
                        }
                    }
                    if(isset($attribute) && !empty($attribute))                 $product_attrs = implode(", ",$attribute);
                    if(isset($variation['sku']) && !empty($variation['sku']))   $sku = $variation['sku'];

                    $discount_percent_html = $this->wdvppr_discount_percentage($variation, (int)$variation["regular_price"], (int)$variation["price"]);
                    
                    $sku  =   (isset($variation['sku']) && !empty($variation['sku'])) ? $variation['sku'] : '';
                    $formatted_price = ($variationProduct->get_price_html()) ? $variationProduct->get_price_html(): '';
                    $formatted_price = (isset($format) && $format == 'on') ?  ' From ' . $formatted_price: $formatted_price;    // Product name - From Price
                    $product_attrs  = (isset($attribute) && !empty($attribute)) ? implode(", ",$attribute): '';     // 
                    $product_sku    = (isset($sku_type) && isset($sku) && $sku_type == 'on') ? ' (' . $sku . ')': '';   // Product name - From Price (sku)
                    $variation_text[] = '<span class="wdvppr-product-name">'. $product_name .'</span>';
                    $variation_text[] = '<span class="wdvppr-product-attribute">'.$product_attrs . $product_sku.'</span>';
                    $variation_text[] = '<span class="wdvppr-product-price">'.$formatted_price.'</span>';
                    
                    if($discount_percent_html != '') {
                        $variation_text[] = $discount_percent_html;
                    }

                    $variation_detail[] = '<li class="wdvppr-variation-'.$variation['id'].'">' . implode(" - ",$variation_text) . '</li>';    // list li
                }
            }
            
            return $variation_detail;
        }

        /*
         * Formate price tag
        */
        public function format_price( $format, $type, $product  ){

            switch ( $format ) {

            case "on":
                if( $product->get_variation_regular_price( $type, true ) !== $product->get_variation_sale_price( $type, true ) ){
                    $formatted_price =  wc_format_sale_price( wc_price( $product->get_variation_regular_price( $type, true ) ), wc_price( $product->get_variation_sale_price( $type, true ) ) );
                    $discount_percent_html = $this->wdvppr_discount_percentage($product, (int)$product->get_variation_regular_price( $type, true ), (int)$product->get_variation_sale_price( $type, true ));

                    $formatted_price .= $discount_percent_html;
                }
                else{
                    $formatted_price = wc_price( $product->get_variation_price( $type, true ) );
                }

                $price = apply_filters( 'wdvppr_formatted_price', $formatted_price, $type, $product );
                break;

            default:
                $formatted_price = wc_price( $product->get_variation_price( $type, true ) );
                $price = apply_filters( 'wdvppr_non_formatted_price', $formatted_price, $type, $product );
            }

            return apply_filters('wdvppr_format_price_fiter', $price, $type, $product);
        }

        /**
         * Pushing price range of product inside `woocommerce_available_variation`
         */
        public function rewrite_woocommerce_available_variation( $default, $class, $variation ){

            // Getting parent product id by variation id
            $product_id = wp_get_post_parent_id( $variation->get_id() );

            // Getting parent product instance
            $parent_product = wc_get_product( $product_id );

            // Pushing the initial price [if WC_Product class initialized]
            if($parent_product != null || $parent_product != false) {
                $default['wdvppr_init_price'] = $parent_product->get_price_html();
            }
            // $default['wdvppr_init_price'] = ($parent_product != null || $parent_product != false) ? $parent_product->get_price_html() : '';

            return apply_filters( 'wdvppr_woocommerce_available_variation', $default, $class, $variation );
        }

        /**
         * Reset "Clear" link control
         */
        public function wdvppr_remove_reset_link( $link ){
            global $hide_reset_link;

            if ( $hide_reset_link === "" ){
                return $link;
            }
            return false;
        }

        /**
         * Add plugin menu page for admin.
        */
        public function plugin_menu_page(){
            add_submenu_page(
                'woocommerce',
                'Disable price range',
                'Disable price range',
                'manage_options',
                'wdvppr-disable-price-range',
                array($this,'admin_menu_disable_price_range')
            );
        }

        public function register_settings_callback() {
            register_setting('wdvppr-all-settings','wdvppr_options',array($this,'sanitize_callback'));
        }

        public function sanitize_callback($input) {
            $new_input = array();

            if(isset($input) && !empty($input)) {
                foreach($input as $key => $val) {
                    if(isset($input[$key]) && $key == 'wdvppr_custom_text') {
                        $new_input[$key] = htmlspecialchars($input[$key]);
                        
                    }elseif(isset($input[$key]) && !empty($input[$key])) {
                        $new_input[$key] = sanitize_text_field($input[$key]);
                    }
                }
            }

            return $new_input;
        }

        /**
         * Discount Percentage
         */
        public function wdvppr_discount_percentage($product, $max_price = 0, $min_price = 0) {
            global $display_discount_badge;
            $discount_percent = ((int)$max_price > 0) ? 100 - ((int)$min_price / (int)$max_price * 100): 0;
            if($discount_percent <= 0 || $display_discount_badge != 'on')  return false;
            
            $discount_percent_html = '<span class="wdvppr-sale-badge">' . number_format($discount_percent,0) . '%</span>';;
            return apply_filters('wdvppr_discount_percent', $discount_percent_html, $product, $max_price, $min_price );
        }

        public function admin_menu_disable_price_range() {            
            require_once("admin/general-settings.php");
            
        }
    }
    new wdvppr_disable_price_range();
}
