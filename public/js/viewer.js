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
    defaultViewMode: "SIZED_CONTAINER",
    enableLinearization: true,
};

/* Wait for Adobe Acrobat Services PDF Embed API to be ready */
document.addEventListener("adobe_dc_view_sdk.ready", function () {
    const fileToRead = document.getElementById("file-picker");

    fileToRead.addEventListener(
        "change",
        function (event) {
            var files = fileToRead.files;
            if (files.length > 0) {
                var reader = new FileReader();
                reader.onloadend = function (e) {
                    const filePromise = Promise.resolve(e.target.result);
                    /* Initialize the AdobeDC View object */
                    const adobeDCView = new AdobeDC.View({
                        /* Pass your registered client id */
                        clientId: "ef8f6d21909d4d0189accb2e78167985",
                        /* Pass the div id in which PDF should be rendered */
                        divId: "adobe-dc-view",
                    });

                    // Pass the filePromise and name of the file to the previewFile API
                    adobeDCView.previewFile({
                        content: { promise: filePromise },
                        metaData: { fileName: files[0].name },
                    });
                };
                reader.readAsArrayBuffer(files[0]);
            }
        },
        false
    );
});
