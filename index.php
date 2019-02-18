<?php

include_once "./lib/bdd.php"; //  inclure et évaluer le fichier spécifié une seule fois

$bdd = connexionBDD();

// on défini le nombre de message par page
$messageParPage = 2;
// je créer une requete qui va calculer le nombre total de billet en temps reel
$total = $bdd->query('SELECT count(*) as total FROM billets')->fetch();
// je sauve le nombre de billet total en integer
$total = intval($total["total"]);
// je calcul le nombre de page en faisant un arrondi a l'entier superieur
$nbPage = ceil ($total / $messageParPage);

// je définis la page actuelle en fonction de la variable get["page"]
if(isset($_GET['page'])) // Si la variable $_GET['page'] existe...
{
    $pageActuelle=intval($_GET['page']);

    if($pageActuelle>$nbPage) // Si la valeur de $pageActuelle (le numéro de la page) est plus grande que $nombreDePages...
    {
        $pageActuelle=$nbPage;
    }
}
else // Sinon
{
    $pageActuelle=1; // La page actuelle est la n°1
}
// je calcul mes limit en SQL pour ma requete avec le billet d'entrée
$premiereEntree=($pageActuelle-1)*$messageParPage;
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Mon blog</title>
    <link href="style.css" rel="stylesheet" />
</head>

<body>
<h1>Mon super blog !</h1>
<p>Derniers billets du blog :</p>

<?php

$req = $bdd->query('SELECT id, titre, contenu, DATE_FORMAT(date_creation, \'%d/%m/%Y à %Hh%imin%ss\') AS date_creation_fr FROM billets ORDER BY date_creation DESC LIMIT '. $premiereEntree .', '. $messageParPage);
while ($donnees = $req->fetch())
{
?>
<div class="news">
    <h3>
        <?php echo htmlspecialchars($donnees['titre']); ?>
        <em>le <?php echo $donnees['date_creation_fr']; ?></em>
    </h3>
    
    <p>
    <?php
    // On affiche le contenu du billet
    echo nl2br(htmlspecialchars($donnees['contenu']));
    ?>
    <br />
    <em><a href="commentaires.php?billet=<?php echo $donnees['id']; ?>">Commentaires</a></em>
    </p>
</div>
<?php
} // Fin de la boucle des billets

$req->closeCursor();
?>
        <p><?php if($pageActuelle != 1) { ?><a href="?page=<?php echo $pageActuelle - 1 ?>">Précendent</a> - <?php } ?>
            Page <?php echo $pageActuelle ?> / <?php echo $nbPage ?>

            <?php if($pageActuelle != $nbPage) { ?>- <a href="?page=<?php echo $pageActuelle + 1 ?>">Suivant</a><?php } ?>
        </p>
</body>
</html>