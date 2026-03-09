<?php

namespace Yankov\MetaFieldsBuilder\Fields;

/**
 * Renders a dropdown select input.
 *
 * Options are passed as an associative array of value => label pairs.
 *
 * @since 1.1.0
 */
class SelectField implements Field
{
    /**
     * @param string $name
     * @param string $label
     * @param array<string, string> $options Value => Label pairs.
     */
    public function __construct(
        public readonly string $name,
        public readonly string $label,
        public readonly array $options = []
    ) {}

    /**
     * Render the field.
     *
     * @param int $post_id
     * @return void
     */
    public function render(int $post_id)
    {
        $field_value = get_post_meta($post_id, $this->name, true);

        $options_html = '<option value="">— Select —</option>';
        foreach ($this->options as $value => $option_label) {
            $selected = selected($field_value, $value, false);
            $options_html .= "<option value=\"{$value}\" {$selected}>{$option_label}</option>";
        }

        echo "
        <div id=\"field_id_{$this->name}\" style=\"display:block;margin-bottom:8px;\">
            <label style=\"display:block;margin-bottom:4px;\">{$this->label}:</label>
            <select name=\"{$this->name}\">{$options_html}</select>
        </div>";
    }

    /**
     * Save the field data into the database table.
     *
     * @param int $post_id
     * @return void
     */
    public function save(int $post_id)
    {
        if (isset($_POST[$this->name])) {
            $value = sanitize_text_field($_POST[$this->name]);

            if (array_key_exists($value, $this->options) || $value === '') {
                update_post_meta($post_id, $this->name, $value);
            }
        }
    }
}
