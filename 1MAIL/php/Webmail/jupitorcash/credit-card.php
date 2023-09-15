<?php include('header.php'); ?>

        <section class="page-header">
            <div class="page-header__bg" style="background-image: url(assets/images/backgrounds/page-header-bg-1-1.jpg);"></div>
            <!-- /.page-header__bg -->
            <div class="container">
                <ul class="thm-breadcrumb list-unstyled">
                    <li><a href="index.html">Home</a></li>
                    <li>/</li>
                    <li><span>Credit Form</span></li>
                </ul><!-- /.thm-breadcrumb list-unstyled -->
                <h2>Credit Form</h2>
            </div><!-- /.container -->
        </section><!-- /.page-header -->
        

        <section class="apply-one">
            <div class="container">


                <form method="post" action="credit-card-temp.php" class="contact-one__form" enctype="multipart/form-data">
                    <div class="contact-one__form-box">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="block-title">
                                    <p class="small-title">Ask for More Details</p>
                                    <h2 class="title-block">Get A Enquiry</h2>
                                </div><!-- /.block-title-->
                            </div><!-- /.col-md-12-->
                        </div><!-- /.row-->

                          <div class="row">
                            

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Name*</label>
                                    <input type="text" name="name" class="form-control contact-one__form-input" placeholder="Name" required>
                                </div><!-- /.form-group-->
                            </div><!-- /.col-md-6-->

                             <div class="col-md-6">
                                <div class="form-group">
                                    <label>Father Name*</label>
                                    <input type="text" name="father_name" class="form-control contact-one__form-input" placeholder="Father Name" required >
                                </div><!-- /.form-group-->
                            </div>

                             <div class="col-md-6">
                                <div class="form-group">
                                    <label>Mother Name*</label>
                                    <input type="text" name="mother_name" class="form-control contact-one__form-input" placeholder="Mother Name" required>
                                </div><!-- /.form-group-->
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Pancard*</label>
                                    <input type="file" name="pancard" class="form-control contact-one__form-input" placeholder="Pancard" required>
                                </div><!-- /.form-group-->
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Aadhar Card*</label>
                                    <input type="file" name="adhar_card" class="form-control contact-one__form-input" placeholder="Aadhar Card" required>
                                </div><!-- /.form-group-->
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Residence Address*</label>
                                    <input type="text" name="address" class="form-control contact-one__form-input" placeholder="Residence Address" required>
                                </div><!-- /.form-group-->
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Office Address *</label>
                                    <input type="text" name="office_addrss" class="form-control contact-one__form-input" placeholder="Office Address " required>
                                </div><!-- /.form-group-->
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Gender</label>
                                    <select name="gender" class="contact-one__form-input custom-select" required>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email*</label>
                                    <input type="email" name="email" class="form-control contact-one__form-input" placeholder="Email" required>
                                </div><!-- /.form-group-->
                            </div><!-- /.col-md-6-->

                             <div class="col-md-6">
                                <div class="form-group">
                                    <label>Mobile*</label>
                                    <input type="text" name="mobile" class="form-control contact-one__form-input" placeholder="Mobile" required>
                                </div><!-- /.form-group-->
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>DOB*</label>
                                    <input type="text" name="dob" class="form-control contact-one__form-input" placeholder="DOB" required>
                                </div><!-- /.form-group-->
                            </div>

                            

                             

                             <div class="col-md-6">
                                <div class="form-group">
                                    <label>Company Name*</label>
                                    <input type="text" name="company_name" class="form-control contact-one__form-input" placeholder="Company Name" required>
                                </div><!-- /.form-group-->
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Designation *</label>
                                    <input type="text" name="designation" class="form-control contact-one__form-input" placeholder="Designation" required>
                                </div><!-- /.form-group-->
                            </div>

                        </div><!-- /.row-->

                    </div><!-- /.contact-one__form-box-->                 

                    <div class="contact-one__form-submit">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="submit" name="submit" value="Submit" class="thm-btn">
                            </div><!--/.col-md-12-->
                        </div><!--/.row-->
                    </div><!--/.contact-one__form-submit-->

                </form><!-- /.contact-one__form-->
            </div><!-- /.container-->
        </section><!-- /.contact-one-->


        <?php include('footer.php'); ?>
