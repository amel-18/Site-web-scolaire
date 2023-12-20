<?php

namespace App\Http\Controllers;

use App\Models\Cour;
use App\Models\Planning;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnseignantController extends Controller {

    public function index(): View|Application|Factory
    {
        $cours = Cour::where('user_id', Auth::user()->id)->get();

        return view('prof.index', ['cours' => $cours]);
    }

    public function cour($id): View|Application|Factory
    {
        $cour = Cour::find($id);
        $plannings = Planning::where('cours_id', $id)->get();

        return view('prof.cour', ['cour' => $cour, 'plannings' => $plannings]);
    }

    public function createCour(Request $request): RedirectResponse
    {
        $request->validate([
            'id' => 'required|int',
            'date_debut' => 'required|date_format:Y-m-d\TH:i',
            'date_fin' => 'required|date_format:Y-m-d\TH:i',
        ]);

        if ($request->date_debut > $request->date_fin) {
            return back()->withErrors([
                'date' => 'La date de début doit être antérieure à la date de fin.',
            ]);
        }

        $planning = new Planning();
        $planning->cours_id = $request->input('id');
        $planning->date_debut = $request->input('date_debut');
        $planning->date_fin = $request->input('date_fin');

        $planning->save();

        return back()->with('success', 'Le cours a bien été ajouté.');
    }

    public function modifyCourForm($id): View|Application|Factory
    {
        $planning = Planning::find($id);
        $cour = Cour::find($planning->cours_id);

        return view('prof.edit', ['cour' => $cour, 'planning' => $planning]);
    }

    public function modifyCour(Request $request): RedirectResponse
    {
        $request->validate([
            'id' => 'required|int',
            'date_debut' => 'required|date_format:Y-m-d\TH:i',
            'date_fin' => 'required|date_format:Y-m-d\TH:i',
        ]);

        if ($request->date_debut > $request->date_fin) {
            return back()->withErrors([
                'date' => 'La date de début doit être antérieure à la date de fin.',
            ]);
        }

        $planning = Planning::find($request->input('id'));
        $planning->date_debut = $request->input('date_debut');
        $planning->date_fin = $request->input('date_fin');

        $planning->save();

        $cour = Cour::find($planning->cours_id);

        return redirect()->route('prof.cour', $cour->id)->with('success', 'Le cours a bien été modifié.');
    }

    public function deleteCour($id): RedirectResponse
    {
        $planning = Planning::find($id);
        $cour = Cour::find($planning->cours_id);

        $planning->delete();

        return redirect()->route('prof.cour', $cour->id)->with('success', 'Le cours a bien été supprimé.');
    }

    public function planning(): View|Application|Factory
    {
        $cours = Cour::where('user_id', Auth::user()->id)->get();

        for ($i = 0; $i < count($cours); $i++) {
            $cours[$i]->plannings = Planning::where('cours_id', $cours[$i]->id)->get();
        }

        return view('prof.planning', ['cours' => $cours]);
    }
}
