<?= $this->extend('default') ?>

<?= $this->section('content') ?>
<?= $this->include('includes/topbar') ?>
<div class="p-4">
  <div class="d-flex align-items-center justify-content-between">
    <h2>Vagas</h2>
    <a href="/vagas/criar" class="btn btn-primary">Criar vaga</a>
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
        <th >Ações</th>
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
        <th >Ações</th>
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
  $(document).ready(async function() {
    $('#vagasTable').DataTable({
      serverSide: true,
      ajax: {
        url: '<?= base_url('/api/vaga') ?>',
        type: 'GET'
      },
      lengthMenu: [10, 20, 50, 100],
      columns: [{
          data: 'id'
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
              <a href="<?= base_url('/vagas/remover') ?>/${data}" class="btn btn-danger"><i class="bi bi-trash"></i></a>`;
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