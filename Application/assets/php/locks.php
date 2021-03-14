<?php
//require_once "connect.php";

// Ouverture et fermeture
if (isset($_GET["manip"])) {
	$get    = explode(' ', $_GET["manip"]);
	$id     = $get[0];
	$action = ($get[1] == "0" ? "1" : "0");
	
	manipPorte($id, $action);
	
	header("location: ../../portes.php");
}

if (isset($_GET["bulk"])) {
	$get    = explode(' ', $_GET["bulk"]);
	$id     = $get[0];
	$action = ($get[1] == "0" ? "1" : "0");
	
	manipGroupe($id, $action);
	
	header("location: ../../groupes.php");
}

function manipPorte($porte, $action) { //admin.py porte $user $porte
	if (!isset($adminID)) {
		$adminID = "172077";//getAdminID();
	}
	
	$cmd = "cd ../py && python admin.py porte ".$adminID." ".$porte;
	exec($cmd);
	//header("location: $cmd");
	sleep(1);
}

function manipGroupe($groupe, $action) { //admin.py groupe $groupe $user $action
	if (!isset($adminID)) {
		$adminID = "172077";//getAdminID();
	}
	
	$cmd = "cd ../py && python admin.py groupe ".$groupe." ".$adminID." ".$action;
	exec($cmd);
	sleep(1);
}





/*/ Commandes automatiques
function creerCommande($id) { //admin.py commande $user create $id
	if (!isset($adminID)) {
		$adminID = getAdminID();
	}
	
	$cmd = "cd ../py && python admin.py commande ".$adminID." create ".$id;
	echo $cmd;
	sleep(5);
	exec($cmd);
}

function supprCommande($id) { //admin.py commande $user delete $id
	if (!isset($adminID)) {
		$adminID = getAdminID();
	}
	
	$cmd = "cd ../py && python admin.py commande ".$adminID." delete ".$id;
	exec($cmd);
}

function modifCommande($id) {
	supprCommande($id);
	creerCommande(getMax("commandes", "IDCmd"));
}*/
?>