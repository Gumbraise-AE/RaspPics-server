/** @type {import('tailwindcss').Config} */
module.exports = {
    content: ["./assets/**/*.js", "./templates/**/*.html.twig", "./templates/*.html.twig",], theme: {
        extend: {
            colors: {
                pourpre: {
                    900: "#170208",
                    800: "#1c020a",
                    700: "#20020b",
                    600: "#25020d",
                    500: "#29030e",
                    400: "#2e0310",
                    300: "#431c28",
                    200: "#583540",
                    100: "#6d4f58",
                    DEFAULT: "#2e0310",
                    light: "#c4505b",
                }
            }
        }, fontFamily: {
            display: ["Montserrat", "sans-serif"],
        },
    }, plugins: [],
}
