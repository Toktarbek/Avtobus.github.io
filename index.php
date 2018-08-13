<?php
require_once 'core/init.php';
include 'includes/head.php';
include 'includes/navigation.php';

$dd=date("Y-m-d");
$result=$db->query("SELECT * FROM gorod Where tan=0");

?>
<div class="container">
	<div class="page-header text-center">
		<h3>Укажите маршрут и дату поездки, чтобы узнать расписание и купить билеты на автобус</h3>
		<form class="form-inline" method="post">
			<div class="form-group">
				<input type="text" name="stan1" id="stan1" class="form-control" placeholder="Откуда" required>
				<input type="text" name="stan2" id="stan2" class="form-control" placeholder="Куда" required>
				<input type="date" name="date1" id="date1" class="form-control" value="<?=$dd;?>" min=<?=$dd;?>>
				<input type="submit" class="btn btn-primary form-control" value="Найти билеты">
			</div>
		</form>
	</div>
	<?php 
	if(isset($_POST) && !empty($_POST)):
		$stan1=sanitize($_POST['stan1']);
		$stan2=sanitize($_POST['stan2']);
		$date1=sanitize($_POST['date1']);
	?>
	<div class="well">
		<h3 class="text-center"><?=$stan1.' - '.$stan2.' ('.$date1.')';?></h3><br>
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
				$otpr=$db->query("SELECT * FROM gorod WHERE name='$stan1'");
				$qala1=mysqli_fetch_assoc($otpr);
				$id_otpr=$qala1['id'];

				$pr=$db->query("SELECT * FROM gorod WHERE name='$stan2'");
				$qala2=mysqli_fetch_assoc($pr);
				$id_pr=$qala2['id'];

				$quer="SELECT * FROM reys WHERE id_stanOtpr='$id_otpr' AND id_stanPr='$id_pr' AND date_otpr='$date1'";

				$result2=$db->query($quer);
				while($reys=mysqli_fetch_assoc($result2)):
				?>
				<tr>
					<td><?=$stan1;?></td>
					<td><?=$reys['date_otpr'].'/'.$reys['time_otpr'];?></td>
					<td><?=$stan2;?></td>
					<td><?=$reys['date_pr'].'/'.$reys['time_pr'];?></td>
					<td></td>
					<td><?=money($reys['cena']);?></td>
					<td><?=$reys['perevoz'];?></td>
					<td><?=$reys['grapik'];?></td>
				</tr>
			<?php endwhile; ?>
			</tbody>
		</table>
	</div>
<?php else: ?>
	<div class="row">
		<div class="col-md-12">
			<h3>Расписание автобусов по города</h3>
		</div>
	<?php while($qala=mysqli_fetch_assoc($result)): ?>
		<div class="col-md-4">
			<div class="thumbnail">
				<a href="gorod.php?gor=<?=$qala['id'];?>">
					<img src="<?=$qala['images'];?>" style="width: 100%" alt="<?=$qala['name'];?>">
					<div class="caption text-center">
						<p><?=$qala['name'];?></p>
					</div>
				</a>
			</div>
		</div>
	<?php endwhile; ?>
	</div>
<?php endif; ?>
</div>
<?php include 'includes/footer.php'; ?>