<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <title>Yu-stream</title>

  <link rel="stylesheet" type="text/css" href="{{ URL::asset('/css/bootstrap.css') }}">
  <style type="text/css">
  .bs-component + .bs-component {
    margin-top: 1rem;
  }
  @media (min-width: 768px) {
    .bs-docs-section {
      margin-top: 8em;
    }
    .bs-component {
      position: relative;
    }
    .bs-component .modal {
      position: relative;
      top: auto;
      right: auto;
      bottom: auto;
      left: auto;
      z-index: 1;
      display: block;
    }
    .bs-component .modal-dialog {
      width: 90%;
    }
    .bs-component .popover {
      position: relative;
      display: inline-block;
      width: 220px;
      margin: 20px;
    }
    .nav-tabs {
      margin-bottom: 15px;
    }
    .progress {
      margin-bottom: 10px;
    }
  }
  </style>
</head>
<body>

<header>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
      <a class="navbar-brand" href="./">
        yu-stream
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbar">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item">
            <a class="nav-link" href="./">Top <span class="sr-only"></span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="./vod">Vod <span class="sr-only">(current)</span></a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</header>

<div class="container">
  <table class="table table-hover">
    <thead>
      <tr>
        <th scope="col">No.</th>
        <th scope="col">VodName</th>
      </tr>
    </thead>
    <tbody id="listVod">
    </tbody>
  </table>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="{{ URL::asset('/js/bootstrap.min.js') }}"></script>

<script type="text/javascript">
  $('.bs-component [data-toggle="popover"]').popover();
  $('.bs-component [data-toggle="tooltip"]').tooltip();
</script>

<script>
    function getLiveList() {
      $.ajax({
        type: 'get',
        url: '/api/v1/archive',
        dataType: 'json',
        success: function (data) {
          var count = 1;
          data.forEach(function (no) {
            $('#listVod').append(
              $('<tr class="table-active"></tr>')
                .append('<th scope="row">1</th>')
                .append('<td><a href="/vod/' + no.uuid + '">' + no.name + '</a></td>')
            );
            count++;
          });
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
        }
      });
    }
    getLiveList();
</script>
</body>
</html>
