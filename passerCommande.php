<?php
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=ppe;charset=utf8', 'root', 'root'); //connection à la bdd

    $req = 'SELECT * FROM typeconditionnement';
    $typeconditionnement = $bdd->prepare($req);
    $typeconditionnement->execute();
    $donneesTypeConditionnement = $typeconditionnement->fetchAll(PDO::FETCH_ASSOC);

    if (isset($_POST['bouton'])) {
        $quantiteTotal = 0;
        $quantite =0;
        $i = 0;
        $espace = "";

        //On créé le tableau de valeurs
        foreach ($donneesTypeConditionnement AS $donneeTypeConditionnement)
        {
        $val[] = $donneeTypeConditionnement['val'];
        $libelle[] = $donneeTypeConditionnement['libelle'];
        }

        //calcule de la valeur total
        $condi = "";
        foreach($_POST['quantite'] as $quantite)
        {

            $quantiteTotal = $quantiteTotal + $quantite * $val[$i] ;
            

            if ($quantite > 0) 
            {
                $condi = $condi.$espace.$quantite.$libelle[$i];
                $espace = ", ";
            }

        $i = $i + 1;
        }

        //Insertion des données dans la bdd
        $id_variete = htmlspecialchars(trim($_POST['id_variete']));
        $id_typeProduit = htmlspecialchars(trim($_POST['id_typeProduit']));
        $calibre = htmlspecialchars(trim($_POST['calibre']));
        $id_variete = htmlspecialchars(trim($_POST['id_variete']));
        $dateEnvoi = date("d-m-Y H:i:s");

            $req = 'INSERT INTO commande(dateEnvoi, id_connexion, conditionnement, quantite) VALUES(:dateEnvoi, :id_connexion, :conditionnement, :quantite)';
        
            $exec = $bdd->prepare($req);

            $exec->bindValue(':dateEnvoi',$dateEnvoi, PDO::PARAM_STR);
            $exec->bindValue(':id_connexion',$_SESSION['id_connexion'], PDO::PARAM_STR);
            $exec->bindValue(':conditionnement',$condi, PDO::PARAM_STR);
            $exec->bindValue(':quantite',$quantiteTotal, PDO::PARAM_STR);
        
            $exec -> execute();

    }
 ?>

<!DOCTYPE html>
<html>
    <head>
    	<!-- En-tête de la page -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="index2.css" />
        <link href="/www/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
    	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
    	<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <title>Accueil - AGRUR</title>

    </head>

    <body class="sierra">
        <div class="banniere">
        <center><br><img src="images/AgrurLogoFondTransparent.png" width="150px" height="100px"></center>
        <br></div>
         <?php include('inc/menuAccueil.php'); 
		 ?>
		 
		 
		     <form action="#" method="POST" class="form-horizontal">
			  <center><font size=5><?php echo "Passer une Commande" ?></font></a></center> <br>


            <table>
                <tr>
                    <th>Type de conditionnement</th>
                    <th>Quantité</th>
                </tr>

                <?php
                    foreach ($donneesTypeConditionnement AS $donneeTypeConditionnement)
                    {
                        
                ?>
                <tr>
                    <td>
                            <?php echo $donneeTypeConditionnement['libelle']; ?>
                    </td>

                    <td>
                      <input type="text" class="form-control" name="quantite[]" id="text" placeholder="0">
                    </td>
                </tr>
        
                <?php
                    }

                ?>

            </table>
       
        <div class="form-group"> 
            <div class="col-sm-offset-4 col-sm-4">
                <center> <button type="submit" name="bouton" class="btn btn-default"><b>Valider</b></button>  </center>

                <br><br>   
		 	</center>
		  </form>
		  
    </body>


    <script src="/www/bootstrap/js/jquery.js"></script>
    <script src="/www/bootstrap/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="/www/js/bootstrap.min.js"></script>
</html>

<?php 
if (isset($quantiteTotal)) {
    echo "Vous avez commandé un total de ".$quantiteTotal."kg de noix.<br/>";
    echo "Voici les informations de votre commandes: ".$condi;
}
?>