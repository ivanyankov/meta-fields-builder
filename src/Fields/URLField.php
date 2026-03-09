<?php

namespace Yankov\MetaFieldsBuilder\Fields;

/**
 * Renders a URL input with basic validation.
 *
 * @since 1.1.0
 */
class URLField implements Field
{
    public function __construct(
        public readonly string $name,
        public readonly string $label
    ) {}

    /**
     * Render the field.
     *
     * @param int $post_id
     * @return void
     */
    public function render(int $post_id)
    {
        $field_value = esc_url(get_post_meta($post_id, $this->name, true));

        echo "
        <div id=\"field_id_{$this->name}\" style=\"display:block;margin-bottom:8px;\">
            <label style=\"display:block;margin-bottom:4px;\">{$this->label}:</label>
            <input type=\"url\" name=\"{$this->name}\" value=\"{$field_value}\" style=\"width:100%;\" placeholder=\"https://\" />
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
            update_post_meta($post_id, $this->name, esc_url_raw($_POST[$this->name]));
        }
    }
}
