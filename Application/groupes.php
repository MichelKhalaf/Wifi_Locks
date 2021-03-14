<!doctype html>

<html lang='en'>
<?php
	include "assets/php/base.php";
	makehead();
?>

<body>
	<?php
		makeheader("groupes");
	?>
	
	<div class="container" style="margin-top: 3vh">
		<div class="row justify-content-center">
			<?php maketable("groupes", "groupes.php"); ?>
		</div>
	
		<div class="row justify-content-center" style="position: fixed; top: 9.5vh; left: 50px; width: 200px">
			<form action="" method="POST">
				<input type="hidden"  name="id"  value="<?php echo $id ?>">
				
				<input type="hidden"  name="CodeGrp"  class="form-control"  value="<?php echo $CodeGrp ?>"  disabled>
				
				<div class="form-group">
					<label>Nom</label>
					<input type="text"  name="NomGrp"  class="form-control"  placeholder="Nom"  value="<?php echo $NomGrp ?>">
				</div>
				
				<div class="form-group">
					<label>Descriptif</label>
					<input type="text"  name="Descriptif"  class="form-control"  placeholder="Descriptif"  value="<?php echo $Descriptif ?>">
				</div>
				
				<div class="form-group">
					<?php
					if ($update) { echo "<button type='submit'  name='ModifGroupe'  class='btn btn-info'>Modifier</button>"; }
					else         { echo "<button type='submit'  name='CreerGroupe'  class='btn btn-primary'>Créer</button>"; }
					?>
				</div>
			</form>
		</div>
		
		<div class="row justify-content-center" style="position: fixed; top: 9.5vh; right: 50px; width: 200px">
			<form action="" method="POST" class="form-group">
				<label>Recherche</label>
				<select name="RechercheCol"  class="form-control">
					<?php listColumns("groupes"); ?>
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