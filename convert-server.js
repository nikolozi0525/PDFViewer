var express = require("express");
const cors = require("cors");
var bodyParser = require("body-parser");
const ebookConvert = require("ebook-convert");
const multer = require("multer");

const storage = multer.diskStorage({
    destination: (req, file, cb) => {
        cb(null, "temp/");
    },
    filename: (req, file, cb) => {
        cb(null, "temp.epub");
    },
});
const upload = multer({ storage });

const app = express();

app.use(cors());

//Allow all requests from all domains & localhost
app.all("/*", function (req, res, next) {
    res.header("Access-Control-Allow-Origin", "*");
    res.header(
        "Access-Control-Allow-Headers",
        "X-Requested-With, Content-Type, Accept"
    );
    res.header("Access-Control-Allow-Methods", "POST, GET");
    next();
});

app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: false }));

app.post("/epub2pdf", upload.single("epub"), (req, res) => {
    const options = {
        input: "temp/temp.epub", //path to epub
        output: "public/temp/temp.pdf", //path to pdf
    };
    ebookConvert(options, function (err) {
        if (err) console.log(err);
        console.log("converted!");
        res.sendFile(__dirname + "/public/temp/temp.pdf");
    });
});

app.listen(4464, () => console.log(`Example app listening on port 4464!`));
