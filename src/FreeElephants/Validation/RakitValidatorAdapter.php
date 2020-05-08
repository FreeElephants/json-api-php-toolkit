<?php

namespace FreeElephants\Validation;

use FreeElephants\I18n\DummyTranslatorRegistry;
use FreeElephants\I18n\TranslatorRegistryInterface;
use FreeElephants\Validation\Rule\Boolean;
use Rakit\Validation\Validator;

class RakitValidatorAdapter implements ValidatorInterface
{
    private TranslatorRegistryInterface $translatorRegistry;

    public function __construct(TranslatorRegistryInterface $translatorRegistry = null)
    {
        $this->translatorRegistry = $translatorRegistry ?: new DummyTranslatorRegistry();
    }

    public function validate(array $data, Rules $rules, string $language = null): ValidationResult
    {
        list($adoptedRules, $messages) = $this->getRakitAdoptedRules($rules, $language);
        $validation = $this->buildValidator()->validate($data, $adoptedRules, $messages);
        $result = new ValidationResult();
        foreach ($validation->errors()->toArray() as $key => $errors) {
            foreach ($errors as $ruleName => $message) {
                $result->addError($key, $ruleName, $message);
            }
        }

        return $result;
    }

    private function buildValidator(): Validator
    {
        static $validator;

        if (empty($validator)) {
            $validator = new Validator();
            $validator->addValidator(Boolean::class, new Boolean());
        }

        return clone $validator;
    }

    private function getRakitAdoptedRules(Rules $rules, string $language = null): array
    {
        $adoptedRules = [];
        $messages = [];
        foreach ($rules->getRules() as $item) {
            $key = $item[0];
            $validatorName = $item[1];
            $adoptedRules[$key][] = $validatorName;
            if ($message = $item['message'] ?? null) {
                $messageKey = $this->buildMessageKey($key, $validatorName);
                $messages[$messageKey] = $this->translatorRegistry->getTranslator($language ?: TranslatorRegistryInterface::DEFAULT_LANGUAGE)->translate($message);
            }
        }

        return [$adoptedRules, $messages];
    }

    private function buildMessageKey($key, $validatorName): string
    {
        if (strpos($validatorName, ':') > 0) {
            $parts = explode(':', $validatorName);
            $validatorName = array_shift($parts);
        }

        return $key . ':' . $validatorName;
    }
}
