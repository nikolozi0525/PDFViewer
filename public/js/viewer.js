/*
Copyright 2019 Adobe
All Rights Reserved.

NOTICE: Adobe permits you to use, modify, and distribute this file in
accordance with the terms of the Adobe license agreement accompanying
it. If you have received this file from a source other than Adobe,
then your use, modification, or distribution of it requires the prior
written permission of Adobe.
*/

/* Control the default view mode */
const viewerConfig = {
    /* Allowed possible values are "FIT_PAGE", "FIT_WIDTH", "TWO_COLUMN", "TWO_COLUMN_FIT_PAGE" or "". */
    defaultViewMode: "FIT_WIDTH",
    embedMode: "FULL_WINDOW",
    showDownloadPDF: false,
    showPrintPDF: false,
    showLeftHandPanel: true,
    // showDisabledSaveButton: true,

    // enableLinearization: true,
};

/* Wait for Adobe Acrobat Services PDF Embed API to be ready */
document.addEventListener("adobe_dc_view_sdk.ready", function () {
    const fileToRead = document.getElementById("file-picker");

    fileToRead.addEventListener(
        "change",
        function (event) {
            /* Initialize the AdobeDC View object */
            const adobeDCView = new AdobeDC.View({
                /* Pass your registered client id */
                clientId: "ef8f6d21909d4d0189accb2e78167985",
                /* Pass the div id in which PDF should be rendered */
                divId: "adobe-dc-view",
            });

            var files = fileToRead.files;
            if (files.length > 0) {
                if (files[0].type === "application/pdf") {
                    var reader = new FileReader();
                    reader.onloadend = function (e) {
                        const filePromise = Promise.resolve(e.target.result);

                        // Pass the filePromise and name of the file to the previewFile API
                        adobeDCView.previewFile(
                            {
                                content: { promise: filePromise },
                                metaData: { fileName: files[0].name },
                            },
                            viewerConfig
                        );
                    };
                    reader.readAsArrayBuffer(files[0]);
                } else {
                    const formData = new FormData();
                    formData.append("epub", files[0]);
                    $.ajax({
                        url: "http://localhost:4464/epub2pdf",
                        type: "POST",
                        data: formData,
                        success: function (data) {
                            adobeDCView.previewFile(
                                {
                                    content: {
                                        location: { url: "temp/temp.pdf" },
                                    },
                                    metaData: { fileName: files[0].name },
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
        },
        false
    );
});
