<?php

namespace Yankov\MetaFieldsBuilder\Fields;

/**
 * Renders a single checkbox toggle (on/off).
 *
 * Stores "1" when checked, empty string when unchecked.
 *
 * @since 1.1.0
 */
class CheckboxField implements Field
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
        $field_value = get_post_meta($post_id, $this->name, true);
        $checked = checked($field_value, '1', false);

        echo "
        <div id=\"field_id_{$this->name}\" style=\"display:block;margin-bottom:8px;\">
            <label>
                <input type=\"hidden\" name=\"{$this->name}\" value=\"0\" />
                <input type=\"checkbox\" name=\"{$this->name}\" value=\"1\" {$checked} />
                {$this->label}
            </label>
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
        $value = isset($_POST[$this->name]) && $_POST[$this->name] === '1' ? '1' : '';
        update_post_meta($post_id, $this->name, $value);
    }
}
