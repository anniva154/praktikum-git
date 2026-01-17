<footer class="footer-lab mt-5">
    <div class="container py-5">
        <div class="row g-4">

            <!-- MAP / LOKASI -->
            <div class="col-md-5">
                <h4 class="footer-title">SMKN 3 Bangkalan</h4>
                <div class="footer-map">
                    <iframe 
                        src="https://www.google.com/maps?q=SMKN%203%20Bangkalan&output=embed"
                        width="100%" 
                        height="220" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy">
                    </iframe>
                </div>
            </div>

            <!-- CONTACT -->
            <div class="col-md-4">
                <h4 class="footer-title">Contact Us</h4>

                <ul class="footer-contact">
                    <li>
                        <i class="fa-solid fa-phone"></i>
                        (031) 3062126
                    </li>
                    <li>
                        <i class="fa-solid fa-phone"></i>
                        0878-7307-1400 (Humas)
                    </li>
                    <li>
                        <i class="fa-solid fa-envelope"></i>
                        smkn3bangkalan.adm@gmail.com
                    </li>
                    <li>
                        <i class="fa-solid fa-location-dot"></i>
                        Jl. Raya Bancaran, Bangkalan, Jawa Timur
                    </li>
                </ul>

                <h5 class="footer-subtitle mt-4">Opening Hour</h5>
                <p class="footer-text">Senin – Jumat : 07.00 – 17.00</p>
            </div>

            <!-- SOSIAL MEDIA -->
            <div class="col-md-3">
                <h4 class="footer-title">Follow Us</h4>
                <div class="footer-social">
                    <a href="#"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#"><i class="fa-brands fa-youtube"></i></a>
                </div>
            </div>

        </div>
    </div>

    <!-- COPYRIGHT -->
    <div class="footer-bottom text-center py-3">
        <small>© {{ date('Y') }} SIM LAB SMKN 3 Bangkalan</small>
    </div>
</footer>
<style>
    /* ===============================
   FOOTER LAB
================================ */
.footer-lab {
    background: linear-gradient(90deg, #0d6efd, #084298);
    color: #ffffff;
}

/* TITLE */
.footer-title {
    color: #ffd000;
    font-weight: 700;
    margin-bottom: 16px;
}

.footer-subtitle {
    color: #ffd000;
    font-weight: 600;
}

/* MAP */
.footer-map iframe {
    border-radius: 10px;
}

/* CONTACT LIST */
.footer-contact {
    list-style: none;
    padding: 0;
}

.footer-contact li {
    margin-bottom: 10px;
    display: flex;
    align-items: flex-start;
    gap: 10px;
    font-size: 14px;
}

.footer-contact i {
    color: #ffd000;
    margin-top: 4px;
}

/* TEXT */
.footer-text {
    font-size: 14px;
}

/* SOCIAL */
.footer-social a {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background: rgba(255,255,255,.15);
    color: #ffffff;
    font-size: 18px;
    margin-right: 10px;
    transition: .3s;
}

.footer-social a:hover {
    background: #ffd000;
    color: #084298;
}

/* BOTTOM */
.footer-bottom {
    border-top: 1px solid rgba(255,255,255,.2);
    background: rgba(0,0,0,.15);
}

</style>