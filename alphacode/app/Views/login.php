<?= $this->extend('default') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-center" style="height:100vh; width:100vw; align-items:center;">
    <div class="d-flex justify-content-center">
        <form class=" p-4 border rounded-2" style="width: 400px;">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
                <label for="senha" class="form-label">Senha</label>
                <input type="password" class="form-control" id="senha" minlength="6" required>
            </div>
            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary">Login</button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    $(document).ready(async function() {
        $('form').submit(function(e) {
            e.preventDefault();
            let email = $('#email').val();
            let senha = $('#senha').val();
            $.ajax({
                url: '<?= base_url('/api/auth/login') ?>',
                method: 'POST',
                data: {
                    email: email,
                    senha: senha
                },
                success: function(response) {
                    if (response.success) {
                        localStorage.setItem('token', response.token);
                        window.location.href = '<?= base_url('/') ?>';
                    }
                },
                error: function(error) {
                    $('form').addClass('was-validated');
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>