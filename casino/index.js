require('dotenv').config();

const express = require('express');
const bodyParser = require('body-parser');
const nodemailer = require('nodemailer');
const cors = require('cors');

const app = express();
const PORT = 3000;

app.use(cors());
app.use(bodyParser.urlencoded({ extended: true }));
app.use(bodyParser.json());

app.post('/send', async (req, res) => {
    const email = req.body.email;

    if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        return res.status(400).send('無効なメールアドレスです');
    }

    try {
        const transporter = nodemailer.createTransport({
            service: 'Gmail',
            auth: {
                user:process.env.EMAIL_USER,
                pass:process.env.EMAIL_PASS
            }
        });

        await transporter.sendMail({
            from: 'xijingzhiwewn@gmail.com',
            to: email,
            subject: '確認メール',
            text: 'これは確認メールです。'
        });

        res.send('メール送信成功');
    } catch (err) {
        console.error(err);
        res.status(500).send('メール送信失敗');
    }
});

app.listen(PORT, () => {
    console.log(`サーバー起動中: http://localhost:${PORT}`);
});

console.log("EMAIL_USER:", process.env.EMAIL_USER);
console.log("EMAIL_PASS:", process.env.EMAIL_PASS);

const fs = require('fs');
console.log(fs.readFileSync('.env', 'utf8'));

