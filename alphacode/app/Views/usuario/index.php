<?= $this->extend('default') ?>

<?= $this->section('content') ?>
<?= $this->include('includes/topbar') ?>
<div class="p-4">
  <h2>Usuarios</h2>

  <table id="usuariosTable" class="table table-striped" style="width:100%">
    <thead>
      <tr>
        <th>#</th>
        <th>User</th>
        <th>Email</th>
        <th>Candidato ID</th>
        <th>Nome</th>
        <th>Data de Nascimento</th>
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
      </tr>
    </tfoot>
  </table>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
  $(document).ready(async function() {
    $('#usuariosTable').DataTable({
      serverSide: true,
      ajax: {
        url: '<?= base_url('/api/usuario') ?>',
        type: 'GET'
      },
      lengthMenu: [10, 20, 50, 100],
      columns: [{
          data: 'id'
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
        }
      ]
    });
  });
</script>
<?= $this->endSection() ?>