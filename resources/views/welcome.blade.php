<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Document</title>
	</head>
	<body>
		<div id="pdfviewer" style="height: 100vh"></div>

		<script src="assets/pdfviewer/pdfviewer.js"></script>

		<script>
			PSPDFKit.load({
				licenseKey: 'SAI1Z78kjMZTLUSAAva1NGHG-Ov56qcahINeaEExp6_AsD9bihF7Zqbn6_gSL7x8tc3stQdt1UmXiTAU-vOcAGJ-50yY_2ArbfseXqmRKV-elDsgF0B5yqLBI_pupSVpzV5q065z-4sFVsqSOv7hoSVBUdqiCATU0oW-N3bCJRjYxrmdoQ6n9IozC-c8IdE4LxFr5HqTBbb8P6U',
				container: "#pdfviewer",
				document: "1.pdf", // Add the path to your document here.
			})
			.then(function(instance) {
			
				console.log("Pdfviewer loaded", instance);
			})
			.catch(function(error) {
				console.error(error.message);
			});
		</script>
	</body>
</html>
