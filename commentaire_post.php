<?php

// j'utilise la fonction de connexion a la bdd que j'ai partout
include_once "./lib/bdd.php";
$bdd = connexionBDD();

$req = $bdd->prepare('INSERT INTO commentaires(id_billet, auteur, commentaire, date_commentaire) VALUES(:id_billet, :auteur, :commentaire, NOW())');

// Vérifier qu'avant d'executer ta requete, j'ai bien un id_billet, un auteur, et un commentaire
$req->execute(array(
    'id_billet' => $_POST["billet"],
    'auteur' => $_POST['pseudo'],
    'commentaire' => $_POST['message']
));

// s'il existe un pseudo lors de la sauvegarde du commentaire je le sauve en cookie pour un an
if($_POST["pseudo"]) {
    setcookie('pseudo', $_POST["pseudo"], time() + 365*24*3600, null, null, false, true);
}

// je redirige sur le commentaire qui a l'id du billet en get
header("Location: commentaires.php?billet=".$_POST["billet"]);