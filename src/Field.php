<?php

namespace Yankov\MetaFieldsBuilder;

interface Field 
{
    /**
     * Render the field.
     */
    public function render();
    
    /**
     * Save the field data into the database table.
     * 
     * @param int $post_id
     */
    public function save( $post_id );
}