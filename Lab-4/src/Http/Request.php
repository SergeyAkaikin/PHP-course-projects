<?php

namespace MusicService\Http;
use MusicService\Http\Validators\DateValidator;
use MusicService\Http\Validators\EmailValidator;
use MusicService\Http\Validators\IntValidator;
use MusicService\Http\Validators\RequiredValidator;

abstract class Request
{
    /** @var string[] $validationMessages */
    public array $validationMessages = [];
    public function __construct(
        public readonly string $uri,
        public readonly string $method,
        public readonly string $path,
        public readonly object $data
    )
    {
    }

    abstract public function rules(): array;
    abstract public function getModel(): mixed;

    public function validate(): bool
    {
        foreach ($this->rules() as $field => $fieldRules) {
            foreach ($fieldRules as $fieldRule) {
                switch ($fieldRule) {
                    case 'required':
                        if (!(new RequiredValidator())->isValid($field, $this->data->params)) {
                            $this->validationMessages[$field] = 'is required';
                            return false;
                        }
                        break;

                    case 'int':
                        if (!(new IntValidator())->isValid($field, $this->data->params)) {
                            $this->validationMessages[$field] = 'must be int';
                            return false;
                        }
                        break;

                    case 'ableEmail':
                        if (!(new EmailValidator())->isValid($field, $this->data->params)) {
                            $this->validationMessages[$field] = 'invalid format';
                            return false;
                        }
                        break;
                    case 'ableDate':
                        if (!(new DateValidator())->isValid($field, $this->data->params)) {
                            $this->validationMessages[$field] = 'invalid format';
                            return false;
                        }
                        break;
                }
            }
        }
        return true;
    }
}