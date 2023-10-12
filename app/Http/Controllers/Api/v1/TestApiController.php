<?php

namespace App\Http\Controllers\Api\v1;

use aliirfaan\LaravelSimpleApi\Http\Resources\ApiResponseCollection;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\CitronelController;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TestApiController extends CitronelController
{
    use ApiController;

    public function __construct(){
        parent::__construct();

        $this->namespace = 'test';
    }

    public function create(Request $request)
    {
        try {
            $rules_set = [
                'first_name' => ['required', 'string'],
                'last_name' => ['required', 'string'],
                'password' => ['required', 'min:5'],
                'email' => ['required', 'email']
            ];

            $validationResult = $this->apiHelperService->validateRequestFields($request->json()->all(), $rules_set);
            if (!is_null($validationResult)) {
                $errorResource = $this->apiHelperService->apiValidationErrorResponse($this->namespace, $validationResult);
                return $errorResource->response()->setStatusCode($errorResource->collection['status_code']);
            }

            $fail = false;
            if($fail) {
                $errorName = 'OBJECT_NOT_FOUND_ERROR';
                $issue[] = 'test';
                $error[] = $this->apiHelperService->constructErrorDetail($issue);
                $errorResponse = $this->apiHelperService->apiProcessingErrorResponse($this->namespace, $error, 'test');

                return $errorResponse->response()->setStatusCode(403);
            }
        }
        catch (QueryException $e){
            report($e);
            $errorResponse = $this->apiHelperService->apiDatabaseErrorResponse($this->namespace);
            return $this->sendApiResponse($errorResponse, $errorResponse->collection['status_code']);
        }
        catch (Exception $e){
            report($e);
            $errorResponse = $this->apiHelperService->apiUnknownErrorResponse($this->namespace);
            return $this->sendApiResponse($errorResponse, $errorResponse->collection['status_code']);
        }

        $this->data['success'] = true;
        $this->data['message'] = 'Test';
        $this->data['status_code'] = Response::HTTP_OK;

        $resultResponse = new ApiResponseCollection($this->data);
        return $this->sendApiResponse($resultResponse, $resultResponse->collection['status_code']);
    }
}
