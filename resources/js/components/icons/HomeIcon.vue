<template>
    <svg width="72" height="70" viewBox="0 0 72 70" fill="none" xmlns="http://www.w3.org/2000/svg">
        <g filter="url(#filter0_d_1_359)">
            <rect x="15" y="13" width="42" height="40" rx="6" fill="currentColor" />
        </g>
        <defs>
            <filter id="filter0_d_1_359" x="0" y="0" width="72" height="70" filterUnits="userSpaceOnUse"
                color-interpolation-filters="sRGB">
                <feFlood flood-opacity="0" result="BackgroundImageFix" />
                <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"
                    result="hardAlpha" />
                <feOffset dy="2" />
                <feGaussianBlur stdDeviation="7.5" />
                <feComposite in2="hardAlpha" operator="out" />
                <feColorMatrix type="matrix" values="0 0 0 0 0.219608 0 0 0 0 0.247059 0 0 0 0 0.419608 0 0 0 0.21 0" />
                <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_1_359" />
                <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_1_359" result="shape" />
            </filter>
        </defs>
    </svg>

</template>

<script>
export default {
    props: {
        fill: {
            default: '#AB326F'
        }
    }
}
</script>

<style lang="scss" scoped>

</style>
