<?= $this->extend('default') ?>

<?= $this->section('content') ?>
<?= $this->include('includes/topbar') ?>
<div class="p-4">
  <div class="d-flex align-items-center justify-content-between">
    <h2>Usuarios</h2>
    <div>
      <button class="btn btn-danger" id="deletarUsuarios" onclick="deletarEmMassa()">Deletar em massa</button>
      <a href="/usuarios/criar" class="btn btn-primary">Criar usuário</a>
    </div>
  </div>

  <table id="usuariosTable" class="table table-striped" style="width:100%">
    <thead>
      <tr>
        <th>#</th>
        <th>User</th>
        <th>Email</th>
        <th>Candidato ID</th>
        <th>Nome</th>
        <th>Data de Nascimento</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>
    </tbody>
    <tfoot>
      <tr>
        <th>#</th>
        <th>User</th>
        <th>Email</th>
        <th>Candidato ID</th>
        <th>Nome</th>
        <th>Data de Nascimento</th>
        <th>Ações</th>
      </tr>
    </tfoot>
  </table>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?php
$isAdmin = false;
if (isset($usuario) && $usuario['candidato_id'] == null) {
  $isAdmin = true;
}
?>
<script>
  function deletarUsuario(id) {
    if (confirm('Deseja realmente deletar esta usuario?')) {
      $.ajax({
        url: `/api/usuario`,
        type: 'DELETE',
        data: JSON.stringify({
          id: id
        }),
        processData: false,
        contentType: 'application/json',
        success: function() {
          $('#usuariosTable').DataTable().ajax.reload();
          showToast('success', 'Sucesso', 'Usuário deletado com sucesso');
        },
        error: function() {
          showToast('danger', 'Erro', 'Erro ao deletar usuário');
        }
      });
    }
  }
  let ids = [];

  function deletarEmMassa() {
    if (confirm('Deseja realmente deletar estes usuários?')) {
      $.ajax({
        url: `/api/usuario`,
        type: 'DELETE',
        data: JSON.stringify({
          id: ids
        }),
        processData: false,
        contentType: 'application/json',
        success: function() {
          $('#usuariosTable').DataTable().ajax.reload();
          ids = [];
          $('#deletarUsuarios').hide();
          showToast('success', 'Sucesso', 'Usuários deletados com sucesso');
        },
        error: function() {
          showToast('danger', 'Erro', 'Erro ao deletar usuários');
        }
      });
    }
  }

  $(document).ready(async function() {
    $('#deletarUsuarios').hide();
    $('#usuariosTable').on('click', '.usuario-check', function() {
      const isChecked = $(this).is(':checked');
      const value = $(this).val();
      if (isChecked) {
        ids.push(value);
      } else {
        ids = ids.filter(id => id != value);
      }
      if (ids.length > 0) {
        $('#deletarUsuarios').show();
      } else {
        $('#deletarUsuarios').hide();
      }
    });
    $('#usuariosTable').DataTable({
      serverSide: true,
      ajax: {
        url: '<?= base_url('/api/usuario') ?>',
        type: 'GET'
      },
      lengthMenu: [10, 20, 50, 100],
      columns: [{
          data: 'id',
          render: function(data, type, row) {
            <?php if ($isAdmin) : ?>
              let checked = '';
              if (ids.includes(data)) {
                checked = 'checked';
              }
              return '<input type="checkbox" class="usuario-check form-check-input" name="usuario_id" ' + checked + ' value="' + data + '">' + data;
            <?php else : ?>
              return data;
            <?php endif; ?>
          }
        },
        {
          data: 'user'
        },
        {
          data: 'email'
        },
        {
          data: 'candidato_id'
        },
        {
          data: 'nome'
        },
        {
          data: 'data_nascimento'
        }, {
          data: 'id',
          className: 'actions',
          orderable: false,
          render: function(data, type, row) {
            <?php if ($isAdmin) : ?>
              return `<a href="<?= base_url('/usuarios/visualizar') ?>/${data}" class="btn btn-primary"><i class="bi bi-eye"></i></a>
              <a href="<?= base_url('/usuarios/editar') ?>/${data}" class="btn btn-warning"><i class="bi bi-pencil"></i></a>
              <a onclick="deletarUsuario(${data})" class="btn btn-danger"><i class="bi bi-trash"></i></a>`;
            <?php else : ?>
              return `<a href="<?= base_url('/usuarios/visualizar') ?>/${data}" class="btn btn-primary"><i class="bi bi-eye"></i></a>`;
            <?php endif; ?>
          }
        }
      ]
    });
  });
</script>
<?= $this->endSection() ?>