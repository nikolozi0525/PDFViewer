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
    const adobeDCView = new AdobeDC.View({
        /* Pass your registered client id */
        clientId: "e3440e9a3b9a4f75a22e577afe799d36",
        /* Pass the div id in which PDF should be rendered */
        divId: "adobe-dc-view",
    });

    previewFilePromise = adobeDCView.previewFile(
        {
            content: {
                location: { url: bookInfo.file },
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
                                    JSON.parse(bookInfo.annotations)
                                    // JSON.parse(localStorage.getItem("data"))
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
                                    console.log("GET all annotations", result);
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
                    // localStorage.setItem("data", JSON.stringify(result));
                    $.ajax({
                        url: `${location.origin}/api/annotations`,
                        type: "POST",
                        data: {
                            id: bookInfo.id,
                            annotations: JSON.stringify(result),
                        },
                        success: (res) => {
                            console.log("******  ", res);
                        },
                    });
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
