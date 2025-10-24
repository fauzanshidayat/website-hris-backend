<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Team;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateTeamRequest;
use App\Http\Requests\UpdateTeamRequest;

class TeamController extends Controller
{

    public function fetch(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $limit = $request->input('limit', 10);
        $teamQuery = Team::query();
        if ($id) {
            $team = $teamQuery->find($id);

            if ($team) {
                return ResponseFormatter::success($team);
            }

            return ResponseFormatter::error('Team Not Found', 404);
        }

        $teams = $teamQuery->where('company_id', $request->company_id);

        if ($name) {
            $teams->where('name', 'like', '%' . $name . '%');
        }

        return ResponseFormatter::success(
            $teams->paginate($limit),
            'Team Found'
        );
    }

    public function create(CreateTeamRequest $request)
    {
        try {
            if ($request->hasFile('icon')) {
                $path = $request->file('icon')->store('public/icons');
            }
            $team = Team::create([
                'name' => $request->name,
                'icon' => $path,
                'company_id' => $request->company_id,
            ]);

            if (!$team) {
                throw new Exception('Team Not Created');
            }

            // $user = User::find(Auth::user());
            // $user->companies()->attach($team->id);

            return ResponseFormatter::success($team, 'Team Created');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    public function update(UpdateTeamRequest $request, $id)
    {

        try {
            $team = Team::find($id);
            if (!$team) {
                throw new Exception('team Not Found');
            }

            if ($request->hasFile('icon')) {
                $path = $request->file('icon')->store('public/icon');
            }

            $team->update([
                'name' => $request->name,
                'icon' => isset($path) ? $path : $team->icon,
                'company_id' => $request->company_id
            ]);

            return ResponseFormatter::success($team, 'team Updated');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $team = Team::find($id);

            if (!$team) {
                throw new Exception('Team Not Found');
            }

            $team->delete();

            return ResponseFormatter::success('Team Deleted');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }
}
