
<div class="container">

    <div class="row">

        <div class="col-lg-12">
            <h1 style="font-size:40px; font-weight:bold; color:rgba(0,0,0,.3); margin-bottom:30px;">DASHBOARD</h1>
        </div>
    </div>

    <div class="row">

        <div class="col-lg-4">

            <div class="dash-card">

                <div class="stats-card">
                
                    <div class="card-block">

                        <div class="stat-text">
                        
                            <span class="stat"><?php echo $orders?></span>
                        </div>
                    </div>

                    <div class="stat-icon">

                        <div class="ico-block">

                            <span class="ico">shopping_cart</span>
                        </div>
                        <div class="text-block">
                            <strong>Orders</strong>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>

        <div class="col-lg-4">

            <div class="dash-card">

                <div class="stats-card">
                
                    <div class="card-block">

                        <div class="stat-text">
                        
                            <span class="stat"><?php echo $customers?></span>
                        </div>
                    </div>

                    <div class="stat-icon">

                        <div class="ico-block">

                            <span class="ico">people_alt</span>
                        </div>
                        <div class="text-block">
                            <strong>Customers</strong>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>

        <div class="col-lg-4">

            <div class="dash-card">

                <div class="stats-card">
                
                    <div class="card-block">

                        <div class="stat-text">
                        
                            <span class="stat"><?php echo $products?></span>
                        </div>
                    </div>

                    <div class="stat-icon">

                        <div class="ico-block">

                            <span class="ico">business_center</span>
                        </div>
                        <div class="text-block">
                            <strong>Products</strong>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>