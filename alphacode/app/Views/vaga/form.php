<?= $this->extend('default') ?>

<?= $this->section('content') ?>
<?= $this->include('includes/topbar') ?>
<div class="p-4">
  <?php
  if (!isset($vaga)) {
    $vaga = [
      'id' => null,
      'nome' => '',
      'tipo' => '',
      'status' => '',
      'area' => '',
      'pretensao' => '',
      'descricao' => ''
    ];
  }
  if (!isset($visualizar)) {
    $visualizar = false;
  }
  if ($visualizar) {
    $disabled = 'disabled';
  } else {
    $disabled = '';
  }
  $titulo = 'Criar vaga';
  if ($vaga['id'] != null) {
    if ($visualizar == true) {
      $titulo = 'Visualizar Vaga #' . $vaga['id'];
    }
    if ($vaga != null && $visualizar != true) {
      $titulo = 'Editar Vaga #' . $vaga['id'];
    }
  }
  ?>
  <div class="d-flex align-items-center justify-content-between">
    <h2><?= $titulo ?></h2>
    <?php if (isset($usuario['candidato_id'] )) {
      $candidaturaDisabled = $vaga['status'] != 'DISPONIVEL' ? 'disabled' : '';
      if (isset($candidatura['id'])) {
        echo "<button onclick='cancelarCandidatura()' class='btn btn-danger'>Cancelar candidatura</button>";
      } else {
        echo "<button onclick='candidatar()' $candidaturaDisabled class='btn btn-primary'>Candidatar</button>";
      }
    } ?>

  </div>
  <div class="p-4">
    <form>
      <input type="hidden" name="id" value="<?= $vaga['id'] ?>">
      <div class="mb-3">
        <label for="nome" class="form-label">Nome</label>
        <input type="text" class="form-control" id="nome" name="nome" value="<?= $vaga['nome'] ?>" <?= $disabled ?>>
      </div>
      <div class="mb-3">
        <div class="mb-3">
          <label for="tipo" class="form-label">Tipo</label>
          <select class="form-control" id="tipo" name="tipo" <?= $disabled ?>>
            <option disabled <?= $vaga['tipo'] == '' ? 'selected' : '' ?>>Selecione um tipo</option>
            <option value="CLT" <?= $vaga['tipo'] == 'CLT' ? 'selected' : '' ?>>CLT</option>
            <option value="PJ" <?= $vaga['tipo'] == 'PJ' ? 'selected' : '' ?>>PJ</option>
            <option value="FREELANCER" <?= $vaga['tipo'] == 'FREELANCER' ? 'selected' : '' ?>>Freelancer</option>
          </select>
        </div>
      </div>
      <div class="mb-3">
        <label for="status" class="form-label">Status</label>
        <select class="form-control" id="status" name="status" <?= $disabled ?>>
          <option disabled <?= $vaga['status'] == '' ? 'selected' : '' ?>>Selecione um status</option>
          <option value="DISPONIVEL" <?= $vaga['status'] == 'DISPONIVEL' ? 'selected' : '' ?>>Disponível</option>
          <option value="PAUSADO" <?= $vaga['status'] == 'PAUSADO' ? 'selected' : '' ?>>Pausado</option>
          <option value="ENCERRADO" <?= $vaga['status'] == 'ENCERRADO' ? 'selected' : '' ?>>Encerrado</option>
        </select>
      </div>
      <div class="mb-3">
        <label for="area" class="form-label">Área</label>
        <input type="text" class="form-control" id="area" name="area" value="<?= $vaga['area'] ?>" <?= $disabled ?>>
      </div>
      <div class="mb-3">
        <label for="pretensao" class="form-label">Pretensão</label>
        <input type="number" class="form-control" id="pretensao" name="pretensao" value="<?= $vaga['pretensao'] ?>" <?= $disabled ?>>
      </div>
      <div class="mb-3">
        <label for="descricao" class="form-label">Descrição</label>
        <textarea class="form-control" id="descricao" name="descricao" <?= $disabled ?>><?= $vaga['descricao'] ?></textarea>
      </div>
      <?php if (!$visualizar) : ?>
        <button type="submit" class="btn btn-primary">Salvar</button>
      <?php endif; ?>
    </form>
  </div>

  <?php if (isset($candidatos)) : ?>
    <h2>Candidaturas</h2>

    <table id="candidatosTable" class="table table-striped" style="width:100%">
      <thead>
        <tr>
          <th>#</th>
          <th>Nome</th>
          <th>Data de Nascimento</th>
          <th>Descrição</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>

      </tbody>
      <tfoot>
        <tr>
          <th>#</th>
          <th>Nome</th>
          <th>Data de Nascimento</th>
          <th>Descrição</th>
          <th>Ações</th>
        </tr>
      </tfoot>
    </table>
</div>
<?php endif; ?>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
  function candidatar() {
    $.ajax({
      url: `/api/vaga/candidatar/<?= $vaga['id'] ?>`,
      type: 'POST',
      success: function() {
        location.reload();
      }
    });
  }

  function cancelarCandidatura() {
    $.ajax({
      url: `/api/vaga/candidatar/<?= $vaga['id'] ?>`,
      type: 'DELETE',
      success: function() {
        location.reload();
      }
    });
  }
  $(document).ready(async function() {
    <?php if (isset($candidatos)) : ?>
      $('#candidatosTable').DataTable({
        data: <?= json_encode($candidatos) ?>,
        lengthMenu: [10, 20, 50, 100],
        columns: [{
            data: 'id'
          },
          {
            data: 'nome'
          },
          {
            data: 'data_nascimento'
          },
          {
            data: 'descricao'
          },
          {
            data: 'usuario.id',
            className: 'actions',
            orderable: false,
            render: function(data, type, row) {
              <?php if (!isset($usuario['candidato_id'] )) : ?>
                return `<a href="<?= base_url('/usuarios/visualizar') ?>/${data}" class="btn btn-primary"><i class="bi bi-eye"></i></a>`;
              <?php else : ?>
                return ``;
              <?php endif; ?>

            }
          }
        ]
      });
    <?php endif; ?>

    $('form').submit(function(event) {
      event.preventDefault();
      let data = $(this).serializeArray().reduce(function(obj, item) {
        obj[item.name] = item.value;
        return obj;
      }, {});

      <?php if ($vaga['id'] == null) : ?>
        $.ajax({
          url: '<?= base_url('/api/vaga') ?>',
          type: 'POST',
          data: JSON.stringify(data),
          processData: false,
          contentType: 'application/json',
          success: function(response) {
            window.location.href = '<?= base_url('/') ?>';
          }
        });
      <?php else : ?>
        $.ajax({
          url: '<?= base_url('/api/vaga/' . $vaga['id']) ?>',
          type: 'PUT',
          data: JSON.stringify(data),
          processData: false,
          contentType: 'application/json',
          success: function(response) {
            window.location.href = '<?= base_url('/') ?>';
          }
        });
      <?php endif; ?>
    });
  });
</script>
<?= $this->endSection() ?>