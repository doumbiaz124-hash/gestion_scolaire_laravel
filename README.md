# Gestion Scolaire — Application Laravel

Application de gestion scolaire construite à partir du MCD fourni, avec gestion des permissions
par utilisateur, emploi du temps, et suivi des paiements avec calcul automatique du reste à payer.

## Stack technique
- **Framework** : Laravel 12 (PHP ^8.2)
- **Base de données** : MySQL, à administrer via **phpMyAdmin**
- **Frontend** : Blade + Bootstrap 5 (CDN)

Ce dossier contient un projet Laravel complet, **dépendances PHP déjà installées** (`vendor/` inclus).

## Installation

1. **Créer la base de données** dans phpMyAdmin (nom `gestion_scolaire`, ou adaptez `DB_DATABASE` dans `.env`).
2. **Vérifier `.env`** : `DB_USERNAME` / `DB_PASSWORD` selon votre installation locale.
3. **Migrer et peupler** :
   ```bash
   php artisan migrate --seed
   php artisan serve
   ```
   Compte admin par défaut : `admin@ecole.test` / `password` (accès à tous les modules).

## Nouveautés de cette version

### Tableau de bord
- Le total encaissé a été retiré.
- Deux listes triables sont affichées directement : Étudiants (par nom, classe, série) et
  Enseignants (par nom, classe, matière), avec impression de la liste des enseignants.

### Étudiants
- Tri par nom, prénom, classe ou série (clic sur l'en-tête de colonne).
- Filtres par classe / série / recherche.
- **Impression** de la liste des étudiants d'une classe, triée alphabétiquement.
- **Export CSV** téléchargeable (enregistrable sur le PC) de la liste d'une classe.

### Emploi du temps (remplace les Notes)
- Une grille hebdomadaire par classe (Lundi à Samedi).
- Ajout / modification / suppression de créneaux (jour, horaires, matière, enseignant).
- Le nom de l'enseignant s'affiche entre parenthèses sous la matière.
- Impression de l'emploi du temps.

### Paiements
- Les **frais scolaires sont définis sur la classe** (champ `frais_scolaire`), plus besoin de les
  ressaisir à chaque paiement.
- La classe de l'étudiant apparaît dans l'en-tête du formulaire de paiement, après la date.
- Le **reste à payer est calculé automatiquement** (frais de la classe − total déjà payé pour
  l'année scolaire en cours).
- Avant de créer un paiement, l'historique des paiements précédents de l'étudiant pour l'année
  s'affiche, et le montant est plafonné au reste dû.
- **Une fois les frais entièrement payés, aucun nouveau paiement n'est possible** pour cette année
  scolaire (déblocage automatique l'année suivante, car le solde est recalculé par année).
- Sélection de l'étudiant triable par classe / nom / prénom pour accélérer la saisie.
- Liste de tous les paiements triable par nom, prénom, classe, date ou année, avec filtres.
- Historique des paiements d'un étudiant triable par date ou année.
- **Reçu de paiement imprimable**, avec le nom et le rôle de l'utilisateur qui a encaissé (pour
  signature).
- Modifier ou supprimer un paiement recalcule automatiquement le reste à payer de tous les
  paiements suivants de l'étudiant pour cette année.

### Utilisateurs et permissions
- L'**administrateur** a accès à tous les tableaux de bord automatiquement.
- Pour tout autre rôle, l'administrateur doit **cocher les tableaux de bord** (modules) auxquels
  l'utilisateur aura accès lors de sa création ou modification (Étudiants, Enseignants, Classes,
  Séries, Emploi du temps, Paiements).
- La gestion des Rôles et des Utilisateurs reste réservée à l'administrateur.
- Le menu latéral s'adapte automatiquement aux permissions de l'utilisateur connecté.

### Sécurité
- Après une déconnexion, le bouton **"Retour" du navigateur ne permet plus de réafficher une page
  protégée** : les pages authentifiées sont envoyées avec des en-têtes `Cache-Control: no-store`,
  et toute tentative d'accès après déconnexion redirige vers la page de connexion (email + mot de
  passe obligatoires).

## Nouveautés de cette version (Bulletins + Promotion)

### Bulletins
- **Configuration préalable obligatoire** : pour chaque classe, on configure d'abord les matières
  (rubriques) et leur coefficient (menu "Matières"), avant toute saisie de notes.
- **Saisie des notes** : on choisit l'étudiant, puis **la matière** (demandée explicitement), le
  trimestre, et les notes de devoir et de composition (une paire par trimestre et par matière).
- **Calcul automatique** : moyenne par matière ((devoir + composition) / 2), total pondéré par le
  coefficient, moyenne générale du trimestre.
- Le bulletin du **3e trimestre affiche en plus la moyenne générale annuelle** (moyenne des 3
  trimestres).
- Chaque bulletin comporte une ligne de **signature du Directeur** en bas.
- **Impression** : bulletin d'un seul étudiant, ou impression groupée de tous les bulletins d'une
  classe pour un trimestre donné. Recherche/filtre par nom, prénom et classe pour retrouver
  rapidement un étudiant.

### Passage en classe supérieure
- Un aperçu calcule automatiquement, pour chaque étudiant, sa moyenne annuelle et la compare à la
  moyenne de passage de sa classe : **5,00 pour le 1er cycle**, **10,00 pour le 2nd cycle et le
  lycée**. Les classes de type **Jardin passent automatiquement, sans bulletin ni moyenne**.
- Après validation, chaque étudiant admis est déplacé vers la **classe supérieure** configurée sur
  sa classe actuelle (champ "Classe supérieure"), et son **année scolaire est automatiquement
  incrémentée** (ex. 2026-2027 → 2027-2028). Un étudiant qui redouble reste dans la même classe
  mais passe tout de même à l'année suivante.
- Réservé à l'administrateur (menu "Passage classe sup.").

### Paiements — améliorations
- **Consultation des soldes** : recherche un étudiant par nom/prénom/classe pour voir son reste à
  payer, même s'il n'est pas venu payer.
- Avant un nouveau paiement, le système **signale un éventuel reste impayé de l'année scolaire
  précédente** (information seulement — le paiement de l'année en cours reste possible).

### Étudiants — liste imprimée
- L'en-tête de la liste imprimée par classe affiche désormais **Nom, Prénom, Sexe, Classe, Date de
  naissance** (téléphone du tuteur et série retirés).

### Enseignants
- Le formulaire d'ajout/modification d'un enseignant n'affiche plus de champ Classe (un enseignant
  peut intervenir dans plusieurs classes via l'emploi du temps).
- La liste imprimée n'affiche plus de colonne Classe.

## Nouvelles tables

| Table       | Champs principaux |
|-------------|--------------------|
| `matieres`  | classe_id, nom_matiere, coefficient |
| `notes`     | etudiant_id, matiere_id, trimestre (1-3), note_devoir, note_composition, annee_scolaire |

`classes` a également reçu deux nouveaux champs : `cycle` (jardin / premier_cycle / second_cycle /
lycee) et `classe_suivante_id` (classe de destination lors du passage).



| Table              | Champs principaux |
|--------------------|--------------------|
| `roles`            | nom_role |
| `utilisateurs`      | email, password, role_id, **permissions** (JSON des modules autorisés) |
| `classes`           | nom_classe, effectif_classe, **frais_scolaire** |
| `series`            | nom_serie, classe_id |
| `etudiants`         | nom, prénom, date_naissance, tel_tuteur, adresse, annee_scolaire, sexe, classe_id, serie_id |
| `enseignants`       | nom, prénom, tel, adresse, matiere_enseignee, classe_id |
| `emplois_du_temps`  | classe_id, jour, heure_debut, heure_fin, matiere, enseignant_id |
| `paiements`         | date, heure, montant_paye, reste_paiement (calculé), **annee_scolaire**, etudiant_id, **utilisateur_id** |

## Vérifications effectuées
Toutes les fonctionnalités ci-dessus (tri, impression, export CSV, calcul du reste à payer,
blocage du paiement une fois le solde atteint, recalcul après modification/suppression d'un
paiement, permissions par module, en-têtes anti-cache après déconnexion) ont été testées de bout
en bout avec une base SQLite temporaire avant livraison.

### Bulletins (nouveau)
- **Configuration préalable obligatoire** : pour chaque classe, définissez d'abord les matières et
  leur coefficient (menu "Matières"), avant de pouvoir saisir des notes.
- **Saisie des notes** : on choisit d'abord l'étudiant, puis la matière (demandée par le système),
  puis le trimestre (1, 2 ou 3) ; chaque trimestre comporte une note de devoir et une note de
  composition.
- **Calcul automatique** : moyenne par matière ((devoir + composition) / 2), pondération par
  coefficient, total et moyenne générale du trimestre. Sur le bulletin du 3e trimestre, la
  **moyenne générale annuelle** (moyenne des 3 trimestres) est calculée et affichée.
- Le bulletin comporte une ligne de signature **"Le Directeur"** en bas.
- **Impression** : par étudiant (avec filtre nom/prénom/classe) ou par classe entière (tous les
  bulletins à la suite, un par page).
- Un étudiant conserve son bulletin correct même après un passage en classe supérieure : chaque
  note garde en mémoire la classe et les matières de l'époque où elle a été saisie.

### Passage en classe supérieure (nouveau, réservé à l'administrateur)
- Pour chaque classe, on définit un **cycle** (jardin, 1er cycle, 2nd cycle, lycée) et une
  **classe supérieure** de destination.
- Moyennes de passage : 1er cycle 5,00 — 2nd cycle 10,00 — lycée 10,00. En dessous, l'étudiant
  redouble (reste dans la même classe).
- Les classes de **jardin passent automatiquement**, sans bulletin ni moyenne à vérifier.
- Un **aperçu** liste, classe par classe, chaque étudiant avec sa moyenne annuelle et la décision
  (admis/redouble) avant toute validation.
- En validant, le système met à jour la classe de chaque étudiant et **change automatiquement
  l'année scolaire** (ex. "2026-2027" devient "2027-2028") pour tous, qu'ils soient admis ou
  redoublants.

### Paiements — nouveautés
- **Consultation des soldes** : on peut chercher un étudiant (nom, prénom, classe) pour connaître
  son reste à payer, même s'il n'est jamais venu payer.
- **Alerte sur l'année précédente** : à l'ouverture du formulaire de paiement, si l'étudiant a un
  reste impayé de l'année scolaire précédente, une alerte s'affiche — mais le paiement de l'année
  en cours reste possible.

### Étudiants / Enseignants — ajustements
- Liste imprimée des étudiants d'une classe : colonnes "Téléphone du tuteur" et "Série" retirées,
  colonne "Classe" ajoutée (export CSV mis à jour de la même façon).
- Formulaire d'ajout d'un enseignant : le champ "Classe" a été retiré (un enseignant peut intervenir
  dans plusieurs classes via l'emploi du temps). La colonne "Classe" a aussi été retirée de la liste
  imprimée des enseignants.

## Prochaines étapes possibles
- Export PDF des reçus et bulletins (actuellement impression navigateur)
- Notifications de rappel de paiement
- Historique des modifications (audit trail)

## Mise à jour : Bulletins, promotion de classe et ajustements

### Bulletins (remplace/complète l'emploi du temps dans le menu)
- **Configuration préalable obligatoire** : pour chaque classe, dans *Matières*, définissez les
  matières (rubriques) et leur coefficient avant de saisir des notes.
- **Saisie des notes** : choisissez l'étudiant, puis pour chaque note à saisir, le système demande
  de choisir la matière, le trimestre (1, 2 ou 3), la note de devoir et la note de composition.
- **Calcul automatique** : moyenne par matière = (devoir + composition) / 2 ; total et moyenne
  générale du trimestre pondérés par les coefficients ; au 3e trimestre, la **moyenne générale
  annuelle** (moyenne des 3 trimestres) s'affiche en plus sur le bulletin.
- Une ligne de signature **"Le Directeur"** est imprimée en bas de chaque bulletin.
- **Impression** : par étudiant (recherche par nom/prénom/classe) ou par classe entière (tous les
  bulletins des étudiants de la classe, un par page).
- Les notes conservent la classe de l'étudiant au moment de la saisie : un bulletin passé reste
  exact même après un passage en classe supérieure.

### Passage en classe supérieure (fin d'année, réservé à l'administrateur)
- Chaque classe a un **cycle** (Jardin, 1er cycle, 2nd cycle, Lycée) et une **classe supérieure**
  de destination configurables dans *Classes*.
- Moyennes de passage : 1er cycle = 5,00 ; 2nd cycle et Lycée = 10,00 ; **Jardin = passage direct**
  sans bulletin ni moyenne.
- Un aperçu (classe par classe, étudiant par étudiant, avec la moyenne et la décision Admis/Redouble)
  est affiché avant toute validation.
- En cas de validation : les étudiants admis sont déplacés vers la classe supérieure, les redoublants
  restent dans leur classe, et **l'année scolaire de tous les étudiants avance automatiquement**
  (ex : "2026-2027" devient "2027-2028").

### Paiements
- **Consultation des soldes** : une page dédiée permet de filtrer un étudiant par nom, prénom ou
  classe pour connaître son reste à payer, même sans faire de paiement.
- Avant un nouveau paiement, le système vérifie s'il reste un solde impayé de **l'année scolaire
  précédente** et l'affiche en information — le paiement de l'année en cours reste possible.

### Étudiants — liste imprimée
- L'en-tête de la liste imprimée par classe n'affiche plus le téléphone du tuteur ni la série ;
  la **classe** a été ajoutée à la place.

### Enseignants
- Le formulaire d'ajout/modification d'un enseignant n'a plus de champ Classe (un enseignant peut
  intervenir dans plusieurs classes via l'emploi du temps).
- La liste imprimée des enseignants n'affiche plus la colonne Classe.

### Permissions
- Un nouveau module **"Bulletins"** (matières, notes, bulletins) a été ajouté à la liste des
  tableaux de bord attribuables à un utilisateur non-administrateur.
- Le passage en classe supérieure reste réservé à l'administrateur.
