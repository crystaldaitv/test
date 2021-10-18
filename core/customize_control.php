<?php
defined('ABSPATH') || die;

/**
 * Class ThemeCustomizeControl
 */
class ThemeCustomizeControl extends WP_Customize_Control
{
    /**
     * Type
     *
     * @var string
     */
    public $type = 'select';
    /**
     * Type
     *
     * @var string
     */
    public $class = '';
    /**
     * Type icon
     *
     * @var string
     */
    public $icon = 'material-icons';
    /**
     * Type string
     *
     * @var string
     */
    public $tooltiptext = '';
    /**
     * Add support for palettes to be passed in.
     *
     * Supported palette values are true, false, or an array of RGBa and Hex colors.
     *
     * @var boolean
     */
    public $palette;
    /**
     * Add support for showing the opacity value on the slider handle.
     *
     * @var array
     */
    public $show_opacity;

    /**
     * Renders the control wrapper and calls $this->render...() for the internals.
     *
     * @return void
     */
    protected function render()
    {
        if ($this->type === 'range') {
            $this->rangeRender();
        } elseif ($this->type === 'font_style') {
            $id          = 'customize-control-' . str_replace(array('[', ']'), array('-', ''), $this->id);
            $this->class .= 'customize-control customize-control-' . $this->type;

            printf('<li id="%s" class="%s">', esc_attr($id), esc_attr($this->class));
            $this->fontRenderContent();
            echo '</li>';
        } elseif ($this->type === 'select') {
            $id          = 'customize-control-' . str_replace(array('[', ']'), array('-', ''), $this->id);
            $this->class .= 'customize-control customize-control-' . $this->type;

            printf('<li id="%s" class="%s">', esc_attr($id), esc_attr($this->class));
            $this->selectFooterColumnsRenderContent();
            echo '</li>';
        } elseif ($this->type === 'select_option_font') {
            $id          = 'customize-control-' . str_replace(array('[', ']'), array('-', ''), $this->id);
            $this->class .= 'customize-control customize-control-' . $this->type;

            printf('<li id="%s" class="%s">', esc_attr($id), esc_attr($this->class));
            $this->selectFontOptionRenderContent();
            echo '</li>';
        } elseif ($this->type === 'select_option') {
            $id          = 'customize-control-' . str_replace(array('[', ']'), array('-', ''), $this->id);
            $this->class .= 'customize-control customize-control-' . $this->type;

            printf('<li id="%s" class="%s">', esc_attr($id), esc_attr($this->class));
            $this->selectOptionRenderContent();
            echo '</li>';
        } elseif ($this->type === 'alpha-color') {
            $id          = 'customize-control-' . str_replace(array('[', ']'), array('-', ''), $this->id);
            $this->class .= 'customize-control customize-control-' . $this->type;

            printf('<li id="%s" class="%s">', esc_attr($id), esc_attr($this->class));
            $this->pickerColorRenderContent();
            echo '</li>';
        } elseif ($this->type === 'checkbox') {
            $id          = 'customize-control-' . str_replace(array('[', ']'), array('-', ''), $this->id);
            $this->class .= 'customize-control customize-control-' . $this->type;

            printf('<li id="%s" class="%s">', esc_attr($id), esc_attr($this->class));
            $this->checkboxRenderContent();
            echo '</li>';
        }
    }

    /**
     * Render the control's range content and calls $this->rangeRenderContent() for the internals.
     *
     * @return void
     */
    protected function rangeRender()
    {
        $id_customize = str_replace(array('[', ']'), array('-', ''), $this->id);
        $id           = 'customize-control-' . $id_customize;

        //we add class to style mobile and table
        $customize = array();
        if (strpos($id_customize, 'tablet_') !== false) {
            $customize   = explode('tablet_', $id_customize);
            $this->class .= ' ag_theme_tablet customize-control customize-control-' . $this->type;
        } elseif (strpos($id_customize, 'phone_') !== false) {
            $customize   = explode('phone_', $id_customize);
            $this->class .= ' ag_theme_phone customize-control customize-control-' . $this->type;
        } else {
            $customize[1] = '';
            $this->class  .= ' customize-control customize-control-' . $this->type;
        }


        printf('<li id="%s" class="%s" data-customize="%s">', esc_attr($id), esc_attr($this->class), esc_attr($customize[1]));
        $this->rangeRenderContent();
        echo '</li>';
    }

    /**
     * Render the control's font content
     *
     * @return void
     */
    public function pickerColorRenderContent()
    {
        // Process the palette
        if (is_array($this->palette)) {
            $palette = implode('|', $this->palette);
        } else {
            // Default to true.
            $palette = (false === $this->palette || 'false' === $this->palette) ? 'false' : 'true';
        }
        // Support passing show_opacity as string or boolean. Default to true.
        $show_opacity = (false === $this->show_opacity || 'false' === $this->show_opacity) ? 'false' : 'true';
        // Begin the output.
        ?>
        <?php if (!empty($this->label)) : ?>
        <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
        <?php endif; ?>
        <?php if (!empty($this->description)) : ?>
        <span class="description customize-control-description"><?php echo esc_html($this->description); ?></span>
        <?php endif; ?>
        <label>
            <input class="alpha-color-control" type="text" data-show-opacity="<?php echo esc_attr($show_opacity); ?>"
                   data-palette="<?php echo esc_attr($palette); ?>"
                   data-default-color="<?php echo esc_attr($this->setting->default); ?>" <?php esc_attr($this->link()); ?> />
        </label>
        <?php
    }

    /**
     * Render the control range content
     *
     * @return void
     */
    public function rangeRenderContent()
    {
        ?>
        <label>
            <?php if (!empty($this->label)) : ?>
                <span class="tooltip customize-control-title">
                    <?php echo esc_html($this->label); ?>
                    <?php if ($this->tooltiptext !== '') :?>
                    <span class="tooltiptext"><?php echo esc_attr($this->tooltiptext); ?></span>
                    <?php endif; ?>
                </span>
            <?php endif; ?>
            <?php if (!empty($this->description)) : ?>
                <span class="description customize-control-description"><?php echo esc_html($this->description); ?></span>
            <?php endif; ?>
            <input type="<?php echo esc_attr($this->type); ?>" <?php $this->input_attrs(); ?>
                   value="<?php echo esc_attr($this->value()); ?>" <?php $this->link(); ?>
                   data-reset_value="<?php echo esc_attr($this->setting->default); ?>"/>
            <input type="number" <?php $this->input_attrs(); ?> class="ag-pb-range-input"
                   value="<?php echo esc_attr($this->value()); ?>"/>
            <span class="material-icons ag_theme_reset">replay</span>
        </label>
        <?php
    }

    /**
     * Render the control's font content
     *
     * @return void
     */
    public function fontRenderContent()
    {
        ?>
        <label>
            <?php if (!empty($this->label)) : ?>
                <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
            <?php endif; ?>
            <?php if (!empty($this->description)) : ?>
                <span class="description customize-control-description"><?php echo esc_html($this->description); ?></span>
            <?php endif; ?>
        </label>
        <?php $current_values = explode('|', $this->value());
        if (empty($this->choices)) {
            return;
        }
        foreach ($this->choices as $value => $label) :
            $checked_class = in_array($label, $current_values) ? ' ag_theme_checked' : '';
            ?>
            <span class="ag_select_type <?php echo esc_attr($this->icon); ?> <?php echo esc_attr($checked_class); ?> <?php echo esc_attr($label); ?>"
                  data-value="<?php echo esc_attr($label); ?>">
                    <?php if ($this->icon !== 'none') :
                        echo esc_attr($value);
                    endif; ?>
                </span>
            <?php
        endforeach;
        ?>
        <input type="hidden" class="ag_value_option" <?php $this->input_attrs(); ?>
               value="<?php echo esc_attr($this->value()); ?>" <?php $this->link(); ?> />
        <?php
    }

    /**
     * Render the control's select content
     *
     * @return void
     */
    public function selectFooterColumnsRenderContent()
    {
        $current_values = $this->value();
        ?>
        <label>
            <?php if (!empty($this->label)) : ?>
                <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
            <?php endif; ?>
            <?php if (!empty($this->description)) : ?>
                <span class="description customize-control-description"><?php echo esc_html($this->description); ?></span>
            <?php endif; ?>
        </label>
        <div class="ag_theme_select_span ag_select_footer_column ag_theme_option_<?php echo esc_attr($current_values); ?>"></div>
        <ul class="ag_theme_select">
            <?php
            if (empty($this->choices)) {
                return;
            }
            foreach ($this->choices as $value => $label) :
                $checked_class = (string) $value === (string) $current_values ? ' ag_theme_selected' : '';
                ?>
                <li class="ag_theme_option ag_theme_option_<?php echo esc_attr($value); ?> <?php echo esc_attr($checked_class); ?>"
                    data-value="<?php echo esc_attr($value); ?>"></li>
                <?php
            endforeach;
            ?>
        </ul>
        <input type="hidden" class="ag_value_option" <?php $this->input_attrs(); ?>
               value="<?php echo esc_attr($current_values); ?>" <?php $this->link(); ?> />
        <?php
    }

    /**
     * Render the control's input checkbox
     *
     * @return void
     */
    public function checkboxRenderContent()
    {
        $current_values = $this->value();
        ?>
        <span class="customize-inside-control-row">
            <input id="_customize-input-<?php echo esc_attr($this->id);?>" type="checkbox" <?php $this->input_attrs();?>
               value="<?php echo esc_attr($current_values); ?>" <?php $this->link(); ?> />
            <label for="_customize-input-<?php echo esc_attr($this->id);?>" class="tooltip customize-control-title">
                <?php echo esc_html($this->label); ?>
                <?php if ($this->tooltiptext !== '') :?>
                    <span class="tooltiptext"><?php echo esc_attr($this->tooltiptext); ?></span>
                <?php endif; ?>
            </label>
        </span>
        <?php
    }

    /**
     * Render the control's select font content
     *
     * @return void
     */
    public function selectFontOptionRenderContent()
    {
        $current_values = $this->value() ? $this->value() : '';
        ?>
        <label>
            <?php if (!empty($this->label)) : ?>
                <span style="display: inline-block" class="customize-control-title"><?php echo esc_html($this->label); ?></span>
                <span style="float: right" class="preview_font"><?php echo esc_attr__('Font preview', 'advanced-gutenberg-theme')?></span>
            <?php endif; ?>
            <?php if (!empty($this->description)) : ?>
                <span class="description customize-control-description"><?php echo esc_html($this->description); ?></span>
            <?php endif; ?>
        </label>
        <div class="ag_theme_select_span ag_theme_select_option">
            <?php
            if ($current_values === '') {
                echo esc_attr__('Default Theme Font', 'advanced-gutenberg-theme');
            } else {
                echo esc_attr($current_values);
            }
            ?>
        </div>

        <ul class="ag_theme_select">
            <li><input class="seach-option ui-autocomplete-input" value="" placeholder="Search"/></li>
            <li data-value=""
                class="ag_theme_option ag_theme_option_default"><?php echo esc_attr__('Default Theme Font', 'advanced-gutenberg-theme'); ?></li>
            <?php
            if (empty($this->choices)) {
                return;
            }
            foreach ($this->choices as $value => $label) :
                $checked_class = (string) $value === (string) $current_values ? ' ag_theme_selected' : '';
                ?>
                <li class="ag_theme_option ag_theme_option_<?php echo esc_attr($value); ?> <?php echo esc_attr($checked_class); ?>"
                    data-value="<?php echo esc_attr($value); ?>"><?php echo esc_attr($value); ?></li>
                <?php
            endforeach;
            ?>
        </ul>
        <input type="hidden" class="ag_value_option " <?php $this->input_attrs(); ?>
               value="<?php echo esc_attr($current_values); ?>" <?php $this->link(); ?> />
        <?php
    }
    /**
     * Render the control's select template customize option content
     *
     * @return void
     */
    public function selectOptionRenderContent()
    {
        $current_values = $this->value() ? $this->value() : '';
        $class = preg_split('/[\[\]]+/', $this->settings['default']->id);
        ?>
        <label>
            <?php if (!empty($this->label)) : ?>
                <span style="display: inline-block" class="customize-control-title"><?php echo esc_html($this->label); ?></span>
            <?php endif; ?>
            <?php if (!empty($this->description)) : ?>
                <span class="description customize-control-description"><?php echo esc_html($this->description); ?></span>
            <?php endif; ?>
        </label>
        <div class="ag_theme_select_span ag_theme_select_option">
            <?php
            if (empty($current_values)) {
                echo esc_attr($this->choices[0]);
            } else {
                echo esc_attr($current_values);
            }
            ?>
        </div>

        <ul class="ag_theme_select <?php echo esc_attr($class[1]); ?>">
            <li><input class="seach-option ui-autocomplete-input" value="" placeholder="Search"/></li>
            <?php
            if (empty($this->choices)) {
                return;
            }
            foreach ($this->choices as $key => $value) :
                $checked_class = (string) $value === (string) $current_values ? ' ag_theme_selected' : '';
                ?>
                <li class="ag_theme_option ag_theme_option_<?php echo esc_attr($value); ?> <?php echo esc_attr($checked_class); ?>"
                    data-key="<?php echo esc_attr($key); ?>" data-value="<?php echo esc_attr($value); ?>"><?php echo esc_attr($value); ?></li>
                <?php
            endforeach;
            ?>
        </ul>
        <input type="hidden" class="ag_value_option " <?php $this->input_attrs(); ?>
               value="<?php echo esc_attr($current_values); ?>" <?php $this->link(); ?> />
        <?php
    }
}
