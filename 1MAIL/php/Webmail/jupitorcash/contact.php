<?php include('header.php'); ?>
        <section class="page-header">
            <div class="page-header__bg" style="background-image: url(assets/images/backgrounds/page-header-bg-1-1.jpg);"></div>
            <!-- /.page-header__bg -->
            <div class="container">
                <ul class="thm-breadcrumb list-unstyled">
                    <li><a href="index.html">Home</a></li>
                    <li>/</li>
                    <li><span>Contact Us</span></li>
                </ul><!-- /.thm-breadcrumb list-unstyled -->
                <h2>Contact Us</h2>
            </div><!-- /.container -->
        </section><!-- /.page-header -->
        <section class="contact-one">
            <div class="container">
                <div class="row">
                    <div class="col-lg-5">
                        <div class="contact-one__content">
                            <div class="block-title">
                                <p class="small-title">Get in touch with us</p>
                                <h2 class="title-block">Ask for your query</h2>
                            </div>
                            <div class="contact-one__box">
                                <i class="pylon-icon-telephone"></i>
                                <div class="contact-one__box-content">
                                    <h4>Call Anytime</h4>
                                    <a href="tel:7678631169">7678631169</a>
                                </div><!-- /.contact-one__box-content -->
                            </div><!-- /.contact-one__box -->
                            <div class="contact-one__box">
                                <i class="pylon-icon-email1"></i>
                                <div class="contact-one__box-content">
                                    <h4>Write Email</h4>
                                    <a href="mailto:info@jupitorcash.com">info@jupitorcash.com</a>
                                </div><!-- /.contact-one__box-content -->
                            </div><!-- /.contact-one__box -->
                            <div class="contact-one__box">
                                <i class="pylon-icon-pin1"></i>
                                <div class="contact-one__box-content">
                                    <h4>Visit Office</h4>
                                    <a href="#">Rz 210 ground floor dwarika Vihar najafgarh new delhi 110043</a>
                                </div><!-- /.contact-one__box-content -->
                            </div><!-- /.contact-one__box -->
                        </div><!-- /.contact-one__content -->
                    </div><!-- /.col-lg-5 -->
                    <div class="col-lg-7">
                        <form  action="send.php" method="post" class="contact-one__form">
                            <div class="row low-gutters">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" placeholder="Your Name" class="form-control contact-one__form-input" name="name" required>
                                    </div>
                                </div><!-- /.col-md-6 -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="email" placeholder="Your Email" class="form-control contact-one__form-input" name="email" required>
                                    </div>
                                </div><!-- /.col-md-6 -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" placeholder="Phone Number" class="form-control contact-one__form-input" name="mobile">
                                    </div>
                                </div><!-- /.col-md-6 -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" placeholder="Subject" class="form-control contact-one__form-input" name="subject">
                                    </div>
                                </div><!-- /.col-md-6 -->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <textarea name="message" placeholder="Write Message" class="contact-one__form-input"></textarea>
                                    </div>
                                    <button class="thm-btn" name="submit" type="submit">Send A Message</button>
                                </div><!-- /.col-md-6 -->
                            </div><!-- /.row -->
                        </form><!-- /.contact-one__from -->
                    </div><!-- /.col-lg-7 -->
                </div><!-- /.row -->
            </div><!-- /.container -->
        </section><!-- /.contact-one -->
       <?php include('footer.php'); ?>