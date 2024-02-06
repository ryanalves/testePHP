<?= $this->extend('default') ?>

<?= $this->section('content') ?>
<?= $this->include('includes/topbar') ?>
<div class="p-4">
  <?php

  if (!isset($model['id'])) $model['id'] = null;
  if (!isset($model['user'])) $model['user'] = '';
  if (!isset($model['email'])) $model['email'] = '';
  if (!isset($model['candidato_id'])) $model['candidato_id'] = null;
  if (!isset($model['nome'])) $model['nome'] = '';
  if (!isset($model['data_nascimento'])) $model['data_nascimento'] = '';
  if (!isset($model['descricao'])) $model['descricao'] = '';
  if (!isset($visualizar)) {
    $visualizar = false;
  }
  if ($visualizar) {
    $disabled = 'disabled';
  } else {
    $disabled = '';
  }
  $titulo = 'Criar usuário';
  if ($model['id'] != null) {
    if ($visualizar == true) {
      $titulo = 'Visualizar Usuário #' . $model['id'];
    }
    if ($model != null && $visualizar != true) {
      $titulo = 'Editar Usuário #' . $model['id'];
    }
  }
  ?>
  <h2><?= $titulo ?></h2>

  <div class="p-4">
    <form>
      <div class="mb-3">
        <label for="user" class="form-label">User</label>
        <input type="text" class="form-control" id="user" name="user" value="<?= $model['user'] ?>" required <?= $disabled ?>>
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="<?= $model['email'] ?>" required <?= $disabled ?>>
      </div>
      <div class="mb-3">
        <label for="senha" class="form-label">Senha</label>
        <input type="password" class="form-control" id="senha" name="senha" <?= (isset($model['id']) ) ? '' : 'required' ?> <?= $disabled ?>>
      </div>


      <div class="mb-3 d-flex gap-2  ">
        <input type="checkbox" class="form-check" id="candidato_id" name="candidato_id" <?= $model['candidato_id'] ? 'checked' : '' ?> <?= $model['id'] ? 'disabled' : '' ?>>
        <label for="candidato_id">Candidato</label>
      </div>

      <div class="mb-3">
        <label for="nome" class="form-label">Nome</label>
        <input type="text" class="form-control" id="nome" name="nome" required value="<?= $model['nome'] ?>" <?= ($visualizar || ($model['id'] != null && $model['candidato_id'] == null))  ? 'disabled' : '' ?>>
      </div>
      <div class="mb-3">
        <label for="data_nascimento" class="form-label">Data de Nascimento</label>
        <input type="date" class="form-control" id="data_nascimento" required name="data_nascimento" value="<?= $model['data_nascimento'] ?>" <?= ($visualizar || ($model['id'] != null && $model['candidato_id'] == null)) ? 'disabled' : '' ?>>
      </div>
      <div class="mb-3">
        <label for="descricao" class="form-label">Descrição</label>
        <textarea class="form-control" id="descricao" name="descricao" required <?= ($visualizar || ($model['id'] != null && $model['candidato_id'] == null)) ? 'disabled' : '' ?>><?= $model['descricao'] ?></textarea>
      </div>
      <?php if (!$visualizar) : ?>
        <button type="submit" class="btn btn-primary">Salvar</button>
      <?php endif; ?>
    </form>
  </div>

</div>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
  $(document).ready(async function() {
    $('#nome').prop('disabled', true);
    $('#data_nascimento').prop('disabled', true);
    $('#descricao').prop('disabled', true);
    $('#candidato_id').change(function() {
      const isChecked = $(this).is(':checked');
      $('#nome').prop('disabled', !isChecked);
      $('#data_nascimento').prop('disabled', !isChecked);
      $('#descricao').prop('disabled', !isChecked);
    });
    $('form').submit(function(event) {
      event.preventDefault();
      let data = $(this).serializeArray().reduce(function(obj, item) {
        obj[item.name] = item.value;
        return obj;
      }, {});
      <?php if ($model['id'] == null) : ?>

        const isCandidato = $('#candidato_id').is(':checked');
        if (isCandidato) {
          $.ajax({
            url: '<?= base_url('/api/candidato') ?>',
            type: 'POST',
            data: JSON.stringify(data),
            processData: false,
            contentType: 'application/json',
            success: function(response) {
              window.location.href = '<?= base_url('/usuarios') ?>';
            },
            error: function(response) {
              const res = response.responseJSON;
              if (res?.errors?.email) {
                alert(res.errors.email);
              } else {
                alert(res.message);
              }
            }
          });
        } else {
          $.ajax({
            url: '<?= base_url('/api/usuario') ?>',
            type: 'POST',
            data: JSON.stringify(data),
            processData: false,
            contentType: 'application/json',
            success: function(response) {
              window.location.href = '<?= base_url('/usuarios') ?>';
            },
            error: function(response) {
              const res = response.responseJSON;
              if (res?.errors?.email) {
                alert(res.errors.email);
              } else {
                alert(res.message);
              }
            }
          });

        }
      <?php else : ?>
        $.ajax({
          url: '<?= base_url('/api/usuario/' . $model['id']) ?>',
          type: 'PUT',
          data: JSON.stringify(data),
          processData: false,
          contentType: 'application/json',
          success: function(response) {
            window.location.href = '<?= base_url('/usuarios') ?>';
          }
        });
      <?php endif; ?>

    });
  });
</script>
<?= $this->endSection() ?>