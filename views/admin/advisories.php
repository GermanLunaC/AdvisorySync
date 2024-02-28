<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<!-- My CSS -->
	<link rel="stylesheet" href="../styles/admin/config.css">
	<title>AdminHub</title>
    <script src="https://unpkg.com/counterup2@2.0.2/dist/index.js">	</script>
    <script type='module' src="../utils/admin/lenguage.js">	</script>
</head>
<body>

    <?php
        include('views/templates/admin/sidebar.php')
    ?>

	<!-- CONTENT -->
	<section id="content">

        <?php
            include('views/templates/admin/navbar.php')
        ?>

		<!-- MAIN -->
		<main>
			<div class="head-title">
				<div class="left">
					<h1 data-title>Panel</h1>
				</div>
			</div>

			<ul class="box-info">
				<li>
					<i class='bx bxs-calendar-check' ></i>
					<span class="text">
						<h3 class='counter'>1020</h3>
						<p data-allAdvisory>Categoria mas usada</p>
					</span>
				</li>
			</ul>

			<div class="table-data">
				<div class="order">
					<div class="head">
						<h3 data-recent>Advisories</h3>
					</div>
					<table>
						<thead>
							<tr>
								<th data-user >Id</th>
								<th data-date >Title</th>
								<th data-status>Date</th>
								<th data-status>Category</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>
									<p>1</p>
								</td>
								<td>
									<p>Derivadas</p>
								</td>
								<td>
									10-2-2000
								</td>
								<td>
									<p>Matematicas</p>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</main>
		<!-- MAIN -->
		
	</section>
	<!-- CONTENT -->
	

	<script src="../utils/admin/script.js"></script>
	<script src="../utils/admin/counter.js"></script>
</body>
</html>