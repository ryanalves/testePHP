<?= $this->extend('default') ?>
<?= $this->section('script') ?>
    <script>
        $(document).ready(async function() {
            window.location.href = '<?= base_url('/') ?>';
        });
    </script>
<?= $this->endSection() ?>