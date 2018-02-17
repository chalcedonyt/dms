<?php
namespace App\Services\SpreadsheetService;

class SheetHeader
{
    protected $name;
    protected $specialType;

    const SPECIAL_EMAIL = 'Email';
    const SPECIAL_CONTACT = 'Contact';
    const SPECIAL_NAME = 'Name';

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function setSpecialType(string $type)
    {
        $this->specialType = $type;
    }

    public function __get($prop)
    {
        if (property_exists($this, $prop)) {
            return $this->$prop;
        }
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'special_type' => $this->specialType
        ];
    }
}