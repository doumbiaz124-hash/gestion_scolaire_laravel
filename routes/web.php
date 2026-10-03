<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BulletinController;
use App\Http\Controllers\ClasseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmploiDuTempsController;
use App\Http\Controllers\EnseignantController;
use App\Http\Controllers\EtudiantController;
use App\Http\Controllers\MatiereController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SerieController;
use App\Http\Controllers\UtilisateurController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rôles et utilisateurs : réservés à l'administrateur.
    Route::middleware('admin')->group(function () {
        Route::resource('roles', RoleController::class)->except(['show']);
        Route::resource('utilisateurs', UtilisateurController::class)->except(['show']);
    });

    // Classes
    Route::middleware('module:classes')->group(function () {
        Route::resource('classes', ClasseController::class)->parameters(['classes' => 'classe']);
    });

    // Séries
    Route::middleware('module:series')->group(function () {
        Route::resource('series', SerieController::class)->except(['show'])->parameters(['series' => 'serie']);
    });

    // Étudiants
    Route::middleware('module:etudiants')->group(function () {
        Route::get('etudiants/classe/{classe}/liste', [EtudiantController::class, 'listeParClasse'])->name('etudiants.liste-classe');
        Route::get('etudiants/classe/{classe}/export', [EtudiantController::class, 'exportParClasse'])->name('etudiants.export-classe');
        Route::resource('etudiants', EtudiantController::class);
    });

    // Enseignants
    Route::middleware('module:enseignants')->group(function () {
        Route::get('enseignants/imprimer', [EnseignantController::class, 'imprimer'])->name('enseignants.imprimer');
        Route::resource('enseignants', EnseignantController::class)->except(['show']);
    });

    // Emploi du temps
    Route::middleware('module:emploi_du_temps')->group(function () {
        Route::get('emploi-du-temps', [EmploiDuTempsController::class, 'index'])->name('emploi-du-temps.index');
        Route::get('emploi-du-temps/{classe}', [EmploiDuTempsController::class, 'show'])->name('emploi-du-temps.show');
        Route::get('emploi-du-temps/{classe}/imprimer', [EmploiDuTempsController::class, 'imprimer'])->name('emploi-du-temps.imprimer');
        Route::get('emploi-du-temps/{classe}/creneaux/creer', [EmploiDuTempsController::class, 'create'])->name('emploi-du-temps.create');
        Route::post('emploi-du-temps/{classe}/creneaux', [EmploiDuTempsController::class, 'store'])->name('emploi-du-temps.store');
        Route::get('emploi-du-temps/{classe}/creneaux/{creneau}/edit', [EmploiDuTempsController::class, 'edit'])->name('emploi-du-temps.edit');
        Route::put('emploi-du-temps/{classe}/creneaux/{creneau}', [EmploiDuTempsController::class, 'update'])->name('emploi-du-temps.update');
        Route::delete('emploi-du-temps/{classe}/creneaux/{creneau}', [EmploiDuTempsController::class, 'destroy'])->name('emploi-du-temps.destroy');
    });

    // Bulletins : configuration des matières/coefficients, saisie des notes, consultation/impression
    Route::middleware('module:bulletins')->group(function () {
        // Configuration des matières (rubriques, coefficients) par classe
        Route::get('matieres', [MatiereController::class, 'index'])->name('matieres.index');
        Route::get('matieres/{classe}', [MatiereController::class, 'show'])->name('matieres.show');
        Route::post('matieres/{classe}', [MatiereController::class, 'store'])->name('matieres.store');
        Route::get('matieres/{classe}/{matiere}/edit', [MatiereController::class, 'edit'])->name('matieres.edit');
        Route::put('matieres/{classe}/{matiere}', [MatiereController::class, 'update'])->name('matieres.update');
        Route::delete('matieres/{classe}/{matiere}', [MatiereController::class, 'destroy'])->name('matieres.destroy');

        // Saisie des notes
        Route::get('notes/etudiants', [NoteController::class, 'choisirEtudiant'])->name('notes.choisir-etudiant');
        Route::get('notes/etudiant/{etudiant}', [NoteController::class, 'saisie'])->name('notes.saisie');
        Route::post('notes', [NoteController::class, 'store'])->name('notes.store');
        Route::delete('notes/{note}', [NoteController::class, 'destroy'])->name('notes.destroy');

        // Bulletins (consultation / impression)
        Route::get('bulletins/etudiants', [BulletinController::class, 'choisirEtudiant'])->name('bulletins.choisir-etudiant');
        Route::get('bulletins/etudiant/{etudiant}', [BulletinController::class, 'etudiant'])->name('bulletins.etudiant');
        Route::get('bulletins/classe/{classe}', [BulletinController::class, 'classe'])->name('bulletins.classe');
    });

    // Paiements
    Route::middleware('module:paiements')->group(function () {
        Route::get('paiements/soldes', [PaiementController::class, 'soldes'])->name('paiements.soldes');
        Route::get('paiements/etudiants', [PaiementController::class, 'choisirEtudiant'])->name('paiements.choisir-etudiant');
        Route::get('paiements/nouveau/{etudiant}', [PaiementController::class, 'create'])->name('paiements.create');
        Route::get('paiements/{paiement}/recu', [PaiementController::class, 'recu'])->name('paiements.recu');
        Route::get('etudiants/{etudiant}/paiements', [PaiementController::class, 'historique'])->name('paiements.historique');
        Route::resource('paiements', PaiementController::class)->except(['show', 'create']);
    });

    // Passage en classe supérieure (fin d'année scolaire) : réservé à l'administrateur.
    Route::middleware('admin')->group(function () {
        Route::get('promotion', [PromotionController::class, 'index'])->name('promotion.index');
        Route::post('promotion', [PromotionController::class, 'executer'])->name('promotion.executer');
    });
});
