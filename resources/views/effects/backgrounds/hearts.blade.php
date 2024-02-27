@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/effects/backgrounds/hearts.css') }}">
@endpush

@push('scripts')
    <script type="text/javascript">
        const colors = ["#e03776","#8f3e98","#4687bf","#3bab6f","#f9c25e","#f47274"];
        const SVG_NS = 'http://www.w3.org/2000/svg';
        const SVG_XLINK = "http://www.w3.org/1999/xlink";

        let heartsRy = []

        function useTheHeart(n){
        let use = document.createElementNS(SVG_NS, 'use');
        use.n = n;
        use.setAttributeNS(SVG_XLINK, 'xlink:href', '#heart');
        use.setAttributeNS(null, 'transform', `scale(${use.n})`);
        use.setAttributeNS(null, 'fill', colors[n%colors.length]);
        use.setAttributeNS(null, 'x', -69);
        use.setAttributeNS(null, 'y', -69);
        use.setAttributeNS(null, 'width', 138);
        use.setAttributeNS(null, 'height', 138);
        use.setAttributeNS(null, 'z-index', -1);
        
        heartsRy.push(use)
        hearts.appendChild(use);
        }

        for(let n = 18; n >= 0; n--){useTheHeart(n)}

        function Frame(){
        window.requestAnimationFrame(Frame);
        for(let i = 0; i < heartsRy.length; i++){
            if(heartsRy[i].n < 18){heartsRy[i].n +=.01
            }else{
            heartsRy[i].n = 0;
            hearts.appendChild(heartsRy[i])
            }
            heartsRy[i].setAttributeNS(null, 'transform', `scale(${heartsRy[i].n})`);
        }
        }

        Frame()
    </script>
@endpush
<div class="absolute overflow-hidden">
    <svg id="hearts" viewBox="-600 -400 1200 800">
        <defs>
            <symbol  id="heart" viewBox="-69 -16 138 138">
            <path d="M0,12
                    C 50,-30 110,50  0,120
                    C-110,50 -50,-30 0,12z"/>
            </symbol>
        </defs>
    </svg>
</div>