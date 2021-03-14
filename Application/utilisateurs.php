<!doctype html>

<html lang='en'>
<?php
	include "assets/php/base.php";
	makehead();
?>

<body>
	<?php
		makeheader("utilisateurs");
	?>
	
	<?php
	/*if (isset($_SESSION["message"])) {
		echo "
		<div class='alert-".$_SESSION["msg_type"]."'><br><h6 style='padding-left:10px'>"
			.$_SESSION['message'].
		"<br><br></h6></div>";
		
		unset($_SESSION["message"]);
	}*/
	?>
	
	<div class="container" style="margin-top: 3vh">
		<!--div class="row justify-content-center" style="width:60vw"-->
		<div class="row justify-content-center">
			<?php maketable("utilisateurs", "utilisateurs.php"); ?>
		</div>
		
		<!--div class="row justify-content-center" style="position: fixed; top: 9.5vh; left: -7.5vw; width: 30vw"-->
		<div class="row justify-content-center" style="position: fixed; top: 9.5vh; left: 50px; width: 200px">
			<form action="" method="POST">
				<input type="hidden"  name="id"  value="<?php echo $id ?>">
				
				<div class="form-group">
					<label>Matricule</label>
					<?php
					if ($update) { echo "<input type='text'  name='Matricule'  class='form-control'  placeholder='Matricule'  value='$Matricule'  disabled>"; }
					else         { echo "<input type='text'  name='Matricule'  class='form-control'  placeholder='Matricule'  value='$Matricule'>"; }
					?>
				</div>
				
				<div class="form-group">
					<label>Nom</label>
					<input type="text"  name="Nom"  class="form-control"  placeholder="Nom"  value="<?php echo $Nom ?>">
				</div>
				
				<div class="form-group">
					<label>Prénom</label>
					<input type="text"  name="Prenom"  class="form-control"  placeholder="Prénom"  value="<?php echo $Prenom ?>">
				</div>
				
				<div class="form-group">
					<label>Fonction</label>
					<input type="text"  name="Fonction"  class="form-control"  placeholder="Fonction"  value="<?php echo $Fonction ?>">
				</div>
				
				<div class="form-group">
					<?php
					if ($update) { echo "<button type='submit'  name='ModifUtilisateur'  class='btn btn-info'>Modifier</button>"; }
					else         { echo "<button type='submit'  name='CreerUtilisateur'  class='btn btn-primary'>Créer</button>"; }
					?>
				</div>
			</form>
		</div>
		
		<div class="row justify-content-center" style="position: fixed; top: 9.5vh; right: 50px; width: 200px">
			<form action="" method="POST" class="form-group">
				<label>Recherche</label>
				<select name="RechercheCol"  class="form-control">
					<?php listColumns("utilisateurs"); ?>
				</select>
				<br>
				<input type="text"  name="RechercheVal"  class="form-control"  placeholder="Recherche"  value="<?php echo $RechercheVal ?>">
				<br>
				<button type='submit'  name='Rechercher'  class='btn btn-primary'>Rechercher</button>
			</form>
		</div>
	</div>
</body>
</html>