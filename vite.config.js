import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/sass/app.scss",
                "resources/js/jquery.js",
                "resources/js/jqueryui.js",
                "resources/js/app.js"],
            refresh: true,
        }),
    ],
});
