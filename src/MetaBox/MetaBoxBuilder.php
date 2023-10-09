<?php

namespace Yankov\MetaFieldsBuilder\MetaBox;

class MetaBoxBuilder
{
    private string $id;
    private string $title;
    private array $fields = [];
    private string $location;
    private mixed $page_id;

    public function setId(string $id) : self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title) : self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param array $field
     */
    public function setFields(array $fields) : self
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * @param string $location
     * @param int|null $page_id
     */
    public function setLocation(string $location, int|null $page_id = null) : self
    {
        $this->location = $location;
        $this->page_id = $page_id;

        return $this;
    }

    /**
     * @param string $id
     * @param string $title
     * @param array $fields
     * @param string $screen
     * @param string|null $page_id
     * @return MetaBox
     */
    public static function make(string $id, string $title, array $fields, string $screen = 'post', int|null $page_id = null): MetaBox {
        $builder = new self();
        $builder->setId($id)
                ->setTitle($title)
                ->setFields($fields)
                ->setLocation($screen, $page_id);

        return $builder->build();
    }

    /**
     * @return MetaBox
     */
    public function build() : MetaBox
    {
        return new MetaBox($this->id, $this->title, $this->fields, $this->location, $this->page_id);
    }
}
