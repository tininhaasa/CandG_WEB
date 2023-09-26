<?php
$url      = $this->helpers['URLHelper']->getURL();
$location = $this->helpers['URLHelper']->getLocation();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<meta name="author" content="FishingBook">

	<title><?php $this->helpers['URLHelper']->getTitle(); ?></title>

	<!-- Styles -->
	<link rel="stylesheet" href="<?= $url ?>/assets/css/loader.css">
	<link rel="stylesheet" href="<?= $url ?>/assets/css/font.css">
	<link rel="stylesheet" href="<?= $url ?>/assets/css/site/style.css">
	<?php $this->helpers['URLHelper']->getStyles(); ?>
	<link rel="shortcut icon" href="<?php echo $url ?>/assets/img/favicon.png" type="image/x-icon">


</head>

<body>
	<div id="loader-overlay" style="display:none">
		<span class="loader loader-circles"></span>
	</div>

	<header style="background-color:transparent">
		<div class="background-image"></div>
		<nav class="light">
			<div class="menu-mobile">
			</div>

			<div class="div-back" onclick="window.history.back();">
				<button type="button" class="btn-detail pointer btn-back">
					<i class="fas fa-angle-double-left"></i>
				</button>
				<p class="text-back font-black">
					VOLTAR
				</p>
			</div>

			<div class="menu-logo mt-2">
				<div class="location">

				</div>
				<div class="logo login">
					<a href="<?= $url ?>/">
						<img src="<?= $url ?>/assets/img/logo/logo.png" alt="">
					</a>
				</div>
				<div class="profile">

				</div>
			</div>
		</nav>
	</header>

	<main>
		<?php require $file; ?>

		<!-- <button class="btn-chat">
				<i class="fa-solid fa-comments"></i>
			</button> -->
	</main>

	<script type="text/javascript">
		var PATH = "<?php echo $url; ?>";
		var Helpers = {};

		var Parameters = window.location.href.split("/");
		Parameters = Parameters.reverse();
	</script>
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-M6S1VQ711J"></script>
	<script>
		window.dataLayer = window.dataLayer || [];

		function gtag() {
			dataLayer.push(arguments);
		}
		gtag('js', new Date());

		gtag('config', 'G-M6S1VQ711J');
	</script>
	<!-- Scripts -->
	<script defer type="text/javascript" src="<?= $url ?>/assets/libs/jquery/jquery.min.js"></script>
	<script defer type="text/javascript" src="<?= $url ?>/assets/libs/jquery/jquery.mask.min.js"></script>
	<script defer type="text/javascript" src="<?= $url ?>/assets/libs/jquery/jquery.maskMoney.min.js"></script>
	<script defer type="text/javascript" src="<?= $url ?>/assets/js/helpers/helpers.js"></script>
	<?php $this->helpers['URLHelper']->getScripts(); ?>
	<script defer type="text/javascript" src="<?= $url ?>/assets/js/site/translate.min.js"></script>
</body>

</html>