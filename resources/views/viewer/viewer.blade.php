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
    <style>
        print {
            .noprint {
                display: none !important;
            }
        }
    </style>
</head>

<body style="margin: 0px">
    <div id="adobe-dc-view" class="pdf-view"></div>
    <div class="custom-container d-center ">
        <div class="btn-group-vertical" role="group" aria-label="Vertical button group">
            <a href="/" type="button" class="btn btn-primary">BookList page</a>
            <button type="button" class="btn btn-primary my-1" onclick="handleSearchWeb()">Google search...</button>
            <button type="button" class="btn btn-primary" onclick="handleAskAI()">Ask AI</button>
            <button type="button" class="btn btn-primary my-1" onclick="storeAnnotationData()">Save changes</button>
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
    {{-- <script type="text/javascript" src="{{ asset('js/viewer.js') }}"></script> --}}
    <script>
        // Get the PDF viewer element
        const viewerElement = document.getElementById("adobe-dc-view");

        /* Control the default view mode */
        const viewerConfig = {
            /* Allowed possible values are "FIT_PAGE", "FIT_WIDTH", "TWO_COLUMN", "TWO_COLUMN_FIT_PAGE" or "". */
            defaultViewMode: "FIT_WIDTH",
            embedMode: "FULL_WINDOW",
            showDownloadPDF: false,
            showPrintPDF: false,
            showLeftHandPanel: true,
            enableAnnotationAPIs: true /* Default value is false */ ,
            showAnnotationTools: true,
            // enableLinearization: true,
        };

        let selectedText = "";
        let previewFilePromise;
        /* Wait for Adobe Acrobat Services PDF Embed API to be ready */
        document.addEventListener("adobe_dc_view_sdk.ready", function() {
            const adobeDCView = new AdobeDC.View({
                /* Pass your registered client id */
                clientId: "e3440e9a3b9a4f75a22e577afe799d36",
                /* Pass the div id in which PDF should be rendered */
                divId: "adobe-dc-view",
            });
            previewFilePromise = adobeDCView.previewFile({
                    content: {
                        location: {
                            url: bookInfo.file
                        },
                    },
                    metaData: {
                        fileName: bookInfo.name,
                        id: bookInfo.bookId,
                    },
                },
                viewerConfig
            );

            adobeDCView.registerCallback(
                AdobeDC.View.Enum.CallbackType.GET_USER_PROFILE_API,
                (event) => {
                    return new Promise((resolve, reject) => {
                        resolve({
                            code: AdobeDC.View.Enum.ApiResponseCode.SUCCESS,
                            data: {
                                userProfile: {
                                    ...userData,
                                    name: `${userData.firstName} ${userData.lastName}`,
                                },
                            },
                        });
                    });
                }
            );


            adobeDCView.registerCallback(
                AdobeDC.View.Enum.CallbackType.EVENT_LISTENER,
                function(event) {
                    if (event.type === "DOCUMENT_PRINT") {
                        location.reload();
                    }
                    if (event.type === "PREVIEW_SELECTION_END") {
                        previewFilePromise.then((adobeViewer) => {
                            adobeViewer.getAPIs().then((apis) => {
                                apis.getSelectedContent().then((result) => {
                                    const {
                                        type,
                                        data
                                    } = result;
                                    if (type === "text") {
                                        selectedText = data;
                                        console.log("selectedText", selectedText)
                                    }
                                });

                            });
                        }, {});
                    }
                    if (event.type === 'PDF_VIEWER_OPEN') {
                        window.addEventListener('beforeunload', e => {
                            e.preventDefault();
                            storeAnnotationData();
                            event.returnValue = true;
                        })
                        $(window).bind("beforeunload", function(e) {
                            storeAnnotationData();
                            setTimeout(() => {
                                return true;
                            }, 1000);
                        }, false);
                    }
                    if (event.type === "PDF_VIEWER_READY") {


                        previewFilePromise.then((adobeViewer) => {
                            adobeViewer
                                .getAnnotationManager()
                                .then(function(annotationManager) {
                                    /* API to add annotations */
                                    const tempData = JSON.parse(localStorage
                                        .getItem('data'))
                                    if (tempData.length) {
                                        annotationManager
                                            .addAnnotations(
                                                tempData
                                            ).then(function() {
                                                console.log(
                                                    "Annotations added through API successfully"
                                                );

                                            })
                                    }
                                    const annotations = JSON.parse(bookInfo.annotations)

                                    if (annotations.length && tempData.length == 0) {
                                        annotationManager
                                            .addAnnotations(
                                                annotations
                                            )
                                            .then(function() {
                                                console.log(
                                                    "Annotations added through API successfully from db"
                                                );
                                            })
                                            .catch(function(error) {
                                                console.log(error);
                                            });
                                    }
                                });
                        });
                    }
                }, {
                    enableFilePreviewEvents: true,
                    enablePDFAnalytics: true
                }
            );
        });
        const handleSearchWeb = () => {
            if (selectedText) {
                const url = "https://www.google.com/search?q=" + selectedText;
                // Open the URL in a new tab of the Chrome browser
                window.open(url, "_blank");
            } else {
                alert("You have to select the text for the Google Search.");
            }
        };

        const handleAskAI = () => {
            alert("Ask AI will run in the future!!");
        };

        const storeAnnotationData = () => {
            previewFilePromise.then((adobeViewer) => {
                adobeViewer.getAnnotationManager().then(function(annotationManager) {
                    annotationManager
                        .getAnnotations()
                        .then(function(result) {
                            localStorage.setItem("data", JSON.stringify(result));
                            $.ajax({
                                url: `${location.origin}/api/annotations`,
                                type: "POST",
                                data: {
                                    id: bookInfo.id,
                                    annotations: JSON.stringify(result),
                                },
                                success: (res) => {
                                    console.log("Save");
                                },
                            });
                        })
                        .catch(function(error) {
                            console.log(error);
                        });
                });
            });
        };
    </script>



</body>

</html>
