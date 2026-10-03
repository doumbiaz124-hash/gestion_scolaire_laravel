<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Utilisateur extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'utilisateurs';

    protected $fillable = [
        'email',
        'password',
        'role_id',
        'permissions',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'permissions' => 'array',
    ];

    /**
     * Modules (tableaux de bord) disponibles dans l'application.
     * Clé => libellé affiché.
     */
    public const MODULES = [
        'etudiants' => 'Étudiants',
        'enseignants' => 'Enseignants',
        'classes' => 'Classes',
        'series' => 'Séries',
        'emploi_du_temps' => 'Emploi du temps',
        'bulletins' => 'Bulletins (matières, notes, bulletins)',
        'paiements' => 'Paiements',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function isAdmin(): bool
    {
        return $this->role && strtolower($this->role->nom_role) === 'admin';
    }

    /**
     * Vérifie si l'utilisateur a accès à un module/tableau de bord donné.
     * Un administrateur a accès à tout.
     */
    public function hasModule(string $module): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        return in_array($module, $this->permissions ?? [], true);
    }
}
