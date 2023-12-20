<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use App\Models\User;
use App\Models\Cour;
use App\Models\Planning;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index(): View|Application|Factory
    {
        return view('admin.index');
    }

    // Gestion des users

    public function users(): View|Application|Factory
    {
        $users = User::paginate(10);

        for($i = 0; $i < count($users); $i++) {
            $users[$i]->isntLocked = $users[$i]->isntLocked();
        }

        $id = request()->session()->get('id');
        request()->session()->forget('id');

        $approve = request()->session()->get('approve');
        request()->session()->forget('approve');

        $formations = Formation::all();

        return view('admin.users', ['users' => $users, 'id' => $id, 'approve' => $approve, 'formations' => $formations]);
    }

    public function approveUserForm($id): RedirectResponse
    {
        request()->session()->put('approve', $id);

        return redirect()->route('admin.users');
    }

    public function approveUser(Request $request): RedirectResponse
    {
        $request->validate([
            'type' => 'required|string|max:30'
        ]);

        $user = User::find($request->input('id'));
        $user->type = $request->input('type');
        $user->save();

        return redirect()->route('admin.users');
    }

    public function deleteUser($id): RedirectResponse
    {
        if (Auth::user()->id == $id)
            return back()->withErrors([
                'delete' => 'Vous ne pouvez pas vous supprimer vous-même.',
            ]);

        $user = User::find($id);
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'Utilisateur supprimé avec succès');
    }

    public function modifyUserForm($id): RedirectResponse
    {
        request()->session()->put('id', $id);

        return redirect()->route('admin.users');
    }

    public function modifyUser(Request $request): RedirectResponse
    {
        $request->validate([
            'nom' => 'required|string|max:30',
            'prenom' => 'required|string|max:30',
            'login' => 'required|string|max:30',
            'type' => 'required|string|max:30'
        ]);

        if ($request->input('id') == Auth::user()->id && $request->input('type') != 'admin') {
            return back()->withErrors([
                'modify' => 'Vous ne pouvez pas vous enlever les droits d\'administrateurs',
            ]);
        }



        $user = User::find($request->input('id'));
        $user->nom = $request->input('nom');
        $user->prenom = $request->input('prenom');
        $user->login = $request->input('login');
        $user->type = $request->input('type');
        $user->save();

        return redirect()->route('admin.users')->with('success', 'L\'utilisateur a bien été modifié.');
    }

    public function createUser(Request $request) {
        $request->validate([
            'nom' => 'required|string|max:30',
            'prenom' => 'required|string|max:30',
            'login' => 'required|string|max:30',
            'formation' => 'required|int',
            'mdp' => 'required|string|max:60'
        ]);

        $users = User::all();
        foreach ($users as $user) {
            if ($user->login == $request->login) {
                return back()->withErrors([
                    'login' => 'Ce login est déjà utilisé.',
                ]);
            }
        }

        $user = new User();

        if ($request->formation != 0 && $request->formation != -1) {
            $formation = Formation::find($request->formation);
            $user->formation_id = $formation->id;
            $user->type = 'etudiant';
        } else if ($request->formation == 0) {
            $user->type = 'enseignant';
        } else if ($request->formation == -1) {
            $user->type = 'admin';
        }

        $user->nom = $request->nom;
        $user->prenom = $request->prenom;
        $user->login = $request->login;
        $user->mdp = Hash::make($request->mdp);
        $user->save();

        return redirect()->route('admin.users')->with('success', 'L\'utilisateur a bien été créé.');
    }

    // Gestion des formations

    public function formations(): View|Application|Factory
    {
        $formations = Formation::paginate(10);

        $edit = request()->session()->get('edit');
        request()->session()->forget('edit');

        return view('admin.formations', ['formations' => $formations, 'edit' => $edit]);
    }

    public function createFormation(Request $request): RedirectResponse
    {
        $request->validate([
            'intitule' => 'required|string|max:50'
        ]);

        $formation = new Formation();
        $formation->intitule = request()->input('intitule');
        $formation->save();

        return redirect()->route('admin.formations')->with('success', 'La formation a bien été créée.');
    }

    public function modifyFormationForm($id): RedirectResponse
    {
        request()->session()->put('edit', $id);

        return redirect()->route('admin.formations');
    }

    public function modifyFormation(Request $request): RedirectResponse
    {
        $request->validate([
            'intitule' => 'required|string|max:50'
        ]);

        $formation = Formation::find($request->input('id'));
        $formation->intitule = $request->input('intitule');
        $formation->save();

        return redirect()->route('admin.formations')->with('success', 'La formation a bien été modifiée.');
    }

    // Gestion des cours

    public function cours(): View|Application|Factory
    {
        $cours = Cour::paginate(10);

        for($i = 0; $i < count($cours); $i++) {
            $cours[$i]->formation = Formation::find($cours[$i]->formation_id);
            $cours[$i]->enseignant = User::find($cours[$i]->user_id);
        }

        $formations = Formation::all();

        $enseignants = User::where('type', 'enseignant')->get();

        $edit = request()->session()->get('edit');
        request()->session()->forget('edit');


        return view('admin.cours', ['cours' => $cours, 'edit' => $edit, 'formations' => $formations, 'enseignants' => $enseignants]);
    }

    public function createCour(Request $request): RedirectResponse
    {
        $request->validate([
            'intitule' => 'required|string|max:50',
            'formation' => 'required|int',
            'enseignant' => 'required|int'
        ]);

        $cour = new Cour();
        $cour->intitule = $request->input('intitule');
        $cour->formation_id = $request->input('formation');
        $cour->user_id = $request->input('enseignant');
        $cour->save();

        return redirect()->route('admin.cours')->with('success', 'Le cours a bien été créé.');
    }

    public function modifyCourForm($id): RedirectResponse
    {
        request()->session()->put('edit', $id);

        return redirect()->route('admin.cours');
    }


    public function modifyCour(Request $request): RedirectResponse
    {
        $request->validate([
            'intitule' => 'required|string|max:50',
            'formation' => 'required|int',
            'enseignant' => 'required|int'
        ]);

        $cour = Cour::find($request->input('id'));
        $cour->intitule = $request->input('intitule');
        $cour->formation_id = $request->input('formation');
        $cour->user_id = $request->input('enseignant');
        $cour->save();

        return redirect()->route('admin.cours')->with('success', 'Le cours a bien été modifié.');
    }

    public function deleteCour($id): RedirectResponse
    {
        $cour = Cour::find($id);
        $cour->delete();

        return redirect()->route('admin.cours')->with('success', 'Le cours a bien été supprimé.');
    }

    //Gestion des plannings

    public function cour($id): View|Application|Factory
    {
        $cour = Cour::find($id);
        $plannings = Planning::where('cours_id', $id)->get();

        return view('admin.cour', ['cour' => $cour, 'plannings' => $plannings]);
    }

    public function planningCreate(Request $request): RedirectResponse
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

        return back()->with('success', 'Le planning a bien été créé.');
    }

    public function planningEditForm($id): View|Application|Factory
    {
        $planning = Planning::find($id);
        $cour = Cour::find($planning->cours_id);

        return view('admin.planning', ['planning' => $planning, 'cour' => $cour]);
    }

    public function planningEdit(Request $request): RedirectResponse
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

        return redirect()->route('admin.cour.planning', $cour->id)->with('success', 'Le planning a bien été modifié.');
    }

    public function planningDelete($id): RedirectResponse
    {
        $planning = Planning::find($id);
        $cour = Cour::find($planning->cours_id);
        $planning->delete();

        return redirect()->route('admin.cour.planning', $cour->id)->with('success', 'Le planning a bien été supprimé.');
    }


}
