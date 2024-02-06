<?= $this->extend('default') ?>

<?= $this->section('content') ?>
<?= $this->include('includes/topbar') ?>
<div class="p-4">
  <div class="d-flex align-items-center justify-content-between">
    <h2>Vagas</h2>
    <div>
      <button class="btn btn-danger" id="deletarVagas" onclick="deletarEmMassa()">Deletar em massa</button>
      <a href="/vagas/criar" class="btn btn-primary">Criar vaga</a>
    </div>
  </div>

  <table id="vagasTable" class="table table-striped" style="width:100%">
    <thead>
      <tr>
        <th>#</th>
        <th>Nome</th>
        <th>Tipo</th>
        <th>Status</th>
        <th>Área</th>
        <th>Pretensão</th>
        <th>Ações</th>
      </tr>
    </thead>
    <tbody>

    </tbody>
    <tfoot>
      <tr>
        <th>#</th>
        <th>Nome</th>
        <th>Tipo</th>
        <th>Status</th>
        <th>Área</th>
        <th>Pretensão</th>
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
  function deletarVaga(id) {
    if (confirm('Deseja realmente deletar esta vaga?')) {
      $.ajax({
        url: `/api/vaga`,
        type: 'DELETE',
        data: JSON.stringify({
          id: id
        }),
        processData: false,
        contentType: 'application/json',
        success: function() {
          $('#vagasTable').DataTable().ajax.reload();
          showToast('success', 'Sucesso', 'Vaga deletada com sucesso');
        },
        error: function() {
          showToast('danger', 'Erro', 'Erro ao deletar vaga');
        }
      });
    }
  }
  let ids = [];

  function deletarEmMassa() {
    if (confirm('Deseja realmente deletar estas vagas?')) {
      $.ajax({
        url: `/api/vaga`,
        type: 'DELETE',
        data: JSON.stringify({
          id: ids
        }),
        processData: false,
        contentType: 'application/json',
        success: function() {
          $('#vagasTable').DataTable().ajax.reload();
          ids = [];
          $('#deletarVagas').hide();
          showToast('success', 'Sucesso', 'Vagas deletadas com sucesso');
        },
        error: function() {
          showToast('danger', 'Erro', 'Erro ao deletar vagas');
        }
      });
    }
  }
  $(document).ready(async function() {
    $('#deletarVagas').hide();
    $('#vagasTable').on('click', '.vaga-check', function() {
      const isChecked = $(this).is(':checked');
      const value = $(this).val();
      if (isChecked) {
        ids.push(value);
      } else {
        ids = ids.filter(id => id != value);
      }
      if (ids.length > 0) {
        $('#deletarVagas').show();
      } else {
        $('#deletarVagas').hide();
      }
    });

    $('#vagasTable').DataTable({
      serverSide: true,
      ajax: {
        url: '<?= base_url('/api/vaga') ?>',
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
              return '<input type="checkbox" class="vaga-check form-check-input" name="vaga_id" ' + checked + ' value="' + data + '">' + data;
            <?php else : ?>
              return data;
            <?php endif; ?>
          }
        },
        {
          data: 'nome'
        },
        {
          data: 'tipo'
        },
        {
          data: 'status'
        },
        {
          data: 'area'
        },
        {
          data: 'pretensao'
        }, {
          data: 'id',
          className: 'actions',
          orderable: false,
          render: function(data, type, row) {
            <?php if ($isAdmin) : ?>
              return `<a href="<?= base_url('/vagas/visualizar') ?>/${data}" class="btn btn-primary"><i class="bi bi-eye"></i></a>
              <a href="<?= base_url('/vagas/editar') ?>/${data}" class="btn btn-warning"><i class="bi bi-pencil"></i></a>
              <a onclick="deletarVaga(${data})" class="btn btn-danger"><i class="bi bi-trash"></i></a>`;
            <?php else : ?>
              return `<a href="<?= base_url('/vagas/visualizar') ?>/${data}" class="btn btn-primary"><i class="bi bi-eye"></i></a>`;
            <?php endif; ?>
          }
        }
      ]
    });
  });
</script>
<?= $this->endSection() ?>