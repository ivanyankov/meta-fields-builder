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
     */
    public function render() 
    {
        echo "<label>{$this->label}</label>";
        echo "<input type='text' name='{$this->name}' />";
    }

    /**
     * Save the field data into the database table.
     * 
     * @param int $post_id
     * @return void
     */
    public function save( $post_id ) : void 
    {
        if (isset($_POST[$this->name])) {
            update_post_meta($post_id, $this->name, sanitize_text_field( $_POST[$this->name]));
        }
    }
}