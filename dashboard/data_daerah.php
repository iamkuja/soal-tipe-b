<font size="5"><span class="glyphicon glyphicon-list"></span> Data Daerah</font>
	<?php
	if($_GET["action"] == "delete") {
		$id = intval($_GET["id"]);
		$query_delete = mysqli_query($conn,"DELETE FROM regions WHERE id = '$id'");
		if(!$query_delete) {
	?>
			<script>
				alert('Error! Failed to delete records!');
				window.location = 'index.php';
			</script>
	<?php
		} else {
	?>
			<script>
				alert('Success! Records deleted!');
				window.location = 'index.php';
			</script>
	<?php
		}
	} elseif($_GET["action"] == "edit") {
		$id = intval($_GET["id"]);
		$query_edit = mysqli_query($conn,"SELECT * FROM regions WHERE id = '$id'");
		$data_edit = mysqli_fetch_array($query_edit);
	?>
		<div class="row">
		<div class="col-md-4 top-10">
		<form method="post">
		<div class="form-group">
			<label for="name">Nama Daerah</label>
			<input id="name" name="name" type="text" class="form-control" placeholder="nama daerah" value="<?php echo $data_edit['name']; ?>" autocomplete="off" required>
		</div>
		<button type="submit" class="btn btn-success" name="update">Edit</button>
		</form>
		</div>
		</div>
	<?php
		if(isset($_POST["update"])) {
			$name = $_POST["name"];
			$query_update = mysqli_query($conn,"UPDATE regions SET name = '$name' WHERE id = '$id'");
			if(!$query_update) {
	?>
			<script>
				alert('Error! Failed to edit records!');
				window.location = 'index.php';
			</script>
	<?php
			} else {
	?>
			<script>
				alert('Success! Records edited!');
				window.location = 'index.php';
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
			<label for="name">Nama Daerah</label>
			<input id="name" type="text" name="name" placeholder="nama daerah" class="form-control" autocomplete="off" required="">
		</div>
		<button type="submit" class="btn btn-success" name="add">Submit</button>
		</form>
		</div>
		</div>
	<?php
		if(isset($_POST["add"])) {
			$name = $_POST["name"];
			$date = date("Y-m-d");
			$query_admin = mysqli_query($conn,"SELECT * FROM users WHERE email = '$email'");
			$data_admin = mysqli_fetch_array($query_admin);
			$created_by = $data_admin["id"];
			$query_add = mysqli_query($conn,"INSERT INTO regions(id,name,created_at,created_by) VALUES(NULL,'$name','$date','$created_by')");
			if(!$query_add) {
	?>
			<script>
				alert('Error! Failed to add records!');
				window.location = 'index.php';
			</script>
	<?php
			} else {
	?>
			<script>
				alert('Success! Records added!');
				window.location = 'index.php';
			</script>
	<?php
			}
		}
	}
	?>
<div class="table-responsive top-10">
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th class="text-center">#</th>
				<th class="text-center">Nama Daerah</th>
				<th class="text-center">Jumlah Penduduk</th>
				<th class="text-center">Total Pendapatan</th>
				<th class="text-center">Rata-rata Pendapatan</th>
				<th class="text-center">Status</th>
				<th class="text-center">Action</th>
			</tr>
		</thead>
		<tbody>
		<?php
        	$halaman = 5;
        	$page = isset($_GET["p"]) ? (int)$_GET["p"] : 1;
        	$mulai = ($page>1) ? ($page * $halaman) - $halaman : 0;
			$querys_daerah = mysqli_query($conn,"SELECT * FROM regions");
			$total = mysqli_num_rows($querys_daerah);
			$pages = ceil($total/$halaman);
			$query_daerah = mysqli_query($conn,"SELECT * FROM regions LIMIT $mulai, $halaman");
			// jika tidak ada data
			if(mysqli_num_rows($query_daerah) == 0) {
		?>	<tr>
				<td>No records here!</td>
			</tr>
		<?php
			} else {
				$no = 1;
				// melooping data
				while($data_daerah = mysqli_fetch_array($query_daerah)) {
					$cari_id = intval($data_daerah["id"]);
					$query_penduduk = mysqli_query($conn,"SELECT * FROM person WHERE region_id = '$cari_id'");
					// menghitung penduduk
					$hitung_penduduk = mysqli_num_rows($query_penduduk);
					// menghitung pendapatan penduduk dan rata rata pendapatan
					$cek_penduduk = mysqli_fetch_array($query_penduduk);
					if($hitung_penduduk < 2) {
						$cek_total = mysqli_query($conn,"SELECT SUM(income) AS total FROM person WHERE region_id = '$cari_id'");
						$data_pendapatan = mysqli_fetch_array($cek_total);
						$pendapatan_penduduk = $data_pendapatan["total"];
						$rata_rata = $pendapatan_penduduk;
					} else {
						$cek_total = mysqli_query($conn,"SELECT SUM(income) AS total FROM person WHERE region_id = '$cari_id'");
						$data_pendapatan = mysqli_fetch_array($cek_total);
						$pendapatan_penduduk = $data_pendapatan["total"];
						$rata_rata = $pendapatan_penduduk/$hitung_penduduk;
					}
		?>
			<tr>
				<td class="text-center"><?php echo $no++; ?></td>
				<td class="text-center"><?php echo $data_daerah["name"]; ?></td>
				<td class="text-center"><?php echo $hitung_penduduk; ?></td>
				<td class="text-center"><?php echo "Rp. ".number_format($pendapatan_penduduk,2,',','.'); ?></td>
				<td class="text-center"><?php echo "Rp. ".number_format($rata_rata,2,',','.'); ?></td>
				<td class="text-center">
				<?php if($rata_rata < 1700000) { ?>
					<span class="label label-danger">Sangat Kurang</span>
				<?php } elseif($rata_rata > 2200000) { ?>
					<span class="label label-success">Cukup</span>
				<?php } elseif($rata_rata > 1700000 OR $rata_rata < 2200000) { ?>
					<span class="label label-warning">Kurang</span>
				<?php } ?>
				</td>
				<td class="text-center">
					<a href="?action=add"><span class="glyphicon glyphicon-plus"></span></a>
					<a href="?action=edit&id=<?php echo $cari_id; ?>"><span class="glyphicon glyphicon-pencil"></span></a>
					<a href="?action=delete&id=<?php echo $cari_id; ?>"><span class="glyphicon glyphicon-trash"></span></a>
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
        			echo "<li><a style='text-style:none; color: black' href='?p=$i'>$i</a></li>";
        		}
        	echo "</ul>";
        ?>