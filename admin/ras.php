<?php
require_once '../core/init.php';
include 'includes/head.php';
include 'includes/navigation.php';

$dd=date("Y-m-d");
if (isset($_GET['delete'])) {
	$id_del=sanitize($_GET['delete']);
	$db->query("DELETE FROM reys WHERE id='$id_del'");
	header('location:ras.php');
}

if (isset($_GET['add']) || isset($_GET['edit'])):
	$result1=$db->query("SELECT * FROM gorod");
	$result2=$db->query("SELECT * FROM gorod");
	$stan1=((isset($_POST['stan1']) && $_POST['stan1'] !='')?sanitize($_POST['stan1']):'');
	$dOtpr=((isset($_POST['dOtpr']) && $_POST['dOtpr'] !='')?sanitize($_POST['dOtpr']):'');
	$dTime=((isset($_POST['dTime']) && $_POST['dTime'] !='')?sanitize($_POST['dTime']):'');
	$stan2=((isset($_POST['stan2']) && $_POST['stan2'] !='')?sanitize($_POST['stan2']):'');

	$dpr=((isset($_POST['dpr']) && $_POST['dpr'] !='')?sanitize($_POST['dpr']):'');
	$tpr=((isset($_POST['tpr']) && $_POST['tpr'] !='')?sanitize($_POST['tpr']):'');
	$cena=((isset($_POST['cena']) && $_POST['cena'] !='')?sanitize($_POST['cena']):'');
	$perv=((isset($_POST['perv']) && $_POST['perv'] !='')?sanitize($_POST['perv']):'');
	$grap=((isset($_POST['grap']) && $_POST['grap'] !='')?sanitize($_POST['grap']):'');

	if (isset($_GET['edit'])) {
		$edit_id=(int)$_GET['edit'];
		$eresult3=$db->query("SELECT * FROM reys WHERE id='$edit_id'");
		$raspy=mysqli_fetch_assoc($eresult3);

		$stan1=(isset($_POST['stan1']) && $_POST['stan1'] !='')?sanitize($_POST['stan1']):$raspy['id_stanOtpr'];
		$stan2=(isset($_POST['stan2']) && $_POST['stan2'] !='')?sanitize($_POST['stan2']):$raspy['id_stanPr'];
		$dOtpr=((isset($_POST['dOtpr']) && $_POST['dOtpr'] !='')?sanitize($_POST['dOtpr']):$raspy['date_otpr']);
		$dTime=((isset($_POST['dTime']) && $_POST['dTime'] !='')?sanitize($_POST['dTime']):$raspy['time_otpr']);

		$dpr=((isset($_POST['dpr']) && $_POST['dpr'] !='')?sanitize($_POST['dpr']):$raspy['date_pr']);
		$tpr=((isset($_POST['tpr']) && $_POST['tpr'] !='')?sanitize($_POST['tpr']):$raspy['time_pr']);
		$cena=((isset($_POST['cena']) && $_POST['cena'] !='')?sanitize($_POST['cena']):$raspy['cena']);
		$perv=((isset($_POST['perv']) && $_POST['perv'] !='')?sanitize($_POST['perv']):$raspy['perevoz']);
		$grap=((isset($_POST['grap']) && $_POST['grap'] !='')?sanitize($_POST['grap']):$raspy['grapik']);
	}

	if ($_POST) {
		$errors=array();
		$required=array('stan1','stan2','dTime','tpr','cena','perv','grap');

		foreach ($required as $field) {
			if($_POST[$field]==''){
				$errors[]='Заполните все поля.';
				break;
			}
		}

		if (!empty($errors)) {
			echo display_errors($errors);
		}else{
			
			$insertSql="INSERT INTO reys(id_stanOtpr,date_otpr,time_otpr,id_stanPr,date_pr,time_pr,cena,perevoz,grapik) 
			VALUES('$stan1','$dOtpr','$dTime','$stan2','$dpr','$tpr','$cena','$perv','$grap')";
			if (isset($_GET['edit'])) {
			$insertSql="UPDATE reys SET id_stanOtpr='$stan1',date_otpr='$dOtpr',time_otpr='$dTime',id_stanPr='$stan2',date_pr='$dpr',time_pr='$tpr',cena='$cena',perevoz='$perv',grapik='$grap' WHERE id='$edit_id'";
			}

			$db->query($insertSql);
			header('location: ras.php');
		}
	}

?>
<div class="rows">
<h2 class="text-center">Добавить новый информация</h2><hr>
	<form action="ras.php?<?=((isset($_GET['edit']))?'edit='.$edit_id:'add=1'); ?>" method="post" enctype="multipart/form-data">
		<div class="form-group col-md-6">
			<label for="stan1">Станция отправления:</label>
			<select id="stan1" name="stan1" class="form-control">
				<option value=""<?=(($stan1=='')?' selected':'');?>></option>
				<?php while($p=mysqli_fetch_assoc($result1)): ?>
					<option value="<?=$p['id'];?>"<?=(($stan1==$p['id'])?' selected':'');?>>
					<?=$p['name'];?>
					</option>
				<?php endwhile; ?>
			</select>
		</div>

		<div class="form-group col-md-6">
			<label for="stan2">Станция прибытия:</label>
			<select id="stan2" name="stan2" class="form-control">
				<option value=""<?=(($stan2=='')?' selected':'');?>></option>
				<?php while($ss=mysqli_fetch_assoc($result2)): ?>
					<option value="<?=$ss['id'];?>"<?=(($stan2==$ss['id'])?' selected':'');?>>
					<?=$ss['name'];?>
					</option>
				<?php endwhile; ?>
			</select>
		</div>

		<div class="form-group col-md-6">
			<label for="dOtpr">День отправления:</label>
			<input type="date" name="dOtpr" id="dOtpr" class="form-control" value="<?=(isset($_GET['add']))?$dd:$dOtpr;?>" min="<?=$dd;?>">
		</div>

		<div class="form-group col-md-6">
			<label for="dpr">День прибытия:</label>
			<input type="date" name="dpr" id="dpr" class="form-control" value="<?=(isset($_GET['add']))?$dd:$dpr;?>" min="<?=$dd;?>">
		</div>

		<div class="form-group col-md-6">
			<label for="dTime">Время отправления:</label>
			<input type="text" name="dTime" id="dTime" class="form-control" value="<?=$dTime;?>">
		</div>
		<div class="form-group col-md-6">
			<label for="tpr">Время прибытия:</label>
			<input type="text" name="tpr" id="tpr" class="form-control" value="<?=$tpr;?>">
		</div>

		<div class="form-group col-md-6">
			<label for="cena">Цена билета:</label>
			<input type="text" name="cena" id="cena" class="form-control" value="<?=$cena;?>">
		</div>
		<div class="form-group col-md-6">
			<label for="perv">Перевозчик:</label>
			<input type="text" name="perv" id="perv" class="form-control" value="<?=$perv;?>">
		</div>

		<div class="form-group col-md-6">
			<label for="grap">График движения:</label>
			<input type="text" name="grap" id="grap" class="form-control" value="<?=$grap;?>">
		</div>

		<div class="form-group pull-right" style="margin:30px 15px 0 0;">
			<a href="ras.php" class="btn btn-default pull-right" style="margin-left:10px; ">Cancel</a>
			<input type="submit" value="<?=((isset($_GET['edit']))?' Изменить':'Добавить');?>" class="btn btn-success pull-right">
		</div> <div class="clearfix"></div>
	</form>
</div>
<?php else:
 $result=$db->query("SELECT * FROM reys");
?>
<div class="rows">
	<h2 class="text-center">Расписание</h2>
	<a href="ras.php?add=1" class="btn btn-default btn-success pull-right" id="batyrma" class="clearfix">Добавить</a><hr>
	<table class="table table-bordered table-condensed table-bordered">
		<thead><th></th><th>ID станция отправления</th><th>Время отправления</th><th>ID станция прибытия</th><th>Время прибытия</th><th>Цена билета</th><th>Перевозчик</th><th>График движения</th></thead>
		<tbody>
		<?php while($reys=mysqli_fetch_assoc($result)): 
		?>
			<tr>
				<td>
					<a href="ras.php?edit=<?=$reys['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
					<a href="ras.php?delete=<?=$reys['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a>
				</td>
				<td><?=$reys['id_stanOtpr'];?></td>
				<td><?=$reys['date_otpr'].'/'.$reys['time_otpr'];?></td>
				<td><?=$reys['id_stanPr'];?></td>
				<td><?=$reys['date_pr'].'/'.$reys['time_pr'];?></td>
				<td><?=$reys['cena'];?></td>
				<td><?=$reys['perevoz'];?></td>
				<td><?=$reys['grapik'];?></td>
			</tr>
		<?php endwhile; ?>
		</tbody>
	</table>
</div>
<?php 
endif;
include 'includes/footer.php'; 
?>