<?php

namespace App\Http\Controllers\Api\v1;

use aliirfaan\LaravelSimpleApi\Http\Resources\ApiResponseCollection;
use App\Http\Controllers\ApiController;
use App\Models\Customer;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\CitronelController;
use Illuminate\Database\QueryException;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tymon\JWTAuth\Facades\JWTAuth;

class CustomerController extends CitronelController
{
    use ApiController;
    public function __construct()
    {
        parent::__construct();
        $this->user = JWTAuth::parseToken()->authenticate();
        $this->namespace = 'CustomerController';
    }

    public function GetAllCustomer()
    {
        $customer = Customer::all();
        return response()->json($customer);
    }

    public function SaveCustomer(Request $request)
    {
        $rules_set = [
            'Firstname' => ['required', 'string'],
            'Lastname' => ['required', 'string'],
            'Password' => ['required', 'min:5'],
            'Email' => ['required', 'email', "unique:customers,Email"]
        ];

        try {

            $validationResult = $this->apiHelperService->validateRequestFields($request->json()->all(), $rules_set);

            if (!is_null($validationResult)) {
                $errorResource = $this->apiHelperService->apiValidationErrorResponse($this->namespace, $validationResult);
                return $errorResource->response()->setStatusCode($errorResource->collection['status_code']);
            }


            $customer_ = new Customer;
            $customer_->Firstname = $request->Firstname;
            $customer_->Lastname = $request->Lastname;
            $customer_->Email = $request->Email;
            $customer_->Password = $request->Password;
            $customer_->DateCreated = date_create()->format('Y-m-d');

            $customer_->save();

            $this->data['result'] = 200;
            $this->data['message'] = 'Customer successfully saved!';
            $this->data['success'] = true;
            $this->data['status_code'] = ResponseAlias::HTTP_OK;

        } catch (Exception $e) {
            $errorResponse = $this->apiHelperService->apiUnknownErrorResponse($this->namespace);
            return $this->sendApiResponse($errorResponse, $errorResponse->collection['status_code']);
        }
        $resultResponse = new ApiResponseCollection($this->data);
        $response = $this->sendApiResponse($resultResponse, $resultResponse->collection['status_code']);

        return $response;
    }

    public function DeleteCustomer($id)
    {
        if (Customer::where('id', $id)->exists()) {
            $customer = Customer::find($id);
            $customer->delete();

            $this->data['result'] = 200;
            $this->data['message'] = 'Customer is deleted from the database!';
            $this->data['success'] = true;
            $this->data['status_code'] = ResponseAlias::HTTP_OK;

            $resultResponse = new ApiResponseCollection($this->data);
            $response = $this->sendApiResponse($resultResponse, $resultResponse->collection['status_code']);
            return $response;

        } else {

            $this->data['result'] = 500;
            $this->data['message'] = 'Customer not found!';
            $this->data['success'] = true;
            $this->data['status_code'] = ResponseAlias::HTTP_OK;

            $resultResponse = new ApiResponseCollection($this->data);
            $response = $this->sendApiResponse($resultResponse, $resultResponse->collection['status_code']);
            return $response;
        }
    }

    public function SearchCustomer(Request $request, $id)
    {
        if (Customer::where('id', $id)->exists()) {
            $customer = Customer::find($id);

            if (!empty($customer)) {
                return response()->json($customer, 200);
            }
        } else {
            $this->data['result'] = 500;
            $this->data['message'] = 'Customer not found';
            $this->data['success'] = true;
            $this->data['status_code'] = ResponseAlias::HTTP_NOT_FOUND;

            $resultResponse = new ApiResponseCollection($this->data);
            $response = $this->sendApiResponse($resultResponse, $resultResponse->collection['status_code']);
            return $response;
        }
    }

    public function UpdateCustomer(Request $request, $id)
    {
        $rules_set = [
            'Firstname' => ['required', 'string'],
            'Lastname' => ['required', 'string'],
            'Password' => ['required', 'min:5'],
        ];

        try {

            $validationResult = $this->apiHelperService->validateRequestFields($request->json()->all(), $rules_set);

            if (!is_null($validationResult)) {
                $errorResource = $this->apiHelperService->apiValidationErrorResponse($this->namespace, $validationResult);
                return $errorResource->response()->setStatusCode($errorResource->collection['status_code']);
            }

            if (Customer::where('id', $id)->exists()) {
                $customer_ = Customer::find($id);

                if (!empty($customer_)) {
                    $customer_->Firstname = is_null($request->Firstname) ? $customer_->Firstname : $request->Firstname;
                    $customer_->Lastname = is_null($request->Lastname) ? $customer_->Lastname : $request->Lastname;
                    $customer_->Email = is_null($request->Email) ? $customer_->Email : $request->Email;
                    $customer_->Password = is_null($request->Password) ? $customer_->Password : $request->Password;

                    $customer_->save();

                    $this->data['result'] = 200;
                    $this->data['message'] = 'Customer updated successfully!';
                    $this->data['success'] = true;
                    $this->data['status_code'] = ResponseAlias::HTTP_OK;
                }
            } else {

                $this->data['result'] = 500;
                $this->data['message'] = 'Update Failed!. Customer not found';
                $this->data['success'] = true;
                $this->data['status_code'] = ResponseAlias::HTTP_OK;

                $resultResponse = new ApiResponseCollection($this->data);
                $response = $this->sendApiResponse($resultResponse, $resultResponse->collection['status_code']);
                return $response;
            }

        } catch (Exception $e) {
            $errorResource = $this->apiHelperService->apiUnknownErrorResponse($this->namespace, 'An Error occured');
            $response = $this->sendApiResponse($errorResource, $errorResource->collection['status_code']);
            return $response;
        }

        $resultResponse = new ApiResponseCollection($this->data);
        $response = $this->sendApiResponse($resultResponse, $resultResponse->collection['status_code']);

        return $response;

    }
}