<!DOCTYPE html>
<html>

<head>
    <title>Pdf custom viewer</title>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta id="viewport" name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>

<body style="margin: 0px">
    <div id="adobe-dc-view" class="pdf-view"></div>
    <div class="custom-container d-center ">
        <input type="file" id="file-picker" accept=".pdf,.epub" style="display: none;">
        <div class="btn-group-vertical" role="group" aria-label="Vertical button group">
            {{-- <button type="button" class="btn btn-primary"
                onclick="document.getElementById('file-picker').click();">Browse...</button> --}}
            <button type="button" class="btn btn-primary my-1" onclick="handleSearchWeb()">Google search...</button>
            <button type="button" class="btn btn-primary" onclick="handleAskAI()">Ask AI</button>
            <button type="button" class="btn btn-primary my-1" onclick="storeAnnotationData()">Export</button>
        </div>
    </div>

    <script>
        var userData = {!! json_encode($user) !!}
        var bookInfo = {!! json_encode($book) !!}
    </script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://acrobatservices.adobe.com/view-sdk/viewer.js"></script>
    <script type="text/javascript" src="{{ asset('js/viewer.js') }}"></script>



</body>

</html>
