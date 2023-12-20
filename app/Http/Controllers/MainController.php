<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MainController extends Controller
{
    public function index(): RedirectResponse
    {
        if (Auth::user()->isntLocked()) {
            if (Auth::user()->isAdmin()) {
                return redirect()->route('admin');
            } else if (Auth::user()->isProf()) {
                return redirect()->route('prof');
            } else {
                return redirect()->route('user');
            }
        } else {
            return redirect()->route('locked');
        }
    }

    public function formLogin(): View|Application|Factory
    {
        return view('login');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'login' => 'required|string|max:30',
            'mdp' => 'required|string|max:60'
        ]);

        $credentials = [
            'login' => $request->input('login'),
            'password' => $request->input('mdp')
        ];


        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return $this->index();
        }

        return back()->withErrors([
            'login' => 'Le login ou le mot de passe est incorrect.',
        ]);
    }

    public function formRegister(): View|Application|Factory
    {
        $formations = Formation::all();

        return view('register', ['formations' => $formations]);
    }

    public function register(Request $request): RedirectResponse
    {
        $request->validate([
            'nom' => array(
                'required',
                'regex:/^[A-Za-zÀ-ÖØ-ö\s]+$/',
                'max:40'),
            'prenom' => array(
                'required',
                'regex:/^[A-Za-zÀ-ÖØ-ö\s]+$/',
                'max:40'),
            'login' => 'required|string|max:30|unique:users',
            'mdp' => 'required|string|max:60|confirmed'
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

        if ($request->formation != 0) {
            $formation = Formation::find($request->formation);
            $user->formation_id = $formation->id;
        }

        $user->nom = $request->nom;
        $user->prenom = $request->prenom;
        $user->login = $request->login;
        $user->mdp = Hash::make($request->mdp);
        $user->type = null;
        $user->save();

        Auth::login($user);

        return $this->index();
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function locked(): View|Application|Factory
    {
        return view('locked');
    }

    public function profile(): View|Application|Factory
    {
        $user = Auth::user();

        return view('profile', ['user' => $user]);
    }

    public function editProfile(Request $request): RedirectResponse
    {
        $request->validate([
            'nom' => array(
                'required',
                'regex:/^[A-Za-zÀ-ÖØ-ö\s]+$/',
                'max:40'),
            'prenom' => array(
                'required',
                'regex:/^[A-Za-zÀ-ÖØ-ö\s]+$/',
                'max:40'),
        ]);

        $user = Auth::user();
        $user->nom = $request->nom;
        $user->prenom = $request->prenom;
        $user->save();

        return redirect()->route('profile')->with('success', 'Votre profile a bien été modifié');
    }

    public function editPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'mdp' => 'required|string|max:60|confirmed'
        ]);

        $user = Auth::user();
        $user->mdp = Hash::make($request->mdp);
        $user->save();

        return redirect()->route('profile')->with('success', 'Votre mot de passe a bien été modifié');
    }

}
