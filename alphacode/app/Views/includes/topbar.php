<nav class="navbar navbar-expand-lg bg-body-secondary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Alphacode</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" aria-current="page" href="/">Vagas</a>
        </li>
        <li class="nav-item" id="usuariosItem" style="display: none;">
          <a class="nav-link" aria-current="page" href="/usuarios">Usuários</a>
        </li>
        <li class="nav-item" id="candidaturasItem" style="display: none;">
          <a class="nav-link" aria-current="page" href="/candidaturas">Candidaturas</a>
        </li>
      </ul>
      <a class="btn btn-outline-danger" type="submit" onclick="logout()">Logout</a>
    </div>
  </div>
</nav>

<script>
  function logout() {
    localStorage.removeItem('token');
    window.location.href = '<?= base_url('/login') ?>';
  }
</script>