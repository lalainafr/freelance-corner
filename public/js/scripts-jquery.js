//  --- JQUERY  --- 
    
    $(document).ready(function () {
        function loop() {
            $('#txt1').hide();
            $('#txt2').hide();
            $('#txt3').hide();
            $('#txt4').hide();
            function show_txt1() {
                $('#txt1').fadeToggle(5000).fadeToggle(4999);
            }
            function show_txt2() {
                $('#txt2').fadeToggle(4999).fadeToggle(4999);
            }
            function show_txt3() {
                $('#txt3').fadeToggle(5000).fadeToggle(4999);
            }
            function show_txt4() {
                $('#txt4').fadeToggle(5000).fadeToggle(4999);
            }
            show_txt1();
            window.setTimeout(show_txt2, 10000);
            window.setTimeout(show_txt3, 20000);
            window.setTimeout(show_txt4, 30000);
        }
        loop();


        // ANIMATION PAGE ACCUEIL - FAQ

        $('#content-faq1').hide();
        $('#content-faq2').hide();
        $('#content-faq3').hide();
        $('#content-faq4').hide();


        $('#top-faq1').click(() => {
            $('#content-faq1').slideToggle(1000).animate({ opacity: .7, margin: 15 })
        });
        $('#top-faq2').click(() => {
            $('#content-faq2').slideToggle(1000).animate({ opacity: .7, margin: 15 })
        });
        $('#top-faq3').click(() => {
            $('#content-faq3').slideToggle(1000).animate({ opacity: .7, margin: 15 })
        });
        $('#top-faq4').click(() => {
            $('#content-faq4').slideToggle(1000).animate({ opacity: .7, margin: 15 })
        });

    });





