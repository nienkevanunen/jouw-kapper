<?php
  require_once __DIR__ . '/includes/content.php';

  $site = load_content('site.json');
  $pageText = load_content('page-text.json');
  $prices = load_content('prices.json');
  $openingHours = load_content('opening-hours.json');
  $promotions = load_content('promotions.json');
  $gallery = load_content('gallery.json');

  $activeAddress = get_active_address($site);
  $address = $activeAddress['street'] ?? '';
  $addressLine2 = $activeAddress['line2'] ?? '';
  $addressFull = $activeAddress['fullHtml'] ?? '';
  $mapsLink = $activeAddress['mapsLink'] ?? '';
  $facebookUrl = $site['facebookUrl'] ?? 'https://www.facebook.com/profile.php?id=100063615640789';

  $zaterdagSluit = get_schedule_variable('zaterdag_sluit', $openingHours);
?>
<!DOCTYPE html>
<html lang="nl-NL">

<head>
  <meta charset="utf-8">
  <title><?php echo htmlspecialchars(content_text($pageText, 'meta.title', 'Jouw Kapper - Monnickendam')); ?></title>
  <meta content="<?php echo htmlspecialchars(content_text($pageText, 'meta.description')); ?>" name="description">
  <meta content="<?php echo htmlspecialchars(content_text($pageText, 'meta.keywords')); ?>" name="keywords">
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
  <link href="css/style.css?v=2.2" rel="stylesheet">


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
  <script src="https://kit.fontawesome.com/9479742b9e.js" crossorigin="anonymous"></script>

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
          <li class="menu-active"><a href="#intro"><?php echo htmlspecialchars(content_text($pageText, 'nav.home', 'Home')); ?></a></li>
          <li><a href="#diensten"><?php echo htmlspecialchars(content_text($pageText, 'nav.services', 'Diensten')); ?></a></li>
          <li><a href="#schedule"><?php echo htmlspecialchars(content_text($pageText, 'nav.hours', 'Openingstijden')); ?></a></li>
          <li><a href="#updates"><?php echo htmlspecialchars(content_text($pageText, 'nav.updates', 'Updates')); ?></a></li>
          <li><a href="#gallery"><?php echo htmlspecialchars(content_text($pageText, 'nav.portfolio', 'Portfolio')); ?></a></li>
          <li><a href="#events"><?php echo htmlspecialchars(content_text($pageText, 'nav.promotions', 'Acties')); ?></a></li>
          <li class="buy-tickets"><a href="#contact"><?php echo htmlspecialchars(content_text($pageText, 'nav.contact', 'Contact')); ?></a></li>
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

              #if ($start <= $today && $end >= $today) {
              #  echo
              #  '<div class="col-md-8 mb-1">
              #      <div class="alert alert-secondary" role="alert">
              #      <b>Update 02/10/2022</b><br>
              #      We zijn verhuist naar Kalversteeg 2-A!
              #      </div>
    	#				   </div>';
        #      }
            ?>
				</div>


        <div class="row justify-content-center">
        	<div class="col-md-6 col-lg-4">
				    <div class="text-center">
					    <img src="img/logo.png?80172489074" alt="<?php echo htmlspecialchars(content_text($pageText, 'intro.logoAlt', 'Jouw Kapper')); ?>" class="img-fluid">
					<!--<h1>Welkom bij<br><span>jouw</span> kapper.</h1>-->
				    </div>
          </div>
        </div>

				<div class="row justify-content-center mt-4">
					<div class="col-md-6">
            <a href="<?php echo htmlspecialchars($site['bookingUrl'] ?? ''); ?>" target="_blank"><?php echo htmlspecialchars(content_text($pageText, 'intro.bookingLinkText', 'Klik hier om een afspraak te maken →')); ?></a>
          </div>
				</div>
			<!--<a href="#diensten" class="about-btn scrollto">Meer informatie</a>	-->

				<div class="row justify-content-center">
					<div class="col-md-7 col-lg-5">
						<div class="card mt-4">
						  <video controls>
							<source src="img/finnleys.mp4" type="video/mp4">
							<?php echo htmlspecialchars(content_text($pageText, 'intro.videoFallback', 'Sorry, jouw browser ondersteunt geen video elementen.')); ?>
						  </video>
						</div>
						<i><div class="card-text small"><?php echo htmlspecialchars(content_text($pageText, 'intro.partnerPrefix', 'Partner van')); ?> <a href="https://www.finnleys.eu/" target="_blank"><?php echo htmlspecialchars(content_text($pageText, 'intro.partnerName', "Finnley's Haircosmetics")); ?></a></div></i>
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
    <section id="diensten" class="site-section section-soft wow fadeInUp">

      <div class="container">
		<div class="section-header">
		  <h2><?php echo htmlspecialchars(content_text($pageText, 'sections.servicesTitle', 'Diensten')); ?></h2>
      <p><?php echo htmlspecialchars(content_text($pageText, 'sections.servicesText', 'Heldere behandelingen en prijzen, overzichtelijk gegroepeerd per service.')); ?></p>
		</div>
	
		<!--<div class="row justify-content-center mb-2">
			<div class="col-lg-6">
				<b><mark>I.v.m. alle voorzorgsmaatregelen van COVID-19 voor onze hygiëne en veiligheid zijn alle prijzen omhoog gegaan.</b>
			</div>
		</div>-->
	

		<div class="row justify-content-center service-grid">
      <?php render_price_sections($prices); ?>
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
      </div>
  </section>

    <!--==========================
      Schedule Section
    ============================-->
    <section id="schedule" class="site-section section-light wow fadeInUp">

      <div class="container">

        <div class="section-header">
          <h2><?php echo htmlspecialchars(content_text($pageText, 'sections.hoursTitle', 'Openingstijden')); ?></h2>
          <p><?php echo htmlspecialchars(content_text($pageText, 'sections.hoursText', 'Plan je bezoek op een moment dat goed past.')); ?></p>
        </div>

				<div class="row justify-content-center">
					<div class="col-sm-10 col-md-7 col-lg-5">
            <ul class="list-group list-group-flush hours-card">
              <?php render_opening_hours($openingHours, ['zaterdag_sluit' => $zaterdagSluit]); ?>
            </ul>
					</div>
        </div>
      </div>
    </section>

    <!--==========================
      Updates Section
    ============================-->
    <section id="updates" class="site-section wow fadeInUp">
      <div class="container">
        <div class="updates-panel">
          <div class="row align-items-center">
            <div class="col-lg-4">
              <div class="updates-copy">
                <span class="updates-eyebrow"><?php echo htmlspecialchars(content_text($pageText, 'updates.eyebrow', 'Live vanaf Facebook')); ?></span>
                <h2><?php echo htmlspecialchars(content_text($pageText, 'updates.title', 'Laatste updates uit de salon')); ?></h2>
                <p>
                  <?php echo content_nl2br(content_text($pageText, 'updates.text')); ?>
                </p>

                <div class="updates-pills" aria-label="Soorten updates">
                  <?php foreach (content_lines(content_text($pageText, 'updates.pills')) as $pill) : ?>
                    <span><?php echo htmlspecialchars($pill); ?></span>
                  <?php endforeach; ?>
                </div>

                <a class="updates-button" href="<?php echo htmlspecialchars($facebookUrl); ?>" target="_blank" rel="noopener">
                  <?php echo htmlspecialchars(content_text($pageText, 'updates.buttonText', 'Volg Jouw Kapper op Facebook')); ?>
                </a>
              </div>
            </div>

            <div class="col-lg-8">
              <div class="facebook-updates-card">
                <div class="facebook-updates-topbar">
                  <span></span>
                  <span></span>
                  <span></span>
                </div>

                <div class="facebook-updates-feed text-center">
                  <div class="fb-page"
                    data-href="<?php echo htmlspecialchars($facebookUrl); ?>"
                    data-tabs="timeline"
                    data-width="640"
                    data-height="560"
                    data-small-header="true"
                    data-adapt-container-width="true"
                    data-hide-cover="false"
                    data-show-facepile="false">
                    <blockquote cite="<?php echo htmlspecialchars($facebookUrl); ?>" class="fb-xfbml-parse-ignore">
                      <a href="<?php echo htmlspecialchars($facebookUrl); ?>" target="_blank" rel="noopener"><?php echo htmlspecialchars(content_text($pageText, 'updates.feedLinkText', 'Bekijk de laatste updates op Facebook')); ?></a>
                    </blockquote>
                  </div>
                </div>

                <p class="facebook-updates-fallback">
                  <?php echo htmlspecialchars(content_text($pageText, 'updates.fallbackBeforeLink', 'Feed niet zichtbaar? Bekijk de updates direct op')); ?>
                  <a href="<?php echo htmlspecialchars($facebookUrl); ?>" target="_blank" rel="noopener"><?php echo htmlspecialchars(content_text($pageText, 'updates.fallbackLinkText', 'Facebook')); ?></a><?php echo htmlspecialchars(content_text($pageText, 'updates.fallbackAfterLink', '.')); ?>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!--==========================
      Gallery Section
    ============================-->
    <section id="gallery" class="site-section section-soft wow fadeInUp">

      <div class="container">
        <div class="section-header">
          <h2><?php echo htmlspecialchars(content_text($pageText, 'sections.portfolioTitle', 'Portfolio')); ?></h2>
          <p><?php echo htmlspecialchars(content_text($pageText, 'sections.portfolioText', 'Een indruk van recent werk, kleuren en styling uit de salon.')); ?></p>
        </div>
      </div>
      <div class="container gallery-wrapper">
        <div class="owl-carousel gallery-carousel">
          <?php render_gallery($gallery); ?>
        </div>
      </div>

    </section>

    <!--==========================
      Event Section
    ============================-->
    <section id="events" class="site-section section-light wow fadeInUp">

      <div class="container">
        <div class="section-header">
          <h2><?php echo htmlspecialchars(content_text($pageText, 'sections.promotionsTitle', 'Acties')); ?></h2>
          <p><?php echo htmlspecialchars(content_text($pageText, 'sections.promotionsText', 'Speciale mogelijkheden en arrangementen die extra aandacht verdienen.')); ?></p>
        </div>

        <div class="row justify-content-center promo-grid">
          <?php render_promotions($promotions); ?>
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
    <section id="contact" class="site-section section-soft wow fadeInUp">

      <div class="container">

        <div class="section-header">
          <h2><?php echo htmlspecialchars(content_text($pageText, 'sections.contactTitle', 'Kom in contact')); ?></h2>
          <p><?php echo htmlspecialchars(content_text($pageText, 'sections.contactTextBeforeLink', 'Om een afspraak te maken, klik')); ?> <a href="<?php echo htmlspecialchars($site['bookingUrl'] ?? ''); ?>" target="_blank"><?php echo htmlspecialchars(content_text($pageText, 'sections.contactLinkText', 'hier')); ?></a> <?php echo htmlspecialchars(content_text($pageText, 'sections.contactTextAfterLink', 'of neem contact op.')); ?></p>
        </div>

        <div class="row contact-info">

          <div class="col-sm-4">
            <div class="contact-address">
              <i class="ion-ios-location-outline"></i>
              <h3><i class="fa fa-home"></i></h3>
              <address><a target="_blank" href="<?php echo $mapsLink; ?>">
                <?php echo $addressFull; ?>
              </a></address>
            </div>
          </div>

          <div class="col-sm-4">
            <div class="contact-phone">
              <i class="ion-ios-telephone-outline"></i>
              <h3><i class="fa fa-phone"></i></h3>
              <p><a href="tel:<?php echo htmlspecialchars($site['phone']['tel'] ?? ''); ?>"><?php echo htmlspecialchars($site['phone']['display'] ?? ''); ?></a></p>
            </div>
          </div>

          <div class="col-sm-4">
            <div class="contact-email">
              <i class="ion-ios-email-outline"></i>
              <h3><i class="fa fa-pencil"></i></h3>
              <p><a href="mailto:<?php echo htmlspecialchars($site['email'] ?? ''); ?>"><?php echo htmlspecialchars($site['email'] ?? ''); ?></a></p>
            </div>
          </div>

        </div>

        <div class="form">
          <div id="sendmessage"><?php echo htmlspecialchars(content_text($pageText, 'contactForm.successMessage', 'Bedankt voor het berichtje! We zullen zo snel mogelijk contact opnemen.')); ?></div>
          <div id="errormessage"></div>
          <form action="" method="post" role="form" class="contactForm">
            <div class="form-row">
              <div class="form-group col-md-6">
                <input type="text" name="name" class="form-control" id="name" placeholder="<?php echo htmlspecialchars(content_text($pageText, 'contactForm.namePlaceholder', 'Jouw naam')); ?>" data-rule="minlen:3" data-msg="<?php echo htmlspecialchars(content_text($pageText, 'contactForm.nameValidation', 'Please enter at least 3 chars')); ?>" />
                <div class="validation"></div>
              </div>
              <div class="form-group col-md-6">
                <input type="email" class="form-control" name="email" id="email" placeholder="<?php echo htmlspecialchars(content_text($pageText, 'contactForm.emailPlaceholder', 'Jouw email')); ?>" data-rule="email" data-msg="<?php echo htmlspecialchars(content_text($pageText, 'contactForm.emailValidation', 'Please enter a valid email')); ?>" />
                <div class="validation"></div>
              </div>
            </div>
            <div class="form-group">
              <input type="text" class="form-control" name="subject" id="subject" placeholder="<?php echo htmlspecialchars(content_text($pageText, 'contactForm.subjectPlaceholder', 'Onderwerp')); ?>" data-rule="minlen:4" data-msg="<?php echo htmlspecialchars(content_text($pageText, 'contactForm.subjectValidation', 'Please enter at least 8 chars of subject')); ?>" />
              <div class="validation"></div>
            </div>
            <div class="form-group">
              <textarea class="form-control" name="message" rows="5" data-rule="required" data-msg="<?php echo htmlspecialchars(content_text($pageText, 'contactForm.messageValidation', 'Please write something for us')); ?>" placeholder="<?php echo htmlspecialchars(content_text($pageText, 'contactForm.messagePlaceholder', 'Bericht')); ?>"></textarea>
              <div class="validation"></div>
            </div>
            <div class="text-center"><button type="submit"><?php echo htmlspecialchars(content_text($pageText, 'contactForm.submitButton', 'Verstuur')); ?></button></div>
          </form>
        </div>

      </div>
    </section><!-- #contact -->

  </main>

  <div class="modal fade" id="newLocationModal" tabindex="-1" role="dialog" aria-labelledby="newLocationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header border-0 pb-0">
          <h5 class="modal-title" id="newLocationModalLabel"><?php echo htmlspecialchars(content_text($pageText, 'modal.title', 'We verhuizen!')); ?></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="<?php echo htmlspecialchars(content_text($pageText, 'modal.closeLabel', 'Sluiten')); ?>">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body pt-2 pb-4">
          <p class="lead mb-2"><?php echo htmlspecialchars(content_text($pageText, 'modal.lead', 'Vanaf 5 januari verwelkomen we je op onze nieuwe locatie.')); ?></p>
          <p class="mb-3">
            <?php echo content_nl2br(content_text($pageText, 'modal.address', "'t Prooyen 4\nMonnickendam")); ?>
          </p>
          <a class="btn btn-block new-location-btn" href="https://maps.app.goo.gl/vmMJbsUXsttWDWAx7" target="_blank" rel="noopener">
            <?php echo htmlspecialchars(content_text($pageText, 'modal.buttonText', 'Bekijk route op Google Maps')); ?>
          </a>
          <small class="text-muted d-block mt-3"><?php echo htmlspecialchars(content_text($pageText, 'modal.footer', 'Tot snel op onze nieuwe plek!')); ?></small>
        </div>
      </div>
    </div>
  </div>


  <!--==========================
    Footer
  ============================-->
  <footer id="footer">
    <div class="footer-top">
      <div class="container">
        <div class="row">

          <div class="col-lg-3 col-md-6 footer-info">
            <img src="img/logo.png" alt="<?php echo htmlspecialchars(content_text($pageText, 'footer.logoAlt', 'Jouwkapper')); ?>">
            <p><?php echo htmlspecialchars(content_text($pageText, 'footer.aboutText')); ?></p>
          </div>

          <div class="col-lg-3 col-md-6 footer-links">
            <h4><?php echo htmlspecialchars(content_text($pageText, 'footer.linksTitle', 'Links')); ?></h4>
            <ul>
              <li><i class="fa fa-angle-right"></i> <a href="#intro"><?php echo htmlspecialchars(content_text($pageText, 'nav.home', 'Home')); ?></a></li>
              <li><i class="fa fa-angle-right"></i> <a href="#diensten"><?php echo htmlspecialchars(content_text($pageText, 'nav.services', 'Diensten')); ?></a></li>
              <li><i class="fa fa-angle-right"></i> <a href="#schedule"><?php echo htmlspecialchars(content_text($pageText, 'nav.hours', 'Openingstijden')); ?></a></li>
              <li><i class="fa fa-angle-right"></i> <a href="#updates"><?php echo htmlspecialchars(content_text($pageText, 'nav.updates', 'Updates')); ?></a></li>
              <li><i class="fa fa-angle-right"></i> <a href="#gallery"><?php echo htmlspecialchars(content_text($pageText, 'nav.portfolio', 'Portfolio')); ?></a></li>
              <li><i class="fa fa-angle-right"></i> <a href="#events"><?php echo htmlspecialchars(content_text($pageText, 'nav.promotions', 'Acties')); ?></a></li>
              <li><i class="fa fa-angle-right"></i> <a href="#contact"><?php echo htmlspecialchars(content_text($pageText, 'nav.contact', 'Contact')); ?></a></li>
            </ul>
          </div>

          <div class="col-lg-3 col-md-6 footer-contact">
            <h4><?php echo htmlspecialchars(content_text($pageText, 'footer.contactTitle', 'Contact')); ?></h4>
            <p>
              <?php echo $address; ?><br>
              <?php echo $addressLine2; ?><br>
              <?php echo htmlspecialchars(content_text($pageText, 'footer.country', 'Nederland')); ?><br>
              <b><?php echo htmlspecialchars(content_text($pageText, 'footer.mobileLabel', 'Mobiel:')); ?></b> <?php echo htmlspecialchars($site['phone']['footer'] ?? ''); ?><br>
              <b><?php echo htmlspecialchars(content_text($pageText, 'footer.emailLabel', 'Email:')); ?></b> <?php echo htmlspecialchars($site['email'] ?? ''); ?><br>
            </p>

            <div class="social-links">
              <a href="<?php echo htmlspecialchars($facebookUrl); ?>" class="facebook" target="_blank" rel="noopener"><i class="fa fa-facebook"></i></a>
              <!--<a href="#" class="instagram"><i class="fa fa-instagram"></i></a>
              <a href="#" class="linkedin"><i class="fa fa-linkedin"></i></a>-->
              <!--<a href="#" class="twitter"><i class="fa fa-twitter"></i></a>
              <a href="#" class="google-plus"><i class="fa fa-google-plus"></i></a>-->
            </div>

          </div>

          <div class="col-lg-3 col-md-6">
            <h4><?php echo htmlspecialchars(content_text($pageText, 'footer.facebookTitle', 'Facebook')); ?></h4>
            <p><?php echo htmlspecialchars(content_text($pageText, 'footer.facebookText', 'Bekijk de laatste nieuwtjes, acties en wijzigingen op onze Facebook-pagina.')); ?></p>
            <div class="social-links">
              <a href="<?php echo htmlspecialchars($facebookUrl); ?>" class="facebook" target="_blank" rel="noopener"><i class="fa fa-facebook"></i></a>
            </div>
          </div>

        </div>
      </div>
    </div>

    <div class="container">
      <div class="copyright">
        <?php echo htmlspecialchars(format_copyright_notice(content_text($pageText, 'footer.copyright', 'Jouwkapper. All Rights Reserved. | KvK: 68171390'))); ?>
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
  <script>
    (function () {
      var modal = $('#newLocationModal');
      if (!modal.length) {
        return;
      }

      // Stop showing popup after January 10, 2026
      var today = new Date();
      var stopDate = new Date(2026, 0, 10); // January 10, 2026 (month is 0-indexed)
      if (today > stopDate) {
        return; // Don't show popup after Jan 10, 2026
      }

      var storageKey = 'jkNewLocationPopupDismissedAt';
      var shouldShow = true;

      try {
        var lastDismissed = localStorage.getItem(storageKey);
        if (lastDismissed) {
          var last = parseInt(lastDismissed, 10);
          if (!isNaN(last) && (Date.now() - last) <= 24 * 60 * 60 * 1000) {
            shouldShow = false;
          }
        }
      } catch (error) {
        // localStorage unavailable, continue with default behavior
      }

      if (shouldShow) {
        $(window).on('load', function () {
          modal.modal('show');
        });
      }

      modal.on('hidden.bs.modal', function () {
        try {
          localStorage.setItem(storageKey, Date.now().toString());
        } catch (error) {
          // Ignore write errors so popup can still close gracefully
        }
      });
    })();
  </script>
</body>

</html>
