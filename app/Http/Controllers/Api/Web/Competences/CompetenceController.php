<?php

namespace App\Http\Controllers\Api\Web\Competences;

use App\Http\Controllers\Controller;
use App\Http\Requests\InternalUser\StoreRequest;
use App\Models\User;
use App\Models\CompanyRole;
use App\Models\Competence;
use App\Models\CompetenceLog;
use Illuminate\Http\Request;
use App\Models\InternalUser;
use App\Http\Requests\InternalUser\UpdateRequest;
use Symfony\Component\HttpFoundation\Response;

class CompetenceController extends Controller
{


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $competence_id
     * @return \Illuminate\Http\Response
     */
    public function destroy($competence_id)
    {
        $competence = Competence::findOrFail($competence_id);
        if ($competence) {
            CompetenceLog::where('competence_id', $competence_id)->delete();
            $competence->delete();
        }
        return response()->json(
            [],
            Response::HTTP_NO_CONTENT
        );
    }
}
