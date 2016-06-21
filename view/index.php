<!doctype html>
<html>
<?php
	$head = array("title" => "Index");
	$this->render("ext/head.php", $head);
?>
	<body>
		<?php $this->render("ext/menu.php");?>
		<?php $this->render("ext/header.php");?>
		<div id="fh5co-main">
			<div class="container">
				<div class="row">
					<div class="col-md-8 col-md-offset-2 text-center fh5co-lead-wrap">
						<div class="row">
							<div class="col-md-12 text-center">
								<img src="http://i.imgur.com/yr9iecI.png" alt="ZERO" height="70">
								<p class="text-justify larger topspace-md">
									ZERO é um projeto feito para todos os amantes de música que adoram acompanhar seus próprios habitos músicais.
									<br/>
									Com ele são gerados gráficos semanais, listando os artistas, álbuns e músicas mais ouvidos durante o período, podendo acompanhar o desempenho desses semana por semana, e visualizar as mudanças e descobertas de seus artistas favoritos com o passar do tempo.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php $this->render("ext/footer.php");?>
		</div>
	</body>
</html>