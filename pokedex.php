<?php

$jsonString = file_get_contents('pokemon.json');


$data = json_decode($jsonString, false);


if (json_last_error() !== JSON_ERROR_NONE) {
    die('Erreur JSON : ' . json_last_error_msg());
}


$types = [
    'Normal'   => 'normal',
    'Feu'      => 'feu',
    'Eau'      => 'eau',
    'Plante'   => 'plante',
    'Électrik' => 'electrik',
    'Glace'    => 'glace',
    'Combat'   => 'combat',
    'Poison'   => 'poison',
    'Sol'      => 'sol',
    'Vol'      => 'vol',
    'Psy'      => 'psy',
    'Insecte'  => 'insecte',
    'Roche'    => 'roche',
    'Spectre'  => 'spectre',
    'Dragon'   => 'dragon',
    'Ténèbres' => 'tenebres',
    'Acier'    => 'acier',
    'Fée'      => 'fee',
];


$nomsStats = [
    'hp'      => 'PV',
    'atk'     => 'Attaque',
    'def'     => 'Défense',
    'spe_atk' => 'Attaque Spéciale',
    'spe_def' => 'Défense Spéciale',
    'vit'     => 'Vitesse',
];

function e($texte) {
    return htmlspecialchars((string) $texte, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Pokédex</title>
</head>
<body>
    <header>
        <h1>Pokédex</h1>
        <p><?= count($data) - 1 ?> Pokémon</p>
        <input type="search" id="recherche" placeholder="Rechercher un Pokémon (nom ou numéro)…">
    </header>

    <nav>
    <?php foreach ($data as $pokemon): ?>
        <?php
        // Missing no est un bug de poké donc je le saute
        if ($pokemon->pokedex_id == 0) continue;


        $typePrincipal = $pokemon->types[0]->name ?? 'Normal';
        $classeType = $types[$typePrincipal] ?? 'normal';
        ?>
        <div class="carte <?= $classeType ?>"
             data-recherche="<?= e($pokemon->name->fr . ' ' . $pokemon->name->en . ' ' . $pokemon->pokedex_id) ?>">

            <span class="numero">#<?= str_pad($pokemon->pokedex_id, 3, '0', STR_PAD_LEFT) ?></span>

            <img src="<?= e($pokemon->sprites->regular) ?>" alt="<?= e($pokemon->name->fr) ?>" loading="lazy">

            <h2><?= e($pokemon->name->fr) ?></h2>

            <p>
            <?php if ($pokemon->stats): ?>
                <?php foreach ($pokemon->stats as $cle => $valeur): ?>
                    <?= $nomsStats[$cle] ?? e($cle) ?> : <?= e($valeur) ?><br>
                <?php endforeach; ?>
            <?php else: ?>
                Statistiques inconnues
            <?php endif; ?>
            </p>

            <div class="types">
                <?php foreach ($pokemon->types ?? [] as $type): ?>
                    <span class="<?= $types[$type->name] ?? 'normal' ?>"><?= e($type->name) ?></span>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
    </nav>

    <script>

        document.getElementById('recherche').addEventListener('input', function () {
            const texte = this.value.toLowerCase().trim();
            document.querySelectorAll('.carte').forEach(function (carte) {
                carte.hidden = !carte.dataset.recherche.toLowerCase().includes(texte);
            });
        });
    </script>
</body>
</html>
