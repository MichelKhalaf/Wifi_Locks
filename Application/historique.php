<!doctype html>

<html lang='en'>
<?php
	include "assets/php/base.php";
	makehead();
?>

<body>
	<?php
		makeheader("historique");
	?>
	
	<div class="container" style="margin-top: 3vh">
		<a href='assets/php/connect.php?delete_historique'  class='btn btn-danger'  style='font-family: Courier; color: #FFF'>Supprimer Tout l'Historique</a>
		<br><br>
		<div class="row justify-content-center">
			<?php maketable("historique", "historique.php"); ?>
		</div>
	</div>
	
	<!--<div class="row justify-content-center" style="position: fixed; top: 9.5vh; left: 50px; width: 200px">		
	<form action="" method="POST" class="form-group">
		<label>Recherche</label>
		<select name="RechercheCol"  class="form-control">
			<?php listColumns("historique"); ?>
		</select>
		<br>
		<input type="text"  name="RechercheVal"  class="form-control"  placeholder="Recherche"  value="<?php echo "$RechercheVal" ?>">
		<br>
		<button type='submit'  name='Rechercher'  class='btn btn-primary'>Rechercher</button>
	</form>
	</div>-->
</body>
</html>