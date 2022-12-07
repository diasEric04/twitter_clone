<div class="col-md-3">
	<div class="perfil">
		<div class="perfilTopo">

		</div>

		<div class="perfilPainel">
					
			<div class="row mt-2 mb-2">
				<div class="col mb-2">
					<span class="perfilPainelNome"><?= $this->view->dados_usuarios['nome'] ?></span>
				</div>
			</div>

			<div class="row mb-2">

				<div class="col">
					<span class="perfilPainelItem">Tweets</span><br />
					<span class="perfilPainelItemValor"><?= $this->view->dados_usuarios['tweets'] ?></span>
				</div>

				<div class="col">
					<span class="perfilPainelItem">Seguindo</span><br />
					<span class="perfilPainelItemValor"><?= $this->view->dados_usuarios['seguindo'] ?></span>
				</div>

				<div class="col">
					<span class="perfilPainelItem">Seguidores</span><br />
					<span class="perfilPainelItemValor"><?= $this->view->dados_usuarios['seguidores'] ?></span>
				</div>

			</div>
		</div>
	</div>
</div>