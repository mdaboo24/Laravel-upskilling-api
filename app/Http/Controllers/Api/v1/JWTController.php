<?php

namespace App\Http\Controllers\Api\v1;

use aliirfaan\LaravelSimpleApi\Http\Resources\ApiResponseCollection;
use App\Http\Controllers\CitronelController;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;


class JWTController extends CitronelController
{
    public function register(Request $request)
    {
        //Validate data
        $rules_set = [
            'name' => ['string', 'required'],
            'password' => ['required', 'min:5'],
            'email' => ['required', 'email', "unique:users,email",]
        ];

        $validationResult = $this->apiHelperService->validateRequestFields($request->json()->all(), $rules_set);

        if (!is_null($validationResult)) {
            $errorResource = $this->apiHelperService->apiValidationErrorResponse($this->namespace, $validationResult);
            return $errorResource->response()->setStatusCode($errorResource->collection['status_code']);
        }

        //Request is valid, create new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        //User created, return success response
        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $user
        ], ResponseAlias::HTTP_OK);
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        $rules_set = [
            'password' => ['required', 'min:5'],
            'email' => ['required', 'email']
        ];

        $validationResult = $this->apiHelperService->validateRequestFields($request->json()->all(), $rules_set);

        if (!is_null($validationResult)) {
            $errorResource = $this->apiHelperService->apiValidationErrorResponse($this->namespace, $validationResult);
            return $errorResource->response()->setStatusCode($errorResource->collection['status_code']);
        }

        //Request is validated
        //Crean token
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Login credentials are invalid.',
                ], 400);
            }
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Could not create token.',
            ], 500);
        }

        //Token created, return with success response and jwt token
        return response()->json([
            'success' => true,
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $rules_set = [
            'token' => ['required']
        ];

        $validationResult = $this->apiHelperService->validateRequestFields($request->json()->all(), $rules_set);

        if (!is_null($validationResult)) {
            $errorResource = $this->apiHelperService->apiValidationErrorResponse($this->namespace, $validationResult);
            return $errorResource->response()->setStatusCode($errorResource->collection['status_code']);
        }

        try {

            JWTAuth::invalidate($request->token);

            $this->data['result'] = 200;
            $this->data['message'] = 'User has been logged out';
            $this->data['success'] = true;
            $this->data['status_code'] = ResponseAlias::HTTP_OK;

            $resultResponse = new ApiResponseCollection($this->data);
            $response = $this->sendApiResponse($resultResponse, $resultResponse->collection['status_code']);
            return $response;

        } catch (JWTException $exception) {

            $this->data['result'] = 200;
            $this->data['message'] = 'Sorry, user cannot be logged out';
            $this->data['success'] = true;
            $this->data['status_code'] = ResponseAlias::HTTP_INTERNAL_SERVER_ERROR;

            $resultResponse = new ApiResponseCollection($this->data);
            $response = $this->sendApiResponse($resultResponse, $resultResponse->collection['status_code']);
            return $response;
        }
    }

    public function get_user(Request $request)
    {
        $rules_set = [
            'token' => ['required']
        ];

        $validationResult = $this->apiHelperService->validateRequestFields($request->json()->all(), $rules_set);

        if (!is_null($validationResult)) {
            $errorResource = $this->apiHelperService->apiValidationErrorResponse($this->namespace, $validationResult);
            return $errorResource->response()->setStatusCode($errorResource->collection['status_code']);
        }

        $user = JWTAuth::authenticate($request->token);

        return response()->json(['user' => $user]);
    }


}