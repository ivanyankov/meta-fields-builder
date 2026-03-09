<?php

namespace Yankov\MetaFieldsBuilder\Fields;

/**
 * Renders a numeric input with optional min, max and step constraints.
 *
 * @since 1.1.0
 */
class NumberField implements Field
{
    public function __construct(
        public readonly string $name,
        public readonly string $label,
        public readonly float $min = 0,
        public readonly float $max = 100,
        public readonly float $step = 1
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

        echo "
        <div id=\"field_id_{$this->name}\" style=\"display:block;margin-bottom:8px;\">
            <label style=\"display:block;margin-bottom:4px;\">{$this->label}:</label>
            <input type=\"number\" name=\"{$this->name}\" value=\"{$field_value}\" min=\"{$this->min}\" max=\"{$this->max}\" step=\"{$this->step}\" />
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
            $value = floatval($_POST[$this->name]);
            $value = max($this->min, min($this->max, $value));
            update_post_meta($post_id, $this->name, $value);
        }
    }
}
