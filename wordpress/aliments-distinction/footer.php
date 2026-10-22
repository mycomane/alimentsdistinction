<?php defined( 'ABSPATH' ) || exit; ?>
<!-- ===== NEWSLETTER ===== -->
    <section class="newsletter">
        <div class="container">
            <div class="newsletter__inner">
                <div>
                    <span class="newsletter__label">Rejoignez la communauté</span>
                    <h3 class="newsletter__title">Recettes, nouveautés<br>& <em>offres exclusives</em></h3>
                </div>
                <div class="ad-newsletter-panel">
                <?php $ad_newsletter = get_theme_mod( 'ad_newsletter_url', '' ); if ( $ad_newsletter ) : ?>
<a class="btn btn-outline-charcoal" href="<?php echo esc_url( $ad_newsletter ); ?>">S’inscrire à l’infolettre</a>
<?php else : ?><p class="t-body">L’inscription à l’infolettre n’est pas encore disponible.</p><?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== FOOTER ===== -->
    <footer class="footer" id="contact">
        <div class="container">
            <div class="footer__top">
                <div>
                    <div class="footer__brand-name">Aliments Distinction</div>
                    <div class="footer__brand-sub">L'excellence à votre table</div>
                    <p class="footer__brand-desc">Des produits d'exception du terroir québécois, sélectionnés avec soin et livrés directement à votre porte. Sans contrat, avec passion.</p>
                    <div class="footer__social">
                        <a href="https://www.facebook.com/alimentsdistinction/" class="footer__social-link" aria-label="Facebook" target="_blank" rel="noopener">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="https://www.instagram.com/alimentsdistinction/" class="footer__social-link" aria-label="Instagram" target="_blank" rel="noopener">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="16" height="16"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                    </div>
                </div>
                <div>
                    <div class="footer__col-title">Collections</div>
                    <ul class="footer__links">
                        <li><a class="footer__link" href="<?php echo esc_url( ad_collection_url( 'viandes' ) ); ?>">Viandes Prime</a></li>
                        <li><a class="footer__link" href="<?php echo esc_url( ad_collection_url( 'fruits-de-mer-poissons' ) ); ?>">Fruits de mer et poissons</a></li>
                        <li><a class="footer__link" href="<?php echo esc_url( ad_collection_url( 'veau-volaille-agneau' ) ); ?>">Veau · Volaille & Agneau</a></li>
                        <li><a class="footer__link" href="<?php echo esc_url( ad_collection_url( 'produits-gourmets' ) ); ?>">Produits gourmets</a></li>
                        <li><a class="footer__link" href="<?php echo esc_url( ad_collection_url( 'desserts' ) ); ?>">Desserts artisanaux</a></li>
                    </ul>
                </div>
                <div>
                    <div class="footer__col-title">Informations</div>
                    <ul class="footer__links">
                        <li><a class="footer__link" href="<?php echo esc_url( ad_page_url( 'notre-histoire', 'story' ) ); ?>">Notre histoire</a></li>
                        <li><a class="footer__link" href="<?php echo esc_url( ad_page_url( 'comment-commander', 'process' ) ); ?>">Comment commander</a></li>
                        <li><a class="footer__link" href="<?php echo esc_url( ad_page_url( 'livraison', 'process' ) ); ?>">Zones de livraison</a></li>
                        <li><a class="footer__link" href="<?php echo esc_url( ad_page_url( 'faq', 'contact' ) ); ?>">Foire aux questions</a></li>
                    </ul>
                </div>
                <div>
                    <div class="footer__col-title">Contact</div>
                    <ul class="footer__links">
                        <li><a class="footer__link" href="<?php echo esc_url( ad_page_url( 'contact', 'contact' ) ); ?>">Nous joindre</a></li>
                        <li><a class="footer__link" href="<?php echo esc_url( ad_page_url( 'politique-livraison', 'process' ) ); ?>">Politique de livraison</a></li>
                        <?php if ( get_privacy_policy_url() ) : ?><li><a class="footer__link" href="<?php echo esc_url( get_privacy_policy_url() ); ?>">Confidentialité</a></li><?php endif; ?>
                    </ul>
                </div>
            </div>
            <div class="footer__bottom">
                <p class="footer__copy">© <?php echo esc_html( wp_date( 'Y' ) ); ?> <span>Aliments Distinction</span>. Tous droits réservés.</p>
                <p class="footer__copy">Fait avec amour au <span>Québec</span> ◆</p>
            </div>
        </div>
    </footer>

    <?php wp_footer(); ?>
</body>
</html>
