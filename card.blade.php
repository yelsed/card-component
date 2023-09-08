@props(['product'])
<div class="card user">
    <span style="background: {{ $product->color }}" class="inner-card-backface">
        <span class="flex flex-col items-center justify-center flip-inner-card">
            <h1 class="text-6xl font lg:text-9xl/normal">€{{ $product->price }},-</h3>
            <p class="lg:text-xl font">{{ __('Credit only to be used with the real card') }}</p>
        </span>
    </span>
    <span style="background: {{ $product->color }}" class="relative flex items-center inner-card">
        <span class="flex flex-col justify-center w-full">
            <x-icons.tally-ho />
            <span class="mx-auto text-5xl font lg:text-7xl/10">Giftcard</span>
        </span>
        <span class="glare"></span>
    </span>
</div>

@push('scripts')
    <script>
        let calculateAngle = function(e, item, parent) {
            let dropShadowColor = `rgba(0, 0, 0, 0.3)`
            if (parent.getAttribute('data-filter-color') !== null) {
                dropShadowColor = parent.getAttribute('data-filter-color');
            }

            parent.classList.add('animated');
            // Get the x position of the users mouse, relative to the button itself
            let x = Math.abs(item.getBoundingClientRect().x - e.clientX);
            // Get the y position relative to the button
            let y = Math.abs(item.getBoundingClientRect().y - e.clientY);

            // Calculate half the width and height
            let halfWidth = item.getBoundingClientRect().width / 2;
            let halfHeight = item.getBoundingClientRect().height / 2;

            // Use this to create an angle. I have divided by 6 and 4 respectively so the effect looks good.
            // Changing these numbers will change the depth of the effect.
            let calcAngleX = (x - halfWidth) / 6;
            let calcAngleY = (y - halfHeight) / 14;

            let gX = (1 - (x / (halfWidth * 2))) * 100;
            let gY = (1 - (y / (halfHeight * 2))) * 100;

            item.querySelector('.glare').style.background =
                `radial-gradient(circle at ${gX}% ${gY}%, rgb(199 198 243), transparent)`;
            // And set its container's perspective.
            parent.style.perspective = `${halfWidth * 6}px`
            item.style.perspective = `${halfWidth * 6}px`

            // Set the items transform CSS property
            item.style.transform = `rotateY(${calcAngleX}deg) rotateX(${-calcAngleY}deg) scale(1.04)`;
            parent.querySelector('.inner-card-backface').style.transform =
                `rotateY(${calcAngleX}deg) rotateX(${-calcAngleY}deg) scale(1.04) translateZ(-4px)`;

            if (parent.getAttribute('data-custom-perspective') !== null) {
                parent.style.perspective = `${parent.getAttribute('data-custom-perspective')}`
            }

            // Reapply this to the shadow, with different dividers
            let calcShadowX = (x - halfWidth) / 3;
            let calcShadowY = (y - halfHeight) / 6;

            // Add a filter shadow - this is more performant to animate than a regular box shadow.
            item.style.filter = `drop-shadow(${-calcShadowX}px ${-calcShadowY}px 15px ${dropShadowColor})`;
        }

        document.querySelectorAll('.card').forEach(function(item) {
            item.addEventListener('click', function() {
                item.classList.toggle('flipped');
            });

            item.addEventListener('mouseenter', function(e) {
                calculateAngle(e, this.querySelector('.inner-card'), this);
            });

            item.addEventListener('mousemove', function(e) {
                calculateAngle(e, this.querySelector('.inner-card'), this);
            });

            item.addEventListener('mouseleave', function(e) {
                let dropShadowColor = `rgba(0, 0, 0, 0.3)`
                if (item.getAttribute('data-filter-color') !== null) {
                    dropShadowColor = item.getAttribute('data-filter-color')
                }
                item.classList.remove('animated');
                item.querySelector('.inner-card').style.transform = `rotateY(0deg) rotateX(0deg) scale(1)`;
                item.querySelector('.inner-card-backface').style.transform =
                    `rotateY(0deg) rotateX(0deg) scale(1.01) translateZ(-4px)`;
                item.querySelector('.inner-card').style.filter =
                    `drop-shadow(0 10px 15px ${dropShadowColor})`;
            });
        })
    </script>
@endpush

@push('styles')
    <style>
        .font {
            font-family: 'Palmer Lake Print';
            font-weight: normal;
            font-style: normal;
        }
        #dark-light-container>div {
            float: left;
            box-sizing: border-box;
            position: relative;
            padding: 0,5rem;
            width: 50%;
            text-align: center;
        }

        .white-container {
            background: white;
        }

        .black-container {
            background: black;
        }

        .card {
            box-shadow: none;
            backface-visibility: visible;
            background: transparent;
            font-family: Inter, -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Oxygen, Ubuntu, Cantarell, Open Sans, Helvetica Neue, sans-serif;
            transform-style: preserve-3d;
            padding: 0;
            height: auto;
            margin: 0 2rem 4rem 0;
            width: 18rem;
            height: 25rem;
            float: left;
            transition: all 0.2s ease-out;
            border: none;
            letter-spacing: 1px;
        }

        .flip,
        .unflip {
            background: rgba(224, 33, 33, 0.1);
            font-size: 1rem;
            position: absolute;
            top: 1rem;
            right: 1rem;
            padding: 0.5rem 0.75rem;
            border-radius: 100px;
            line-height: 1rem;
            cursor: pointer;
            transition: all 0.1s ease-out;
        }

        .unflip {
            top: auto;
            background: #4d4dd6;
            bottom: 1rem;
        }

        .flip:hover {
            background: #1b29ed;
            background: rgba(0, 0, 0, 0.3);
        }

        .inner-card-backface {
            transform: rotateX(0) rotateY(0deg) scale(1) translateZ(-4px);
            border-radius: 14px;
            position: absolute;
            color: rgb(0, 0, 0);
            box-sizing: border-box;
            transition: all 0.15s ease-out;
            will-change: transform, filter;
            width: 100%;
            height: 100%;
        }

        .card.flipped {
            transform: rotateY(180deg);
        }

        .card .flip-inner-card {
            transform: rotateY(180deg);
            padding: 2rem 1.5rem;
            box-sizing: border-box;
            width: 100%;
            height: 100%;
        }

        .card .inner-card {
            font-size: 2rem;
            color: white;
            padding: 1rem 2rem;
            line-height: 3rem;
            will-change: transform, filter;
            float: none;
            background-size: calc(100% + 6px) auto;
            background-position: -3px -3px;
            transition: all 0.15s ease-out;
            height: auto;
            border-radius: 14px;
            box-sizing: border-box;
            overflow: hidden;
            transform: rotateX(0deg) rotateY(0deg) scale(1);
            height: 100%;
            filter: drop-shadow(0 15px 15px rgba(0, 0, 0, 0.3));
            font-weight: 500;
            perspective-origin: 0 0;
            letter-spacing: 0;
        }

        .card .glare {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            transition: all 0.1s ease-out;
            opacity: 0.6;
            pointer-events: none;
            height: 100%;
            border-radius: 14px;
            z-index: 9999;
            mix-blend-mode: hard-light;
            background: radial-gradient(circle at 50% 50%, rgb(199 198 243), transparent);
        }

        .card .top-section {
            background: linear-gradient(45deg, hsl(0deg 58% 51%), hsl(249deg 100% 54%));
            position: absolute;
            top: 0;
            left: 0;
            border-radius: 14px 14px 0 0;
            width: 100%;
            height: 35%;
            z-index: 99;
        }

        .card .glare {
            opacity: 0;
        }

        .card.animated .glare {
            opacity: 0.3;
        }

        .card.animated.user .glare {
            opacity: 0.15;
        }

        .card .name {
            color: white;
            font-size: 1.5rem;
        }

        .card .bottom-section {
            position: absolute;
            top: 35%;
            left: 0;
            /* background: #0b0b2a; */
            width: 100%;
            height: 65%;
            box-sizing: border-box;
            padding-top: 64px;
            text-align: center;
        }

        .bottom-section .area {
            font-size: 1rem;
            opacity: 0.4;
            display: block;
            line-height: 1rem;
        }

        @media screen and (max-width: 1000px) {
            #dark-light-container>div {
                width: 100%;
            }
        }
    </style>
@endpush
