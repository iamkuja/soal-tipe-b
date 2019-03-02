<font size="5"><span class="glyphicon glyphicon-list"></span> Data Penduduk</font>
<div class="row">
<div class="col-md-2 top-10">
<form method="post">
<select name="filter" class="form-control">
<option selected="">Pilih Daerah</option>
<option value="0">Semua Daerah</option>
<?php
$query_filter = mysqli_query($conn,"SELECT * FROM regions");
while ($data_filter = mysqli_fetch_array($query_filter)) {
?>
	<option value="<?php echo $data_filter['id'] ?>"><?php echo $data_filter["name"]; ?></option>
<?php
}
?>
</select>
<button type="submit" class="btn btn-success" style="margin-top: 5px" name="filters">Filter</button>
</form>
</div>
</div>
	<?php
	if($_GET["action"] == "delete") {
		$id = intval($_GET["id"]);
		$query_delete = mysqli_query($conn,"DELETE FROM person WHERE id = '$id'");
		if(!$query_delete) {
	?>
			<script>
				alert('Error! Failed to delete records!');
				window.location = '?menu=data_penduduk';
			</script>
	<?php
		} else {
	?>
			<script>
				alert('Success! Records deleted!');
				window.location = '?menu=data_penduduk';
			</script>
	<?php
		}
	} elseif($_GET["action"] == "edit") {
		$id = intval($_GET["id"]);
		$query_edit = mysqli_query($conn,"SELECT * FROM person WHERE id = '$id'");
		$data_edit = mysqli_fetch_array($query_edit);
	?>
		<div class="row">
		<div class="col-md-4 top-10">
		<form method="post">
		<div class="form-group">
			<label for="name">Nama Penduduk</label>
			<input id="name" name="name" type="text" class="form-control" placeholder="nama penduduk" value="<?php echo $data_edit['name']; ?>" autocomplete="off" required>
		</div>
		<div class="form-group">
			<label for="gaji">Gaji</label>
			<input id="gaji" name="gaji" type="text" class="form-control" placeholder="gaji" value="<?php echo $data_edit['income']; ?>" autocomplete="off" required>
		</div>
		<button type="submit" class="btn btn-success" name="update">Edit</button>
		</form>
		</div>
		</div>
	<?php
		if(isset($_POST["update"])) {
			$name = $_POST["name"];
			$income = $_POST["gaji"];
			$query_update = mysqli_query($conn,"UPDATE person SET name = '$name', income = '$income' WHERE id = '$id'");
			if(!$query_update) {
	?>
			<script>
				alert('Error! Failed to edit records!');
				window.location = '?menu=data_penduduk';
			</script>
	<?php
			} else {
	?>
			<script>
				alert('Success! Records edited!');
				window.location = '?menu=data_penduduk';
			</script>
	<?php
			}
		}
	} elseif($_GET["action"] == "add") {
	?>
		<div class="row">
		<div class="col-md-4 top-10">
		<form method="post">
		<div class="form-group">
			<label for="name">Nama Penduduk</label>
			<input id="name" type="text" name="name" placeholder="nama penduduk" class="form-control" autocomplete="off" required="">
		</div>
		<div class="form-group">
			<label for="gaji">Gaji</label>
			<input id="gaji" type="text" name="gaji" placeholder="gaji" class="form-control" autocomplete="off" required="">
		</div>
		<div class="form-group">
			<label for="daerah">Daerah</label>
			<select id="daerah" name="daerah" class="form-control">
			<?php
				$query_pilih_daerah = mysqli_query($conn,"SELECT * FROM regions");
				while($data_pilih_daerah = mysqli_fetch_array($query_pilih_daerah)) {
			?>
				<option value="<?php echo $data_pilih_daerah['id']; ?>"><?php echo $data_pilih_daerah["name"]; ?></option>
			<?php
				}
			?>
			</select>
		</div>
		<div class="form-group">
		<label for="alamat">Alamat</label>
		<textarea id="alamat" class="form-control" name="alamat"></textarea>
		</div>
		<button type="submit" class="btn btn-success" name="add">Submit</button>
		</form>
		</div>
		</div>
	<?php
		if(isset($_POST["add"])) {
			$name = $_POST["name"];
			$income = $_POST["gaji"];
			$regions = $_POST["daerah"];
			$address = $_POST["alamat"];
			$date = date("Y-m-d");
			$query_admin = mysqli_query($conn,"SELECT * FROM users WHERE email = '$email'");
			$data_admin = mysqli_fetch_array($query_admin);
			$created_by = $data_admin["id"];
			$query_add = mysqli_query($conn,"INSERT INTO person(id,name,region_id,address,income,created_at,created_by) VALUES(NULL,'$name','$regions','$address','$income','$date','$created_by')");
			if(!$query_add) {
	?>
			<script>
				alert('Error! Failed to add records!');
				window.location = '?menu=data_penduduk';
			</script>
	<?php
			} else {
	?>
			<script>
				alert('Success! Records added!');
				window.location = '?menu=data_penduduk';
			</script>
	<?php
			}
		}
	}
	?>
<div class="table-responsive">
	<table class="table table-striped table-bordered top-10">
		<thead>
			<tr>
				<th class="text-center">#</th>
				<th class="text-center">Nama Penduduk</th>
				<th class="text-center">Gaji</th>
				<th class="text-center">Daerah</th>
				<th class="text-center">Action</th>
			</tr>
		</thead>
		<tbody>
		<?php
        	$halaman = 5;
        	$page = isset($_GET["p"]) ? (int)$_GET["p"] : 1;
        	$mulai = ($page>1) ? ($page * $halaman) - $halaman : 0;
			$querys_penduduk = mysqli_query($conn,"SELECT * FROM person");
			$total = mysqli_num_rows($querys_penduduk);
			$pages = ceil($total/$halaman);
			if(isset($_POST["filters"])) {
				$filter = $_POST["filter"];
				if(!$filter == 0) {
					$query_penduduk = mysqli_query($conn,"SELECT * FROM person WHERE region_id = '$filter' LIMIT $mulai, $halaman");
				} else {
					$query_penduduk = mysqli_query($conn,"SELECT * FROM person LIMIT $mulai, $halaman");
				}
			} else {
				$query_penduduk = mysqli_query($conn,"SELECT * FROM person LIMIT $mulai, $halaman");
			}
			// jika tidak ada data
			if(mysqli_num_rows($query_penduduk) == 0) {
		?>
			<tr>
				<td>No records here!</td>
			</tr>
		<?php
			} else {
				$no = 1;
				// melooping data
				while($data_penduduk = mysqli_fetch_array($query_penduduk)) {
					$cari_id = $data_penduduk["region_id"];
					$id = intval($data_penduduk["id"]);
					$query_daerah = mysqli_query($conn,"SELECT * FROM regions WHERE id = '$cari_id'");
					$data_daerah = mysqli_fetch_array($query_daerah);
		?>
			<tr>
				<td class="text-center"><?php echo $no++ ?></td>
				<td class="text-center"><?php echo $data_penduduk["name"]; ?></td>
				<td class="text-center"><?php echo "Rp ".number_format($data_penduduk["income"],2,',','.'); ?></td>
				<td class="text-center"><?php echo $data_daerah["name"]; ?></td>
				<td class="text-center">
					<a href="?menu=data_penduduk&action=add"><span class="glyphicon glyphicon-plus"></span></a>
					<a href="?menu=data_penduduk&action=edit&id=<?php echo $id; ?>"><span class="glyphicon glyphicon-pencil"></span></a>
					<a href="?menu=data_penduduk&action=delete&id=<?php echo $id; ?>"><span class="glyphicon glyphicon-trash"></span></a>
				</td>
			</tr>
		<?php
			}
		}
		?>
		</tbody>
	</table>
</div>
	        <?php
	        // pagination
        	echo "<ul class='pagination'>";
        		for($i=1; $i<=$pages;$i++) {
        			echo "<li><a style='text-style:none; color: black' href='?menu=data_penduduk&p=$i'>$i</a></li>";
        		}
        	echo "</ul>";
        ?>