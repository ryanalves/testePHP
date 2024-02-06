<?= $this->extend('default') ?>

<?= $this->section('content') ?>
<?= $this->include('includes/topbar') ?>
<div class="p-4">
  <h2>Vagas</h2>

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
<script>
  $(document).ready(async function() {
    $('#vagasTable').DataTable({
      ajax: {
        url: '<?= base_url('/api/vaga/candidaturas') ?>',
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
            return `<a href="<?= base_url('/vagas/visualizar') ?>/${data}" class="btn btn-primary"><i class="bi bi-eye"></i></a>`;
          }
        }
      ]
    });
  });
</script>
<?= $this->endSection() ?>