<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Jouw Kapper - Monnickendam</title>
  <meta content="Met al meer dan 20 jaar ervaring vind ik dit werk nog steeds elke dag net zo leuk. Iedere dag weer een nieuwe inspiratie! Als vrouw en moeder weet ik dat flexibiliteit belangrijk is. Door jou dat aan te bieden kan ik zelf ook flexibel zijn in het uitoefenen van mijn vak." name="description">
  <meta content="jouw kapper monnickendam waterland finnleys haircosmetics haarkleuren haarkleur knippen fohnen kleurbehandeling knip heren dames kinderen model tondeuse pony watergolf wassen permanenten uitgroei kleuring highlights verfspoeling deelkleuring balayage wenkbrauwen epileren verven opfrissen ervaring inspiratie flexibiliteit vak flexibel vrouw moeder werk feest bruiloft bruid arrangementen make-up haar nagels doneren gratis" name="keywords">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <!-- Favicons -->
  <link href="img/favicon.ico" rel="icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i|Raleway:300,400,500,700,800" rel="stylesheet">

  <!-- Bootstrap CSS File -->
  <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Libraries CSS Files -->
  <link href="lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="lib/animate/animate.min.css" rel="stylesheet">
  <link href="lib/venobox/venobox.css" rel="stylesheet">
  <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

  <!-- Main Stylesheet File -->
  <link href="css/style.css?v=2.1" rel="stylesheet">

  <!-- =======================================================
    Theme Name: TheEvent
    Theme URL: https://bootstrapmade.com/theevent-conference-event-bootstrap-template/
    Author: BootstrapMade.com
    License: https://bootstrapmade.com/license/
  ======================================================= -->

  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-157163460-1"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-157163460-1');
  </script>

</head>

<body>

  <!-- Facebook -->
	<div id="fb-root"></div>
	<script async defer crossorigin="anonymous" src="https://connect.facebook.net/nl_NL/sdk.js#xfbml=1&version=v6.0&appId=2465563456850426&autoLogAppEvents=1"></script>

  <!--==========================
    Header
  ============================-->
  <header id="header">
    <div class="container">

      <div id="logo" class="pull-left">
        <!-- Uncomment below if you prefer to use a text logo -->
        <h1><div class="d-none d-sm-inline ml-2"><a href="/"><span>Jouw</span>kapper</a></div></h1>
      </div>

      <nav id="nav-menu-container">
        <ul class="nav-menu">
          <li class="menu-active"><a href="#intro">Home</a></li>
          <li><a href="#diensten">Diensten</a></li>
          <li><a href="#schedule">Openingstijden</a></li>
          <li><a href="#gallery">Portfolio</a></li>
          <li><a href="#events">Acties</a></li>
          <li class="buy-tickets"><a href="#contact">Contact</a></li>
        </ul>
      </nav><!-- #nav-menu-container -->
    </div>
  </header><!-- #header -->

  <!--==========================
    Intro Section
  ============================-->




	<section id="intro">
		<div class="jumbotron text-white intro-container fadeIn ">
			<div class="container text-center mt-5">
				<div class="row justify-content-center">


            <?php
              $today = date('m-d');
              $start = '10-01';
              $end = '10-31';

              if ($start <= $today && $end >= $today) {
                echo
                '<div class="col-md-8 mb-1">
                    <div class="alert alert-secondary" role="alert">
                    <b>Update 02/10/2022</b><br>
                    We zijn verhuist naar Kalversteeg 2-A!
                    </div>
    					   </div>';
              }
            ?>
				</div>


        <div class="row justify-content-center">
        	<div class="col-md-6 col-lg-4">
				    <div class="text-center">
					    <img src="img/logo.png?80172489074" alt="Jouw Kapper" class="img-fluid">
					<!--<h1>Welkom bij<br><span>jouw</span> kapper.</h1>-->
				    </div>
          </div>
        </div>

				<div class="row justify-content-center mt-4">
					<div class="col-md-6">
						Ik ben Marielle. Met al meer dan 20 jaar ervaring vind ik dit werk nog steeds elke dag net zo leuk. Iedere dag weer een nieuwe inspiratie! Als vrouw en moeder weet ik dat flexibiliteit belangrijk is. Door jou dat aan te bieden kan ik zelf ook flexibel zijn in het uitoefenen van mijn vak.
          </div>
				</div>
			<!--<a href="#diensten" class="about-btn scrollto">Meer informatie</a>	-->

				<div class="row justify-content-center">
					<div class="col-md-7 col-lg-5">
						<div class="card mt-4">
						  <video controls>
							<source src="img/finnleys.mp4" type="video/mp4">
							Sorry, jouw browser ondersteunt geen video elementen.
						  </video>
						</div>
						<div class="card-text small">Partner van <a href="https://www.finnleys.eu/" target="_blank">Finnley's Haircosmetics</a>.</div>
					</div>
				</div>

			</div><!-- /.container   -->

			</div> <!-- jumbotron -->
	</section>

  <main id="main">

    <!--==========================
      About Section
    ============================-->
    <!--<section id="about">
      <div class="container">
        <div class="row">
          <div class="col-lg-6">
            <h2>Over Jouw Kapper</h2>
            <p>Sed nam ut dolor qui repellendus iusto odit. Possimus inventore eveniet accusamus error amet eius aut accusantium et. Non odit consequatur repudiandae sequi ea odio molestiae. Enim possimus sunt inventore in est ut optio sequi unde.</p>
          </div>
          <div class="col-lg-3">
            <h3>Where</h3>
            <p>Downtown Conference Center, New York</p>
          </div>
          <div class="col-lg-3">
            <h3>When</h3>
            <p>Monday to Wednesday<br>10-12 December</p>
          </div>
        </div>
      </div>
    </section>-->

    <!--==========================
      Diensten Section
    ============================-->
    <section id="diensten" class="wow fadeInUp">

      <div class="container">
		<div class="section-header">
		  <h2>Diensten</h2>
		</div>
	
		<!--<div class="row justify-content-center mb-2">
			<div class="col-lg-6">
				<b><mark>I.v.m. alle voorzorgsmaatregelen van COVID-19 voor onze hygiëne en veiligheid zijn alle prijzen omhoog gegaan.</b>
			</div>
		</div>-->
	
		<div class="row justify-content-center">
		<div class="col-lg-6">
            <h3 class="mt-4">Knip & kleurbehandelingen</h3>
            <ul class="list-group list-group-flush"> 
              <li class="list-group-item d-flex justify-content-between align-items-center">
                Wassen, knippen, drogen <div><b>€30,00/b></div>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                Wassen, knippen, föhnen <div><b>€38,50/b></div>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                Wassen, föhnen <div><b>€25,00</b></div>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                Pony knippen <div><b>€7,00</b></div>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                Heren knippen <div><b>€25,00</b></div>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                Tondeuse <div><b>€20,00</b></div>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                Kleuren, föhnen <div><b>€55,00</b></div>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                Knippen, kleuren, föhnen <div><b>€75,00</b></div>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                Knippen, 2 kleuren, föhnen <div><b>€87,50</b></div>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                Toner <div><b>€25,00</b></div>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                Toner tijdens kleurbehandeling <div><b>€15,00</b></div>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                Highlights (folie), knippen, föhnen <div><b>€97,50</b></div>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                2 kleuren (folie), knippen, föhnen <div><b>€105,00</b></div>
              </li>
            </ul>
	    </div>
	    <div class="col-lg-6">
		    
       		<div class="row">
           	<div class='col-md-12 col-xs-12'>
		            <h3 class="mt-4">Kinderen</h3>
		            <ul class="list-group list-group-flush">
		              <li class="list-group-item d-flex justify-content-between align-items-center">
		                Kinderen t/m 5 jaar <div><b>€15,00</b></div>
		              </li>
		              <li class="list-group-item d-flex justify-content-between align-items-center">
		                Kinderen 6 t/m 13 jaar <div><b>€20,00</b></div>
		              </li>
		              <li class="list-group-item d-flex justify-content-between align-items-center">
		                Kinderen 14 t/m 16 jaar <div><b>€25,00</b></div>
		              </li>
		            </ul>
	           </div>
	           <div class="CONTENT_B" class='col-md-12 col-xs-12'>
		            <h3 class="mt-4">Treatments</h3>
		            <ul class="list-group list-group-flush">
		              <li class="list-group-item d-flex justify-content-between align-items-center">
		                Simplex behandeling <div><b>€27,50</b></div>
		              </li>
		              <li class="list-group-item d-flex justify-content-between align-items-center">
		                Defrizz (keratine) behandeling <!--<div><b>€20,00</b></div>-->
		              </li>
		              <li class="list-group-item d-flex justify-content-between align-items-center">
		                Filltastic (botox) behandeling <!--<div><b>€25,00</b></div>-->
		              </li>
		            </ul>
	           </div>
	       	   </div>
	    	</div>
        </div>
	      <!--
        <div class="row justify-content-center">
        <div class="col-md-10">
          <h3 class="mt-4">Nagelbehandeling <mark class="text-muted">Nieuw!</mark></h3>
          <img class="img-max float-right" src="img/melsluxurynails.png"><br>
          Vanaf 3 oktober 2022 kunt u ook een afspraak maken bij <b>Mel's Luxury Nails</b>. <br>
          Neem contact op (+31 6 28583852) of kom langs!
        </div>
      </div>-->
    </section>

    <!--==========================
      Schedule Section
    ============================-->
    <section id="schedule" class="wow fadeInUp">

      <div class="container">

        <div class="section-header">
          <h2>Openingstijden</h2>
        </div>

				<div class="row justify-content-center">
					<div class="col-md-4">
            <ul class="list-group list-group-flush">
              <li class="list-group-item d-flex justify-content-between align-items-center">
                Maandag <b>9:00 – 13:30</b>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                Dinsdag <b>9:00 – 17:30</b>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                Woensdag <b>9:00 – 17:30</b>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                Donderdag <b>9:00 – 17:30</b>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                Vrijdag <b>9:00 – 17:30</b>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                Zaterdag <b>9:00 – 13:30</b>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                Zo <b>Op aanvraag</b>
              </li>
            </ul>
					</div>
        </div>
      </div>
    </section>

    <!--==========================
      Gallery Section
    ============================-->
    <section id="gallery" class="wow fadeInUp">

      <div class="container">
        <div class="section-header">
          <h2>Portfolio</h2>
        </div>
      </div>
      <div class="container gallery-wrapper">
        <div class="owl-carousel gallery-carousel">
          <?php
            for ($x = 16; $x > 0; $x--) {
                echo '<a href="img/gallery/'.$x.'.jpg" class="venobox" data-gall="gallery-carousel"><img src="img/gallery/'.$x.'.jpg" alt=""></a>';
            }
          ?>
        </div>
      </div>

    </section>

    <!--==========================
      Event Section
    ============================-->
    <section id="events" class="wow fadeInUp">

      <div class="container">
        <div class="section-header">
          <h2>Acties</h2>
        </div>

        <div class="row justify-content-center">
          <div class="col-md-3">
            <div class="card mb-4 box-shadow">
              <img class="card-img-top" src="img/acties/haarwensen.jpg" alt="Card image cap">
              <div class="card-body">
                <p class="card-text">Wil jij jouw haar doneren? Dan knippen wij jouw haar gratis!</p>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card mb-4 box-shadow">
              <img class="card-img-top" src="img/acties/bruiloft.jpg" alt="Card image cap">
              <div class="card-body">
                <p class="card-text">Heb jij binnenkort een feest of bruiloft? Of ben je zelf de bruid? Dan hebben wij verschillende arrangementen op maat! Haar en make-up, met eventueel nagels. Voor meer informatie zoals prijsopvage: bel, mail of app.</p>
              </div>
            </div>
          </div>


        </div>
      </div>


    </section>



    <!--==========================
      F.A.Q Section
    ============================-->
    <!--<section id="faq" class="wow fadeInUp">

      <div class="container">

        <div class="section-header">
          <h2>F.A.Q </h2>
        </div>

        <div class="row justify-content-center">
          <div class="col-lg-9">
              <ul id="faq-list">

                <li>
                  <a data-toggle="collapse" class="collapsed" href="#faq1">Non consectetur a erat nam at lectus urna duis? <i class="fa fa-minus-circle"></i></a>
                  <div id="faq1" class="collapse" data-parent="#faq-list">
                    <p>
                      Feugiat pretium nibh ipsum consequat. Tempus iaculis urna id volutpat lacus laoreet non curabitur gravida. Venenatis lectus magna fringilla urna porttitor rhoncus dolor purus non.
                    </p>
                  </div>
                </li>

                <li>
                  <a data-toggle="collapse" href="#faq2" class="collapsed">Feugiat scelerisque varius morbi enim nunc faucibus a pellentesque? <i class="fa fa-minus-circle"></i></a>
                  <div id="faq2" class="collapse" data-parent="#faq-list">
                    <p>
                      Dolor sit amet consectetur adipiscing elit pellentesque habitant morbi. Id interdum velit laoreet id donec ultrices. Fringilla phasellus faucibus scelerisque eleifend donec pretium. Est pellentesque elit ullamcorper dignissim. Mauris ultrices eros in cursus turpis massa tincidunt dui.
                    </p>
                  </div>
                </li>

                <li>
                  <a data-toggle="collapse" href="#faq3" class="collapsed">Dolor sit amet consectetur adipiscing elit pellentesque habitant morbi? <i class="fa fa-minus-circle"></i></a>
                  <div id="faq3" class="collapse" data-parent="#faq-list">
                    <p>
                      Eleifend mi in nulla posuere sollicitudin aliquam ultrices sagittis orci. Faucibus pulvinar elementum integer enim. Sem nulla pharetra diam sit amet nisl suscipit. Rutrum tellus pellentesque eu tincidunt. Lectus urna duis convallis convallis tellus. Urna molestie at elementum eu facilisis sed odio morbi quis
                    </p>
                  </div>
                </li>

                <li>
                  <a data-toggle="collapse" href="#faq4" class="collapsed">Ac odio tempor orci dapibus. Aliquam eleifend mi in nulla? <i class="fa fa-minus-circle"></i></a>
                  <div id="faq4" class="collapse" data-parent="#faq-list">
                    <p>
                      Dolor sit amet consectetur adipiscing elit pellentesque habitant morbi. Id interdum velit laoreet id donec ultrices. Fringilla phasellus faucibus scelerisque eleifend donec pretium. Est pellentesque elit ullamcorper dignissim. Mauris ultrices eros in cursus turpis massa tincidunt dui.
                    </p>
                  </div>
                </li>

                <li>
                  <a data-toggle="collapse" href="#faq5" class="collapsed">Tempus quam pellentesque nec nam aliquam sem et tortor consequat? <i class="fa fa-minus-circle"></i></a>
                  <div id="faq5" class="collapse" data-parent="#faq-list">
                    <p>
                      Molestie a iaculis at erat pellentesque adipiscing commodo. Dignissim suspendisse in est ante in. Nunc vel risus commodo viverra maecenas accumsan. Sit amet nisl suscipit adipiscing bibendum est. Purus gravida quis blandit turpis cursus in
                    </p>
                  </div>
                </li>

                <li>
                  <a data-toggle="collapse" href="#faq6" class="collapsed">Tortor vitae purus faucibus ornare. Varius vel pharetra vel turpis nunc eget lorem dolor? <i class="fa fa-minus-circle"></i></a>
                  <div id="faq6" class="collapse" data-parent="#faq-list">
                    <p>
                      Laoreet sit amet cursus sit amet dictum sit amet justo. Mauris vitae ultricies leo integer malesuada nunc vel. Tincidunt eget nullam non nisi est sit amet. Turpis nunc eget lorem dolor sed. Ut venenatis tellus in metus vulputate eu scelerisque. Pellentesque diam volutpat commodo sed egestas egestas fringilla phasellus faucibus. Nibh tellus molestie nunc non blandit massa enim nec.
                    </p>
                  </div>
                </li>

              </ul>
          </div>
        </div>

      </div>

    </section>-->

    <!--==========================
      Contact Section
    ============================-->
    <section id="contact" class="section-bg wow fadeInUp">

      <div class="container">

        <div class="section-header">
          <h2>Kom in contact</h2>
          <p>Neem contact met ons op om een afspraak te maken.</p>
        </div>

        <div class="row contact-info">

          <div class="col-sm-4">
            <div class="contact-address">
              <i class="ion-ios-location-outline"></i>
              <h3><i class="fa fa-home"></i></h3>
              <address><a target="_blank" href="https://goo.gl/maps/nVC19SeFZfMzkw4E7">
                Kalversteeg 2-A<br>1141SM Monnickendam<br>Nederland
              </a></address>
            </div>
          </div>

          <div class="col-sm-4">
            <div class="contact-phone">
              <i class="ion-ios-telephone-outline"></i>
              <h3><i class="fa fa-phone"></i></h3>
              <p><a href="tel:+31650747279">06 5074 7279</a></p>
            </div>
          </div>

          <div class="col-sm-4">
            <div class="contact-email">
              <i class="ion-ios-email-outline"></i>
              <h3><i class="fa fa-pencil"></i></h3>
              <p><a href="mailto:info@jouw-kapper.nl">info@jouw-kapper.nl</a></p>
            </div>
          </div>

        </div>

        <div class="form">
          <div id="sendmessage">Bedankt voor het berichtje! We zullen zo snel mogelijk contact opnemen.</div>
          <div id="errormessage"></div>
          <form action="" method="post" role="form" class="contactForm">
            <div class="form-row">
              <div class="form-group col-md-6">
                <input type="text" name="name" class="form-control" id="name" placeholder="Jouw naam" data-rule="minlen:3" data-msg="Please enter at least 3 chars" />
                <div class="validation"></div>
              </div>
              <div class="form-group col-md-6">
                <input type="email" class="form-control" name="email" id="email" placeholder="Jouw email" data-rule="email" data-msg="Please enter a valid email" />
                <div class="validation"></div>
              </div>
            </div>
            <div class="form-group">
              <input type="text" class="form-control" name="subject" id="subject" placeholder="Onderwerp" data-rule="minlen:4" data-msg="Please enter at least 8 chars of subject" />
              <div class="validation"></div>
            </div>
            <div class="form-group">
              <textarea class="form-control" name="message" rows="5" data-rule="required" data-msg="Please write something for us" placeholder="Bericht"></textarea>
              <div class="validation"></div>
            </div>
            <div class="text-center"><button type="submit">Verstuur</button></div>
          </form>
        </div>

      </div>
    </section><!-- #contact -->

  </main>


  <!--==========================
    Footer
  ============================-->
  <footer id="footer">
    <div class="footer-top">
      <div class="container">
        <div class="row">

          <div class="col-lg-3 col-md-6 footer-info">
            <img src="img/logo.png" alt="Jouwkapper">
            <p>Ik ben Marielle. Met al meer dan 20 jaar ervaring vind ik dit werk nog steeds elke dag net zo leuk. Iedere dag weer een nieuwe inspiratie! Als vrouw en moeder weet ik dat flexibiliteit belangrijk is. Door jou dat aan te bieden kan ik zelf ook flexibel zijn in het uitoefenen van mijn vak.</p>
          </div>

          <div class="col-lg-3 col-md-6 footer-links">
            <h4>Links</h4>
            <ul>
              <li><i class="fa fa-angle-right"></i> <a href="#intro">Home</a></li>
              <li><i class="fa fa-angle-right"></i> <a href="#diensten">Diensten</a></li>
              <li><i class="fa fa-angle-right"></i> <a href="#schedule">Openingstijden</a></li>
              <li><i class="fa fa-angle-right"></i> <a href="#gallery">Portfolio</a></li>
              <li><i class="fa fa-angle-right"></i> <a href="#events">Acties</a></li>
              <li><i class="fa fa-angle-right"></i> <a href="#contact">Contact</a></li>
            </ul>
          </div>

          <div class="col-lg-3 col-md-6 footer-contact">
            <h4>Contact</h4>
            <p>
              Kalversteeg 2-A<br>
              1141 SM Monnickendam <br>
              Nederland<br>
              <b>Mobiel:</b> (+31) 06 507 472 79<br>
              <b>Email:</b> info@jouw-kapper.nl<br>
            </p>

            <div class="social-links">
              <a href="https://www.facebook.com/Jouw-kapper-245623526155098/" class="facebook"><i class="fa fa-facebook"></i></a>
              <!--<a href="#" class="instagram"><i class="fa fa-instagram"></i></a>
              <a href="#" class="linkedin"><i class="fa fa-linkedin"></i></a>-->
              <!--<a href="#" class="twitter"><i class="fa fa-twitter"></i></a>
              <a href="#" class="google-plus"><i class="fa fa-google-plus"></i></a>-->
            </div>

          </div>

          <div class="fb-page" data-href="https://www.facebook.com/Jouw-kapper-245623526155098/" data-tabs="timeline" data-width="" data-height="" data-small-header="true" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><blockquote cite="https://www.facebook.com/Jouw-kapper-245623526155098/" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/Jouw-kapper-245623526155098/">Jouw-kapper</a></blockquote></div>

        </div>
      </div>
    </div>

    <div class="container">
      <div class="copyright">
        &copy; 2019 <b>Jouwkapper</b>. All Rights Reserved. | KvK: 68171390
      </div>
      <!--<div class="credits">
        Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
      </div>-->
    </div>
  </footer><!-- #footer -->

  <a href="#" class="back-to-top"><i class="fa fa-angle-up"></i></a>

  <!-- JavaScript Libraries -->
  <script src="lib/jquery/jquery.min.js"></script>
  <script src="lib/jquery/jquery-migrate.min.js"></script>
  <script src="lib/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="lib/easing/easing.min.js"></script>
  <script src="lib/superfish/hoverIntent.js"></script>
  <script src="lib/superfish/superfish.min.js"></script>
  <script src="lib/wow/wow.min.js"></script>
  <script src="lib/venobox/venobox.min.js"></script>
  <script src="lib/owlcarousel/owl.carousel.min.js"></script>

  <!-- Contact Form JavaScript File -->
  <script src="contactform/contactform.js"></script>

  <!-- Template Main Javascript File -->
  <script src="js/main.js"></script>
</body>

</html>
