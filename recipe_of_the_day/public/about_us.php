<head>
    <link rel="stylesheet" href="style.css"> 
</head>
<?php require_once "partials/header.php"; ?>

<style>
.about-section {
    padding: 40px 20px;
}

.about-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 40px;
    align-items: start;
}

.about-text {
    text-align: left;
}

.section-title {
    font-family: 'Lobster', cursive;
    font-size: 36px;
    color: #333;
    margin-bottom: 25px;
    padding-bottom: 15px;
    display: inline-block;
}

.about-text p {
    font-size: 16px;
    line-height: 1.8;
    color: #555;
    margin-bottom: 18px;
}

.about-signature {
    margin-top: 25px;
    padding-top: 15px;
    border-top: 2px solid #eee;
    font-style: italic;
}

.about-signature strong {
    color: #79a206;
    font-size: 18px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

.stat-item {
    text-align: center;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 25px 15px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    border: 1px solid #dee2e6;
    transition: all 0.3s;
}

.stat-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(121, 162, 6, 0.15);
    background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
}

.stat-item i {
    font-size: 35px;
    color: #79a206;
    margin-bottom: 12px;
}

.stat-item h3 {
    font-size: 32px;
    font-weight: bold;
    color: #333;
    margin-bottom: 6px;
}

.stat-item p {
    font-size: 13px;
    color: #777;
    text-transform: uppercase;
}

.about-cta {
    margin: 40px 0 20px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 50px 40px;
    text-align: center;
    border-radius: 10px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.08);
    border: 1px solid #dee2e6;
}

.about-cta h2 {
    font-family: 'Lobster', cursive;
    font-size: 36px;
    margin-bottom: 10px;
    color: #333;
}

.about-cta p {
    font-size: 16px;
    margin-bottom: 25px;
    color: #555;
}

.btn-green {
    background: #79a206;
    color: white;
    padding: 12px 35px;
    font-size: 16px;
    font-weight: bold;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s;
}

.btn-green:hover {
    background: #5e7f04;
    color: white;
    transform: translateY(-2px);
}

@media (max-width: 992px) {
    .about-container {
        grid-template-columns: 1fr;
        gap: 30px;
    }
}

@media (max-width: 576px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .about-cta {
        padding: 30px 20px;
    }
}
</style>

<section class="about-section">
    <div class="container">
        <div class="about-container">
            
            <div class="about-text">
                <h2 class="section-title">Who We Are</h2>
                <p>
                    Welcome to <strong>Recipe of the Day</strong>, your number one source for all tasty recipes. 
                    We're dedicated to giving you the very best of culinary ideas, with a focus on ease of cooking, 
                    health, and deliciousness.
                </p>
                <p>
                    Founded in 2025, Recipe of the Day has come a long way from its beginnings. 
                    When we first started out, our passion for "eco-friendly cooking" drove us to do tons of research, 
                    so that Recipe of the Day can offer you the world's most advanced recipes.
                </p>
                <p>
                    We serve customers all over the world and are thrilled that we're able to turn our passion into our own website.
                </p>
                
                <div class="about-signature">
                    <p>Bon Appétit,</p>
                    <strong>The Recipe Team</strong>
                </div>
            </div>

            <div class="stats-grid">
                <div class="stat-item">
                    <i class="fa fa-cutlery"></i>
                    <h3>500+</h3>
                    <p>Recipes</p>
                </div>
                <div class="stat-item">
                    <i class="fa fa-users"></i>
                    <h3>10k+</h3>
                    <p>Happy Cooks</p>
                </div>
                <div class="stat-item">
                    <i class="fa fa-heart"></i>
                    <h3>1.5M</h3>
                    <p>Favorites</p>
                </div>
                <div class="stat-item">
                    <i class="fa fa-globe"></i>
                    <h3>50+</h3>
                    <p>Countries</p>
                </div>
            </div>

        </div>
    </div>
</section>

<div class="container">
    <section class="about-cta">
        <h2>Ready to start cooking?</h2>
        <p>Discover our latest recipes and start your culinary journey today.</p>
        <a href="all_recipes.php" class="btn-green">Browse Recipes</a>
    </section>
</div>

<?php require_once "partials/footer.php"; ?>