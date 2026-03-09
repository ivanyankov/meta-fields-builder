<?php

namespace Yankov\MetaFieldsBuilder\Fields;

/**
 * Renders a group of checkboxes for multi-select.
 *
 * Options are passed as an associative array of value => label pairs.
 * Selected values are stored as a serialized array in a single meta key.
 *
 * @since 1.2.0
 */
class CheckboxGroupField implements Field
{
    /**
     * @param string               $name    Meta key.
     * @param string               $label   Group label.
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
        $saved = get_post_meta($post_id, $this->name, true);
        $saved = is_array($saved) ? $saved : [];

        echo "<div id=\"field_id_{$this->name}\" style=\"display:block;margin-bottom:8px;\">";
        echo "<label style=\"display:block;margin-bottom:4px;font-weight:bold;\">{$this->label}:</label>";

        foreach ($this->options as $value => $option_label) {
            $checked = in_array($value, $saved, true) ? 'checked' : '';
            echo "<label style=\"display:inline-block;margin-right:12px;\">
                <input type=\"checkbox\" name=\"{$this->name}[]\" value=\"{$value}\" {$checked} />
                {$option_label}
            </label>";
        }

        echo '</div>';
    }

    /**
     * Save the field data into the database table.
     *
     * @param int $post_id
     * @return void
     */
    public function save(int $post_id)
    {
        $values = isset($_POST[$this->name]) && is_array($_POST[$this->name])
            ? array_filter($_POST[$this->name], fn($v) => array_key_exists($v, $this->options))
            : [];

        update_post_meta($post_id, $this->name, array_values($values));
    }
}
