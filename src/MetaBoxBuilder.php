<?php

namespace MetaFieldsBuilder;

class MetaBoxBuilder
{
    /**
     * @var string
     */
    private string $id;

    /**
     * @var string
     */
    private string $title;

    /**
     *  @var Field[]
     */
    private array $fields = [];

    /**
     * @var string
     */
    private string $location;

    /**
     * @var mixed
     */
    private mixed $page_id;

    /**
     * @param string $id
     */
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
     * @param Field $field
     */
    public function addField(Field $field) : self
    {
        $this->fields[] = $field;

        return $this;
    }

    /**
     * @param string $location
     */
    public function setLocation(string $location, string|null $page_id = null) : self
    {
        $this->location = $location;
        $this->page_id = $page_id;

        return $this;
    }

    /**
     * @return MetaBox
     */
    public function build() : MetaBox
    {
        return new MetaBox($this->id, $this->title, $this->fields, $this->location, $this->page_id);
    }
}
