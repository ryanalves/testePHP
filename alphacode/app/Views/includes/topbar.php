<?php
if (!isset($route)) {
  $route = '';
}

?>
<nav class="navbar navbar-expand-lg bg-body-secondary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Alphacode</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link  <?= $route == 'vagas' ? 'active' : '' ?>" aria-current="page" href="/">Vagas</a>
        </li>

        <?php if ($usuario != null && $usuario['candidato_id'] == null) : ?>
          <li class="nav-item" id="usuariosItem">
            <a class="nav-link  <?= $route == 'usuarios' ? 'active' : '' ?>" aria-current="page" href="/usuarios">Usuários</a>
          </li>
        <?php endif; ?>

        <?php if ($usuario != null && $usuario['candidato_id'] != null) : ?>
          <li class="nav-item" id="candidaturasItem">
            <a class="nav-link  <?= $route == 'candidaturas' ? 'active' : '' ?>" aria-current="page" href="/candidaturas">Candidaturas</a>
          </li>
        <?php endif; ?>

      </ul>
      <?php if ($usuario == null) : ?>
        <a class="btn btn-outline-primary" href="/login">Login</a>
      <?php else : ?>
        <a class="btn btn-outline-danger" href="/logout">Logout</a>
      <?php endif; ?>
    </div>
  </div>
</nav>
