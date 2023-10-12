<?php

namespace App\Http\Controllers;

use aliirfaan\LaravelSimpleApi\HypermediaRelation;
use aliirfaan\LaravelSimpleApi\Services\ApiHelperService;
use App\Services\Api\v1\Helper\HelperService;
use Illuminate\Http\Response;

class CitronelController extends Controller
{
    public $apiHelperService;

    public $hypermediaRelation;

    public $data;

    public $helperService;

    /**
     * namespace
     *
     * @var string a namespace used for better error logging when generaring debug id
     */
    public $namespace;

    public function __construct()
    {
        $this->apiHelperService = new ApiHelperService();
        $this->hypermediaRelation = new HypermediaRelation();
        $this->data = $this->apiHelperService->responseArrayFormat;
        $this->helperService = new HelperService();
    }

    public function generateCustomerErrorMessage($errors, $namespace, $processCode, $errorMessageKey = null, $errorName = 'PROCESSING_ERROR', $statusCode = Response::HTTP_BAD_REQUEST)
    {
        $errorCode = $processCode . config('error-catalogue.processing_error.code');
        $errorMessage =  __('error_catalogue/messages.processing_error', ['code' => $errorCode]);
        if (!is_null($errorMessageKey)) {
            $errorMessage = __($errorMessageKey, ['code' => $errorCode]);
        }

        return $this->apiHelperService->apiErrorResponse($errors, $namespace, $errorName, $errorMessage, $statusCode);
    }

    public function generateCustomCustomerErrorMessage($errors, $namespace, $processCode, $subProcessCode = null, $errorMessageKey = null, $errorName = 'PROCESSING_ERROR', $statusCode = Response::HTTP_BAD_REQUEST)
    {
        $errorCode = $processCode . config('error-catalogue.processing_error.code');
        if (!is_null($subProcessCode)) {
            $errorCode = $processCode . config($subProcessCode);
        }
        $errorMessage =  __('error_catalogue/messages.processing_error', ['code' => $errorCode]);
        if (!is_null($errorMessageKey)) {
            $errorMessage = __($errorMessageKey, ['code' => $errorCode]);
        }

        return $this->apiHelperService->apiErrorResponse($errors, $namespace, $errorName, $errorMessage, $statusCode);
    }

    public function generateCustomErrorMessage($errors, $namespace, $processCode, $message = null, $errorName = 'PROCESSING_ERROR', $statusCode = Response::HTTP_BAD_REQUEST)
    {
        $errorCode = $processCode . config('error-catalogue.processing_error.code');
        $errorMessage =  __('error_catalogue/messages.processing_error', ['code' => $errorCode]);
        if (!is_null($message)) {
            $errorMessage = $message;
        }

        return $this->apiHelperService->apiErrorResponse($errors, $namespace, $errorName, $errorMessage, $statusCode);
    }

    public function generateValidationErrorMessage($errors, $namespace, $processCode, $errorMessageKey = null)
    {
        $errorCode = $processCode . config('error-catalogue.validation_error.code');
        $errorMessage =  __('error_catalogue/messages.validation_error', ['code' => $errorCode]);
        if (!is_null($errorMessageKey)) {
            $errorMessage = __($errorMessageKey, ['code' => $errorCode]);
        }

        return $this->apiHelperService->apiValidationErrorResponse($errors, $namespace, $errorMessage);
    }

    public function generateCustomValidationErrorMessage($errors, $namespace, $processCode, $subProcessCode = null, $errorMessageKey = null)
    {
        $errorCode = $processCode . config('error-catalogue.validation_error.code');
        if (!is_null($subProcessCode)) {
            $errorCode = $processCode . config($subProcessCode);
        }
        $errorMessage =  __('error_catalogue/messages.validation_error', ['code' => $errorCode]);
        if (!is_null($errorMessageKey)) {
            $errorMessage = __($errorMessageKey, ['code' => $errorCode]);
        }

        return $this->apiHelperService->apiValidationErrorResponse($errors, $namespace, $errorMessage);
    }

    public function generateCustomValidationErrorByMessage($errors, $namespace, $processCode, $message = null)
    {
        $errorCode = $processCode . config('error-catalogue.validation_error.code');
        $errorMessage =  __('error_catalogue/messages.validation_error', ['code' => $errorCode]);
        if (!is_null($message)) {
            $errorMessage = $message;
        }

        return $this->apiHelperService->apiValidationErrorResponse($errors, $namespace, $errorMessage);
    }

    public function generateDatabaseErrorMessage($namespace, $processCode, $errorMessageKey = null)
    {
        $errorCode = $processCode . config('error-catalogue.database_error.code');
        $errorMessage =  __('error_catalogue/messages.database_error', ['code' => $errorCode]);
        if (!is_null($errorMessageKey)) {
            $errorMessage = __($errorMessageKey, ['code' => $errorCode]);
        }

        return $this->apiHelperService->apiDatabaseErrorResponse($namespace, $errorMessage);
    }

    public function generateCustomDatabaseErrorMessage($namespace, $processCode, $subProcessCode = null, $errorMessageKey = null)
    {
        $errorCode = $processCode . config('error-catalogue.database_error.code');
        if (!is_null($subProcessCode)) {
            $errorCode = $processCode . config($subProcessCode);
        }
        $errorMessage =  __('error_catalogue/messages.database_error', ['code' => $errorCode]);
        if (!is_null($errorMessageKey)) {
            $errorMessage = __($errorMessageKey, ['code' => $errorCode]);
        }

        return $this->apiHelperService->apiDatabaseErrorResponse($namespace, $errorMessage);
    }

    public function generateAuthorizationErrorMessage($namespace, $processCode, $errorMessageKey = null)
    {
        $errorCode = $processCode . config('error-catalogue.authorization_error.code');
        $errorMessage =  __('error_catalogue/messages.authorization_error', ['code' => $errorCode]);
        if (!is_null($errorMessageKey)) {
            $errorMessage = __($errorMessageKey, ['code' => $errorCode]);
        }

        return $this->apiHelperService->apiAuthorizationErrorResponse($namespace, $errorMessage);
    }

    public function generateCustomAuthorizationErrorMessage($namespace, $processCode, $subProcessCode = null, $errorMessageKey = null)
    {
        $errorCode = $processCode . config('error-catalogue.authorization_error.code');
        if (!is_null($subProcessCode)) {
            $errorCode = $processCode . config($subProcessCode);
        }
        $errorMessage =  __('error_catalogue/messages.authorization_error', ['code' => $errorCode]);
        if (!is_null($errorMessageKey)) {
            $errorMessage = __($errorMessageKey, ['code' => $errorCode]);
        }

        return $this->apiHelperService->apiUnknownErrorResponse($namespace, $errorMessage);
    }

    public function generateUnknownErrorMessage($namespace, $processCode, $errorMessageKey = null)
    {
        $errorCode = $processCode . config('error-catalogue.unknown_error.code');
        $errorMessage =  __('error_catalogue/messages.unknown_error', ['code' => $errorCode]);
        if (!is_null($errorMessageKey)) {
            $errorMessage = __($errorMessageKey, ['code' => $errorCode]);
        }

        return $this->apiHelperService->apiUnknownErrorResponse($namespace, $errorMessage);
    }

    public function generateCustomUnknownErrorMessage($namespace, $processCode, $subProcessCode = null, $errorMessageKey = null)
    {
        $errorCode = $processCode . config('error-catalogue.unknown_error.code');
        if (!is_null($subProcessCode)) {
            $errorCode = $processCode . config($subProcessCode);
        }
        $errorMessage =  __('error_catalogue/messages.unknown_error', ['code' => $errorCode]);
        if (!is_null($errorMessageKey)) {
            $errorMessage = __($errorMessageKey, ['code' => $errorCode]);
        }

        return $this->apiHelperService->apiUnknownErrorResponse($namespace, $errorMessage);
    }

    public function generateNotFoundErrorMessage($namespace, $processCode, $errorMessageKey = null)
    {
        $errorCode = $processCode . config('error-catalogue.record_not_found.code');
        $errorMessage =  __('error_catalogue/messages.record_not_found', ['code' => $errorCode]);
        if (!is_null($errorMessageKey)) {
            $errorMessage = __($errorMessageKey, ['code' => $errorCode]);
        }

        return $this->apiHelperService->apiNotFoundErrorResponse($namespace, $errorMessage);
    }
}
