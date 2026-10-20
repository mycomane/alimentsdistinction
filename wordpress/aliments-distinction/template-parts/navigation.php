<?php defined( 'ABSPATH' ) || exit; ?>


    <!-- ===== NAVIGATION ===== -->
    <nav class="nav" id="mainNav">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="display:flex; align-items:center; gap:14px; text-decoration:none;">
            <img src="<?php echo esc_url( ad_asset( 'images/logo.png' ) ); ?>" alt="Aliments Distinction" style="height:88px; width:auto; object-fit:contain;">
            <div class="nav-logo">
                <span class="nav-logo__main">Aliments Distinction</span>
                <span class="nav-logo__sub">Produits Locaux · Livraison à domicile</span>
            </div>
        </a>
        <button class="ad-menu-toggle" type="button" aria-expanded="false" aria-controls="ad-navigation">Menu</button>
<ul class="nav-links" id="ad-navigation">
            <li><a class="nav-link" href="<?php echo esc_url( ad_home_section( 'collections' ) ); ?>">Nos produits</a></li>
            <li><a class="nav-link" href="<?php echo esc_url( ad_home_section( 'story' ) ); ?>">Notre histoire</a></li>
            <li><a class="nav-link" href="<?php echo esc_url( ad_home_section( 'recettes' ) ); ?>">Recettes</a></li>
            <li><a class="nav-link" href="<?php echo esc_url( ad_home_section( 'process' ) ); ?>">Livraison</a></li>
            <li><a class="nav-link" href="<?php echo esc_url( ad_home_section( 'contact' ) ); ?>">Contact</a></li>
        </ul>
        <div class="nav-actions">
            <a href="<?php echo esc_url( ad_search_url() ); ?>" aria-label="Rechercher des produits"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg></a>
            <a href="<?php echo esc_url( ad_account_url() ); ?>" aria-label="Mon compte"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></a>
            <a href="<?php echo esc_url( ad_cart_url() ); ?>" aria-label="Panier"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg><span class="ad-cart-count"><?php echo esc_html( ad_cart_count() ); ?></span></a>
            <a href="https://www.facebook.com/alimentsdistinction/" target="_blank" rel="noopener" aria-label="Facebook" style="display:flex;align-items:center;">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="currentColor" stroke="none"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
            </a>
            <a href="https://www.instagram.com/alimentsdistinction/" target="_blank" rel="noopener" aria-label="Instagram" style="display:flex;align-items:center;">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="currentColor" stroke="none"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
            </a>
        </div>
    </nav>

    