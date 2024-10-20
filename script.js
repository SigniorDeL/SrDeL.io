const express = require('express');
const multer = require('multer');
const path = require('path');
const fs = require('fs');

const app = express();
app.use(express.static('public'));

const storage = multer.diskStorage({
    destination: (req, file, cb) => {
        cb(null, 'uploads');
    },
    filename: (req, file, cb) => {
        cb(null, `${Date.now()}-${file.originalname}`);
    }
});

const upload = multer({ storage: storage });

let videoCount = 0; // Счетчик загруженных видео

app.post('/upload', upload.single('videoFile'), (req, res) => {
    const videoTitle = req.body.videoTitle;
    videoCount += 1;

    const oldPath = path.join(__dirname, 'uploads', req.file.filename);
    const newPath = path.join(__dirname, 'uploads', `${videoCount}.mp4`);

    fs.rename(oldPath, newPath, (err) => {
        if (err) return res.status(500).send(err);

        const videoPage = `
            <!DOCTYPE html>
            <html lang="ru">
            <head>
                <meta charset="UTF-8">
                <title>${videoTitle}</title>
            </head>
            <body>
                <h1>${videoTitle}</h1>
                <video width="640" height="480" controls>
                    <source src="/uploads/${videoCount}.mp4" type="video/mp4">
                    Ваш браузер не поддерживает воспроизведение видео.
                </video>
            </body>
            </html>
        `;

        fs.writeFileSync(path.join(__dirname, 'public', `${videoCount}.html`), videoPage);

        res.redirect(`/${videoCount}.html`);
    });
});

// Создаем папку, если не существует
fs.mkdirSync('uploads', { recursive: true });

const PORT = 3000;
app.listen(PORT, () => {
    console.log(`Сервер работает на ${PORT}`);
});
