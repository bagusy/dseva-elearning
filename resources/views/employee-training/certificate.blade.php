<!DOCTYPE html>
<html lang="en">
<head>
    <title>Certificate</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
</head>
<body>

<div class="container-fluid">
    <div class="row content">
        <div class="col-12" style="margin-top: 0px">
            <div id="html-content-holder" style="height:620px; width: 874px; text-align: center; margin-top: 0px">
                <img src="/images/certificate.png" style="height: auto; width: 100%; margin-top: 0px">
                <h3 style="font-family: 'Great Vibes', cursive; position: relative; font-size: 45px; color: #5a7a5b; text-align: center; top: -350px">{{ $courseEnrollment['user']['name'] }}</h3>
                <h4 style="font-family: 'Times New Roman', sans-serif; font-size: 23px; position: relative; color: #5a7a5b; text-align: center; top: -290px">"{{ $courseEnrollment['course']['title'] }}"</h4>
                <h4 style="font-family: 'Times New Roman', sans-serif; font-size: 20px; position: relative; color: #000000; text-align: center; top: -230px">{{ $courseEnrollment['user']['company']['name'] }}<br><small>powered by Dseva</small></h4>
                <h4 style="font-family: 'Times New Roman', sans-serif; font-size: 15px; position: relative; color: #ffffff; text-align: right; top: -220px; margin-right: 15px">Certificate No<br>{{ $courseEnrollment['certificate_no'] }}</h4>
            </div>
            <div id="previewImage"></div>
            <br>
            <div style="width: 874px; text-align: right">
                <a style="text-align: center; background-image: linear-gradient(to right, #17951b , #52c721);" class="btn btn-success btn-sm" id="btn-Convert-Html2Image">Download</a>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="/dashboard/assets/js/jquery.min.js"></script>
<script type="text/javascript" src="/js/html2canvas.js"></script>
<script>
    $(document).ready(function () {

        // Global variable
        var element = $("#html-content-holder");

        // Global variable
        var getCanvas;

        html2canvas(element, {
            onrendered: function (canvas) {
                $("#previewImage").append(canvas);
                getCanvas = canvas;
                document.getElementById('html-content-holder').innerHTML = '';
                document.getElementById('html-content-holder').style.display = 'none';
            }
        });
        $("#btn-Convert-Html2Image").on('click', function () {
            var imgageData =
                getCanvas.toDataURL("image/png", 1);

            // Now browser starts downloading
            // it instead of just showing it
            var newData = imgageData.replace(
                /^data:image\/png/, "data:application/octet-stream");

            $("#btn-Convert-Html2Image").attr(
                "download", "{{ $courseEnrollment['user']['name'] }} - {{ $courseEnrollment['course']['title'] }}.png").attr(
                "href", newData);
        });
    });
</script>
</body>
</html>

