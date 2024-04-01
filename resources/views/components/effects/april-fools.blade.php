<div>

    <img id="rickRollGif" class="hidden fixed top-0 left-0 z-10 dvd-screen-animation" src="{{ asset('assets/uploads/april/rickroll.gif') }}" alt="">
    <img id="allStarGif" class="hidden fixed top-0 left-0 z-10 dvd-screen-animation spin-infinite" src="{{ asset('assets/uploads/april/all-star.gif') }}" alt="">

    <audio id="rickRollSong">
        <source src="{{ asset('assets/uploads/april/rr.mp3') }}" type="audio/mpeg">
    </audio>

    <audio id="allStarSong">
        <source src="{{ asset('assets/uploads/april/all-star.mp3') }}" type="audio/mpeg">
    </audio>

    <style type="text/css">
        button:hover{
            display: none !important;
        }
        a:hover{
            display: none !important;
        }
        input[type=submit]:hover{
            display: none !important;
        }
        div[wire:click]:hover{
            display: none !important;
        }
        .spin-infinite{
            animation: rotation 2s infinite linear;
        }

        .soft-shake-x{
            animation: shake-x 1s infinite linear;
        }

        .soft-shake-y{
            animation: shake-y 1s infinite linear;
        }

        .soft-shake-z{
            animation: shake-z 1s infinite linear;
        }

        @keyframes rotation{
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(359deg);
            }
        }

        @keyframes shake-x {
            0% {transform: rotateX(0deg);}
            25% {transform: rotateX(60deg);}
            50% {transform: rotateX(15deg);}
            75% {transform: rotateX(90deg);}
            100% {transform: rotateX(15deg);}
        }

        @keyframes shake-y {
            0% {transform: rotateY(0deg);}
            25% {transform: rotateY(66deg);}
            50% {transform: rotateY(0deg);}
            75% {transform: rotateY(45deg);}
            100% {transform: rotateY(5deg);}
        }

        @keyframes shake-z {
            0% {transform: rotateZ(0deg);}
            25% {transform: rotateZ(25deg);}
            50% {transform: rotateZ(0deg);}
            75% {transform: rotateZ(20deg);}
            100% {transform: rotateZ(5deg);}
        }

        @keyframes hor-movement {
            from {
                margin-left: 0%;
            }
            to {
                margin-left: 85%;
            }
        }

        @keyframes ver-movement {
            from {
                margin-top: 0%;
            }
            to {
                margin-top: 25%;
            }
        }

        .dvd-screen-animation {
            animation-name: hor-movement, ver-movement;
            animation-duration: 3.141s, 1.414s;
            animation-iteration-count: infinite;
            animation-direction: alternate;
            animation-timing-function: linear;
        }
    </style>

    <script type="text/javascript">
        $(function() {

            // Amount of times it has been triggered this session
            var timesTriggered = {!! $triggered !!};
            // Session key name for trigger amount
            var sessionKey = 'times-triggered';
            // Percentage chance of triggering
            var triggerValueMin = 15;
            var triggerValueMax = 45;
            // Max amount of times it can trigger
            var maxTriggers = 15;
            // Amount of seconds it will stay active
            var triggerDuration = 15;
            
            // Listen for click anywhere on page
            $("#background").click(function () {

                // Get random int between 1 and 100
                var diceRoll = getRandomInt(1, 100);

                if(diceRoll <= triggerValueMin && timesTriggered <= maxTriggers)
                {
                    rickRoll();
                    swapLogo();
                    addTrigger();
                }else if(diceRoll > triggerValueMin && diceRoll <= triggerValueMax && timesTriggered <= maxTriggers)
                {
                    allStar();
                    swapLogo();
                    addTrigger();
                }else
                {
                    swapLogo();
                    shakeWebsite();
                }
                
            });

            function addTrigger()
            {
                var times = parseInt(timesTriggered) + 1;
                var url = {!! json_encode(route('session.set')) !!};
                var payload = {
                    key: sessionKey,
                    value: times
                };

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "POST",
                    url: url,
                    data: payload,
                    cache: false,
                    success: function(data){
                        timesTriggered = data;
                    }
                });
            }

            function getRandomInt(min, max)
            {
                return Math.floor(Math.random() * max) + min;
            }

            function swapLogo()
            {

                var logoFull = $('#oholcurse-logo-full');

                if(logoFull)
                {
                    var logoFullApril = {!! json_encode(asset('assets/uploads/images/oholcurse-logo-april.png')) !!};
                    logoFull.attr('src', logoFullApril);
                }

                var logoSmall = $('#oholcurse-logo-small');

                if(logoSmall)
                {
                    var logoSmallApril = {!! json_encode(asset('assets/uploads/images/oholcurse-logo-april-small.png')) !!};

                    logoSmall.attr('src', logoSmallApril);
                    logoSmall.addClass('spin-infinite');
                }
            }

            function rickRoll()
            {
                var gif = $('#rickRollGif');
                gif.removeClass('hidden');

                var audioSource = document.getElementById("rickRollSong");
                audioSource.volume = 0.2;
                audioSource.play();

                setTimeout(function(){
                    gif.addClass('hidden');
                    audioSource.pause();
                }, triggerDuration * 1000);
            }

            function allStar()
            {
                var gif = $('#allStarGif');
                gif.removeClass('hidden');

                var audioSource = document.getElementById("allStarSong");
                audioSource.volume = 0.2;
                audioSource.play();

                setTimeout(function(){
                    gif.addClass('hidden');
                    audioSource.pause();
                }, triggerDuration * 1000);
            }

            function shakeWebsite()
            {
                var main = $("#background");
                var rand = getRandomInt(1, 100);

                if(rand < 33)
                {
                    var animation = "soft-shake-x";
                }else if(rand > 33 && rand < 66)
                {
                    var animation = "soft-shake-y";
                }else if(rand > 66)
                {
                    var animation = "soft-shake-z";
                }
                
                main.addClass(animation);

                setTimeout(function(){
                    main.removeClass(animation);
                }, 5000);
            }

        });
    </script>
</div>