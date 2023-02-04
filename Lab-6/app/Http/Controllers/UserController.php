<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use JsonMapper;

class UserController extends Controller
{

    public function __construct(private UserRepository $repository)
    {
    }

    /**
     *
     * @return Response
     */
    public function index()
    {
        $users = $this->repository->getUsers();
        return response($users, 200);

    }

    /**
     *
     * @param UserStoreRequest $request
     * @return Response
     */
    public function store(UserStoreRequest $request)
    {
        $validated = $request->validated();
        /**
         * @var User $data
         */
        $data = (new JsonMapper())->map((object)$validated, new User());
        $this->repository->putUser(
            $data->name,
            $data->surname,
            $data->lastname,
            $data->birth_date,
            $data->email,
            $data->user_name
        );

        return response()->noContent();
    }

    /**
     *
     * @param int $id
     * @return JsonResponse|Response
     */
    public function show($id)
    {

        $user = $this->repository->getUser($id);
        return ($user === null) ? response()->json('User not found', 404) : response()->json($user);
    }

    /**
     *
     * @param UserStoreRequest $request
     * @param int $id
     * @return Response
     */
    public function update(UserStoreRequest $request, $id)
    {
        $validated = $request->validated();
        /**
         * @var User $data
         */
        $data = (new JsonMapper())->map((object)$validated, new User());
        $this->repository->updateUser(
            $id,
            $data->name,
            $data->surname,
            $data->lastname,
            $data->birth_date,
            $data->email,
            $data->user_name
        );
        return response()->noContent();
    }

    /**
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->repository->deleteUser($id);
        return response()->noContent();

    }
}
