const mix = require("laravel-mix");

mix.js("resources/js/app.js", "public/js")
    .vue() // 👈 Thêm dòng này
    .sass("resources/sass/app.scss", "public/css");
