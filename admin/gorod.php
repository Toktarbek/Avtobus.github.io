<?php
require_once '../core/init.php';
include 'includes/head.php';
include 'includes/navigation.php';

if (isset($_GET['delete'])) {
	$id_del=sanitize($_GET['delete']);
	$db->query("DELETE FROM gorod WHERE id='$id_del'");
	header('location:gorod.php');
}

$dbpath='';
if (isset($_GET['add']) || isset($_GET['edit'])):
	$title=((isset($_POST['title']) && $_POST['title'] !='')?sanitize($_POST['title']):'');
	$saved_image='';

	if (isset($_GET['edit'])) {
		$edit_id=(int)$_GET['edit'];
		$sql1=("SELECT* FROM gorod WHERE id='$edit_id'");
		$result2=$db->query($sql1);
		$port1=mysqli_fetch_assoc($result2);

			if (isset($_GET['delete_image'])) {
				$image=$port1['images'];
				$image_url=$_SERVER['DOCUMENT_ROOT'].$image;
				unlink($image_url);
				unset($image);
				$db->query("UPDATE gorod SET images='$image' WHERE id='$edit_id'");
				header('Location: gorod.php?edit='.$edit_id);
			}

		$title=(isset($_POST['title']) && $_POST['title'] !='')?sanitize($_POST['title']):$port1['name'];
		$saved_image=($port1['images'] !='')?$port1['images']:'';
		$dbpath=$saved_image;
	}

	if ($_POST) {
		$errors=array();
		$allowed=array('png','PNG','jpg','jpeg','gif');
		if ($_POST['title']=='') {
			$errors[]='Запишите названия городов.';
		}
		
		if (!empty($_FILES)) {
			$name=$_FILES['photo']['name'];
			$nameArray=explode('.', $name);
			$fileName=$nameArray[0];
			$fileExt=$nameArray[1];
			$mime=explode('/', $_FILES['photo']['type']);
			$mimeType=$mime[0];
			$mimeExt=$mime[1];
			$tmpLoc=$_FILES['photo']['tmp_name'];
			$fileSize=$_FILES['photo']['size'];
			$uploadName=md5(microtime()).'.'.$fileExt;
			$uploadPath=BASEURL.'images/'.$uploadName;
			$dbpath='/avtobus/images/'.$uploadName;
			if ($mimeType!='image') {
					$errors[]='Сурет болу керек.';
				}
				if (!in_array($fileExt, $allowed)) {
					$errors[]='Бұл файл png, jpg, jpeg және gif болу керек.';
				}
				if ($fileSize>15000000) {
					$errors[]='Бұл файл 15MB тан аспау керек.';
				}
				if ($fileExt !=$mimeExt && ($mimeExt=='jpeg' && $fileExt !='jpg')) {
					$errors[]='Файлдің кеңейтілуі сәйкес келмейді.';
				}
		}

		if (!empty($errors)) {
			$display=display_errors($errors);
		}else{
			move_uploaded_file($tmpLoc, $uploadPath);
			$inserSql="INSERT INTO gorod(name,images) 
			VALUES('$title','$dbpath')";
			if (isset($_GET['edit'])) {
				$inserSql="UPDATE gorod SET name='$title',images='$dbpath' WHERE id='$edit_id'";
			}
			$db->query($inserSql);
			header('location:gorod.php');
		}
}
?>
<div class="row">
<h2 class="text-center">Добавить новый город</h2><hr>
	<form action="gorod.php?<?=((isset($_GET['edit']))?'edit='.$edit_id:'add=1'); ?>" method="post" enctype="multipart/form-data">
		<div class="form-group col-md-6">
			<label for="title">наименование:</label>
			<input type="text" name="title" id="title" class="form-control" value="<?=$title;?>">
		</div>
		
		<div class="form-group col-md-6">
		<?php if($saved_image !=''): ?>
			<div class="thumbnail col-md-6">
				<img src="<?=$saved_image;?>" style="width: 100%;">
				<a href="gorod.php?delete_image=1&edit=<?=$edit_id;?>" class="pull-right">Delete image</a>
			</div>
		<?php else: ?>
			<label for="photo">Суретін жүктеу:</label>
			<input type="file" name="photo" id="photo" class="form-control">
		<?php endif; ?>
		</div>

		<div class="form-group pull-right" style="margin-right: 15px;">
			<a href="gorod.php" class="btn btn-default pull-right">Cancel</a>
			<input type="submit" value="<?=((isset($_GET['edit']))?' Изменить':'Добавить');?>" class="btn btn-success pull-right">
		</div> <div class="clearfix"></div>
	</form>
</div>
<?php else: 
$result=$db->query("SELECT * FROM gorod");
if (isset($_GET['tan'])) {
	$id=(int)$_GET['id'];
	$tan=(int)$_GET['tan'];
	$tansql = "UPDATE gorod SET tan = '$tan' WHERE id = '$id'";
	$db->query($tansql);
	header('Location: gorod.php');
}
?>
<div class="rows">
	<h2 class="text-center">Города</h2>
	<a href="gorod.php?add=1" class="btn btn-default btn-success pull-right" id="batyrma" class="clearfix">Добавить</a><hr>
	<table class="table table-bordered table-condensed table-bordered">
		<thead><th></th><th>Город</th><th>Фото</th><th></th></thead>
		<tbody>
		<?php while($gorod=mysqli_fetch_assoc($result)): 
		?>
			<tr>
				<td>
					<a href="gorod.php?edit=<?=$gorod['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
					<a href="gorod.php?delete=<?=$gorod['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a>
				</td>
				<td><?=$gorod['name'];?></td>
				<td><?=$gorod['images'];?></td>
				<td>
					<a href="gorod.php?tan=<?=(($gorod['tan']==0)?'1':'0');?>&id=<?=$gorod['id'];?>" class="btn btn-default btn-xs">
						<span class="glyphicon glyphicon-<?=(($gorod['tan']==1)?'minus':'plus');?>"></span>
					</a>&nbsp <?=(($gorod['tan']==1)?'Предложить':'');?>
				</td>
			</tr>
		<?php endwhile; ?>
		</tbody>
	</table>
</div>
<?php 
endif;
include 'includes/footer.php';
?>