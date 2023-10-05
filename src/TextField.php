<?php

namespace Yankov\MetaFieldsBuilder;

class TextField implements Field
{
    /**
     * @var string
     */
    private $name;

    /**
     *  @var string
     */
    private $label;

    public function __construct($name, $label)
    {
        $this->name = $name;
        $this->label = $label;
    }

    /**
     * Render the field.
     *
     * @param int $post_id
     */
    public function render($post_id) : void
    {
        $field_value = get_post_meta($post_id, $this->name, true);

        echo "
        <div id=\"field_id_{$this->name}\" style=\"display:block;margin-bottom:8px;\">
            <label style=\"display:block;margin-bottom:4px;\">{$this->label}:</label>
            <input type='text' name='{$this->name}' value=\"{$field_value}\"/>
        </div>";
    }

    /**
     * Save the field data into the database table.
     *
     * @param int $post_id
     * @return void
     */
    public function save($post_id) : void
    {
        if (isset($_POST[$this->name])) {
            update_post_meta($post_id, $this->name, sanitize_text_field($_POST[$this->name]));
        }
    }
}