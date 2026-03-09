<?php

namespace Yankov\MetaFieldsBuilder\Fields;

/**
 * Renders a multi-line textarea input.
 *
 * @since 1.1.0
 */
class TextareaField implements Field
{
    public function __construct(
        public readonly string $name,
        public readonly string $label,
        public readonly int $rows = 4
    ) {}

    /**
     * Render the field.
     *
     * @param int $post_id
     * @return void
     */
    public function render(int $post_id)
    {
        $field_value = esc_textarea(get_post_meta($post_id, $this->name, true));

        echo "
        <div id=\"field_id_{$this->name}\" style=\"display:block;margin-bottom:8px;\">
            <label style=\"display:block;margin-bottom:4px;\">{$this->label}:</label>
            <textarea name=\"{$this->name}\" rows=\"{$this->rows}\" style=\"width:100%;\">{$field_value}</textarea>
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
            update_post_meta($post_id, $this->name, sanitize_textarea_field($_POST[$this->name]));
        }
    }
}
