
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Webcam Capture</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
</head>
<body>
<div class="container text-center">
    <h1>Laravel Webcam Capture</h1>

    <form id="form" method="POST" action="{{ route('login.submit') }}">
        @csrf
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div id="my_camera"></div>
                <button type="button" id="take_snap" class="btn btn-info btn-sm mt-2">Take Snapshot</button>
                <input type="hidden" name="image" class="image-tag">
            </div>
        </div>
    </form>
</div>


<script>
    $(document).ready(function () {
        Webcam.set({
            width: 250,
            height: 190,
            image_format: 'jpeg',
            jpeg_quality: 90
        });
        Webcam.attach('#my_camera');

        Webcam.on('load', function () {
            setTimeout(function () {
                Webcam.snap(function (data_uri) {
                    $('.image-tag').val(data_uri);
                    $('#form').submit();
                });
            }, 1000); // Delay to ensure the camera initializes properly
        });
    });
</script>

</body>
</html>

