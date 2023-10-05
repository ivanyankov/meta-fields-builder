<?php

namespace Yankov\MetaFieldsBuilder;

class MetaBoxBuilder
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     *  @var Field[]
     */
    private $fields = [];

    /**
     * @var string
     */
    private $location;

    /**
     * @var int
     */
    private $page_id;

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param Field $field
     */
    public function addField(Field $field)
    {
        $this->fields[] = $field;

        return $this;
    }

    /**
     * @param string $location
     */
    public function setLocation($location, $page_id = null)
    {
        $this->location = $location;
        $this->page_id = $page_id;

        return $this;
    }

    /**
     * @return MetaBox
     */
    public function build()
    {
        return new MetaBox($this->id, $this->title, $this->fields, $this->location, $this->page_id);
    }
}
