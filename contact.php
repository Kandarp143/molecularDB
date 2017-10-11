<?php include('include/header.php') ?>
<html>
<head> <?php include('include/links.php') ?>
    <!--        https://jumk.de/nospam/stopspam.html-->
    <script type="text/javascript">
        <!--
        function UnCryptMailto(s) {
            var n = 0;
            var r = "";
            for (var i = 0; i < s.length; i++) {
                n = s.charCodeAt(i);
                if (n >= 8364) {
                    n = 128;
                }
                r += String.fromCharCode(n - 1);
            }
            return r;
        }

        function linkTo_UnCryptMailto(s) {
            location.href = UnCryptMailto(s);
        }

        // --> </script>
</head>
<body style='font-family="Andale Mono"'>

<div id="wrapper">
    <?php include('include/nav.php') ?>
    <div id="page">

        <div id="content">
            <div class="post">
                <h1 class="title">Contact Us ! </h1>
                <div class="entry">
                    
					<p>This database was developed an is maintained by the Boltzmann-Zuse Society for Computaional Molecular Engineering at the <a href="http://thermo.mv.uni-kl.de" target="_blank">
                             <b>Laboratory of Engineering Thermodynamics</b></a> at the University of Kaiserslautern (Prof. Hasse) and at the <a href="http://thet.uni-paderborn.de" target="_blank"> <b>Chair of Thermodynamics and Energy Technology</b></a> at the University of Paderborn (Prof. Vrabec).<br/></p>
					<p>Contact for the Molecular Models of the Boltzmann-Zuse Society:<br/></p>

                    <b>E-mail:</b>
                    <p><a href="javascript:linkTo_UnCryptMailto('nbjmup;tjnpo/tufqiboAnw/voj.lm/ef');"><b>simon.stephan[at]mv.uni-kl.de</b></a></p>
                    <b>Address:</b>
                    <p>
                        University of Kaiserslautern <br/>
                        Laboratory of Engineering Thermodynamics <br/>
                        Erwin-Schrödinger-Straße 44<br/>
                        D-67663 Kaiserslautern, Germany
                    </p>
                    <p>
                        <b>Phone:</b> +49 631 205-2311<br/>
                        <b>Fax:
                        </b> +49 631 205 3835<br/>

                    </p>
                    <p>Feel free to contact the developers with feedback, contributions, and requests for support.</p>

                </div>
            </div>
        </div>
        <!-- end #content    nbjmup;dpoubduANNC[T/ef  -->
        <style>
            .contact-pic {

                /*background: url(../img/img06.jpg) no-repeat left top;*/
                background: url(img/contact2.png);
                background-size: cover;
                opacity: 0.9;
                filter: alpha(opacity=90); /* For IE8 and earlier */
                min-height: 390px;
                margin: 0 0 0 10px;
            }
        </style>
        <div id="sidebar" class="contact-pic">
        </div>
        <!-- end #sidebar -->
        <div style="clear:both; margin:0;"></div>
    </div>
    <!-- end #page -->

</div>

<div id="footer">
    <?php include('include/footer.php') ?>
</div>
<!-- end #footer -->
</body>
</html>

