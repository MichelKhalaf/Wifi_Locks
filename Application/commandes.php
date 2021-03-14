<!doctype html>

<html lang='en'>
<?php
	include "assets/php/base.php";
	makehead();
?>

<body>
	<?php
		makeheader("commandes");
	?>
	
	<div class="container" style="margin-top: 3vh">
		<div class="row justify-content-center">
			<?php maketable("commandes", "commandes.php"); ?>
		</div>
	
		<div class="row justify-content-center" style="position: fixed; top: 9.5vh; left: 50px; width: 200px">
			<form action="" method="POST">
				<input type="hidden"  name="id"  value="<?php echo $id ?>">
				
				<input type="hidden"  name="IDCmd"  class="form-control"  value="<?php echo $IDCmd ?>"  disabled>
				
				<div class="form-group">
					<label>Nom</label>
					<input type="text"  name="NomCmd"  class="form-control"  placeholder="Nom"  value="<?php echo $NomCmd ?>">
				</div>
				
				<div class="form-group">
					<label>Description</label>
					<input type="text"  name="DescCmd"  class="form-control"  placeholder="Description"  value="<?php echo $DescCmd ?>">
				</div>
				
				<div class="form-group">
					<label>Commande</label>
					<select name="Commande"  class="form-control"  placeholder="Commande"  value="<?php echo $Commande ?>">
						<option value="0">Fermer</option>
						<option value="1">Ouvrir</option>
					</select>
					
				</div>
				
				<div class="form-group">
					<label>Groupe à Manipuler</label>
					<select name="CodeGrp"  class="form-control"  value="<?php echo $CodeGrp ?>">
						<?php listOptions("groupes", "CodeGrp", "NomGrp"); ?>
					</select>
				</div>
				
				<div class="form-group">
					<label>Date d'Éxécution</label>
					<?php
						$current = explode(' ', date('Y-m-d H:i:s'));
						//$execute = explode(' ', $DateExec);
						$minVal  = $current[0]."T".$current[1];
						//$execVal = $execute[0]."T".$execute[1];
					?>
					<input type="text"  name="DateExec"  class="form-control"  placeholder="YYYY-MM-JJ hh:mm:ss"  value="<?php echo $DateExec ?>">
					<!-- <input type="datetime-local"  name="DateExec"  class="form-control"  placeholder="YYYY-MM-AA hh:mm:ss"  value="<php echo $execVal >"  min="<php echo $minVal >"> -->
				</div>
				
				<div class="form-group">
					<label>Répétition?</label>
					<select name="Repetition"  class="form-control"  placeholder="Repetition"  value="<?php echo $Repetition ?>">
						<option value="0">Une fois</option>
						<option value="1">Chaque jour</option>
					</select>
				</div>
							
				<div class="form-group">
					<?php
					if ($update) { echo "<button type='submit'  name='ModifCmd'  class='btn btn-info'>Modifier</button>"; }
					else         { echo "<button type='submit'  name='CreerCmd'  class='btn btn-primary'>Créer</button>"; }
					?>
				</div>
			</form>
		</div>
		
		<div class="row justify-content-center" style="position: fixed; top: 9.5vh; right: 50px; width: 200px">
			<form action="" method="POST" class="form-group">
				<label>Recherche</label>
				<select name="RechercheCol"  class="form-control">
					<?php listColumns("commandes"); ?>
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