<?php

namespace App\Services\Api\v1\Helper;

use Illuminate\Support\Str;

/**
 * HelperService
 * 
 * Class for general helper functions
 */
class HelperService
{
    /**
     * formatValidDate
     *
     * Converts a valid date from one format to another
     * @param  string $dateStr
     * @param  string $inputDateFormat
     * @param  string $outputDateFormat
     * @return string
     */
    public function formatValidDate($dateStr, $inputDateFormat ='dMY', $outputDateFormat = null)
    {
        if(is_null($outputDateFormat)) {
            $outputDateFormat = $this->getDateDisplayFormat();
        }
        $dateObj = \DateTime::createFromFormat($inputDateFormat, $dateStr);
        return $dateObj->format($outputDateFormat);
    }

    public function getDateDisplayFormat()
    {
        return strval(config('citronel.date_display_format'));
    }

    public function formatCurrencyAmount($amount, $decimals = 2, $decimalSeparator = '.', $thousandsSeparator = ',')
    {   
        $amount = number_format($amount, $decimals);
        return strval(config('selfcare.currency_symbol')) . ' ' . $amount;
    }

    public function makeObject($className, $parameters = [], $classPath = 'App\\Services\\Api\\v1\\')
    {
        $object = app()->makeWith($classPath . $className, $parameters);
        
        return $object;
    }

    public function generationCorrelationToken()
    {
        return (string) Str::uuid();
    }

    public function getDBEngineDateDisplayFormat()
    {
        return strval(config('citronel.engine_date_display_format'));
    }

    public function cleanPhoneNumber($phoneNumber) {
        
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

        return $phoneNumber;
    }

    /**
     * mutateRequestFields
     *
     * Transform the request fields to the required format if a mutator method exists
     * For example to transform 'identifier' field, implement a mutator method named 'mutateIdentifierField'
     * 
     * @param  array $requestFields
     * @return array
     */
    public function mutateRequestFields($requestFields, $object)
    {
        foreach ($requestFields as $key => $value) {
            $mutatorName = 'mutate' . ucfirst($key) . 'Field';
            if (method_exists($object, $mutatorName)) {
                $requestFields[$key] = $object->$mutatorName($value);
            }
        }

        return $requestFields;
    }
}