<!DOCTYPE html>
<html>
<head>
    <title>Adobe Acrobat Services PDF Embed API Sample</title>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <meta id="viewport" name="viewport" content="width=device-width, initial-scale=1"/>
    <script type="text/javascript" src="{{ 'js/viewer.js' }}"></script>
    <link rel="stylesheet" href="{{ 'css/custom.css' }}">
</head>
<body style="margin: 0px">
    <div id="adobe-dc-view" class="pdf-view"></div>
    <div class="custom-container d-center">
        <input type="file" id="file-picker" accept=".pdf,.epub"  style="display: none;">
        <input type="button" value="Browse..." onclick="document.getElementById('file-picker').click();" />
    </div>
    
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://acrobatservices.adobe.com/view-sdk/viewer.js"></script>
</body>
</html>
