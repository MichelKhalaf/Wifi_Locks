<?php
include "helper.php";
include "locks.php";
//require_once "assets/php/connect.php";
require_once "connect.php";


// Construit le <head>
function makehead() {
	echo "
		<head>
			<meta charset='utf-8'>
			<meta name='author' content='MDP18'>
			<title>Gestion des Serrures</title>
			
			<link rel='icon' type='image/png' sizes='32x32' href='assets/img/favicon-96x96.png'>
			
			<link rel='stylesheet' href='assets/css/bootstrap.css'>
			<link rel='stylesheet' href='assets/css/header.css'>
			<link rel='stylesheet' href='assets/css/index.css'>
			<link rel='stylesheet' href='assets/css/table.css'>
			<link rel='stylesheet' href='assets/css/modal.css'>
			
			<script type='text/javascript' src='assets/js/jquery-3.4.1.js'></script>
			<script type='text/javascript' src='assets/js/bootstrap.js'></script>
			
		</head>
	";
}

// Construit le header des pages
function makeheader($pagename) {
	echo "
		<div class='header'>
			<a href='index.php' class='logo'>Gestion des Serrures</a>
			
			<div class='header-right'>";
				$hist = "historique"; //"ouverture"
				$tables = array($hist, "utilisateurs", "portes", "groupes", "permissions", "appartenancegrp", "commandes", "batiments");
				
				foreach($tables as $table) {
					$elem = "<a href='$table.php'";
					if ($table == $pagename) {
						$elem .= " class='active'";
					}
					
					$elem = $elem.">".sql2title($table)."</a>";
					echo $elem;
				}
			echo "	
			</div>
			
			<br><br>
		</div>
	";
}
?>