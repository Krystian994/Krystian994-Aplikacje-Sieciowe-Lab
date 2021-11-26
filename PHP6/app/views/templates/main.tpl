<!DOCTYPE HTML>
<html lang="pl">
<html>
        <head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="{$conf->app_url}/css/main.css" />
                <link rel="stylesheet" href="{$conf->app_url}/css/style.css">
		<noscript><link rel="stylesheet" href="{$conf->app_url}/css/noscript.css" /></noscript>
	</head>
	<body>
		<div id="page-wrapper">

			<!-- Header -->
				<header id="header">

					<nav id="nav">
						<ul>
							<li><a href="{$conf->app_url}/index.php" class="button primary">Strona Glowna</a></li>
							<li><a href="{$conf->app_url}/app/security/login.php" class="button primary">Zaloguj sie</a></li>
						</ul>
					</nav>
				</header>

		

<header class="header">
		<div id="main" class="wrapper style1" parallax-speed="2" align="center">

					<h1>{$page_title|default:"Tytuł domyślny"}</h1>
					<h2>{$page_header|default:"Tytuł domyślny"}</h2>
					<h3>{$page_description|default:"Opis domyślny"}</h3>
	</div>
</header>
<!-- /Header -->


<!-- Content -->
<main id="main">
	<div class="container">
		{block name=content} Domyślna treść zawartości .... {/block}
	</div>
</main>


						

			<!-- Footer -->
				<footer id="footer">
					<ul class="copyright">
						<li>&copy; Krystian Szewczyk. All rights reserved.</li><li>Widok oparty na stylach i szablonie: <a href="http://html5up.net">HTML5 UP</a></li>
					</ul>
				</footer>

		</div>

		<!-- Scripts -->
			<script src="{$conf->app_url}/js/jquery.min.js"></script>
			<script src="{$conf->app_url}/js/jquery.scrolly.min.js"></script>
			<script src="{$conf->app_url}/js/jquery.dropotron.min.js"></script>
			<script src="{$conf->app_url}/js/jquery.scrollex.min.js"></script>
			<script src="{$conf->app_url}/js/browser.min.js"></script>
			<script src="{$conf->app_url}/js/breakpoints.min.js"></script>
			<script src="{$conf->app_url}/js/util.js"></script>
			<script src="{$conf->app_url}/js/main.js"></script>

	</body>
</html>