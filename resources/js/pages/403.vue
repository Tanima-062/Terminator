<template>
    <div class="container">
        <div class="left">
            <div class="number_text">
                <h2 class="title">
                    403
                </h2>
            </div>
            <div class="pt-20 pb-8">
                <h2
                    class="heading"
                >
                {{ $t('Access denied') }}
                </h2>
            </div>
            <div class="">
                <p class="description">
                    {{ $t('You do not have permission to view this resource') }}
                </p>
            </div>
            <ButtonGradient
                @click="goBackOrLogin()"
                type="button"
                class="w-3/5 mt-[70px] back-btn"
            >
                {{ $t("Back to the main page") }}
            </ButtonGradient>
        </div>
        <div class="right">
            <svg width="592" height="561" viewBox="0 0 592 561" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M174.73 493.435C174.73 493.435 269.156 535.074 367.294 486.844C465.431 438.614 519.94 332.653 566.359 247.98C612.778 163.306 594.564 72.4132 524.72 26.499C454.877 -19.4153 402 36.8902 300.33 32.4368C198.659 27.9834 172.266 11.3575 84.5196 63.0611C-3.22664 114.765 -26.7998 250.221 33.4691 356.137C93.7381 462.053 174.73 493.435 174.73 493.435Z" fill="#AB326F"/>
                <path opacity="0.7" d="M174.73 493.435C174.73 493.435 269.156 535.074 367.294 486.844C465.431 438.614 519.94 332.653 566.359 247.98C612.778 163.306 594.564 72.4132 524.72 26.499C454.877 -19.4153 402 36.8902 300.33 32.4368C198.659 27.9834 172.266 11.3575 84.5196 63.0611C-3.22664 114.765 -26.7998 250.221 33.4691 356.137C93.7381 462.053 174.73 493.435 174.73 493.435Z" fill="white"/>
                <path d="M87.5834 104.959C64.9243 134.061 50.9759 168.989 47.3547 205.694L19.4766 201.627C23.8347 159.779 39.9428 120.018 65.9401 86.9375L87.5834 104.959Z" stroke="#808080" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M331.483 430.871C292.336 447.803 249.173 453.223 207.055 446.495C164.937 439.767 125.611 421.169 93.6875 392.884L112.377 371.805C140.297 396.521 174.682 412.771 211.504 418.649C248.327 424.528 286.062 419.792 320.29 404.997L331.483 430.871Z" stroke="#808080" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M242.345 225.153V1.00017C183.554 0.926683 127.104 24.0264 85.2296 65.292L104.394 86.0002L112.484 94.7139L126.631 109.944L227.752 218.77L233.69 225.198L234.477 226.044L237.164 228.924L238.084 229.918L239.569 231.403L241.81 233.822L238.01 234.936L176.346 374.549C160.911 367.732 146.632 358.552 134.024 347.339L232.146 236.643L222.943 239.33L88.0501 278.727L68.0693 284.561L56.6538 287.886L29.5625 295.798C42.0246 333.238 64.1454 366.727 93.691 392.882L112.365 371.803C140.286 396.519 174.671 412.769 211.493 418.647C248.315 424.526 286.051 419.79 320.279 404.995L315.602 394.084L307.349 375.009C292.276 381.558 276.293 385.773 259.95 387.508L242.345 225.153ZM242.345 225.153L224.798 62.7981C196.286 65.8295 169.087 76.3658 145.974 93.3334L242.345 225.153Z" stroke="#808080" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M96.6652 112.585C75.4101 139.98 62.3753 172.85 59.0788 207.367L47.3516 205.69C50.9783 168.995 64.9264 134.078 87.5803 104.984L96.6652 112.585Z" stroke="#808080" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M232.169 236.646L235.48 232.905L237.721 230.382L238.167 229.877L242.353 225.156H233.773H231.457H181.95H79.0625" stroke="#808080" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M58.2765 225.156H46.4009H1.86719" stroke="#808080" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M93.6772 392.883L68.6641 421.102" stroke="#808080" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M112.367 371.8L120.25 362.908L134.04 347.336" stroke="#808080" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M438.304 225.157C438.316 263.377 427.15 300.765 406.181 332.719C385.213 364.673 355.357 389.798 320.29 404.999L315.555 394.088C344.724 381.451 370.123 361.475 389.278 336.106C408.434 310.737 420.693 280.84 424.862 249.326C429.031 217.811 424.967 185.755 413.067 156.277C401.167 126.8 381.836 100.907 356.956 81.1201L364.378 71.8125C387.457 90.1516 406.094 113.469 418.895 140.022C431.696 166.576 438.331 195.679 438.304 225.157Z" stroke="#808080" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M356.939 81.1123L343.994 97.4413C333.097 88.7347 321.123 81.4702 308.367 75.8276L316.769 56.7969C331.143 63.1522 344.643 71.324 356.939 81.1123Z" stroke="#808080" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M364.352 71.8074L404.15 21.7812H448.684" stroke="#808080" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M344.007 97.3672L242.352 225.149" stroke="#808080" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M228.089 93.3025L224.793 62.7969C196.281 65.8282 169.082 76.3646 145.969 93.3321L164.079 118.108C182.853 104.335 204.937 95.7767 228.089 93.3025Z" stroke="#808080" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M257.125 361.842L259.96 387.508C276.303 385.773 292.286 381.558 307.359 375.009L296.968 350.961C284.336 356.644 270.892 360.316 257.125 361.842Z" stroke="#808080" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M259.949 387.508C231.451 390.566 202.65 386.097 176.419 374.549L238.083 234.936L232.219 236.643L134.097 347.339C116.809 332.026 102.972 313.214 93.5027 292.149C84.0337 271.085 79.149 248.248 79.172 225.153C79.172 220.166 79.3946 215.222 79.8548 210.309C82.6023 179.611 94.0214 150.327 112.78 125.873C117.098 120.235 121.779 114.885 126.793 109.856C132.781 103.835 139.233 98.2957 146.091 93.2891L242.462 225.109L259.949 387.508Z" stroke="#808080" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M321.024 47.0156L316.734 56.724" stroke="#808080" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M308.336 75.7578L242.352 225.154" stroke="#808080" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M221.414 31.5547L222.557 42.1389L224.799 62.8025" stroke="#808080" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M242.346 225.153L64.2109 146.477" stroke="#808080" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M79.991 242.703L59.3274 244.944C58.6237 238.372 58.2719 231.766 58.2734 225.156H79.0558C79.0521 231.018 79.3643 236.875 79.991 242.703Z" stroke="#808080" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M59.3264 244.938L48.7422 246.081" stroke="#808080" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M242.345 225.156L85.1406 340.083" stroke="#808080" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M163.672 403.286L167.962 393.578L176.364 374.547" stroke="#808080" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M238.031 234.939L238.714 233.38L239.412 231.807L239.59 231.406L242.351 225.156" stroke="#808080" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M264.797 445.031L262.14 408.171L259.898 387.508" stroke="#808080" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M357.282 382.353L350.988 373.758L338.727 356.969" stroke="#808080" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M242.352 225.156L420.486 303.832" stroke="#808080" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M242.352 225.157L435.954 204.227" stroke="#808080" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M399.556 110.227L390.961 116.521" stroke="#808080" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M374.171 128.781L242.352 225.152" stroke="#808080" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M242.353 101.914C224.124 118.896 177.468 136.057 123.062 172.441C123.062 172.441 126.462 295.428 242.353 362.659V101.914Z" fill="#AB326F" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M242.352 101.914C260.581 118.896 307.237 136.057 361.643 172.441C361.643 172.441 358.243 295.428 242.352 362.659V101.914Z" fill="#263238" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M242.352 112.383C220.353 127.658 182.217 142.206 131.805 175.932C131.805 175.932 134.967 289.894 242.352 352.182C349.738 289.894 352.885 176.007 352.885 176.007C302.488 142.206 264.53 127.658 242.352 112.383Z" fill="#263238" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M352.884 176.003C302.413 142.276 264.529 127.728 242.352 112.453V352.178C349.737 289.89 352.884 176.003 352.884 176.003Z" fill="#AB326F" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M284.231 227.271H201.843C200.954 227.281 200.072 227.116 199.247 226.785C198.422 226.454 197.67 225.963 197.035 225.341C196.4 224.72 195.893 223.979 195.544 223.161C195.194 222.344 195.01 221.466 195 220.577V182.589C195 156.656 216.539 135.547 243.037 135.547C269.535 135.547 291.133 156.7 291.133 182.589V220.532C291.128 221.429 290.944 222.316 290.593 223.142C290.242 223.968 289.731 224.716 289.089 225.343C288.447 225.969 287.687 226.463 286.853 226.794C286.019 227.125 285.128 227.287 284.231 227.271ZM208.687 213.911H277.387V182.589C277.125 173.654 273.39 165.172 266.977 158.945C260.564 152.717 251.976 149.234 243.037 149.234C234.098 149.234 225.51 152.717 219.097 158.945C212.684 165.172 208.949 173.654 208.687 182.589V213.911Z" fill="white" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M294.798 208.969H189.906V295.735H294.798V208.969Z" fill="white" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M253.26 245.003C253.153 242.045 251.903 239.243 249.772 237.188C247.642 235.133 244.797 233.984 241.837 233.984C238.877 233.984 236.032 235.133 233.901 237.188C231.771 239.243 230.521 242.045 230.414 245.003C230.417 247.028 230.971 249.014 232.017 250.748C233.063 252.482 234.562 253.898 236.352 254.845L234.437 274.558H249.118L247.203 254.845C249.016 253.914 250.539 252.505 251.606 250.77C252.674 249.035 253.246 247.04 253.26 245.003Z" fill="#263238" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M501.492 40.0989H531.285V21.7807H513.991L512.24 17.3125H501.492V40.0989Z" fill="#AB326F" stroke="#263238" stroke-miterlimit="10"/>
                <path d="M501.492 69.7005H531.285V51.3971H513.991L512.24 46.9141H501.492V69.7005Z" fill="#AB326F" stroke="#263238" stroke-miterlimit="10"/>
                <path d="M501.492 99.3169H531.285V80.9987H513.991L512.24 76.5156H501.492V99.3169Z" fill="#263238" stroke="#263238" stroke-miterlimit="10"/>
                <path d="M501.492 285.036H531.285V266.718H513.991L512.24 262.25H501.492V285.036Z" fill="#AB326F" stroke="#263238" stroke-miterlimit="10"/>
                <path d="M501.492 314.638H531.285V296.335H513.991L512.24 291.852H501.492V314.638Z" fill="#AB326F" stroke="#263238" stroke-miterlimit="10"/>
                <path d="M501.492 344.255H531.285V325.937H513.991L512.24 321.469H501.492V344.255Z" fill="#263238" stroke="#263238" stroke-miterlimit="10"/>
                <path d="M501.492 375.591H531.285V357.273H513.991L512.24 352.805H501.492V375.591Z" fill="#AB326F" stroke="#263238" stroke-miterlimit="10"/>
                <path d="M501.492 405.193H531.285V386.889H513.991L512.24 382.406H501.492V405.193Z" fill="#AB326F" stroke="#263238" stroke-miterlimit="10"/>
                <path d="M501.492 434.802H531.285V416.484H513.991L512.24 412.016H501.492V434.802Z" fill="#263238" stroke="#263238" stroke-miterlimit="10"/>
                <path d="M457.209 187.165C456.993 184.2 456.993 181.223 457.209 178.258" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M466.86 186.393C466.682 183.888 466.682 181.373 466.86 178.867" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M457.016 182.73L466.709 182.656" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M457.219 177.1C457.474 174.138 457.931 171.197 458.584 168.297" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M466.883 177.907C467.105 175.41 467.492 172.93 468.041 170.484" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M457.758 172.68L467.333 174.164" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M458.859 167.172C459.569 164.28 460.497 161.447 461.635 158.695" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M468.266 169.514C468.87 167.077 469.649 164.685 470.596 162.359" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M460.086 162.883L469.304 165.896" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M462.078 157.623C463.253 154.891 464.623 152.247 466.175 149.711" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M470.984 161.453C471.98 159.149 473.135 156.916 474.443 154.773" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M463.977 153.602L472.586 158.055" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M466.781 148.735C467.568 147.473 468.414 146.256 469.32 145.068L472.11 141.609" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M474.969 153.946C475.633 152.877 476.352 151.841 477.121 150.844L479.482 147.875" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M469.32 145.07L477.114 150.845" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M472.844 140.707C473.853 139.624 474.922 138.585 475.961 137.516C476.495 136.996 476.985 136.447 477.549 136.031L479.257 134.547" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M480.078 147.148L482.72 144.461C483.166 144.016 483.581 143.541 484.057 143.125L485.541 141.938" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M475.953 137.516L482.722 144.463" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M480.164 133.783C481.278 132.796 482.464 131.893 483.712 131.081C484.944 130.265 486.161 129.404 487.423 128.617" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M486.273 141.303C487.204 140.472 488.197 139.712 489.242 139.031C490.296 138.349 491.321 137.547 492.375 136.953" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M483.703 131.086L489.255 139.028" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M488.445 128.071L492.364 125.948C493.715 125.339 495.096 124.79 496.461 124.211" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M493.258 136.47L496.568 134.688L500.027 133.219" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M492.359 125.945L496.575 134.689" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M497.539 123.752C498.905 123.202 500.36 122.846 501.77 122.371L503.893 121.718L506.075 121.258" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M500.945 132.834C502.118 132.359 503.335 132.063 504.523 131.677L506.334 131.113L508.175 130.727" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M501.766 122.367L504.512 131.675" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M507.219 121.079C508.661 120.742 510.124 120.499 511.598 120.352C513.082 120.203 514.567 119.995 516.051 119.906" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M509.133 130.533C510.352 130.228 511.592 130.019 512.844 129.909C514.091 129.791 515.338 129.598 516.585 129.523" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M511.609 120.289L512.856 129.908" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M517.195 119.817L521.649 119.758L526.102 120.114" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M517.562 129.512L521.333 129.453L525.089 129.765" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M521.648 119.758L521.336 129.451" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M527.266 120.203C528.75 120.381 530.235 120.708 531.719 120.96C532.446 121.094 533.203 121.213 533.901 121.361L536.054 121.97" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M526.07 129.836C527.317 129.984 528.549 130.266 529.781 130.474C530.39 130.578 531.014 130.682 531.637 130.816L533.433 131.32" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M531.659 120.961L529.789 130.476" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M537.109 122.297C539.967 123.116 542.765 124.133 545.482 125.34" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M534.383 131.602C536.791 132.297 539.152 133.15 541.449 134.155" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M541.338 123.68L537.969 132.779" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M546.516 125.875L550.479 127.909C551.771 128.636 552.988 129.497 554.235 130.299" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M542.312 134.617L545.667 136.339C546.751 136.948 547.775 137.69 548.844 138.358" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M550.474 127.906L545.68 136.338" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M555.203 130.922C556.436 131.749 557.62 132.646 558.751 133.609C559.936 134.505 561.066 135.471 562.136 136.503" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M549.68 138.891C550.716 139.585 551.707 140.344 552.649 141.162C553.662 141.914 554.624 142.732 555.528 143.611" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M558.758 133.609L552.672 141.165" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M562.977 137.32C564.016 138.374 565.129 139.369 566.109 140.482L568.944 143.926" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M556.25 144.297C557.126 145.202 558.076 146.034 558.892 146.969L561.297 149.938" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M566.113 140.484L558.898 146.971" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M569.68 144.828C571.43 147.236 573.022 149.755 574.445 152.369" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M561.93 150.617C563.399 152.653 564.738 154.78 565.938 156.986" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M572.181 148.516L564.031 153.756" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M574.992 153.383L576.031 155.342L576.907 157.406C577.456 158.771 578.11 160.122 578.555 161.532" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M566.383 157.852L567.273 159.514L568.001 161.251C568.461 162.424 569.04 163.552 569.485 164.739" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M576.922 157.406L568.016 161.251" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M578.914 162.641C579.345 164.125 579.82 165.461 580.191 166.901L581.111 171.265" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M569.711 165.688C570.067 166.89 570.483 168.077 570.809 169.295L571.581 172.976" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M580.201 166.906L570.805 169.296" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M581.352 172.414C581.776 175.365 582.014 178.34 582.064 181.321" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M571.781 173.938C572.124 176.398 572.322 178.876 572.375 181.36" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M581.844 176.836L572.18 177.682" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M582.069 182.445C582.069 183.188 582.069 183.93 582.069 184.672L581.891 186.884C581.742 188.368 581.727 189.853 581.445 191.337" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M572.386 182.43C572.386 183.053 572.386 183.677 572.386 184.3L572.238 186.185C572.176 187.438 572.047 188.687 571.852 189.926" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M581.89 186.885L572.227 186.188" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M581.252 192.469C580.97 193.953 580.762 195.438 580.42 196.848L579.203 201.138" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M571.674 190.852C571.422 192.084 571.259 193.331 570.962 194.548L569.938 198.17" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M580.426 196.85L570.984 194.594" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M578.871 202.25C577.887 205.056 576.716 207.794 575.367 210.444" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M569.68 199.164C568.838 201.526 567.846 203.831 566.711 206.067" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M577.256 206.405L568.305 202.664" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M574.834 211.484L573.81 213.459C573.438 214.097 573.008 214.706 572.622 215.344L570.188 219.055" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M566.278 206.938L565.417 208.615C565.105 209.164 564.734 209.669 564.407 210.203L562.344 213.365" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M572.63 215.339L564.406 210.203" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M569.457 219.977L566.651 223.51C565.716 224.667 564.602 225.662 563.578 226.731" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M561.708 214.125L559.348 217.094C558.561 218.074 557.611 218.905 556.75 219.81" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M566.655 223.431L559.352 217.062" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M562.764 227.484C561.715 228.544 560.6 229.535 559.424 230.453C558.307 231.433 557.133 232.345 555.906 233.185" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M556.063 220.539C555.183 221.436 554.241 222.27 553.242 223.033C552.302 223.867 551.311 224.64 550.273 225.349" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M559.432 230.436L553.242 222.969" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M554.956 233.898C553.694 234.7 552.521 235.62 551.2 236.318L547.281 238.352" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M549.418 225.82C548.349 226.503 547.369 227.305 546.256 227.869L542.945 229.665" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M551.196 236.247L546.297 227.875" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M546.242 238.867C543.542 240.111 540.759 241.167 537.914 242.029" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M542.13 230.102C539.845 231.145 537.495 232.037 535.094 232.774" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M542.129 240.61L538.641 231.555" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M536.812 242.375C535.398 242.829 533.955 243.186 532.492 243.444C531.024 243.785 529.537 244.043 528.039 244.216" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M534.129 233.055C532.936 233.442 531.715 233.74 530.478 233.945C529.261 234.248 528.027 234.471 526.781 234.613" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M532.488 243.446L530.484 233.945" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M526.946 244.289C525.461 244.423 523.977 244.705 522.492 244.69H518.039" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M525.807 234.719C524.563 234.899 523.309 235.013 522.052 235.06H518.281" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M522.515 244.748L522.055 235.055" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M516.899 244.781C513.909 244.641 510.932 244.289 507.992 243.727" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M517.297 235.086C514.806 234.958 512.326 234.66 509.875 234.195" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M512.461 244.423L513.545 234.789" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M506.917 243.513C505.456 243.247 504.014 242.89 502.597 242.445C501.154 242.086 499.736 241.635 498.352 241.094" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M508.862 234.018C507.626 233.797 506.406 233.494 505.21 233.113C503.996 232.811 502.801 232.434 501.633 231.984" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M502.594 242.447L505.221 233.109" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M497.262 240.682C495.882 240.103 494.457 239.643 493.135 238.975L489.172 236.867" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M500.728 231.628C499.57 231.138 498.367 230.767 497.254 230.144L493.914 228.422" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M493.125 238.975L497.237 230.188" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M488.141 236.392C485.677 234.729 483.094 233.259 480.719 231.359" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M492.993 227.956C490.915 226.472 488.703 225.314 486.773 223.711" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M484.414 233.954L489.877 225.938" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M479.886 230.646C477.584 228.762 475.421 226.713 473.414 224.516" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M486.033 223.149C484.095 221.55 482.27 219.819 480.57 217.969" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M476.555 227.692L483.22 220.641" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M472.631 223.674C470.676 221.433 468.876 219.063 467.242 216.578" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M479.909 217.243C478.254 215.371 476.731 213.387 475.352 211.305" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M469.812 220.214L477.532 214.336" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M466.608 215.594C465.03 213.073 463.631 210.444 462.422 207.727" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M474.814 210.439C473.483 208.308 472.302 206.086 471.281 203.789" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M464.375 211.737L472.94 207.18" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M461.944 206.673C460.804 203.925 459.851 201.103 459.094 198.227" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M470.881 202.906C469.918 200.583 469.115 198.198 468.477 195.766" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M460.391 202.499L469.565 199.367" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M458.859 197.116C458.164 194.224 457.668 191.288 457.375 188.328" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M468.223 194.813C467.64 192.369 467.219 189.89 466.961 187.391" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M457.906 192.75L467.466 191.117" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M520.008 144.461C525.37 144.528 530.658 145.733 535.519 147.997C540.381 150.261 544.706 153.533 548.208 157.595C551.709 161.656 554.308 166.416 555.832 171.558C557.355 176.7 557.769 182.107 557.045 187.421" stroke="#263238" stroke-width="2" stroke-miterlimit="10"/>
                <path d="M484.25 168.697C487.028 161.492 491.943 155.309 498.334 150.976C504.726 146.643 512.289 144.368 520.011 144.456" stroke="#AB326F" stroke-width="5" stroke-miterlimit="10"/>
                <path d="M526.391 219.501C519.78 220.711 512.967 220.138 506.651 217.841C500.335 215.545 494.745 211.608 490.455 206.435C486.165 201.263 483.33 195.041 482.242 188.409C481.153 181.778 481.851 174.976 484.262 168.703" stroke="#263238" stroke-width="2" stroke-miterlimit="10"/>
                <path d="M557.052 187.422C555.967 195.317 552.418 202.667 546.912 208.427C541.405 214.187 534.221 218.063 526.383 219.501" stroke="#AB326F" stroke-width="5" stroke-miterlimit="10"/>
                <path d="M204.391 408.176H234.184V389.858H216.89L215.138 385.375H204.391V408.176Z" fill="#AB326F" stroke="#263238" stroke-miterlimit="10"/>
                <path d="M204.391 437.771H234.184V419.453H216.89L215.138 414.984H204.391V437.771Z" fill="#AB326F" stroke="#263238" stroke-miterlimit="10"/>
                <path d="M204.391 467.372H234.184V449.054H216.89L215.138 444.586H204.391V467.372Z" fill="#263238" stroke="#263238" stroke-miterlimit="10"/>
                <path d="M251.891 408.176H281.684V389.858H264.39L262.638 385.375H251.891V408.176Z" fill="#AB326F" stroke="#263238" stroke-miterlimit="10"/>
                <path d="M251.891 437.771H281.684V419.453H264.39L262.638 414.984H251.891V437.771Z" fill="#263238" stroke="#263238" stroke-miterlimit="10"/>
                <path d="M251.891 467.372H281.684V449.054H264.39L262.638 444.586H251.891V467.372Z" fill="#AB326F" stroke="#263238" stroke-miterlimit="10"/>
                <path d="M204.391 35.5747H234.184V17.2565H216.89L215.138 12.7734H204.391V35.5747Z" fill="#AB326F" stroke="#263238" stroke-miterlimit="10"/>
                <path d="M204.391 65.177H234.184V46.8588H216.89L215.138 42.3906H204.391V65.177Z" fill="#AB326F" stroke="#263238" stroke-miterlimit="10"/>
                <path d="M204.391 94.7786H234.184V76.4604H216.89L215.138 71.9922H204.391V94.7786Z" fill="#AB326F" stroke="#263238" stroke-miterlimit="10"/>
                <path d="M251.891 35.5747H281.684V17.2565H264.39L262.638 12.7734H251.891V35.5747Z" fill="#263238" stroke="#263238" stroke-miterlimit="10"/>
                <path d="M251.891 65.177H281.684V46.8588H264.39L262.638 42.3906H251.891V65.177Z" fill="#AB326F" stroke="#263238" stroke-miterlimit="10"/>
                <path d="M251.891 94.7786H281.684V76.4604H264.39L262.638 71.9922H251.891V94.7786Z" fill="#263238" stroke="#263238" stroke-miterlimit="10"/>
                <path d="M418.887 560.642C463.979 560.642 500.532 554.993 500.532 548.024C500.532 541.055 463.979 535.406 418.887 535.406C373.796 535.406 337.242 541.055 337.242 548.024C337.242 554.993 373.796 560.642 418.887 560.642Z" fill="#263238"/>
                <path d="M403.718 530.672C403.718 530.672 388.131 540.692 379.595 542.548C371.06 544.403 368.462 547.001 375.513 550.341C382.564 553.681 404.089 554.795 414.109 552.568C424.129 550.341 427.84 553.31 428.954 548.857C429.779 544.676 429.652 540.364 428.583 536.239C427.84 532.899 409.656 527.703 403.718 530.672Z" fill="white" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M383.312 330.266C383.312 330.266 385.168 359.213 390.364 381.851C395.559 404.489 398.157 434.92 400.755 451.62C403.353 468.32 405.208 495.783 405.579 508.03C405.95 520.276 406.693 523.987 405.95 525.101C405.208 526.214 403.353 526.585 403.353 528.07C403.353 529.554 402.982 532.894 402.982 532.894C402.982 532.894 406.322 535.863 415.228 539.203C424.135 542.543 430.073 544.399 430.073 544.399C432.743 541.833 434.447 538.425 434.897 534.75C436.011 528.07 432.671 520.276 432.671 515.823C432.671 511.37 442.32 472.403 438.98 456.445C435.64 440.487 428.96 422.302 428.96 412.653C428.96 403.004 432.671 374.428 432.671 374.428C432.671 374.428 442.32 358.471 442.691 346.966C442.92 340.16 442.796 333.347 442.32 326.555C442.32 326.555 398.528 332.864 383.312 330.266Z" fill="#263238" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M390.966 226.936C387.583 236.161 385.747 245.881 385.533 255.704C385.533 270.623 383.365 302.925 381.183 317.027C379.001 331.13 377.398 331.961 381.183 334.128C384.968 336.296 396.117 338.745 412.668 337.661C429.22 336.578 446.811 331.145 447.687 328.755C448.563 326.365 447.954 323.054 447.137 319.848C446.321 316.641 450.106 282.395 441.986 253.908C433.866 225.422 428.136 211.022 424.336 207.222L420.536 203.422L390.966 226.936Z" fill="#AB326F" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M365.452 154.73C365.452 154.73 364.917 164.78 366.268 168.313C367.619 171.846 368.168 175.899 366.818 178.615C365.467 181.332 362.735 184.553 364.635 186.216C365.889 187.266 367.253 188.176 368.703 188.932C368.703 188.932 374.136 198.7 376.852 204.133C379.569 209.566 380.92 210.917 383.636 209.833C385.612 208.995 387.518 208.002 389.337 206.864C389.337 206.864 396.937 215.548 398.021 217.716C398.996 219.317 400.289 220.702 401.821 221.783L420.288 201.684C417.102 198.135 414.86 193.842 413.771 189.199C411.871 181.332 411.336 180.293 413.236 176.715C415.137 173.137 418.937 172.915 416.755 164.231C414.572 155.547 417.571 152.83 409.703 147.412C401.836 141.994 386.902 141.162 382.568 141.162C379.076 141.067 375.648 142.109 372.8 144.131C370.603 145.779 365.452 154.73 365.452 154.73Z" fill="white" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M383.069 134.662C383.069 134.662 363.534 140.897 360.802 143.347C358.071 145.796 364.885 160.715 364.885 160.715L378.452 152.832L372.767 159.676C372.767 159.676 369.798 160.477 380.367 159.943C390.937 159.408 396.103 155.593 396.103 155.593C396.103 155.593 389.319 163.194 389.868 165.361C390.417 167.528 394.203 171.061 395.286 172.694C396.37 174.327 398.003 172.16 398.003 172.16C398.003 172.16 399.636 178.944 400.17 177.311C400.686 174.892 401.048 172.443 401.254 169.978C401.254 169.978 403.703 164.559 407.192 165.91C410.68 167.261 412.61 175.945 410.16 180.027C408.629 182.484 406.192 184.238 403.376 184.911C403.376 184.911 409.077 191.146 410.695 189.795C412.313 188.444 412.877 185.728 413.961 184.362C415.044 182.996 428.612 171.343 430.29 162.927C431.967 154.51 419.706 134.158 410.205 132.258C400.704 130.358 383.069 134.662 383.069 134.662Z" fill="#263238" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M366.913 169.769L369.124 178.675C369.229 179.148 369.481 179.575 369.844 179.895C370.207 180.215 370.662 180.412 371.143 180.457C373.622 180.679 378.15 180.902 378.744 179.729C379.56 178.111 378.477 167.527 377.111 165.894C375.448 163.89 371.663 165.493 369.822 166.369C369.077 166.784 368.358 167.245 367.67 167.75C367.363 167.978 367.128 168.29 366.994 168.648C366.86 169.006 366.831 169.395 366.913 169.769Z" fill="white" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M379.008 170.199L401.542 168.031L379.112 172.648L379.008 170.199Z" fill="#263238" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M402.08 184.914C402.08 184.914 405.346 194.415 403.164 197.116C400.982 199.818 393.945 204.182 393.945 204.182" fill="white"/>
                <path d="M402.08 184.914C402.08 184.914 405.346 194.415 403.164 197.116C400.982 199.818 393.945 204.182 393.945 204.182" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M420.27 196.266C420.27 196.266 385.534 228.018 386.35 228.835C387.167 229.651 393.357 239.077 393.357 239.077C398.105 231.994 403.223 225.166 408.691 218.622C414.415 212.571 420.456 206.828 426.787 201.417L420.27 196.266Z" fill="#263238" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M338.636 281.477C338.636 281.477 328.869 281.477 326.969 283.659C325.068 285.841 330.22 287.993 332.12 288.261C334.497 288.25 336.866 287.981 339.186 287.459L338.636 281.477Z" fill="white" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M342.916 279.041C342.916 279.041 338.566 277.408 337.483 280.941C336.399 284.474 339.116 295.043 340.734 295.325C342.352 295.607 343.183 294.509 343.183 291.258C343.183 288.007 342.916 279.041 342.916 279.041Z" fill="white" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M350.511 278.432C350.511 278.432 344.81 275.181 342.628 277.615C340.446 280.05 342.91 294.449 342.91 294.449C344.528 295.266 347.245 285.765 349.694 282.781C350.8 281.515 351.794 280.154 352.663 278.714L350.511 278.432Z" fill="white" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M385.79 278.777C385.79 278.777 371.687 291.796 369.238 291.796C366.789 291.796 356.754 280.128 354.319 278.777C351.885 277.426 348.619 285.012 347.802 287.461C347.187 290.133 346.824 292.856 346.719 295.596C346.883 295.814 347.1 295.986 347.35 296.096C347.599 296.206 347.873 296.249 348.145 296.222C348.416 296.195 348.676 296.099 348.9 295.943C349.123 295.786 349.302 295.575 349.42 295.329C351.114 292.443 352.655 289.47 354.037 286.422L355.67 288.604" fill="white"/>
                <path d="M385.79 278.777C385.79 278.777 371.687 291.796 369.238 291.796C366.789 291.796 356.754 280.128 354.319 278.777C351.885 277.426 348.619 285.012 347.802 287.461C347.187 290.133 346.824 292.856 346.719 295.596C346.883 295.814 347.1 295.986 347.35 296.096C347.599 296.206 347.873 296.249 348.145 296.222C348.416 296.195 348.676 296.099 348.9 295.943C349.123 295.786 349.302 295.575 349.42 295.329C351.114 292.443 352.655 289.47 354.037 286.422L355.67 288.604" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M363.274 287.992C363.274 287.992 354.857 289.358 353.507 291.792C352.156 294.227 351.874 294.509 354.857 294.761C357.841 295.014 364.907 295.845 365.991 294.494C367.074 293.143 364.358 288.259 363.274 287.992Z" fill="white" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M314.418 304.548L280.766 231.008L301.385 232.091L330.436 301.03L314.418 304.548Z" fill="#7D7D7D" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M328.535 295.863L313.602 302.379H395.291L384.173 295.328L328.535 295.863Z" fill="#7D7D7D" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M417.004 226.391C417.004 226.391 406.687 228.291 405.054 237.524C403.421 246.757 408.023 282.058 408.023 282.845V289.361C408.023 290.979 414.539 290.445 422.14 293.963C429.74 297.481 434.357 303.196 434.357 298.847C434.357 294.497 433.273 242.438 433.273 242.438" fill="#AB326F"/>
                <path d="M417.004 226.391C417.004 226.391 406.687 228.291 405.054 237.524C403.421 246.757 408.023 282.058 408.023 282.845V289.361C408.023 290.979 414.539 290.445 422.14 293.963C429.74 297.481 434.357 303.196 434.357 298.847C434.357 294.497 433.273 242.438 433.273 242.438" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M433.828 263.286L438.178 248.367" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M402.906 285.007L404.257 296.674C404.257 296.674 433.842 314.043 434.659 313.508C435.475 312.974 436.292 311.326 436.292 308.624C436.292 305.923 440.626 291.791 435.475 288.54C430.324 285.289 411.382 284.739 411.382 284.739C411.382 284.739 405.89 283.656 402.906 285.007Z" fill="#263238" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M406.419 296.144C406.419 296.144 378.466 306.179 360.282 307.263C342.097 308.347 339.93 307.263 339.93 307.263C339.93 307.263 338.297 300.746 335.58 299.128C332.864 297.51 324.18 298.846 322.547 297.762C320.914 296.679 320.929 293.161 319.845 292.062C318.761 290.964 313.907 291.528 313.907 291.528C313.907 291.528 311.473 292.878 311.473 294.779C311.473 296.679 309.84 308.896 312.007 309.979C314.175 311.063 342.409 316.496 352.993 316.496C363.577 316.496 394.795 319.465 410.798 317.031C426.8 314.596 432.783 312.696 432.248 308.347C431.714 303.997 411.57 295.046 406.419 296.144Z" fill="white" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M312.523 293.156L313.073 307.526" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M316.875 291.789L317.677 305.906" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M322.558 297.727C322.558 297.727 321.474 300.992 323.374 302.343C325.274 303.694 332.607 304.793 332.607 304.793" fill="white"/>
                <path d="M322.558 297.727C322.558 297.727 321.474 300.992 323.374 302.343C325.274 303.694 332.607 304.793 332.607 304.793" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M403.44 257.875H390.406V279.311H403.44V257.875Z" fill="white" stroke="#263238" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>

        </div>
    </div>
</template>

<script setup>
import ButtonGradient from "@/components/button/GradientCustome.vue";
import { useRouter, useRoute } from "vue-router";


const router = useRouter();

const goBackOrLogin = () => {
    router.push({ name: "dashboard" })
};
</script>

<style lang="scss" scoped>
.container {
    display: flex;
    margin: auto auto;
    width: 1100px;
    margin-top: 10%;
    gap: 125px;
}

.left {
    width: 50%;
    margin-top: 10%;
}

.right {
    width: 50%;
}

.title {
    font-family: 'Poppins';
    font-style: normal;
    font-weight: 600;
    font-size: 156px;
    line-height: 32px;
    /* or 21% */

    display: flex;
    align-items: center;

    color: #AB326F;
}

.heading {
    font-family: 'Poppins';
    font-style: normal;
    font-weight: 600;
    font-size: 39px;
    line-height: 32px;
    color: #263238;
}

.description {
    font-family: 'Poppins';
    font-style: normal;
    font-weight: 400;
    font-size: 25px;
    line-height: 32px;
    color: #636363;
}

.back-btn {
    background: linear-gradient(262.9deg, #C52E62 -44.07%, #8B387F 155.41%);
    border-radius: 10px;
}
</style>
