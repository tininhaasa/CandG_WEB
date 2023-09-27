<?php $url = $this->helpers['URLHelper']->getUrl(); ?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<meta name="author" content="FishingBooking">
	<meta name="description" content="O app de pesca feito pra você!">
	<meta name="keywords" content="pescaria, campeonato, hospedage, pesca, peixe, hotel, barco, equipamentos, equipamento">

	<title><?php $this->helpers['URLHelper']->getTitle(); ?></title>

	<!-- Styles -->
	<link rel="stylesheet" href="<?= $url ?>/assets/css/loader.css">
	<link rel="stylesheet" href="<?= $url ?>/assets/css/font.css">
	<link rel="stylesheet" href="<?= $url ?>/assets/css/site/style.css">
	<link rel="stylesheet" href="<?= $url ?>/assets/libs/bootstrap/css/bootstrap.min.css">
	<?php $this->helpers['URLHelper']->getStyles(); ?>
	<link rel="shortcut icon" href="<?php echo $url ?>/assets/img/favicon.png" type="image/x-icon">

</head>
<body>
	<div id="loader-overlay" style="display:none">
		<span class="loader loader-circles"></span>
	</div>

	<div id="google_translate_element"></div>

	<header>
	</header>

	<main>

		<?php require $file; ?>

	</main>

	<footer>

		<nav class="navbar bg-dark">
			<div class="row w-100">
				<div class="col-6 d-flex justify-content-start align-items-center"><p class="text-light">Castings and Grandaxes © Todos os direitos reservados</p></div>
				<div class="col-6 d-flex justify-content-end align-items-center flex-row ">
					<a class="mr-2 link-underline link-underline-opacity-0 link-light" href="<?= $url ?>/termos">Politica de Privacidade</a>
					<a  class="mr-2 link-underline link-underline-opacity-0 link-light"  href="<?= $url ?>/conduta">Código de Conduta</a>
					<a  class="mr-1 link-underline link-underline-opacity-0 link-light"  href="<?= $url ?>/segurança">Política de segurança</a>
				</div>
			</div>
		</nav>
	</footer>

	<!-- Scripts -->
	<script type="text/javascript">
		var PATH = "<?php echo $url; ?>";
		var Helpers = {};
		var Parameters = window.location.href.split("/");
		Parameters = Parameters.reverse();
	</script>
	<script defer type="text/javascript" src="<?= $url ?>/assets/libs/jquery/jquery.min.js"></script>
	<script defer type="text/javascript" src="<?= $url ?>/assets/libs/bootstrap/js/bootstrap.min.css"></script>


	<script defer type="text/javascript" src="<?= $url ?>/assets/js/helpers/helpers.js"></script>

	<?php $this->helpers['URLHelper']->getScripts(); ?>
	<script defer type="text/javascript" src="<?= $url ?>/assets/js/site/menu.js"></script>

</body>

</html>