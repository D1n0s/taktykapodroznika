<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\Createmodel=UserAPIRequest;
use App\Http\Requests\API\Updatemodel=UserAPIRequest;
use App\Models\model=User;
use App\Repositories\model=UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;

/**
 * Class model=UserAPIController
 */
class model=UserAPIController extends AppBaseController
{
    private model=UserRepository $model=UserRepository;

    public function __construct(model=UserRepository $model=UserRepo)
    {
        $this->model=UserRepository = $model=UserRepo;
    }

    /**
     * Display a listing of the model=Users.
     * GET|HEAD /model=-users
     */
    public function index(Request $request): JsonResponse
    {
        $model=Users = $this->model=UserRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($model=Users->toArray(), 'Model= Users retrieved successfully');
    }

    /**
     * Store a newly created model=User in storage.
     * POST /model=-users
     */
    public function store(Createmodel=UserAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        $model=User = $this->model=UserRepository->create($input);

        return $this->sendResponse($model=User->toArray(), 'Model= User saved successfully');
    }

    /**
     * Display the specified model=User.
     * GET|HEAD /model=-users/{id}
     */
    public function show($id): JsonResponse
    {
        /** @var model=User $model=User */
        $model=User = $this->model=UserRepository->find($id);

        if (empty($model=User)) {
            return $this->sendError('Model= User not found');
        }

        return $this->sendResponse($model=User->toArray(), 'Model= User retrieved successfully');
    }

    /**
     * Update the specified model=User in storage.
     * PUT/PATCH /model=-users/{id}
     */
    public function update($id, Updatemodel=UserAPIRequest $request): JsonResponse
    {
        $input = $request->all();

        /** @var model=User $model=User */
        $model=User = $this->model=UserRepository->find($id);

        if (empty($model=User)) {
            return $this->sendError('Model= User not found');
        }

        $model=User = $this->model=UserRepository->update($input, $id);

        return $this->sendResponse($model=User->toArray(), 'model=User updated successfully');
    }

    /**
     * Remove the specified model=User from storage.
     * DELETE /model=-users/{id}
     *
     * @throws \Exception
     */
    public function destroy($id): JsonResponse
    {
        /** @var model=User $model=User */
        $model=User = $this->model=UserRepository->find($id);

        if (empty($model=User)) {
            return $this->sendError('Model= User not found');
        }

        $model=User->delete();

        return $this->sendSuccess('Model= User deleted successfully');
    }
}
