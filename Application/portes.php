<!doctype html>

<html lang='en'>
<?php
	include "assets/php/base.php";
	makehead();
?>

<body>
	<?php
		makeheader("portes");
	?>
	
	<div class="container" style="margin-top: 3vh">
		<div class="row justify-content-center">
			<?php maketable("portes", "portes.php"); ?>
		</div>
	
		<div class="row justify-content-center" style="position: fixed; top: 9.5vh; left: 50px; width: 200px">
			<form action="" method="POST">
				<input type="hidden"  name="id"  value="<?php echo $id ?>">
				
				<div class="form-group">
					<label>Code</label>
					<?php
					if ($update) { echo "<input type='text'  name='CodePorte'  class='form-control'  placeholder='Code'  value='$CodePorte'  disabled>"; }
					else         { echo "<input type='text'  name='CodePorte'  class='form-control'  placeholder='Code'  value='$CodePorte'>"; }
					?>
				</div>
				
				<div class="form-group">
					<label>Nature</label>
					<input type="text"  name="Nature"  class="form-control"  placeholder="Nature"  value="<?php echo $Nature ?>">
				</div>
				
				<div class="form-group">
					<label>Bâtiment</label>
					<select name="NumBat"  class="form-control"  value="<?php echo $NumBat ?>">
						<?php listOptions("batiments", "NumBat", "NomBat"); ?>
					</select>
				</div>
				
				<div class="form-group">
					<label>Étage dans le Bâtiment</label>
					<input type="text"  name="Etage"  class="form-control"  placeholder="Étage"  value="<?php echo $Etage ?>">
				</div>
				
				<!-- EtatPorte et EtatSerrure à 0 par défaut -->
				
				<div class="form-group">
					<?php
					if ($update) { echo "<button type='submit'  name='ModifPorte'  class='btn btn-info'>Modifier</button>"; }
					else         { echo "<button type='submit'  name='CreerPorte'  class='btn btn-primary'>Créer</button>"; }
					?>
				</div>
			</form>
		</div>
		
		<div class="row justify-content-center" style="position: fixed; top: 9.5vh; right: 50px; width: 200px">
			<form action="" method="POST" class="form-group">
				<label>Recherche</label>
				<select name="RechercheCol"  class="form-control">
					<?php listColumns("portes"); ?>
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