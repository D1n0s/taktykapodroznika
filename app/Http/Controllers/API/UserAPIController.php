<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\UserAPIRequest;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;

class UserAPIController extends AppBaseController
{
    private $model;

    public function __construct(UserRepository $model)
    {
        $this->model = $model;
    }

    public function index(Request $request): JsonResponse
    {
        $users = $this->model->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($users->toArray(), 'Users retrieved successfully');
    }

    public function store(UserAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        $user = $this->model->create($input);

        return $this->sendResponse($user->toArray(), 'User saved successfully');
    }

    public function show($id): JsonResponse
    {
        $user = $this->model->find($id);

        if (empty($user)) {
            return $this->sendError('User not found');
        }

        return $this->sendResponse($user->toArray(), 'User retrieved successfully');
    }

    public function update($id, UserAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        $user = $this->model->find($id);

        if (empty($user)) {
            return $this->sendError('User not found');
        }

        $user = $this->model->update($input, $id);

        return $this->sendResponse($user->toArray(), 'User updated successfully');
    }

    public function destroy($id): JsonResponse
    {
        $user = $this->model->find($id);

        if (empty($user)) {
            return $this->sendError('User not found');
        }

        $user->delete();

        return $this->sendSuccess('User deleted successfully');
    }
}
