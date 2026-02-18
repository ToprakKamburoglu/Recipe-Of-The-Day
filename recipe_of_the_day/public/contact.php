<head>
    <link rel="stylesheet" href="style.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<?php require_once "partials/header.php"; ?>

<style>
.contact-form .form-group {
    margin-bottom: 25px; /* Boşluk artırıldı */
}

.contact-form .form-row {
    gap: 25px; /* İki input arası boşluk */
}

.contact-form .form-input {
    padding: 14px 15px; /* İçerik padding artırıldı */
}

.contact-form .textarea {
    min-height: 180px; /* Textarea yüksekliği artırıldı */
}

.contact-btn {
    margin-top: 10px; /* Buton üstü boşluk */
}
</style>

<section class="contact-section">
    <div class="container">
        
        <div class="contact-wrapper">
            <h2 class="section-title">Get in Touch</h2>
            <p class="contact-desc">Have a question or a recipe to share? Fill out the form below!</p>

            <?php if (isset($_GET['status'])): ?>
                <?php if ($_GET['status'] == 'success'): ?>
                    <div style="background-color: #d4edda; color: #155724; padding: 15px; margin-bottom: 20px; border: 1px solid #c3e6cb; border-radius: 5px;">
                        <i class="fa fa-check-circle"></i> Message sent successfully! We will get back to you soon.
                    </div>
                <?php elseif ($_GET['status'] == 'error'): ?>
                    <div style="background-color: #f8d7da; color: #721c24; padding: 15px; margin-bottom: 20px; border: 1px solid #f5c6cb; border-radius: 5px;">
                        <i class="fa fa-exclamation-circle"></i> Something went wrong. Please try again.
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <form action="contact_submit.php" method="POST" class="contact-form">
                
                <div class="form-row">
                    <div class="form-group half">
                        <label>Name</label>
                        <input type="text" name="name" class="form-input" placeholder="Your Name" required>
                    </div>
                    <div class="form-group half">
                        <label>Email</label>
                        <input type="email" name="email" class="form-input" placeholder="Your Email" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Subject</label>
                    <input type="text" name="subject" class="form-input" placeholder="What is this about?" required>
                </div>

                <div class="form-group">
                    <label>Message</label>
                    <textarea name="message" class="form-input textarea" placeholder="Write your message here..." required></textarea>
                </div>

                <button type="submit" class="btn btn-green contact-btn">Send Message</button>
            </form>
        </div>

    </div>
</section>
<?php require_once "partials/footer.php"; ?>