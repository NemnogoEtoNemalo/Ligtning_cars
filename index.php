<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lightning cars</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
</body>
<main>
    <header class="header">
        <a href="./index.php"><img src="./img/Logo.png" class="logo"></a>
        <div class="header-box">
            <ul class="main-menu">
                <li class="menu-item">
                    <a href="./index.php#home">Home</a>
                </li>
                <li class="menu-item">
                    <a href="./index.php#catalog">Catalog</a>
                </li>
                <li class="menu-item">
                    <a href="./index.php#about-us">About us</a>
                </li>
                <li class="menu-item">
                    <a href="./index.php#contacts">Contacts</a>
                </li>
            </ul>
        </div>
        <div class="regisration">
        <ul class="reg-menu">
            <?php if (isset($_SESSION['user_id'])): ?>
                <li class="rmenu-item">
                    <a href="./profile.php">My Profile</a>
                </li>
                <li class="rmenu-item">
                    <a href="./logout.php">Logout</a>
                </li>
            <?php else: ?>
                <li class="rmenu-item">
                    <a href="./login.php">Log in</a>
                </li>
                <li class="rmenu-item">
                    <a href="./register.php">Registration</a>
                </li>
            <?php endif; ?>
        </ul>
        <img src="./img/Group 1.png" class="reg-img">
        </div>
    </header>
    <section class="home">
        <h4 class="home-title" id="home">
            Home
        </h4>
        <div class="home-info">
            <img src="./img/Firefly_Car showroom with electric cars inside 621057 1.png">
            <div class="home-text">
                <h5 class="home-text1">
                    Lightning Cars is an innovative company specializing in the sale of cutting-edge electric vehicles that 
                    combine high performance, sustainability, and  advanced technology.
                    We offer:Speed & Power – EVs with instant acceleration and dynamic driving. Long Range – advanced battery solutions for worry-free travel. 
                    Sleek Design – modern, ergonomic, and stylish models. Eco-Friendly – zero emissions and a commitment to a greener future.
                </h5>
                <h4 class="home-text2">
                    Lightning Cars – electrifying every ride!
                </h4>
            </div>
        </div>
    </section>
    <section class="catalog">
        <h4 class="catalog-title" id="catalog">Catalog</h4>
        <div class="catalog-list">
            <div class="catalog-item">
                <h4 class="item-title">Tesla Model 3</h4>
                <img src="./img/tesla-model-3-3 1.png">
                <h5 class="item-info">
                    Battery capacity 65 kW*h<br>
                    Power reserve 430 km<br>
                    Engine power 258 hp (192 kW)<br>
                    Torque 660 Nm<br>
                    Maximum speed 225 km/h<br>
                    Acceleration to 100 km/h in 5.6 seconds<br><br>

                    44 990 $<br>
                </h5>
            </div>
            <div class="catalog-item">
                <h4 class="item-title">Nissan Ariya</h4>
                <img src="./img/nissan-ariya 1.png">
                <h5 class="item-info">
                    Battery capacity 63 kW*h<br>
                    Power reserve 360 km<br>
                    Engine power 218 hp (162 kW)<br>
                    Torque 300 Nm<br>
                    Maximum speed 167 km/h<br>
                    Acceleration to 100 km/h in 7.9 seconds<br><br>

                    39 990 $<br>
                </h5>
            </div>
            <div class="catalog-item">
                <h4 class="item-title">Audi E-tron</h4>
                <img src="./img/audi-e-tron 1.png">
                <h5 class="item-info">
                    Battery capacity 95 kW*h<br>
                    Power reserve 365 km<br>
                    Engine power 408 hp (304 kW)<br>
                    Torque 664 Nm<br>
                    Maximum speed 160 km/h<br>
                    Acceleration to 100 km/h in 9 seconds<br><br>

                    76 990 $<br>
                </h5>
            </div>
            <div class="catalog-item">
                <h4 class="item-title">BMW iX3</h4>
                <img src="./img/bmw-ix3 1.png">
                <h5 class="item-info">
                    Battery capacity 80 kW*h<br>
                    Power reserve 440 km<br>
                    Engine power 286 hp (210 kW)<br>
                    Maximum speed 200 km/h<br>
                    Acceleration to 100 km/h in 6.8 seconds<br><br><br>

                    68 990 $<br>
                </h5>
            </div>
            <div class="catalog-item">
                <h4 class="item-title">Hyundai Kona Electric</h4>
                <img src="./img/hyundai-kona-electric 1.png">
                <h5 class="item-info">
                    Battery capacity 68 kW*h<br>
                    Power reserve 484 km<br>
                    Engine power 204 hp (152 kW)<br>
                    Torque 395 Nm<br>
                    Maximum speed 167 km/h<br>
                    Acceleration to 100 km/h in 7.9 seconds<br><br>

                    38 990 $<br>
                </h5>
            </div>
            <div class="catalog-item">
                <h4 class="item-title">Tesla Model 3</h4>
                <img src="./img/porsche-taycan 1.png">
                <h5 class="item-info">
                    Battery capacity 93.4 kW*h<br>
                    Power reserve 463 km<br>
                    Engine power 761 hp (192 kW)<br>
                    Maximum speed 250 km/h<br>
                    Acceleration to 100 km/h in 2.8 seconds<br><br><br>

                    109 990 $<br>
                </h5>
            </div>
        </div>
    </section>
    <section class="about-us">
        <h4 class="about-us-title" id="about-us">About us</h4>
        <div class="about-us-info">
            <div class="about-us-info1">
                Lightning Cars – The Future of Electric Driving<br>
                At Lightning Cars, we're revolutionizing the way people think about electric vehicles. 
                As a forward-thinking EV dealership, we specialize in high-performance,  eco-friendly cars that deliver thrilling acceleration, 
                cutting-edge  technology, and zero emissions.
            </div>
            <div class="about-us-info2">
                <div class="about-us-info2-1">
                    <div class="wwd">
                        <h5 class="wwd-title">What we do:</h5>
                        <ul class="wwd-list">
                            <li class="wwd-item">
                            <h5>Curated Selection – We offer only the most advanced and reliable electric models from leading manufacturers</h5>
                            </li>
                            <li class="wwd-item">
                            <h5>Personalized Service – Our EV experts help you find the perfect car for your lifestyle and budget</h5>
                            </li>
                            <li class="wwd-item">
                            <h5>Seamless Ownership – From test drives to charging solutions, we support you at every step</h5>
                            </li>
                            <li class="wwd-item">
                            <h5>Sustainable Mission – We're committed to accelerating the transition to clean transportation</h5>
                            </li>
                        </ul>
                    </div>
                    <div class="about-us-info2-2">
                        <h5>Whether you're a tech enthusiast, performance driver, or eco-conscious commuter, Lightning Cars powers your journey into the electric future. 
                            Visit us today and experience the instant torque, whisper-quiet ride, and smart features that make EVs extraordinary.</h5>
                    </div>
                </div>
                <img src="./img/Firefly_Electric car sales company office 629027 1.png">
            </div>
        </div>
    </section>
    <section class="contacts">
        <h4 class="contacts-title" id="contacts">Contacts</h4>
        <div class="contacts-box">
            <div class="contacs-info">
                <h4 class="contacts-title1">Lightning Cars – Electric Vehicle Dealership</h4>
                <ul class="contacs-addeses">
                    <li class="addreses-item">
                    <h4>Address: 4500 Electric Avenue, Los Angeles, CA 90016, USA</h4> 
                    </li>
                    <li class="addreses-item">
                    <h4>Phone: +1 (310) 555-0198</h4> 
                    </li>
                    <li class="addreses-item">
                    <h4>Email: info@lightningcars.com</h4> 
                    </li>
                </ul>
                <h4 class="Business-hours">
                    Business Hours:<br>
                    Monday-Friday: 9:00 AM – 7:00 PM<br>
                    Saturday: 10:00 AM – 6:00 PM<br>
                    Sunday: Closed<br>
                </h4>
                <h4>
                    Visit Us:
                    Conveniently located in the heart of Los Angeles, just off the I-10 freeway. 
                    Ample charging stations and test drive vehicles available on-site.
                    Schedule a test drive today and feel the instant power of electric driving!
                </h4>
            </div>
            <img src="./img/Firefly_Electric car sales company office (outside vi) 415625 1.png">
        </div>
    </section>
    <footer>
        <div>
            <img src="./img/Logo.png" >
            <h4 class="footer-text">All rights reserved</h4>
        </div>
    </footer>
</main>
</html>