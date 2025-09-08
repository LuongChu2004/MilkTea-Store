<?php require('layout/header.php') ?>

<main style="padding-top: 0px;">
    <!-- Section About Us -->
    <section class="about-us">
        <div class="overlay">
            <h3>COMPANY PROFILE</h3>
        </div>
    </section>

    <!-- Service -->
    <div class="service">
        <h4>WHAT WE STAND FOR</h4>
    </div>

    <!-- About Details -->
    <section class="about-details">
        <div class="container">
            <!-- Left -->
            <div class="description">
                <h5>Togetherness</h5>
                <p>We are guided by PEER: Proactive, Enthusiasm, <br>Equality, Respect.</p>
                <p>We believe in mutual appreciation and shared <br> inspiration.</p>
                <p>We foster connection through tea.</p>
            </div>

            <!-- Center -->
            <div class="desc-center">
                <h5>Sincerity</h5>
                <p>We are transparent in all aspects and treat every <br>interaction genuinely.</p>
            </div>

            <!-- Right -->
            <div class="vision">
                <h5>Integrity</h5>
                <p>We uphold integrity in all we do, guided by <br>strong moral principles and unwavering ethics.</p>
                <p>We do what’s right, and we do it right.</p>
            </div>
        </div>
    </section>
    <section class="our-goal">
        <div class="goal-content">
            <h2>OUR GOAL</h2>
            <p>
                To serve tea lovers in 100 countries,<br>
                generate 300,000 employment opportunities worldwide,<br>
                and deliver 15 billion cups of freshly brewed tea annually.
            </p>
        </div>
    </section>
</main>
<style>
    /* Reset */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: Arial, sans-serif;
    }

    /* Banner About */
    .about-us {
        position: relative;
        width: 100%;
        height: 100vh;
        background-image: url('/Chagge_Store/images/bg/bg-about-1.png');
        background-size: cover;
        background-position: center;

    }

    .about-us .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.4);
        color: white;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 20px;
        padding-top: 0px;
    }

    .about-us h3 {
        font-size: 52px;
        margin-bottom: 20px;
        font-family: 'Arial', cursive;
        align-items: left;
        padding-left: 100px;
        padding-bottom: 40px;
        letter-spacing: 3px;
        font-weight: bold;
    }

    .about-us h6 {
        font-size: 18px;
        max-width: 800px;
        line-height: 1.6;
        color: burlywood;
    }

    /* Service */
    .service h4 {
        font-size: 40px;
        font-weight: bold;
        color: brown;
        margin: 50px 0;
        text-align: center;
    }
</style>
<style>
    /* Our Goal */
    .our-goal {
        position: relative;
        width: 100%;
        height: 90vh;
        /* chiếm toàn màn hình */
        background: url('/Chagge_Store/images/bg/bg-our-goal.jpeg')no-repeat center center/cover;
        display: flex;
        align-items: center;
        /* căn giữa theo chiều dọc */
    }

    .goal-content {
        color: white;
        max-width: 600px;
        margin-left: 10vw;
        /* cách lề trái */
        line-height: 2.3;
    }

    .goal-content h2 {
        font-size: 36px;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .goal-content p {
        font-size: 16px;
        color: white;
        font-weight: normal;
    }


    /* About Details */
    .about-details .container {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 30px;
        padding: 0 50px;
        font-family: sans-serif;
    }

    .description,
    .vision,
    .desc-center {
        flex: 1;
        text-align: center;
    }

    .description h5,
    .vision h5,
    .desc-center h5 {
        font-size: 23px;
        margin-bottom: 15px;
        color: #011E41;
    }

    .description p,
    .vision p,
    .desc-center p {
        font-size: 16px;
        line-height: 2.3;
        color: #5f6464;
        font-weight: normal;
    }

    .image-center img {
        max-width: 100%;
        height: auto;
        border-radius: 10px;
    }
</style>
<?php require('layout/footer.php') ?>