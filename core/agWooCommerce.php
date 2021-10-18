<?php
defined('ABSPATH') || die;

/**
 * Class AgWooCommerce
 */
class AgWooCommerce
{
    /**
     * Option theme
     *
     * @var array
     */
    public $themeOption = array();

    /**
     * Check wooCommerce activate
     *
     * @var boolean
     */
    public $wooCommerceActivated = false;

    /**
     * Check page is wooCommerce
     *
     * @var boolean
     */
    public $isWooCommerce = false;

    /**
     * AgWooCommerce constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->themeOption = ag_get_option(AG_THEME_NAME_OPTION, AG_THEME_FOLDER, 'full');
        if (function_exists('is_woocommerce')) {
            $this->wooCommerceActivated = class_exists('woocommerce');
            $this->isWooCommerce = is_woocommerce();
            add_filter('woocommerce_add_to_cart_fragments', array($this, 'headerAddToCartFragment'), 10, 1);
        }
        $this->agAddHook();
    }

    /**
     * Get hook
     *
     * @return void
     */
    public function agAddHook()
    {
        add_action('ag_add_woocommerce_themeOption', array($this, 'agAddWooCommerceThemeOption'), 10);
        add_action('ag_woocommerce_cart_text', array($this, 'wooCustomAddToCartText'), 10, 1);
        add_action('advanced_gutenberg_theme_wooCustomImageWidth', array($this, 'wooCustomImageWidth'), 10);
    }

    /**
     * Woocommerce add to cart fragment
     *
     * @param array $fragments Fragments
     *
     * @return mixed
     */
    public function headerAddToCartFragment($fragments)
    {
        global $woocommerce;

        ob_start();
        ?>
        <a class="ag_theme_cart cart-customlocation" href="<?php echo esc_url(wc_get_cart_url()); ?>"
            title="<?php esc_attr_e('View your shopping cart', 'advanced-gutenberg-theme'); ?>">
            <i class="material-icons-outlined">shopping_cart</i>
            <span>
                <?php
                echo esc_attr($woocommerce->cart->cart_contents_count);
                ?>
            </span>
        </a>
        <?php
        $fragments['a.cart-customlocation'] = ob_get_clean();

        return $fragments;
    }

    /**
     * Update option of theme when it was changed
     *
     * @return void
     */
    public function agAddWooCommerceThemeOption()
    {
        $this->themeOption = ag_get_option(AG_THEME_NAME_OPTION, AG_THEME_FOLDER, 'full');
    }

    /**
     * Function change text of cart text
     *
     * @param array $params Text change
     *
     * @return void
     */
    public function wooCustomAddToCartText($params)
    {
        if ($this->wooCommerceActivated) {
            if (isset($params['cart_text']) && $params['cart_text'] !== '') {
                $wooButtonStyle = isset($this->themeOption['custom']['wooButtonStyle'])
                    ? $this->themeOption['custom']['wooButtonStyle'] : '1';
                if ($wooButtonStyle === '1') {
                    $cart_text = $params['cart_text'];
                    add_filter('woocommerce_product_single_add_to_cart_text', function () use ($cart_text) {
                        return $cart_text;
                    });

                    add_filter('woocommerce_product_add_to_cart_text', function () use ($cart_text) {
                        return $cart_text;
                    });
                }
            }
        }
    }

    /**
     * Function change image size of product
     *
     * @return void
     */
    public function wooCustomImageWidth()
    {
        if ($this->wooCommerceActivated) {
            $agWoo = get_theme_support('woocommerce');
            $agWoo = is_array($agWoo) ? $agWoo[0] : array();

            if (isset($this->themeOption['custom']['agWooSingle'])) {
                $agWoo['thumbnail_image_width'] = (int)$this->themeOption['custom']['agWooSingle'];
            }
        }
    }
}
