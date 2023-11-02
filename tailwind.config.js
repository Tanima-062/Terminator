/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],

    theme: {
        extend: {
            colors: {
                body: "#F9FAFB",
                bodyText: "#4B4B4B",
                input: '#555555',
                value: '#707070',
                btnGradient1: "#C52E62",
                btnGradient2: "#8B387F",
                overlay: "#060606",
                title: "#8B387F",
                heading: "#AB326F",
                error: "#F93232",
                gray: "#6F6F6F",
                textGray: "#6F6F6F"
            },
            width: {
                588: "588px",
            },
            borderColor: {
                input: "#E6DEE5"
            },
            borderRadius: {
                sm: "10px",
            },
            fontFamily: {
                poppins: ["Poppins", "sans-serif"],
                inter: ["Inter", "sans-serif"],
                roboto: ["Roboto ", "sans-serif"],
                lato: ["Lato ", "sans-serif"],
            },
            fontSize: {
                formHeading: [
                    "25px",
                    {
                        lineHeight: "32px",
                        fontWeight: "600",
                    },
                ],
                formSubHeading: [
                    "20px",
                    {
                        lineHeight: "24px",
                        fontWeight: "600",
                    },
                ],
                formLabel: [
                    "18px",
                    {
                        lineHeight: "22px",
                        fontWeight: "600",
                        fontFamily: 'inter',
                    },
                ],
                12: [
                    '12px', {
                        lineHeight: '32px'
                    }
                ],
                16: [
                    '16px', {
                        lineHeight: '19px'
                    }
                ],
                p: [
                    "13px",
                    {
                        lineHeight: "16px",
                    },
                ],
                h4: [
                    "16px",
                    {
                        lineHeight: "19px",
                    },
                ],
                nd: [
                    "16px",
                    {
                        lineHeight: "24px",
                    }
                ],
                md: [
                    "20px",
                    {
                        lineHeight: "38px",
                    },
                ],
                "2xl": [
                    "25px",
                    {
                        lineHeight: "38px",
                    },
                ],
                "3xl": [
                    "31px",
                    {
                        lineHeight: "38px",
                    },
                ],
                "4xl": [
                    "40px",
                    {
                        lineHeight: "60px",
                    },
                ],
            },
            screens: {
                xs: { max: "600px" },
            },

            boxShadow: {
                link: '0px 1px 28px rgba(95, 76, 92, 0.18)',
                container: '1px 4px 28px rgba(95, 76, 92, 0.18)'
            }
        },
    },
    plugins: [],
}
