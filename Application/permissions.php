<!doctype html>

<html lang='en'>
<?php
	include "assets/php/base.php";
	makehead();
?>

<body>
	<?php
		makeheader("permissions");
	?>
	
	<div class="container" style="margin-top: 3vh">
		<div class="row justify-content-center">
			<?php maketable("privileges", "permissions.php"); ?>
		</div>
	
		<div class="row justify-content-center" style="position: fixed; top: 9.5vh; left: 50px; width: 200px">
			<form action="" method="POST">			
				<div class="form-group">
					<label>Utilisateur</label>
					<input type="text"  name="Utilisateur"  class="form-control"  placeholder="Utilisateur"  value="<?php echo $Utilisateur ?>">
				</div>
				
				<div class="form-group">
					<label>Groupe</label>
					<!--input type="text"  name="Groupe"  class="form-control"  placeholder="Groupe"  value="<?php //echo $Groupe ?>"-->
					<select name="Groupe"  class="form-control"  value="<?php echo $Groupe ?>">
						<?php listOptions("groupes", "CodeGrp", "NomGrp"); ?>
					</select>
				</div>
				
				<div class="form-group">
					<button type="submit"  name="CreerPrivilege"  class="btn btn-primary">Créer</button>
				</div>
			</form>
		</div>
		
		<div class="row justify-content-center" style="position: fixed; top: 9.5vh; right: 50px; width: 200px">	
			<form action="" method="POST" class="form-group">
				<label>Recherche</label>
				<select name="RechercheCol"  class="form-control">
					<?php listColumns("privileges"); ?>
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