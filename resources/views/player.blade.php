<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <title>Yu-stream</title>

  <link href="https://cdnjs.cloudflare.com/ajax/libs/video.js/7.6.6/video-js.css" rel="stylesheet">
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
  .video-container {
    width: 100%;
    margin: 15px;
  }

  .video-js .vjs-menu-button-popup .vjs-menu {
      left: auto;
      right: 0;
  }

  </style>
</head>
<body>

<header>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
      <a class="navbar-brand" href="/">
        yu-stream
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbar">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item">
            <a class="nav-link" href="/">Top <span class="sr-only">(current)</span></a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</header>

<div class="container">
  <h3 id="live_name">title</h3>
  <div class="row">
    <div class="col-md-12">
      <div class="video-container">
        <video id="amazon-ivs-videojs" class="video-js vjs-16-9 vjs-big-play-centered" controls autoplay playsinline>
        </video>
      </div>
    </div>
  </div>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="{{ URL::asset('/js/bootstrap.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/video.js/7.6.6/video.min.js"></script>
<script src="https://player.live-video.net/1.0.0/amazon-ivs-videojs-tech.min.js"></script>
<script src="https://player.live-video.net/1.0.0/amazon-ivs-quality-plugin.min.js"></script>

<script type="text/javascript">
  $('.bs-component [data-toggle="popover"]').popover();
  $('.bs-component [data-toggle="tooltip"]').tooltip();
</script>

<script>
    function getLivedetail(uuid) {
      $.ajax({
        type: 'get',
        url: '/api/v1/stream/' + uuid,
        dataType: 'json',
        success: function (data) {
          $('#live_name').text(data.name);
          streamSetUp(data.playback_url);
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {
        }
      });
    }

    // Initialize player
    function streamSetUp (stream) {
        // Set up IVS playback tech and quality plugin
        registerIVSTech(videojs);
        registerIVSQualityPlugin(videojs);

        // Initialize video.js player
        const videoJSPlayer = videojs("amazon-ivs-videojs", {
            techOrder: ["AmazonIVS"],
            controlBar: {
                playToggle: {
                    replay: false
                }, // Hides the replay button for VOD
                pictureInPictureToggle: false // Hides the PiP button
            }
        });

        // Use the player API once the player instance's ready callback is fired
        const readyCallback = function () {
            // This executes after video.js is initialized and ready
            window.videoJSPlayer = videoJSPlayer;

            // Get reference to Amazon IVS player
            const ivsPlayer = videoJSPlayer.getIVSPlayer();

            // Show the "big play" button when the stream is paused
            const videoContainerEl = document.querySelector("#amazon-ivs-videojs");
            videoContainerEl.addEventListener("click", () => {
                if (videoJSPlayer.paused()) {
                    videoContainerEl.classList.remove("vjs-has-started");
                } else {
                    videoContainerEl.classList.add("vjs-has-started");
                }
            });

            // Enables manual quality selection plugin
            videoJSPlayer.enableIVSQualityPlugin();

            // Set volume and play default stream
            videoJSPlayer.volume(0.5);
            videoJSPlayer.src(stream);
        };

        // Register ready callback
        videoJSPlayer.ready(readyCallback);
    };

    getLivedetail('{{ $uuid }}');
</script>
</body>
</html>
