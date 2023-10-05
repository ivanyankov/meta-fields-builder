<?php

namespace Yankov\MetaFieldsBuilder;

interface Field
{
    /**
     * Render the field.
     *
     * @param int $post_id
     */
    public function render($post_id);

    /**
     * Save the field data into the database table.
     *
     * @param int $post_id
     */
    public function save($post_id);
}
