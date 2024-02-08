<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Alphacode Vagas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link href="//cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet">
  <style>
    .actions {
      width: 150px;
      text-align: center;
    }
  </style>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script src="//cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
</head>

<body>
  <?= $this->renderSection('content') ?>

  <div id="toasts" class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">

  </div>
</body>

<div style="display: none;"><?= $this->renderSection('templates') ?>
  <div id="toast-template" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header text-white">
      <strong class="me-auto">Info</strong>
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">
      Hello, world! This is a toast message.
    </div>
  </div>
</div>
<script>
  $(document).ready(function() {
    <?php
    helper('toast');
    echo get_toast();
    ?>
  });

  function showToast(type, titulo, message) {
    let toast = $('#toast-template').clone();
    toast.removeClass('hide');
    toast.find('.toast-body').text(message);
    toast.find('.toast-header').addClass('bg-' + type);
    toast.appendTo('#toasts');
    toast.toast('show');
  }
</script>
<?= $this->renderSection('script') ?>

</html>