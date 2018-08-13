<?php
require_once 'core/init.php';
include 'includes/head.php';
include 'includes/navigation.php';

$result=$db->query("SELECT * FROM gorod");
$date1=date("Y-m-d");

?>

<div class="container">
	<div class="page-header text-center">
		<form class="form-inline" method="post">
			<div class="form-group">
				<label for="gorod">Выберите город:</label>
				<select class="form-control" name="gor" id="gor">
					<option></option>
					<?php while($gorod=mysqli_fetch_assoc($result)): ?>
						<option value="<?=$gorod['id']?>"><?=$gorod['name']?></option>
					<?php endwhile; ?>
				</select>
				<input type="date" name="uaqyt" id="uaqyt" value="<?=$date1;?>" min="<?=$date1;?>">
				<input type="submit" class="btn btn-success" value="Расписание">
			</div>
		</form>
	</div>
	<div class="well">
		<table class="table table-responsive">
			<thead>
				<th>Станция отправления</th>
				<th>Время отправления</th>
				<th>Станция прибытия</th>
				<th>время прибытия</th>
				<th>время в пути</th>
				<th>цена билета</th>
				<th>перевозчик</th>
				<th>график движения</th>
			</thead>
			<tbody>
				<?php 
				if (isset($_POST) && !empty($_POST)) {
				$id_gor=sanitize($_POST['gor']);
				$date2=sanitize($_POST['uaqyt']);
			}
			else{
				$id_gor=0;
			}
				if ($id_gor!=0) {
					$quer="SELECT a.name AS stan1,b.name AS stan2,r.date_otpr AS otpr,r.time_otpr AS otime,r.date_pr AS pr,r.time_pr AS ptime,r.cena AS baga,r.perevoz AS pre,r.grapik AS gra
				FROM gorod a,gorod b,reys r
				WHERE a.id=r.id_stanOtpr AND b.id=id_stanPr AND r.id_stanOtpr='$id_gor' AND r.date_otpr='$date2'";
				}
				else{
				$quer="SELECT a.name AS stan1,b.name AS stan2,r.date_otpr AS otpr,r.time_otpr AS otime,r.date_pr AS pr,r.time_pr AS ptime,r.cena AS baga,r.perevoz AS pre,r.grapik AS gra
				FROM gorod a,gorod b,reys r
				WHERE a.id=r.id_stanOtpr AND b.id=id_stanPr AND r.date_otpr='$date1'";
			}

				$result2=$db->query($quer);
				while($reys=mysqli_fetch_assoc($result2)):
				?>
				<tr>
					<td><?=$reys['stan1'];?></td>
					<td><?=$reys['otpr'].'/'.$reys['otime'];?></td>
					<td><?=$reys['stan2'];?></td>
					<td><?=$reys['pr'].'/'.$reys['ptime'];?></td>
					<td></td>
					<td><?=money($reys['baga']);?></td>
					<td><?=$reys['pre'];?></td>
					<td><?=$reys['gra'];?></td>
				</tr>
			<?php endwhile; ?>
			</tbody>
		</table>
	</div>
</div>

<?php include 'includes/footer.php';