import defaultConfig from "tailwindcss/defaultTheme";
import colors from "tailwindcss/colors";

/** @type {import('tailwindcss').Config} */
export default {
    content: ["./resources/**/*.blade.php"],
    theme: {
        extend: {
            fontFamily: {
                serif: ["STIX Two Text", ...defaultConfig.fontFamily.serif],
            },
            colors: {
                gray: colors.stone,
            },
        },
    },
    plugins: [require("@tailwindcss/forms")],
};
