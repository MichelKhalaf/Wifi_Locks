<?php
$CodeGrp      = "";
$CodePorte    = "";
$Commande     = "";
$DateExec     = "";
$DescCmd      = "";
$Descriptif   = "";
$Etage        = "";
$EtatPorte    = "";
$EtatSerrure  = "";
$Fonction     = "";
$Groupe       = "";
$IDCmd        = "";
$Matricule    = "";
$Nature       = "";
$Nom          = "";
$NomBat       = "";
$NomCmd       = "";
$NomGrp       = "";
$NumBat       = "";
$Porte        = "";
$Prenom       = "";
$Repetition   = "";
$Utilisateur  = "";
$RechercheVal = "";





function phpAlert($msg) {
    echo '<script type="text/javascript">alert("' . $msg . '")</script>';
}

function pre_r($array) {
	echo "<pre>";
	print_r($array);
	echo "</pre>";
}

function isDatetime($string) {
	return preg_match('(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})', $string);
}

function bin2txt($value, $mode) {
	if ($value == "0" || $value == 0) {
		if ($mode == "state") return "Fermée";
		return "Fermeture";
	} else if ($value == "1" || $value == 1) {
		if ($mode == "state") return "Ouverte";
		return "Ouverture";
	} else {
		return $value;
	}
}

function rep2txt($value) {
	if ($value == "0" || $value == 0) {
		return "Une fois";
	} else if ($value == "1" || $value == 1) {
		return "Chaque jour";
	} else {
		return $value;
	}
}

function sql2header($column) {
	$columns = [
		"Action"	  => "Action",
		"CodeGrp"     => "Groupe",
		"CodePorte"   => "Porte",
		"Commande"    => "Commande",
		"Date"		  => "Date",
		"DateExec"    => "Date d'Éxécution",
		"DescCmd"     => "Description",
		"Descriptif"  => "Description",
		"Etage"       => "Étage",
		"EtatPorte"   => "État",
		"EtatSerrure" => "Serrure",
		"Fonction"    => "Fonction",
		"Groupe"      => "Groupe",
		"IDCmd"       => "ID",
		"Matricule"   => "Matricule",
		"Nature"      => "Nature",
		"Nom"         => "Nom",
		"NomBat"      => "Nom Bâtiment",
		"NomCmd"      => "Nom",
		"NomGrp"      => "Nom",
		"NumBat"      => "Num Bâtiment",
		"Porte"       => "Porte",
		"porte"       => "Porte",
		"Prenom"      => "Prénom",
		"Repetition"  => "Répétition",
		"Utilisateur" => "Utilisateur",
	];
	
	return $columns[$column];
	return $column;
}

function sql2title($table) {;
	$tables = [
		"appartenancegrp" => "Appartenance aux Groupes",
		"batiments"       => "Bâtiments",
		"commandes"		  => "Commandes",
		"groupes"		  => "Groupes",
		"historique"  	  => "Historique",
		"ouverture"   	  => "Historique",
		"portes"    	  => "Portes",
		"permissions"     => "Privilèges",
		"privileges"	  => "Privilèges",
		"utilisateurs"    => "Utilisateurs",
	];
	
	if ($tables[$table]) return $tables[$table];
	return $table;
}
?>