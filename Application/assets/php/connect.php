<?php
//include "locks.php";

// Constantes
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "wifi_locks";

// Variables
$update = false;
$id     = 0;





// Création et vérification de la connexion
$database = new mysqli($servername, $username, $password, $dbname) or die("Connection échouée:\n". $database->connect_error);
session_start();
$adminID    = getAdminID();
//require_once "locks.php";





// Récupération des données (SELECT)
function maketable($table, $filename) {
	global $dbname;
	global $database;
	
	// Recherche des colonnes de la table
	
	if ($table == "privileges" || $table == "appartenancegrp") {
		$sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '$dbname' AND TABLE_NAME = 'details".$table."'";
	} else {
		$sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '$dbname' AND TABLE_NAME = '$table'";
	}
	
	$query = $database->query($sql) or die("Requête échouée:\n$sql\n".$database->connect_error);
	
	while($row = $query->fetch_assoc()){
		$rows[] = $row;
	}
	$columns = array_column($rows, "COLUMN_NAME");
	
	// Construction de la table
	$sql = "SELECT * FROM $table";
	if ($table == "historique") {
		$sql = $sql." ORDER BY Date DESC";
	} else if ($table == "privileges" || $table == "appartenancegrp") {
		$sql = "SELECT * FROM details".$table;
	}
	
	if (isset($_POST["Rechercher"])) {
		$col = trim($_POST["RechercheCol"]);
		$val = trim($_POST["RechercheVal"]);
		$sql = $sql." WHERE $col LIKE '%$val%'";
	}
	
	$rows = $database->query($sql); // Objet contenant: current_field, field_count, lengths, num_rows, type
	
	echo "
	<table class='table'>";
		// Header de la table
		echo "
		<thead>
			<tr>";
			foreach ($columns as $column) {
				echo "
				<th>".sql2header($column)."</th>";
			}
			if ($table != "historique" && $table != "manipulation") { // Ne pas ajouter des actions à l'historique
				echo "
				<th colspan='2'>Action</th>";
			}
			echo "
			</tr>
		</thead>";
		
		// Lignes de la table
		while ($row = $rows->fetch_assoc()) { // Itération sur des Arrays contenant les valeurs d'une ligne
			echo "<tr>";
			$rowCount = 0; // Nombre de Lignes
			$rowKey0 = ""; // Valeur à la colonne 1
			$rowKey1 = ""; // Valeur à la colonne 2
			$rowKey2 = ""; // Valeur à la colonne 3
			foreach ($row as $val) {		
				if ($rowCount == 0) $rowKey0 = $val;
				if ($rowCount == 1) $rowKey1 = $val;
				if ($rowCount == 2) $rowKey2 = $val;
				
				if ($table == "portes" && $rowCount >= 4) { // Portes: EtatPorte, EtatSerrure
					$val = bin2txt($val, "state");
				} else if (($table == "historique" || $table == "manipulation") && $rowCount == 2) { // Historique: Action
					$val = bin2txt($val, "action");
				} else if ($table == "commandes") {
					if ($rowCount == 3) { // Commandes: Action
						$val = bin2txt($val, "action");
					} else if ($rowCount == 6) { // Commandes: Repetition
						$val = rep2txt($val);
					}
				}
				
				echo "<td>".$val."</td>";
				
				$rowCount++;
			}
			//Création des boutons
			if ($table != "historique" && $table != "manipulation") {
				$style = "'font-family: Courier; color: #FFF'";
				$btninfo = "'btn btn-info'";
				$btndngr = "'btn btn-danger'";
				$btnsucc = "'btn btn-success'";
				$btnwarn = "'btn btn-warning'";
				
				if ($table == "portes") { // Portes
					$etat     = getEtatSerrure($rowKey0);
					$action   = ($etat == 0 ? "Ouvrir" : "Fermer");
					$btnmanip = ($etat == 0 ? $btnsucc : $btnwarn);
					echo "<td>
						<a href='assets/php/locks.php?manip=$rowKey0 $etat'      class=$btnmanip  style=$style>$action</a>
						<a href='$filename?edit_$table=$rowKey0'                 class=$btninfo   style=$style>Modif.</a>
						<a href='assets/php/connect.php?delete_$table=$rowKey0'  class=$btndngr   style=$style>Suppr.</a>
					</td>";
				} else if ($table == "groupes") { // Groupes
					echo "<td>
						<a href='assets/php/locks.php?bulk=$rowKey0 0'           class=$btnsucc   style=$style>Ouvrir</a>
						<a href='assets/php/locks.php?bulk=$rowKey0 1'           class=$btnwarn   style=$style>Fermer</a>
						<a href='$filename?edit_$table=$rowKey0'                 class=$btninfo   style=$style>Modif.</a>
						<a href='assets/php/connect.php?delete_$table=$rowKey0'  class=$btndngr   style=$style>Suppr.</a>
					</td>";
				} else if ($table == "privileges") { // Privileges
					echo "<td>
						<a href='assets/php/connect.php?delete_$table=$rowKey0-$rowKey2'  class=$btndngr  style=$style>Supprimer</a>
					</td>";
				} else if ($table == "appartenancegrp") { // AppartenanceGrp
					echo "<td>
						<a href='assets/php/connect.php?delete_$table=$rowKey0-$rowKey1'  class=$btndngr  style=$style>Supprimer</a>
					</td>";
				} else { // Utilisateurs, Batiments
					echo "<td>
						<a href='$filename?edit_$table=$rowKey0'                 class=$btninfo   style=$style>Modif.</a>
						<a href='assets/php/connect.php?delete_$table=$rowKey0'  class=$btndngr   style=$style>Suppr.</a>
					</td>";
				}
			}
			echo "</tr>";
		}
		
	echo "
	</table>";
}





// Création de données (INSERT)
if (isset($_POST["CreerPorte"])) { // Création d'une Porte
	$CodePorte   = trim($_POST["CodePorte"]);
	$Nature      = trim($_POST["Nature"]);
	$NumBat      = trim($_POST["NumBat"]);
	$Etage       = trim($_POST["Etage"]);
	$EtatPorte   = 0;
	$EtatSerrure = 0;
	
	$sql = "INSERT INTO portes (CodePorte, Nature, NumBat, Etage, EtatPorte, EtatSerrure) VALUES ("
		."'". $CodePorte   ."', "
		."'". $Nature      ."', "
		."'". $NumBat      ."', "
		."'". $Etage       ."', "
		."'". $EtatPorte   ."', "
		."'". $EtatSerrure ."')";
	
	$database->query($sql) or die("Requête échouée:\n$sql\n".$database->connect_error);
	
	$_SESSION['message'] = "Entrée sauvegardée: $Nature $CodePorte (Bâtiment $NumBat Étage $Etage)";
	$_SESSION['msg_type'] = "success";
	
	$cmd = "cd assets/py && python admin.py porte ".$CodePorte;
	exec($cmd);
	
	header("location: portes.php");
}

if (isset($_POST["CreerUtilisateur"])) { // Création d'un Utilisateur
	$Matricule = trim($_POST["Matricule"]);
	$Nom       = trim($_POST["Nom"]);
	$Prenom    = trim($_POST["Prenom"]);
	$Fonction  = trim($_POST["Fonction"]);
	
	$sql = "INSERT INTO utilisateurs (Matricule, Nom, Prenom, Fonction) VALUES ("
		."'". $Matricule ."', "
		."'". $Nom       ."', "
		."'". $Prenom    ."', "
		."'". $Fonction  ."')";
	
	$database->query($sql) or die("Requête échouée:\n$sql\n".$database->connect_error);
	
	$_SESSION['message'] = "Entrée sauvegardée: $Nom $Prenom ($Matricule) - $Fonction";
	$_SESSION['msg_type'] = "success";
	
	header("location: utilisateurs.php");
}

if (isset($_POST["CreerPrivilege"])) { // Création d'un Privilège
	$Utilisateur = trim($_POST["Utilisateur"]);
	$Groupe      = trim($_POST["Groupe"]);
	
	$sql = "INSERT INTO privileges (Matricule, CodeGrp) VALUES ("
		. $Utilisateur .", "
		. $Groupe      .")";
	
	$database->query($sql) or die("Requête échouée:\n$sql\n".$database->connect_error);
	
	$_SESSION['message'] = "Entrée sauvegardée: $Utilisateur a accès au Groupe $Groupe";
	$_SESSION['msg_type'] = "success";
	
	header("location: permissions.php");
}

if (isset($_POST["CreerCmd"])) { // Création d'une Commande
	$NomCmd     = trim($_POST["NomCmd"]);
	$DescCmd    = trim($_POST["DescCmd"]);
	$Commande   = trim($_POST["Commande"]);
	$CodeGrp    = trim($_POST["CodeGrp"]);
	$DateExec   = trim($_POST["DateExec"]);
	$Repetition = trim($_POST["Repetition"]);
	
	//if (isDatetime($DateExec)) {
		$sql = "INSERT INTO commandes (NomCmd, DescCmd, Commande, CodeGrp, DateExec, Repetition) VALUES ("
			."'". $NomCmd     ."', "
			."'". $DescCmd    ."', "
			."'". $Commande   ."', "
			."'". $CodeGrp    ."', "
			."'". $DateExec   ."', "
			."'". $Repetition ."')";
		
		$database->query($sql) or die("Requête échouée:\n$sql\n".$database->connect_error);
		
		sleep(1);
		
		creerCommande(getMax("commandes", "IDCmd"));
		
		$action = $Commande == "1" ? "Ouverture" : "Fermeture";
		$delai  = $Repetition == 1 ? "jour" : $Repetition." jours";
		
		$_SESSION['message'] = "Entrée sauvegardée: $action des Portes du Groupe $CodeGrp chaque $delai à partir du $DateExec";
		$_SESSION['msg_type'] = "success";
		
		header("location: commandes.php");
	//} else {
	//	echo "<p style='color:red'>Le format de la date n'est pas valide</p>";
	//}
}

if (isset($_POST["CreerGroupe"])) { // Création d'un Groupe
	$NomGrp     = trim($_POST["NomGrp"]);
	$Descriptif = trim($_POST["Descriptif"]);
	
	$sql = "INSERT INTO groupes (NomGrp, Descriptif) VALUES ("
		."'". $NomGrp     ."', "
		."'". $Descriptif ."')";
	
	$database->query($sql) or die("Requête échouée:\n$sql\n".$database->connect_error);
	
	$_SESSION['message'] = "Entrée sauvegardée: Groupe '$NomGrp'";
	$_SESSION['msg_type'] = "success";
	
	header("location: groupes.php");
}

if (isset($_POST["CreerAppartenance"])) { // Création d'une Appartenance
	$Porte  = trim($_POST["Porte"]);
	$Groupe = trim($_POST["Groupe"]);
	
	$sql = "INSERT INTO appartenancegrp (Porte, Groupe) VALUES ("
		."'". $Porte  ."', "
		.     $Groupe .")";
	
	$database->query($sql) or die("Requête échouée:\n$sql\n".$database->connect_error);
	
	$_SESSION['message'] = "Entrée sauvegardée: $Porte appartient au Groupe $Groupe";
	$_SESSION['msg_type'] = "success";
	
	header("location: appartenancegrp.php");
}

if (isset($_POST["CreerBatiment"])) { // Création d'un Batiment
	$NomBat = trim($_POST["NomBat"]);
	
	$sql = "INSERT INTO batiments (NomBat) VALUES ("
		."'". $NomBat ."')";
	
	$database->query($sql) or die("Requête échouée:\n$sql\n".$database->connect_error);
	
	$_SESSION['message'] = "Entrée sauvegardée: Bâtiment '$NomBat'";
	$_SESSION['msg_type'] = "success";
	
	header("location: batiments.php");
}





// Suppression de données (DELETE)
if (isset($_GET["delete_portes"])) {
	$id = $_GET["delete_portes"];
	$sql = "DELETE FROM portes WHERE CodePorte = '$id'";
	$database->query(($sql)) or die("Requête échouée:\n$sql\n".$database->connect_error);
	
	$_SESSION['message'] = "Entrée supprimée: Porte $id";
	$_SESSION['msg_type'] = "danger";
	
	header("location: ../../portes.php");
}

if (isset($_GET["delete_utilisateurs"])) {
	$id = $_GET["delete_utilisateurs"];
	$sql = "DELETE FROM utilisateurs WHERE Matricule = $id";
	$database->query($sql) or die("Requête échouée:\n$sql\n".$database->connect_error);
	
	$_SESSION['message'] = "Entrée supprimée: Utilisateur $id";
	$_SESSION['msg_type'] = "danger";
	
	header("location: ../../utilisateurs.php");
}

if (isset($_GET["delete_privileges"])) {
	$id = explode("-", $_GET["delete_privileges"]);
	$sql = "DELETE FROM privileges WHERE Matricule = ".$id[0]." AND CodeGrp = ".$id[1];
	$database->query($sql) or die("Requête échouée:\n$sql\n".$database->connect_error);
	
	$_SESSION['message'] = "Entrée supprimée: Utilisateur ".$id[0]." n'a plus accès au Groupe ".$id[1];
	$_SESSION['msg_type'] = "danger";
	
	header("location: ../../permissions.php");
}

if (isset($_GET["delete_commandes"])) {
	$id = $_GET["delete_commandes"];
	$sql = "DELETE FROM commandes WHERE IDCmd = $id";
	$database->query($sql) or die("Requête échouée:\n$sql\n".$database->connect_error);
	
	sleep(1);
	
	supprCommande($id);
	
	$_SESSION['message'] = "Entrée supprimée: Commande $id";
	$_SESSION['msg_type'] = "danger";
	
	header("location: ../../commandes.php");
}

if (isset($_GET["delete_groupes"])) {
	$id = $_GET["delete_groupes"];
	$sql = "DELETE FROM groupes WHERE CodeGrp = $id";
	$database->query($sql) or die("Requête échouée:\n$sql\n".$database->connect_error);
	
	$_SESSION['message'] = "Entrée supprimée: Groupe $id";
	$_SESSION['msg_type'] = "danger";
	
	header("location: ../../groupes.php");
}

if (isset($_GET["delete_appartenancegrp"])) {
	$id = explode("-", $_GET["delete_appartenancegrp"]);
	$sql = "DELETE FROM appartenancegrp WHERE Porte = ".$id[0]." AND Groupe = '".$id[1]."'";
	$database->query($sql) or die("Requête échouée:\n$sql\n".$database->connect_error);
	
	$_SESSION['message'] = "Entrée supprimée: Porte ".$id[1]." n'appartient plus au Groupe ".$id[0];
	$_SESSION['msg_type'] = "danger";
	
	header("location: ../../appartenancegrp.php");
}

if (isset($_GET["delete_batiments"])) {
	$id = $_GET["delete_batiments"];
	$sql = "DELETE FROM batiments WHERE NumBat = $id";
	$database->query($sql) or die("Requête échouée:\n$sql\n".$database->connect_error);
	
	$_SESSION['message'] = "Entrée supprimée: Batiment $id";
	$_SESSION['msg_type'] = "danger";
	
	header("location: ../../batiments.php");
}

if (isset($_GET["delete_historique"])) {
	$sql = "TRUNCATE table historique";
	$database->query($sql) or die("Requête échouée:\n$sql\n".$database->connect_error);
	header("location: ../../historique.php");
}





// Préparation à la modification de données (SELECT)
if (isset($_GET["edit_portes"])) {
	$update = true;
	
	$id = $_GET["edit_portes"];
	$sql = "SELECT * FROM portes WHERE CodePorte = '$id'";
	$result = $database->query($sql) or die("Requête échouée:\n$sql\n".$database->connect_error);
	
	if($result->num_rows){
		$row = $result->fetch_array();
		$CodePorte   = $row['CodePorte'];
		$Nature      = $row['Nature'];
		$NumBat      = $row['NumBat'];
		$Etage       = $row['Etage'];
		$EtatPorte   = $row['EtatPorte'];
		$EtatSerrure = $row['EtatSerrure'];
	}
}

if (isset($_GET["edit_utilisateurs"])) {
	$update = true;
	
	$id = $_GET["edit_utilisateurs"];
	$sql = "SELECT * FROM utilisateurs WHERE Matricule = $id";
	$result = $database->query($sql) or die("Requête échouée:\n$sql\n".$database->connect_error);
	
	if($result->num_rows){
		$row = $result->fetch_array();
		$Matricule = $row['Matricule'];
		$Nom       = $row['Nom'];
		$Prenom    = $row['Prenom'];
		$Fonction  = $row['Fonction'];
	}
}

if (isset($_GET["edit_commandes"])) {
	$update = true;
	
	$id = $_GET["edit_commandes"];
	$sql = "SELECT * FROM commandes WHERE IDCmd = $id";
	$result = $database->query($sql) or die("Requête échouée:\n$sql\n".$database->connect_error);
	
	if($result->num_rows){
		$row = $result->fetch_array();
		$NomCmd     = $row['NomCmd'];
		$DescCmd    = $row['DescCmd'];
		$Commande   = $row['Commande'];
		$CodeGrp    = $row['CodeGrp'];
		$DateExec   = $row['DateExec'];
		$Repetition = $row['Repetition'];
	}
}

if (isset($_GET["edit_groupes"])) {
	$update = true;
	
	$id = $_GET["edit_groupes"];
	$sql = "SELECT * FROM groupes WHERE CodeGrp = $id";
	$result = $database->query($sql) or die("Requête échouée:\n$sql\n".$database->connect_error);
	
	if($result->num_rows){
		$row = $result->fetch_array();
		$NomGrp     = $row['NomGrp'];
		$Descriptif = $row['Descriptif'];
	}
}

if (isset($_GET["edit_batiments"])) {
	$update = true;
	
	$id = $_GET["edit_batiments"];
	$sql = "SELECT * FROM batiments WHERE NumBat = $id";
	$result = $database->query($sql) or die("Requête échouée:\n$sql\n".$database->connect_error);
	
	if($result->num_rows){
		$row = $result->fetch_array();
		$NomBat     = $row['NomBat'];
	}
}





// Modification de données (UPDATE)
if (isset($_POST["ModifPorte"])) {
	$CodePorte   = trim($_POST["id"]);
	$Nature      = trim($_POST["Nature"]);
	$NumBat      = trim($_POST["NumBat"]);
	$Etage       = trim($_POST["Etage"]);
	//$EtatPorte   = $_POST["EtatPorte"];
	//$EtatSerrure = $_POST["EtatSerrure"];
	
	$sql = "UPDATE portes SET "
		. "Nature = '$Nature', "
		. "NumBat = '$NumBat', "
		. "Etage = '$Etage' "
		. "WHERE CodePorte = '$CodePorte'";
		
	$database->query($sql) or die("Requête échouée:\n$sql\n".$database->connect_error);
	
	$_SESSION['message'] = "Entrée Modifiée: $Nature $CodePorte (Bâtiment $NumBat Étage $Etage";
	$_SESSION['msg_type'] = "warning";
	
	header("location: portes.php");
}

if (isset($_POST["ModifUtilisateur"])) {
	$Matricule = trim($_POST["id"]);
	$Nom       = trim($_POST["Nom"]);
	$Prenom    = trim($_POST["Prenom"]);
	$Fonction  = trim($_POST["Fonction"]);
	
	$sql = "UPDATE utilisateurs SET "
		. "Nom = '$Nom', "
		. "Prenom = '$Prenom', "
		. "Fonction = '$Fonction' "
		. "WHERE Matricule = '$Matricule'";
		
	$database->query($sql) or die("Requête échouée:\n$sql\n".$database->connect_error);
	
	$_SESSION['message'] = "Entrée Modifiée: $Nom $Prenom ($Matricule) - $Fonction";
	$_SESSION['msg_type'] = "warning";
	
	header("location: utilisateurs.php");
}

if (isset($_POST["ModifCmd"])) {
	$IDCmd      = trim($_POST["id"]);
	$NomCmd     = trim($_POST["NomCmd"]);
	$DescCmd    = trim($_POST["DescCmd"]);
	$Commande   = trim($_POST["Commande"]);
	$CodeGrp    = trim($_POST["CodeGrp"]);
	$DateExec   = trim($_POST["DateExec"]);
	$Repetition = trim($_POST["Repetition"]);
	
	$sql = "UPDATE commandes SET "
		. "NomCmd = '$NomCmd', "
		. "DescCmd = '$DescCmd', "
		. "Commande = '$Commande', "
		. "CodeGrp = '$CodeGrp', "
		. "DateExec = '$DateExec', "
		. "Repetition = '$Repetition' "
		. "WHERE IDCmd = '$IDCmd'";
		
	$database->query($sql) or die("Requête échouée:\n$sql\n".$database->connect_error);
	
	sleep(1);
	
	modifCommande($IDCmd);
	
	$_SESSION['message'] = "Entrée Modifiée: $action des Portes du Groupe $CodeGrp chaque $delai à partir du $DateExec";
	$_SESSION['msg_type'] = "warning";
	
	header("location: commandes.php");
}

if (isset($_POST["ModifGroupe"])) {
	$CodeGrp    = trim($_POST["id"]);
	$NomGrp     = trim($_POST["NomGrp"]);
	$Descriptif = trim($_POST["Descriptif"]);
	
	$sql = "UPDATE groupes SET "
		. "NomGrp = '$NomGrp', "
		. "Descriptif = '$Descriptif' "
		. "WHERE CodeGrp = '$CodeGrp'";
		
	$database->query($sql) or die("Requête échouée:\n$sql\n".$database->connect_error);
	
	$_SESSION['message'] = "Entrée Modifiée: Groupe '$NomGrp'";
	$_SESSION['msg_type'] = "warning";
	
	header("location: groupes.php");
}

if (isset($_POST["ModifBatiment"])) {
	$NumBat = trim($_POST["id"]);
	$NomBat = trim($_POST["NomBat"]);
	
	$sql = "UPDATE batiments SET "
		. "NomBat = '$NomBat' "
		. "WHERE NumBat = '$NumBat'";
		
	$database->query($sql) or die("Requête échouée:\n$sql\n".$database->connect_error);
	
	$_SESSION['message'] = "Entrée Modifiée: Bâtiment '$NomBat'";
	$_SESSION['msg_type'] = "warning";
	
	header("location: batiments.php");
}





// Fonctions
function getEtatSerrure($id) {
	global $database;
	
	$sql = "SELECT * FROM portes WHERE CodePorte = '$id'";
	$result = $database->query($sql) or die("Requête échouée:\n$sql\n".$database->connect_error);
	$row = $result->fetch_array();
	
	return $row['EtatSerrure'];
}

function getAdminID() {
	global $database;
	
	$sql = "SELECT * from utilisateurs WHERE Fonction = 'Administrateur'";
	$result = $database->query($sql) or die("Requête échouée:\n$sql\n".$database->connect_error);
	$row = $result->fetch_array();
	
	return $row['Matricule'];
}

function getMax($table, $column) {
	global $database;
	
	$sql = "SELECT MAX($column) AS Maximum FROM $table";
	$result = $database->query($sql) or die("Requête échouée:\n$sql\n".$database->connect_error);
	$row = $result->fetch_array();
	
	return $row['Maximum'];
	
}

function listOptions($table, $valueCol, $textCol = NULL, $orderCol = NULL) {
	global $database;
	
	$textCol  = is_null($textCol)  ? $valueCol : $textCol;
	$orderCol = is_null($orderCol) ? $valueCol : $orderCol;
	
	$sql = "SELECT $valueCol, $textCol, $orderCol FROM $table ORDER BY $orderCol ASC";
	$result = $database->query($sql) or die("Requête échouée:\n$sql\n".$database->connect_error);
	
	while ($row = $result->fetch_assoc()) {
		echo '
			<option value="'.$row[$valueCol].'">'.$row[$textCol].'</option>
		';
	}
}

function listColumns($table) {
	global $dbname;
	global $database;
	
	$sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '$dbname' AND TABLE_NAME = '$table'";
	$result = $database->query($sql) or die("Requête échouée:\n$sql\n".$database->connect_error);
	
	while ($row = $result->fetch_assoc()) {
		echo '
			<option value="'.$row["COLUMN_NAME"].'">'.sql2header($row["COLUMN_NAME"]).'</option>
		';
	}
}





// Commandes automatiques
function creerCommande($id) { //admin.py commande $user create $id
	if (!isset($adminID)) {
		$adminID = getAdminID();
	}
	
	$cmd = "cd assets/py && python admin.py commande create ".$adminID." ".$id;
	
	sleep(1);
	exec($cmd);
	//header("location: $cmd"); //ligne 238
}

function supprCommande($id) { //admin.py commande $user delete $id
	if (!isset($adminID)) {
		$adminID = getAdminID();
	}
	
	$cmd = "cd ../py && python admin.py commande delete ".$adminID." ".$id;
	
	sleep(1);
	exec($cmd);
	//header("location: $cmd"); //ligne 337
}

function modifCommande($id) {
	//supprCommande($id);
	//creerCommande(getMax("commandes", "IDCmd"));
	creerCommande($id);
}
?>