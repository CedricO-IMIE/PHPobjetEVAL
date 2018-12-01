<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" >
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" media="screen" type="text/css" title="Design" href="design.css" />
    <title>Compte bancaire</title>
</head>

<div id="header">

</div>

<ul id="menu">
    <li><a href="index.php?action=add">Ajouter</a></li>
    <li><a href="index.php">Liste</a></li>
</ul>
<body>
<?php
$action = (isset($_GET['action']))?htmlspecialchars($_GET['action']):''; //On récupère la valeur de la variable $action

	try
	{
		// On se connecte à MySQL
		$bdd = new PDO('mysql:host=localhost;dbname=pdo;charset=utf8', 'root', '');
	}
	catch(Exception $e)
	{
		// En cas d'erreur, on affiche un message et on arrête tout
		die('Erreur : '.$e->getMessage());
	}



	switch($action)
{
case "enreg":


    // Si tout va bien, on peut continue

    /*$req = $bdd->prepare('INSERT INTO ajout(type, nom, prenom, telephone, mail, timestamp) VALUES(:typ, :nom, :pre, :tel, :mai, tim)');
    $req->execute(array(
    'typ' => $_POST['type'],
    'nom' => $_POST['nom'],
    'pre' => $_POST['prenom'],
    'tel' => $_POST['telephone'],
    'mai' => $_POST['mail'],
    'tim' => NOW(),
    ));*/
    $req = $bdd->prepare('INSERT INTO ajout (type, nom, prenom, telephone, mail, description, timestamp) VALUES(?, ?, ?, ?, ?, ?, ?)');
    $req->execute(array($_POST['type'], $_POST['nom'], $_POST['prenom'], $_POST['telephone'], $_POST['mail'], $_POST['description'], time()));

    echo '<div id="corps">Tâche ajouté <a href="index.php">Retour</a></div>';


    break;
case "val":
    $req = $bdd->prepare('update ajout SET etat =:eta WHERE id=:id');
    /*	UPDATE company SET NOM_COMPANY = :nom_company WHERE ID_COMPANY = :id_company"*/
    $req->execute(array('eta' => "1", 'id' => $_POST['valid']));

    echo '<div id="corps">Validé <a href="index.php">Retour</a></div>';


    break;
case "add"://Formulaire d'ajout
    ?>
    <div id="corps">
        <form id="formulaire" action="index.php?action=enreg" method="post">

            <table>
                <tr>
                    <td><label for="type">Type</label></td>
                    <td><SELECT name="type" size="1">
                            <OPTION>Rendez vous
                            <OPTION>Devis
                            <OPTION>Commande
                            <OPTION>autre
                        </SELECT>
                    </td>

                </tr>
                <tr>
                    <td><label for="nom">Nom</label></td>
                    <td><input type="text" name="nom"/></td>
                </tr>
                <tr>
                    <td><label for="prenom">prenom</label></td>
                    <td><input type="text" name="prenom"/></td>
                </tr>
                <tr>
                    <td><label for="telephone">téléphone</label></td>
                    <td><input type="text" name="telephone"/></td>
                </tr>
                <tr>
                    <td><label for="mail">mail</label></td>
                    <td><input type="text" name="mail"/></td>
                </tr>
                <tr>
                    <td><label for="description">description</label></td>
                    <td><textarea name="description" rows="5" cols="40"></textarea></td>
                </tr>

                <tr>
                    <td><input type="submit" value="Envoyer"/></td>
                </tr>
            </table>
        </form>
    </div>
    <?php

    break;
default:
?>
<div id="corps"><?php

    // On récupère tout le contenu de la table bureau
    $reponse = $bdd->query('SELECT * FROM ajout');
    // On affiche chaque entrée une à une
    //var_dump($reponse);
    if ($reponse != false) {

        while ($donnees = $reponse->fetch()) {
            switch ($donnees["etat"]) {
                case "1":


                    ?>
                    <div id="yes">
                        <strong>Nom</strong> : <?php echo $donnees['nom']; ?><br/>
                        <strong>Prénom</strong> : <?php echo $donnees['prenom']; ?><br/>
                        <strong>tel</strong> : <?php echo $donnees['telephone']; ?><br/>
                        <strong>Mail</strong> : <?php echo $donnees['mail']; ?><br/>
                        <strong>description</strong> : <?php echo $donnees['description']; ?><br/>
                        <?php echo 'Le ' . date('d/m/Y', $donnees['timestamp']) . ' &agrave; ' . date('H:i:s', $donnees['timestamp']); ?>


                    </div>
                    <?php
                    break;
                default:
                    ?>
                    <div id="no">
                        <strong>Nom</strong> : <?php echo $donnees['nom']; ?><br/>
                        <strong>Prénom</strong> : <?php echo $donnees['prenom']; ?><br/>
                        <strong>tel</strong> : <?php echo $donnees['telephone']; ?><br/>
                        <strong>Mail</strong> : <?php echo $donnees['mail']; ?><br/>
                        <strong>description</strong> : <?php echo $donnees['description']; ?><br/>
                        <?php echo 'Le ' . date('d/m/Y', $donnees['timestamp']) . ' &agrave; ' . date('H:i:s', $donnees['timestamp']); ?>

                        <form method="POST" action="index.php?action=val">
                            <input type="hidden" name="valid" value=<?php echo $donnees['id']; ?>>
                            <input class="myButton" type="submit" name="Submit" value="Validé">
                        </form>
                    </div>
                <?php
            }


        }
        $reponse->closeCursor(); // Termine le traitement de la requête
    }
    }
?>
</div>

</body>
</html>