<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Employe;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateEmployeRequest;
use App\Http\Requests\UpdateEmployeRequest;

class EmployeController extends Controller
{

    public function fetch(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $email = $request->input('email');
        $age = $request->input('age');
        $phone = $request->input('phone');
        $team_id = $request->input('team_id');
        $role_id = $request->input('role_id');
        $limit = $request->input('limit', 10);
        $employeQuery = Employe::query();
        if ($id) {
            $employe = $employeQuery->with(['team', 'role'])->find($id);

            if ($employe) {
                return ResponseFormatter::success($employe);
            }

            return ResponseFormatter::error('Employe Not Found', 404);
        }

        $employes = $employeQuery;

        if ($name) {
            $employes->where('name', 'like', '%' . $name . '%');
        }
        if ($email) {
            $employes->where('email', $email);
        }
        if ($age) {
            $employes->where('age', $age);
        }
        if ($phone) {
            $employes->where('phone', 'like', '%' . $phone . '%');
        }
        if ($role_id) {
            $employes->where('role_id', $role_id);
        }
        if ($team_id) {
            $employes->where('team_id', $team_id);
        }

        return ResponseFormatter::success(
            $employes->paginate($limit),
            'Employe Found'
        );
    }

    public function create(CreateEmployeRequest $request)
    {
        try {
            if ($request->hasFile('photo')) {
                $path = $request->file('icon')->store('public/photos');
            }
            $employe = Employe::create([
                'name' => $request->name,
                'email' => $request->email,
                'gender' => $request->gender,
                'age' => $request->age,
                'phone' => $request->phone,
                'photo' => $path,
                'team_id' => $request->team_id,
                'role_id' => $request->role_id,
            ]);

            if (!$employe) {
                throw new Exception('Employe Not Created');
            }

            // $user = User::find(Auth::user());
            // $user->companies()->attach($employe->id);

            return ResponseFormatter::success($employe, 'Employe Created');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    public function update(UpdateEmployeRequest $request, $id)
    {

        try {
            $employe = Employe::find($id);
            if (!$employe) {
                throw new Exception('employe Not Found');
            }

            if ($request->hasFile('photo')) {
                $path = $request->file('icon')->store('public/photos');
            }

            $employe->update([
                'name' => $request->name,
                'email' => $request->email,
                'gender' => $request->gender,
                'age' => $request->age,
                'phone' => $request->phone,
                'photo' => isset($path) ? $path : $employe->photo,
                'team_id' => $request->team_id,
                'role_id' => $request->role_id,
            ]);

            return ResponseFormatter::success($employe, 'employe Updated');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $employe = Employe::find($id);

            if (!$employe) {
                throw new Exception('Employe Not Found');
            }

            $employe->delete();

            return ResponseFormatter::success('Employe Deleted');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }
}
