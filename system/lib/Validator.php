<?php

namespace Lib;

class Validator
{
    protected $data = [];
    protected $errors = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function require(array $fields)
    {
        foreach ($fields as $field) {
            if (empty($this->data[$field])) {
                $this->errors[$field][] = ucfirst($field) . ' is required.';
            }
        }
        return $this;
    }

    public function email(string $field)
    {
        if (!empty($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field][] = 'Invalid email format.';
        }
        return $this;
    }

    public function min(string $field, int $length)
    {
        if (!empty($this->data[$field]) && strlen($this->data[$field]) < $length) {
            $this->errors[$field][] = ucfirst($field) . " must be at least $length characters.";
        }
        return $this;
    }

    public function max(string $field, int $length)
    {
        if (!empty($this->data[$field]) && strlen($this->data[$field]) > $length) {
            $this->errors[$field][] = ucfirst($field) . " must not exceed $length characters.";
        }
        return $this;
    }

    public function passes(): bool
    {
        return empty($this->errors);
    }

    public function fails(): bool
    {
        return !$this->passes();
    }

    public function errors(): array
    {
        return $this->errors;
    }
}
