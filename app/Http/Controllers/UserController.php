<?php

namespace App\Http\Controllers;

use App\Models\Cour;
use App\Models\Formation;
use App\Models\User;
use App\Models\Planning;
use http\Client\Request;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    public function index(): View|Application|Factory
    {
        $cours = auth()->user()->cours()->get();

        for ($i = 0; $i < count($cours); $i++) {
            $cours[$i]->plannings = Planning::where('cours_id', $cours[$i]->id)->get();
        }

        return view('user.index', ['cours' => $cours]);
    }

    public function cours(): View|Application|Factory
    {
        $formation = Formation::find(auth()->user()->formation_id);
        $cours = Cour::where('formation_id', $formation->id)->get();
        $inscrit = auth()->user()->cours()->get();

        for($i = 0; $i < count($cours); $i++) {
            for($j = 0; $j < count($inscrit); $j++) {
                if($cours[$i]->id == $inscrit[$j]->id) {
                    $cours[$i]->inscrit = true;
                }
            }
        }

        return view('user.cours', ['cours' => $cours]);
    }

    public function inscription($id): RedirectResponse
    {

        $cours = auth()->user()->cours()->where('cours.id', $id)->first();

        if ($cours) {
            return back()->withErrors([
                'cours_id' => 'Vous êtes déjà inscrit à ce cours.'
            ]);
        }

        auth()->user()->cours()->attach($id);

        return back()->with('success', 'Vous êtes inscrit au cours.');
    }

    public function desinscription($id): RedirectResponse
    {
        auth()->user()->cours()->detach($id);

        return back()->with('success', 'Vous êtes désinscrit du cours.');

    }

    public function cour($id): View|Application|Factory
    {
        $cour = Cour::find($id);
        $plannings = Planning::where('cours_id', $cour->id)->get();

        return view('user.cour', ['cour' => $cour, 'plannings' => $plannings]);
    }
}
