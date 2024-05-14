/*
Copyright 2019 Adobe
All Rights Reserved.

NOTICE: Adobe permits you to use, modify, and distribute this file in
accordance with the terms of the Adobe license agreement accompanying
it. If you have received this file from a source other than Adobe,
then your use, modification, or distribution of it requires the prior
written permission of Adobe.
*/

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
    // showDisabledSaveButton: true,
    enableAnnotationAPIs: true /* Default value is false */,

    // enableLinearization: true,
};

let selectedText = "";
let previewFilePromise;

/* Wait for Adobe Acrobat Services PDF Embed API to be ready */
document.addEventListener("adobe_dc_view_sdk.ready", function () {
    const fileToRead = document.getElementById("file-picker");

    fileToRead.addEventListener(
        "change",
        function (event) {
            previewFilePromise && storeAnnotationData();
            /* Initialize the AdobeDC View object */
            const adobeDCView = new AdobeDC.View({
                /* Pass your registered client id */
                clientId: "e3440e9a3b9a4f75a22e577afe799d36",
                /* Pass the div id in which PDF should be rendered */
                divId: "adobe-dc-view",
            });

            var files = fileToRead.files;

            if (files.length > 0) {
                const selectedFile = files[0];
                if (selectedFile.type === "application/pdf") {
                    var reader = new FileReader();
                    reader.onloadend = function (e) {
                        const filePromise = Promise.resolve(e.target.result);

                        // Pass the filePromise and name of the file to the previewFile API
                        previewFilePromise = adobeDCView.previewFile(
                            {
                                content: { promise: filePromise },
                                metaData: {
                                    fileName: selectedFile.name,
                                    id:
                                        selectedFile.name.replace(/\s/g, "-") +
                                        `+${selectedFile.size}+${selectedFile.type}`,
                                    // id: "YYYY888",
                                },
                            },
                            viewerConfig
                        );
                        // );
                    };
                    reader.readAsArrayBuffer(selectedFile);
                } else {
                    const formData = new FormData();
                    formData.append("epub", selectedFile);
                    $.ajax({
                        url: "http://localhost:4464/epub2pdf",
                        type: "POST",
                        data: formData,
                        success: function (data) {
                            previewFilePromise = adobeDCView.previewFile(
                                {
                                    content: {
                                        location: { url: "temp/temp.pdf" },
                                    },
                                    metaData: { fileName: selectedFile.name },
                                },
                                viewerConfig
                            );
                        },
                        cache: false,
                        contentType: false,
                        processData: false,
                    });
                }
            }

            const profile = {
                userProfile: {
                    name: "John Doe",
                    firstName: "John",
                    lastName: "Doe",
                    email: "gg@gg.com",
                },
            };

            adobeDCView.registerCallback(
                AdobeDC.View.Enum.CallbackType.GET_USER_PROFILE_API,
                (event) => {
                    return new Promise((resolve, reject) => {
                        resolve({
                            code: AdobeDC.View.Enum.ApiResponseCode.SUCCESS,
                            data: profile,
                        });
                    });
                }
            );

            adobeDCView.registerCallback(
                AdobeDC.View.Enum.CallbackType.EVENT_LISTENER,
                function (event) {
                    if (event.type === "PREVIEW_SELECTION_END") {
                        previewFilePromise.then((adobeViewer) => {
                            adobeViewer.getAPIs().then((apis) => {
                                apis.getSelectedContent().then((result) => {
                                    const { type, data } = result;
                                    if (type === "text") {
                                        selectedText = data;
                                    }
                                });
                            });
                        });
                    }
                    if (event.type === "PDF_VIEWER_READY") {
                        previewFilePromise.then((adobeViewer) => {
                            adobeViewer
                                .getAnnotationManager()
                                .then(function (annotationManager) {
                                    /* API to add annotations */
                                    annotationManager
                                        .addAnnotations(
                                            JSON.parse(
                                                localStorage.getItem("data")
                                            )
                                        )
                                        .then(function () {
                                            console.log(
                                                "Annotations added through API successfully"
                                            );
                                        })
                                        .catch(function (error) {
                                            console.log(error);
                                        });

                                    /* API to get all annotations */
                                    annotationManager
                                        .getAnnotations()
                                        .then(function (result) {
                                            console.log(
                                                "GET all annotations",
                                                result
                                            );
                                        })
                                        .catch(function (error) {
                                            console.log(error);
                                        });
                                });
                        });
                    }
                },
                { enableFilePreviewEvents: true }
            );
        },
        false
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
        adobeViewer.getAnnotationManager().then(function (annotationManager) {
            annotationManager
                .getAnnotations()
                .then(function (result) {
                    console.log("GET all annotations", result);
                    localStorage.setItem("data", JSON.stringify(result));
                })
                .catch(function (error) {
                    console.log(error);
                });
        });
    });
};

$(window).bind("beforeunload", function (e) {
    e.preventDefault();
    storeAnnotationData();

    setTimeout(() => {
        return true;
    }, 1000);
});
