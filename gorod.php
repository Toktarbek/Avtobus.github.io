<?php
require_once 'core/init.php';
include 'includes/head.php';
include 'includes/navigation.php';

if (isset($_GET['gor'])) {
	$id=sanitize($_GET['gor']);
	$id=(int)$id;
}
else{
	$id=0;
}
$result=$db->query("SELECT * FROM gorod WHERE id=$id");
$gorod=mysqli_fetch_assoc($result);
?>
<div class="container">
	<div class="page-header text-center">
		<h3>Расписание автобусов по города <?=$gorod['name'];?> </h3>
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
				
				$quer="SELECT a.name AS stan1,b.name AS stan2,r.date_otpr AS otpr,r.time_otpr AS otime,r.date_pr AS pr,r.time_pr AS ptime,r.cena AS baga,r.perevoz AS pre,r.grapik AS gra
				FROM gorod a,gorod b,reys r
				WHERE a.id=r.id_stanOtpr AND b.id=id_stanPr AND r.id_stanOtpr='$id'";

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

<?php include 'includes/footer.php'; ?>