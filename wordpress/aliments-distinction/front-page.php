<?php defined( 'ABSPATH' ) || exit; get_header(); ?>
<main id="main-content">
<!-- ===== HERO ===== -->
    <section class="hero plaster-bg">
        <div class="hero__left">
            <!-- Floral coin haut gauche -->
            <div class="floral-accent floral-accent--hero-tl" aria-hidden="true">
                <svg viewBox="0 0 180 180" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="30" cy="30" r="14" fill="#C4857A" opacity="0.35"/>
                    <circle cx="55" cy="18" r="9" fill="#DBAAA4" opacity="0.4"/>
                    <circle cx="18" cy="58" r="9" fill="#DBAAA4" opacity="0.4"/>
                    <circle cx="72" cy="36" r="6" fill="#C4857A" opacity="0.25"/>
                    <circle cx="36" cy="76" r="6" fill="#C4857A" opacity="0.25"/>
                    <path d="M30 30 Q55 10 80 30" stroke="#8BA888" stroke-width="1.5" fill="none" opacity="0.5"/>
                    <path d="M30 30 Q10 55 30 80" stroke="#8BA888" stroke-width="1.5" fill="none" opacity="0.5"/>
                    <ellipse cx="80" cy="30" rx="8" ry="4" fill="#8BA888" opacity="0.4" transform="rotate(-30 80 30)"/>
                    <ellipse cx="30" cy="80" rx="8" ry="4" fill="#8BA888" opacity="0.4" transform="rotate(60 30 80)"/>
                </svg>
            </div>

            <!-- Sunburst + label -->
            <div class="sunburst-wrap">
                <svg viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="24" cy="24" r="7" fill="#B8956A" opacity="0.8"/>
                    <g stroke="#B8956A" stroke-width="1.2" opacity="0.7">
                        <line x1="24" y1="4"  x2="24" y2="10"/>
                        <line x1="24" y1="38" x2="24" y2="44"/>
                        <line x1="4"  y1="24" x2="10" y2="24"/>
                        <line x1="38" y1="24" x2="44" y2="24"/>
                        <line x1="9"  y1="9"  x2="13" y2="13"/>
                        <line x1="35" y1="35" x2="39" y2="39"/>
                        <line x1="39" y1="9"  x2="35" y2="13"/>
                        <line x1="13" y1="35" x2="9"  y2="39"/>
                    </g>
                </svg>
            </div>

            <div class="hero__eyebrow">
                <span class="t-label">Produits Locaux · Québec</span>
            </div>
            <h1 class="t-h1 hero__title">
                La table<br>que vous <em>méritez</em>
            </h1>

            <!-- Ornate divider sous le titre -->
            <div class="ornate-divider" style="margin: 18px 0;">
                <div class="ornate-divider__line"></div>
                <div class="ornate-divider__center">✦ &nbsp; ◆ &nbsp; ✦</div>
                <div class="ornate-divider__line ornate-divider__line--right"></div>
            </div>

            <p class="hero__subtitle">
                Découvrez une sélection de produits d'exception, comprenant du bœuf Canadian Prime, des fruits de mer, du poisson et de la volaille, soigneusement choisis pour les palais les plus exigeants et livrés directement à votre porte.
            </p>
            <div class="hero__ctas">
                <a href="<?php echo esc_url( ad_home_section( 'collections' ) ); ?>" class="btn btn-blush">Découvrir nos produits</a>
                <a href="<?php echo esc_url( ad_home_section( 'story' ) ); ?>" class="btn btn-outline-charcoal">Notre histoire</a>
            </div>
            <div class="hero__trust">
                <div class="trust-item">
                    <span class="trust-item__value">50</span>
                    <span class="trust-item__label">Jours de vieillissement</span>
                </div>
                <div class="trust-item">
                    <span class="trust-item__value">102+</span>
                    <span class="trust-item__label">Produits disponibles</span>
                </div>
                <div class="trust-item">
                    <span class="trust-item__value">100%</span>
                    <span class="trust-item__label">Produits locaux</span>
                </div>
            </div>
        </div>
        <div class="hero__right" style="position:relative; overflow:hidden;">
            <img src="https://images.unsplash.com/photo-1615937691194-97dbd3f3dc29?w=1200&q=90" alt="Produits premium Aliments Distinction">
            <div class="hero__right-overlay"></div>

            <!-- FEUILLES BOTANIQUES coin bas droite -->
            <div class="leaf-deco leaf-deco--bl" aria-hidden="true" style="opacity:0.55; bottom:-10px; left:-10px; width:360px; height:300px;">
                <svg viewBox="0 0 360 300" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Tige principale -->
                    <path d="M20 300 Q60 240 100 190 Q140 140 180 110" stroke="#4a7c59" stroke-width="2.5" fill="none" opacity="0.7"/>
                    <!-- Grande feuille 1 -->
                    <path d="M100 190 C80 160 55 155 45 170 C35 185 50 210 75 215 C95 218 108 200 100 190 Z" fill="#5a9468" opacity="0.85"/>
                    <path d="M100 190 C80 160 55 155 45 170" stroke="#3d6b4a" stroke-width="0.8" fill="none" opacity="0.6"/>
                    <path d="M100 190 L70 180" stroke="#4a7c59" stroke-width="0.7" fill="none" opacity="0.5"/>
                    <path d="M90 195 L65 192" stroke="#4a7c59" stroke-width="0.6" fill="none" opacity="0.4"/>
                    <path d="M80 200 L60 202" stroke="#4a7c59" stroke-width="0.6" fill="none" opacity="0.4"/>
                    <!-- Grande feuille 2 -->
                    <path d="M140 140 C120 115 90 108 82 122 C74 136 88 158 112 162 C132 164 148 152 140 140 Z" fill="#4e8a62" opacity="0.88"/>
                    <path d="M140 140 C120 115 90 108 82 122" stroke="#3a6645" stroke-width="0.8" fill="none" opacity="0.5"/>
                    <path d="M140 140 L108 130" stroke="#4a7c59" stroke-width="0.7" fill="none" opacity="0.5"/>
                    <path d="M130 146 L100 140" stroke="#4a7c59" stroke-width="0.6" fill="none" opacity="0.4"/>
                    <path d="M118 152 L95 150" stroke="#4a7c59" stroke-width="0.6" fill="none" opacity="0.4"/>
                    <!-- Feuille 3 petite -->
                    <path d="M60 250 C40 230 22 228 18 242 C14 255 28 270 48 272 C64 272 68 258 60 250 Z" fill="#6aaa78" opacity="0.8"/>
                    <path d="M60 250 L36 252" stroke="#4a7c59" stroke-width="0.7" fill="none" opacity="0.5"/>
                    <!-- Feuille 4 -->
                    <path d="M160 110 C148 88 122 82 116 96 C110 110 122 130 144 132 C162 132 168 120 160 110 Z" fill="#578f68" opacity="0.82"/>
                    <path d="M160 110 L132 112" stroke="#4a7c59" stroke-width="0.7" fill="none" opacity="0.45"/>
                    <!-- Petites feuilles décoratives -->
                    <path d="M180 80 C170 65 155 62 152 72 C149 82 158 94 172 94 C182 92 186 84 180 80 Z" fill="#7ac285" opacity="0.75"/>
                    <path d="M75 270 C65 258 52 258 50 266 C48 274 58 282 70 280 C78 278 80 272 75 270 Z" fill="#7ac285" opacity="0.7"/>
                </svg>
            </div>

            <!-- Floral coin bas droite sur la photo (original supprimé ci-dessous) -->
            <div class="floral-accent floral-accent--hero-br" aria-hidden="true" style="display:none;">
                <svg viewBox="0 0 320 320" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <!-- Grandes fleurs -->
                    <circle cx="260" cy="260" r="36" fill="#F5E8E6" opacity="0.8"/>
                    <circle cx="220" cy="240" r="20" fill="#DBAAA4" opacity="0.6"/>
                    <circle cx="280" cy="220" r="16" fill="#DBAAA4" opacity="0.5"/>
                    <circle cx="240" cy="200" r="12" fill="#C4857A" opacity="0.4"/>
                    <circle cx="295" cy="260" r="22" fill="#F5E8E6" opacity="0.7"/>
                    <!-- Petites fleurs -->
                    <circle cx="180" cy="270" r="10" fill="#DBAAA4" opacity="0.4"/>
                    <circle cx="260" cy="180" r="8"  fill="#DBAAA4" opacity="0.3"/>
                    <!-- Tiges / feuilles -->
                    <path d="M200 320 Q230 270 260 260" stroke="#8BA888" stroke-width="1.5" fill="none" opacity="0.6"/>
                    <path d="M320 200 Q280 230 260 260" stroke="#8BA888" stroke-width="1.5" fill="none" opacity="0.6"/>
                    <ellipse cx="245" cy="268" rx="18" ry="7" fill="#8BA888" opacity="0.35" transform="rotate(-45 245 268)"/>
                    <ellipse cx="270" cy="248" rx="14" ry="6" fill="#8BA888" opacity="0.3" transform="rotate(30 270 248)"/>
                    <!-- Pétales -->
                    <ellipse cx="260" cy="230" rx="8" ry="14" fill="#F5E8E6" opacity="0.7" transform="rotate(-20 260 230)"/>
                    <ellipse cx="240" cy="255" rx="8" ry="14" fill="#F5E8E6" opacity="0.7" transform="rotate(60 240 255)"/>
                    <ellipse cx="280" cy="255" rx="8" ry="14" fill="#F5E8E6" opacity="0.7" transform="rotate(-60 280 255)"/>
                </svg>
            </div>

            <!-- Badge Canadian Prime -->
            <div class="hero__badge">
                <div class="hero__badge-icon" style="width:68px;height:68px;border-radius:50%;background:white;border:1.5px solid var(--blush);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:4px;flex-shrink:0;">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="var(--blush)" stroke="none">
                        <path d="M2 19h20v2H2zM3 9l3.5 3.5L12 3l5.5 9.5L21 9l1 10H2z"/>
                    </svg>
                    <span style="font-family:var(--font-serif);font-size:0.68rem;font-style:italic;color:var(--charcoal);letter-spacing:0.03em;line-height:1;">Prime</span>
                    <span style="color:var(--blush);font-size:0.5rem;letter-spacing:4px;line-height:1;">★★★</span>
                </div>
                <div>
                    <div class="hero__badge-title">Canadian Prime</div>
                    <div class="hero__badge-sub">Grade le plus élevé au Canada</div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== FEATURES STRIP ===== -->
    <div class="features-strip">
        <div class="container">
            <div class="features-strip__inner">
                <div class="strip-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                    <span class="strip-item__text">Livraison à domicile</span>
                </div>
                <div class="strip-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    <span class="strip-item__text">Emballage Cryovac sous-vide</span>
                </div>
                <div class="strip-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                    <span class="strip-item__text">Producteurs Locaux</span>
                </div>
                <div class="strip-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <span class="strip-item__text">Paiement à la livraison</span>
                </div>
                <div class="strip-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="20 6 9 17 4 12"/></svg>
                    <span class="strip-item__text">Sans contrat de service</span>
                </div>
                <div class="strip-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                    <span class="strip-item__text">Financement Disponible</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== COLLECTIONS ===== -->
    <section class="collections plaster-bg" id="collections" style="position:relative; overflow:hidden;">

        <!-- Feuilles coin bas gauche -->
        <div class="leaf-deco leaf-deco--bl" aria-hidden="true" style="opacity:0.55;">
            <svg viewBox="0 0 300 280" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 280 Q35 235 72 200 Q105 168 135 145" stroke="#4a7c59" stroke-width="1.8" fill="none" opacity="0.55"/>
                <path d="M72 200 C48 178 30 174 24 188 C18 202 34 222 58 224 C76 224 80 208 72 200 Z" fill="#6aaa78" opacity="0.72"/>
                <path d="M72 200 L46 205" stroke="#3a6645" stroke-width="0.8" fill="none" opacity="0.45"/>
                <path d="M105 168 C84 148 65 144 60 156 C55 168 70 186 92 188 C108 188 112 176 105 168 Z" fill="#5a9468" opacity="0.78"/>
                <path d="M28 255 C12 240 4 238 2 248 C0 258 10 268 26 266 C38 264 38 256 28 255 Z" fill="#7ac285" opacity="0.68"/>
                <path d="M135 145 C116 126 98 122 94 134 C90 146 104 162 126 164 C142 162 144 152 135 145 Z" fill="#4e8a62" opacity="0.76"/>
            </svg>
        </div>
        <div class="container">
            <div class="collections__header" data-reveal>
                <div>
                    <span class="t-label">Nos collections</span>
                    <div class="ornate-divider">
                    <div class="ornate-divider__line"></div>
                    <div class="ornate-divider__center">✦ &nbsp; ◆ &nbsp; ✦</div>
                    <div class="ornate-divider__line ornate-divider__line--right"></div>
                </div>
                    <h2 class="t-h2">Une sélection <em style="font-style:italic;color:var(--blush)">d'exception</em></h2>
                </div>
                <a href="<?php echo esc_url( ad_shop_url() ); ?>" class="btn btn-outline-blush">Voir tout le catalogue</a>
            </div>
            <div class="collections__grid">
                <a href="<?php echo esc_url( ad_collection_url( 'viandes' ) ); ?>" class="col-card col-card--wide" data-reveal>
                    <div class="col-card__img">
                        <img src="<?php echo esc_url( ad_asset( 'images/filet.webp' ) ); ?>" alt="Viandes Prime">
                    </div>
                    <div class="col-card__body">
                        <div class="col-card__label">Sélection Prime</div>
                        <h3 class="col-card__title">Viandes d'exception</h3>
                        <p class="col-card__desc">Bœuf canadien, côtes levées, porc et sélection de gibier. Des incontournables pour le BBQ, des repas réconfortants et des pièces qui se démarquent lors des grandes occasions.</p>
                        <span class="col-card__link">Explorer la collection</span>
                    </div>
                </a>
                <a href="<?php echo esc_url( ad_collection_url( 'fruits-de-mer-poissons' ) ); ?>" class="col-card" data-reveal data-d="2">
                    <div class="col-card__img col-card__img-std">
                        <img src="<?php echo esc_url( ad_asset( 'images/seafood.jpg' ) ); ?>" alt="Fruits de mer">
                    </div>
                    <div class="col-card__body">
                        <div class="col-card__label">Produits de la mer</div>
                        <h3 class="col-card__title">Fruits de mer & Poissons</h3>
                        <p class="col-card__desc">Découvrez notre longe de morue, notre saumon de qualité tartare et sushi, nos crevettes nordiques ainsi que nos pétoncles U10, sélectionnés pour leur fraîcheur, leur texture et leur goût exceptionnel.</p>
                        <span class="col-card__link">Explorer</span>
                    </div>
                </a>
                <a href="<?php echo esc_url( ad_collection_url( 'veau-volaille-agneau' ) ); ?>" class="col-card" data-reveal>
                    <div class="col-card__img col-card__img-std">
                        <img src="<?php echo esc_url( ad_asset( 'images/volaille.jpg' ) ); ?>" alt="Veau Agneau">
                    </div>
                    <div class="col-card__body">
                        <div class="col-card__label">Produits locaux</div>
                        <h3 class="col-card__title">Veau · Volaille & Agneau</h3>
                        <p class="col-card__desc">Veau de lait du Québec, carré d'agneau, cuisses de canard confit artisanal.</p>
                        <span class="col-card__link">Explorer</span>
                    </div>
                </a>
                <a href="<?php echo esc_url( ad_collection_url( 'produits-gourmets' ) ); ?>" class="col-card" data-reveal data-d="2">
                    <div class="col-card__img col-card__img-std">
                        <img src="<?php echo esc_url( ad_asset( 'images/tartare.webp' ) ); ?>" alt="Produits Gourmets">
                    </div>
                    <div class="col-card__body">
                        <div class="col-card__label">Produits Préparés</div>
                        <h3 class="col-card__title">Produits Gourmets</h3>
                        <p class="col-card__desc">Coquilles aux fruits de mer, bouchées de saumon fumé à l'érable et sélection raffinée.</p>
                        <span class="col-card__link">Explorer</span>
                    </div>
                </a>
                <a href="<?php echo esc_url( ad_collection_url( 'desserts' ) ); ?>" class="col-card" data-reveal data-d="3">
                    <div class="col-card__img col-card__img-std">
                        <img src="<?php echo esc_url( ad_asset( 'images/desserts.webp' ) ); ?>" alt="Desserts">
                    </div>
                    <div class="col-card__body">
                        <div class="col-card__label">Finales sucrées</div>
                        <h3 class="col-card__title">Desserts Artisanaux</h3>
                        <p class="col-card__desc">Des créations sucrées pour couronner votre repas en beauté.</p>
                        <span class="col-card__link">Explorer</span>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- ===== PROMISE ===== -->
    <section class="promise plaster-bg" id="promise" style="position:relative; overflow:hidden;">

        <!-- FEUILLES coin haut droit -->
        <div class="leaf-deco leaf-deco--tr" aria-hidden="true">
            <svg viewBox="0 0 340 320" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Tige principale depuis coin -->
                <path d="M340 0 Q300 50 265 90 Q230 130 200 160 Q170 190 150 220" stroke="#4a7c59" stroke-width="2" fill="none" opacity="0.6"/>
                <!-- Feuille 1 grande -->
                <path d="M265 90 C290 68 310 58 318 72 C326 86 312 112 288 118 C268 122 258 104 265 90 Z" fill="#5a9468" opacity="0.88"/>
                <path d="M265 90 L295 100" stroke="#3a6645" stroke-width="0.9" fill="none" opacity="0.5"/>
                <path d="M272 100 L298 106" stroke="#4a7c59" stroke-width="0.6" fill="none" opacity="0.4"/>
                <path d="M275 110 L295 114" stroke="#4a7c59" stroke-width="0.6" fill="none" opacity="0.35"/>
                <!-- Feuille 2 -->
                <path d="M230 130 C255 108 275 100 282 114 C289 128 276 152 252 156 C234 158 224 144 230 130 Z" fill="#4e8a62" opacity="0.85"/>
                <path d="M230 130 L260 140" stroke="#3d6b4a" stroke-width="0.9" fill="none" opacity="0.5"/>
                <path d="M238 140 L262 148" stroke="#4a7c59" stroke-width="0.6" fill="none" opacity="0.4"/>
                <!-- Feuille 3 petit -->
                <path d="M310 30 C325 15 338 12 340 22 C342 32 330 46 315 46 C305 44 305 34 310 30 Z" fill="#6aaa78" opacity="0.8"/>
                <!-- Feuille 4 -->
                <path d="M200 160 C222 140 240 134 246 146 C252 158 240 178 218 182 C202 184 194 170 200 160 Z" fill="#578f68" opacity="0.82"/>
                <path d="M200 160 L228 168" stroke="#4a7c59" stroke-width="0.7" fill="none" opacity="0.45"/>
                <!-- Petite -->
                <path d="M340 55 C340 42 340 32 340 25 C332 28 322 38 325 48 C328 56 338 58 340 55 Z" fill="#7ac285" opacity="0.72"/>
            </svg>
        </div>

        <!-- FEUILLES coin bas gauche -->
        <div class="leaf-deco leaf-deco--bl" aria-hidden="true">
            <svg viewBox="0 0 300 280" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 280 Q40 230 80 195 Q115 162 148 138" stroke="#4a7c59" stroke-width="2" fill="none" opacity="0.6"/>
                <path d="M80 195 C55 172 38 168 32 182 C26 196 42 218 66 220 C84 220 88 204 80 195 Z" fill="#5a9468" opacity="0.86"/>
                <path d="M80 195 L52 200" stroke="#3a6645" stroke-width="0.8" fill="none" opacity="0.5"/>
                <path d="M72 204 L48 208" stroke="#4a7c59" stroke-width="0.6" fill="none" opacity="0.4"/>
                <path d="M115 162 C92 140 72 136 66 150 C60 164 76 184 100 186 C118 186 122 172 115 162 Z" fill="#4e8a62" opacity="0.84"/>
                <path d="M115 162 L88 166" stroke="#3d6b4a" stroke-width="0.8" fill="none" opacity="0.5"/>
                <path d="M30 258 C12 242 4 240 2 250 C0 260 12 272 28 270 C40 268 40 258 30 258 Z" fill="#7ac285" opacity="0.75"/>
                <path d="M148 138 C128 118 108 114 104 126 C100 138 114 156 136 158 C152 158 156 146 148 138 Z" fill="#578f68" opacity="0.82"/>
                <path d="M148 138 L122 142" stroke="#4a7c59" stroke-width="0.7" fill="none" opacity="0.45"/>
            </svg>
        </div>

        <!-- Floral décoration coin haut droit (désactivé) -->
        <div class="floral-accent floral-accent--section-tr" aria-hidden="true" style="display:none;">
            <svg viewBox="0 0 260 260" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="200" cy="60"  r="28" fill="#DBAAA4" opacity="0.5"/>
                <circle cx="230" cy="90"  r="18" fill="#C4857A" opacity="0.4"/>
                <circle cx="175" cy="85"  r="14" fill="#F5E8E6" opacity="0.7"/>
                <circle cx="240" cy="130" r="12" fill="#DBAAA4" opacity="0.4"/>
                <circle cx="215" cy="35"  r="10" fill="#C4857A" opacity="0.3"/>
                <path d="M160 100 Q190 60 220 60" stroke="#8BA888" stroke-width="1.5" fill="none" opacity="0.55"/>
                <path d="M220 60 Q250 50 245 90" stroke="#8BA888" stroke-width="1.5" fill="none" opacity="0.55"/>
                <ellipse cx="205" cy="68" rx="16" ry="6" fill="#8BA888" opacity="0.35" transform="rotate(-40 205 68)"/>
                <ellipse cx="232" cy="80" rx="12" ry="5" fill="#8BA888" opacity="0.3" transform="rotate(20 232 80)"/>
            </svg>
        </div>
        <!-- Floral décoration coin bas gauche -->
        <div class="floral-accent floral-accent--section-bl" aria-hidden="true">
            <svg viewBox="0 0 240 240" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="40"  cy="200" r="26" fill="#DBAAA4" opacity="0.45"/>
                <circle cx="70"  cy="220" r="16" fill="#F5E8E6" opacity="0.65"/>
                <circle cx="20"  cy="170" r="14" fill="#C4857A" opacity="0.35"/>
                <circle cx="90"  cy="195" r="10" fill="#DBAAA4" opacity="0.35"/>
                <path d="M20 220 Q40 185 75 190" stroke="#8BA888" stroke-width="1.5" fill="none" opacity="0.5"/>
                <ellipse cx="50"  cy="205" rx="14" ry="5" fill="#8BA888" opacity="0.32" transform="rotate(30 50 205)"/>
            </svg>
        </div>
        <div class="container">
            <div class="promise__header" data-reveal>
                <span class="t-label">Nos engagements</span>
                <div class="ornate-divider ornate-divider--center">
                    <div class="ornate-divider__line"></div>
                    <div class="ornate-divider__center">✦ &nbsp; ◆ &nbsp; ✦</div>
                    <div class="ornate-divider__line ornate-divider__line--right"></div>
                </div>
                <h2 class="t-h2">Pourquoi Aliments<br>Distinction ?</h2>
            </div>
            <div class="promise__grid">
                <div class="promise__card" data-reveal>
                    <div class="promise__icon-wrap">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                    </div>
                    <h3 class="promise__card-title">Sélection Prime</h3>
                    <div class="promise__card-tag">Qualité certifiée</div>
                    <p class="promise__card-text">Découvrez nos différentes coupes, incluant filet mignon, contre-filet, faux-filet, tomahawk, bavette et autres coupes de choix, sélectionnées pour leur qualité, leur persillage et leur richesse en bouche.</p>
                </div>
                <div class="promise__card" data-reveal data-d="2">
                    <div class="promise__icon-wrap">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    </div>
                    <h3 class="promise__card-title">Terroir d'ici</h3>
                    <div class="promise__card-tag">Producteurs locaux</div>
                    <p class="promise__card-text">Parce que bien manger, c'est aussi savoir d'où viennent les produits que l'on met dans notre assiette. Chez Aliments Distinction, nous privilégions autant que possible les producteurs et artisans d'ici, en sélectionnant des produits pour leur qualité, leur fraîcheur et leur savoir-faire.</p>
                </div>
                <div class="promise__card" data-reveal data-d="3">
                    <div class="promise__icon-wrap">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    </div>
                    <h3 class="promise__card-title">Qualité & Santé</h3>
                    <div class="promise__card-tag">Flash-surgelé Cryovac</div>
                    <p class="promise__card-text">Surgelés rapidement afin de préserver toute leur fraîcheur, nos produits sont emballés sous vide Cryovac pour conserver leur qualité, leur texture et leurs saveurs. Sans additif ni conservateur, ils sont prêts à être cuisinés selon vos envies, notamment en cuisson sous-vide.</p>
                </div>
            </div>
        </div>
    </section>

    <?php $ad_featured = ad_featured_product(); if ( $ad_featured ) : ?>
<?php get_template_part( 'template-parts/featured-product' ); ?>
<?php else : ?>
<!-- ===== FEATURED PRODUCT ===== -->
    <section class="featured">
        <div class="container">
            <div class="featured__inner">
                <div class="featured__image-wrap" data-reveal>
                    <div class="featured__image">
                        <img src="https://images.unsplash.com/photo-1558030006-450675393462?w=900&q=90" alt="Contre-filet Canadian Prime">
                    </div>
                    <div class="featured__tag">
                        <div class="featured__tag-dot"></div>
                        <span>Produit signature</span>
                    </div>
                    <div class="featured__card">
                        <div class="featured__card-label">Contre-filet Prime</div>
                        <div class="featured__card-name">Vieilli 50 jours</div>
                        <div class="featured__card-specs">
                            <div class="feat-spec"><span class="feat-spec__k">Grade</span><span class="feat-spec__v">Prime</span></div>
                            <div class="feat-spec"><span class="feat-spec__k">Emballage</span><span class="feat-spec__v">Cryovac</span></div>
                            <div class="feat-spec"><span class="feat-spec__k">Prix</span><span class="feat-spec__v" style="color:var(--blush)"><?php echo $ad_featured ? wp_kses_post( $ad_featured->get_price_html() ) : esc_html__( 'Voir le catalogue', 'aliments-distinction' ); ?></span></div>
                        </div>
                    </div>
                </div>
                <div data-reveal data-d="2">
                    <span class="t-label">Coup de cœur</span>
                    <div class="ornate-divider">
                    <div class="ornate-divider__line"></div>
                    <div class="ornate-divider__center">✦ &nbsp; ◆ &nbsp; ✦</div>
                    <div class="ornate-divider__line ornate-divider__line--right"></div>
                </div>
                    <h2 class="t-h2">Contre-filet<br>Canadian Prime</h2>
                    <div class="featured__price"><?php echo $ad_featured ? wp_kses_post( $ad_featured->get_price_html() ) : esc_html__( 'Voir le catalogue', 'aliments-distinction' ); ?></div>
                    <p class="t-body">La pièce maîtresse de notre sélection. Certifiée Canadian Prime — attribuée à moins de 2 % des bovins canadiens — cette coupe est vieillie à sec 50 jours pour développer une richesse aromatique profonde et une tendreté fondante. Emballée sous vide Cryovac, prête pour votre cuisson sous-vide.</p>
                    <div class="featured__qualities">
                        <span class="quality-pill"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>Sans antibiotiques</span>
                        <span class="quality-pill"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>Riche en protéines</span>
                        <span class="quality-pill"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>Origine traçable</span>
                        <span class="quality-pill"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>Sous-vide compatible</span>
                    </div>
                    <a href="<?php echo esc_url( $ad_featured ? get_permalink( $ad_featured->get_id() ) : ad_shop_url() ); ?>" class="btn btn-blush"><?php echo esc_html( $ad_featured ? __( 'Voir le produit', 'aliments-distinction' ) : __( 'Découvrir les produits', 'aliments-distinction' ) ); ?></a>
                </div>
            </div>
        </div>
    </section>

    <?php endif; ?>
<!-- ===== STORY ===== -->
    <section class="story plaster-bg" id="story">
        <div class="story__inner">
            <div class="story__image">
                <img src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=1000&q=85" alt="Notre passion">
            </div>
            <div class="story__content">
                <span class="t-label">Notre histoire</span>
                <div class="ornate-divider">
                    <div class="ornate-divider__line"></div>
                    <div class="ornate-divider__center">✦ &nbsp; ◆ &nbsp; ✦</div>
                    <div class="ornate-divider__line ornate-divider__line--right"></div>
                </div>
                <h2 class="t-h2">Une passion pour<br><em style="font-style:italic;color:var(--blush)">le bien manger</em></h2>
                <blockquote class="story__quote">« Nous croyons que chaque repas mérite les mêmes ingrédients que les grands restaurants — sans compromis sur la qualité, ni sur l'origine. »</blockquote>
                <p class="t-body">Fondée avec une vision claire, Aliments Distinction s'est donné pour mission de rendre accessible ce que les grands chefs exigent. Nous sélectionnons chaque produit avec le soin d'un chef étoilé, pour votre table de tous les jours.</p>
                <br>
                <p class="t-body">Notre réseau de producteurs québécois de confiance garantit la traçabilité complète et soutient notre économie locale — parce qu'excellence et valeurs ne sont pas incompatibles.</p>
                <a href="<?php echo esc_url( ad_page_url( 'notre-histoire', 'story' ) ); ?>" class="btn btn-outline-blush" style="margin-top: 40px;">Lire notre histoire</a>
            </div>
        </div>
    </section>

    <!-- ===== HEALTH STRIP ===== -->
    <div class="health-strip">
        <div class="container">
            <div class="health-strip__inner">
                <div class="health-item">
                    <div class="health-item__icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></div>
                    <span class="health-item__label">Sans additif</span>
                </div>
                <div class="health-item">
                    <div class="health-item__icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg></div>
                    <span class="health-item__label">Origine traçable</span>
                </div>
                <div class="health-item">
                    <div class="health-item__icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg></div>
                    <span class="health-item__label">Producteurs locaux</span>
                </div>
                <div class="health-item">
                    <div class="health-item__icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg></div>
                    <span class="health-item__label">Riche en protéines</span>
                </div>
                <div class="health-item">
                    <div class="health-item__icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
                    <span class="health-item__label">Élevage éthique</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== RECETTES ===== -->
    <section class="recettes plaster-bg" id="recettes" style="background: var(--cream-2); padding: 100px 0; position: relative; overflow: hidden;">

        <!-- Feuilles coin haut droit -->
        <div class="leaf-deco leaf-deco--tr" aria-hidden="true" style="opacity: 0.45;">
            <svg viewBox="0 0 340 320" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M340 0 Q295 55 260 95 Q225 132 195 162" stroke="#4a7c59" stroke-width="2" fill="none" opacity="0.6"/>
                <path d="M260 95 C283 73 302 66 309 80 C316 94 302 118 278 122 C260 124 252 108 260 95 Z" fill="#5a9468" opacity="0.82"/>
                <path d="M225 132 C246 112 264 106 270 118 C276 130 264 150 242 154 C226 154 218 142 225 132 Z" fill="#4e8a62" opacity="0.78"/>
                <path d="M330 38 C338 22 340 15 340 22 C340 32 335 42 328 42 Z" fill="#7ac285" opacity="0.65"/>
            </svg>
        </div>

        <div class="container">
            <div style="text-align: center; max-width: 560px; margin: 0 auto 64px;" data-reveal>
                <span class="t-label">Inspirations culinaires</span>
                <div class="ornate-divider ornate-divider--center">
                    <div class="ornate-divider__line"></div>
                    <div class="ornate-divider__center">✦ &nbsp; ◆ &nbsp; ✦</div>
                    <div class="ornate-divider__line ornate-divider__line--right"></div>
                </div>
                <h2 class="t-h2">Nos recettes <em style="font-style:italic; color:var(--blush)">signature</em></h2>
                <p class="t-body" style="margin-top: 18px;">Tirez le meilleur de chaque produit avec nos suggestions de préparation — des recettes simples et élégantes conçues autour de nos coupes d'exception.</p>
            </div>

            <div class="ad-recipe-grid">

                <!-- Recette 1 -->
                <div class="col-card" data-reveal style="cursor: default;">
                    <div class="col-card__img col-card__img-std" style="height: 240px;">
                        <img src="https://images.unsplash.com/photo-1558030006-450675393462?w=700&q=85" alt="Contre-filet sous-vide">
                    </div>
                    <div class="col-card__body">
                        <div class="col-card__label">Bœuf Prime</div>
                        <h3 class="col-card__title">Contre-filet sous-vide,<br>beurre à l'ail & thym</h3>
                        <div style="display: flex; gap: 16px; margin: 12px 0;">
                            <span style="font-size: 0.68rem; color: var(--gray); display: flex; align-items: center; gap: 5px;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                55 min
                            </span>
                            <span style="font-size: 0.68rem; color: var(--gray); display: flex; align-items: center; gap: 5px;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                2 personnes
                            </span>
                            <span style="font-size: 0.68rem; color: var(--blush); font-weight: 500;">Facile</span>
                        </div>
                        <p class="col-card__desc">Cuisson précise à 54°C pour un médium-saignant parfait, saisie express à la poêle en fonte. Un résultat digne d'un chef étoilé.</p>
                        <span class="col-card__link">Inspiration culinaire</span>
                    </div>
                </div>

                <!-- Recette 2 -->
                <div class="col-card" data-reveal data-d="2" style="cursor: default;">
                    <div class="col-card__img col-card__img-std" style="height: 240px;">
                        <img src="https://images.unsplash.com/photo-1559847844-5315695dadae?w=700&q=85" alt="Homard grillé">
                    </div>
                    <div class="col-card__body">
                        <div class="col-card__label">Fruits de mer</div>
                        <h3 class="col-card__title">Homard grillé,<br>beurre citronné & ciboulette</h3>
                        <div style="display: flex; gap: 16px; margin: 12px 0;">
                            <span style="font-size: 0.68rem; color: var(--gray); display: flex; align-items: center; gap: 5px;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                25 min
                            </span>
                            <span style="font-size: 0.68rem; color: var(--gray); display: flex; align-items: center; gap: 5px;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                                2 personnes
                            </span>
                            <span style="font-size: 0.68rem; color: var(--blush); font-weight: 500;">Facile</span>
                        </div>
                        <p class="col-card__desc">La simplicité au service de l'excellence. Le homard de la Gaspésie ne demande qu'un peu de beurre et de citron pour révéler tout son caractère.</p>
                        <span class="col-card__link">Inspiration culinaire</span>
                    </div>
                </div>

                <!-- Recette 3 -->
                <div class="col-card" data-reveal data-d="3" style="cursor: default;">
                    <div class="col-card__img col-card__img-std" style="height: 240px;">
                        <img src="https://images.unsplash.com/photo-1600891964092-4316c288032e?w=700&q=85" alt="Carré d'agneau">
                    </div>
                    <div class="col-card__body">
                        <div class="col-card__label">Agneau du Québec</div>
                        <h3 class="col-card__title">Carré d'agneau en croûte<br>d'herbes & moutarde</h3>
                        <div style="display: flex; gap: 16px; margin: 12px 0;">
                            <span style="font-size: 0.68rem; color: var(--gray); display: flex; align-items: center; gap: 5px;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                45 min
                            </span>
                            <span style="font-size: 0.68rem; color: var(--gray); display: flex; align-items: center; gap: 5px;">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                4 personnes
                            </span>
                            <span style="font-size: 0.68rem; color: var(--earth); font-weight: 500;">Intermédiaire</span>
                        </div>
                        <p class="col-card__desc">Une croûte dorée de chapelure, persil et ail qui enrobe délicatement notre carré d'agneau du Québec. Le plat de résistance parfait pour épater vos convives.</p>
                        <span class="col-card__link">Inspiration culinaire</span>
                    </div>
                </div>

            </div>

            <div style="text-align: center; margin-top: 48px;" data-reveal>
                <?php if ( get_page_by_path( 'recettes' ) ) : ?><a href="<?php echo esc_url( ad_page_url( 'recettes', 'recettes' ) ); ?>" class="btn btn-outline-blush">Voir toutes les recettes</a><?php endif; ?>
            </div>
        </div>
    </section>

    <!-- ===== PROCESS ===== -->
    <section class="process" id="process">
        <div class="container">
            <div class="process__header" data-reveal>
                <span class="t-label">De la ferme à votre table</span>
                <div class="ornate-divider ornate-divider--center">
                    <div class="ornate-divider__line"></div>
                    <div class="ornate-divider__center">✦ &nbsp; ◆ &nbsp; ✦</div>
                    <div class="ornate-divider__line ornate-divider__line--right"></div>
                </div>
                <h2 class="t-h2">Une fraîcheur<br>sans compromis</h2>
            </div>
            <div class="process__steps">
                <div class="step" data-reveal>
                    <div class="step__num">01</div>
                    <div class="step__icon-wrap"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg></div>
                    <h3 class="step__title">Sélection rigoureuse</h3>
                    <p class="step__text">Chaque pièce est choisie à la main selon nos standards exigeants. Seuls les meilleurs grades, les producteurs les plus réputés et les produits de saison entrent dans notre catalogue.</p>
                </div>
                <div class="step" data-reveal data-d="2">
                    <div class="step__num">02</div>
                    <div class="step__icon-wrap"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg></div>
                    <h3 class="step__title">Flash-surgélation Cryovac</h3>
                    <p class="step__text">Flash-surgelé à la source pour préserver vitamines et nutriments, puis emballé sous vide Cryovac — sans additif, compatible avec la cuisson sous-vide pour un résultat de chef.</p>
                </div>
                <div class="step" data-reveal data-d="3">
                    <div class="step__num">03</div>
                    <div class="step__icon-wrap"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg></div>
                    <h3 class="step__title">Livraison à votre porte</h3>
                    <p class="step__text">Commandez en ligne ou par téléphone. Paiement à la réception — Interac, Visa ou Mastercard. Sans contrat, sans engagement. Simplement l'excellence livrée chez vous.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== TESTIMONIALS ===== -->
    <section class="testimonials plaster-bg" style="position:relative; overflow:hidden;">
        <!-- Feuilles discrètes coin haut droit -->
        <div class="leaf-deco leaf-deco--tr" aria-hidden="true" style="opacity:0.38;">
            <svg viewBox="0 0 340 320" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M340 0 Q295 55 260 95 Q225 132 195 162" stroke="#4a7c59" stroke-width="2" fill="none" opacity="0.5"/>
                <path d="M260 95 C283 73 302 66 309 80 C316 94 302 118 278 122 C260 124 252 108 260 95 Z" fill="#6aaa78" opacity="0.7"/>
                <path d="M225 132 C246 112 264 106 270 118 C276 130 264 150 242 154 C226 154 218 142 225 132 Z" fill="#5a9468" opacity="0.75"/>
                <path d="M330 38 C338 22 340 15 340 22 C340 32 335 42 328 42 Z" fill="#7ac285" opacity="0.65"/>
            </svg>
        </div>
        <div class="container">
            <div class="testimonials__header" data-reveal>
                <span class="t-label">Ils nous font confiance</span>
                <div class="ornate-divider ornate-divider--center">
                    <div class="ornate-divider__line"></div>
                    <div class="ornate-divider__center">✦ &nbsp; ◆ &nbsp; ✦</div>
                    <div class="ornate-divider__line ornate-divider__line--right"></div>
                </div>
                <h2 class="t-h2">L'excellence,<br>vécue au quotidien</h2>
            </div>
            <div class="testimonials__grid">
                <div class="testimonial" data-reveal>
                    <div class="testimonial__stars"><span class="testimonial__star">★</span><span class="testimonial__star">★</span><span class="testimonial__star">★</span><span class="testimonial__star">★</span><span class="testimonial__star">★</span></div>
                    <p class="testimonial__text">« Le contre-filet Prime est tout simplement le meilleur bœuf que j'ai cuisiné à la maison. La qualité rivalise avec les meilleurs restaurants de Montréal. Je ne peux plus m'en passer. »</p>
                    <div class="testimonial__author">
                        <div class="testimonial__avatar">M</div>
                        <div><div class="testimonial__name">Marie-Ève L.</div><div class="testimonial__location">Laval, Québec</div></div>
                    </div>
                </div>
                <div class="testimonial" data-reveal data-d="2">
                    <div class="testimonial__stars"><span class="testimonial__star">★</span><span class="testimonial__star">★</span><span class="testimonial__star">★</span><span class="testimonial__star">★</span><span class="testimonial__star">★</span></div>
                    <p class="testimonial__text">« La chair de crabe des neiges était incroyable — fraîche, généreuse, un emballage parfait. La livraison s'est faite exactement comme promis. Un service absolument irréprochable. »</p>
                    <div class="testimonial__author">
                        <div class="testimonial__avatar">F</div>
                        <div><div class="testimonial__name">François D.</div><div class="testimonial__location">Brossard, Québec</div></div>
                    </div>
                </div>
                <div class="testimonial" data-reveal data-d="3">
                    <div class="testimonial__stars"><span class="testimonial__star">★</span><span class="testimonial__star">★</span><span class="testimonial__star">★</span><span class="testimonial__star">★</span><span class="testimonial__star">★</span></div>
                    <p class="testimonial__text">« Le carré d'agneau du Québec était une révélation. Tendre, savoureux, et la cuisson sous-vide était un jeu d'enfant. Je recommande à toutes mes amies amatrices de bonne cuisine. »</p>
                    <div class="testimonial__author">
                        <div class="testimonial__avatar">I</div>
                        <div><div class="testimonial__name">Isabelle P.</div><div class="testimonial__location">Ville de Québec</div></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== CTA ===== -->
    <section class="cta" style="position:relative; overflow:hidden;">
        <div class="cta__bg"></div>
        <div class="cta__overlay"></div>

        <!-- FEUILLES BOTANIQUES sur le CTA -->
        <!-- Coin haut gauche -->
        <div class="leaf-deco leaf-deco--tl" aria-hidden="true" style="z-index:2; opacity:0.72;">
            <svg viewBox="0 0 260 260" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 0 Q40 50 75 90 Q110 130 135 165" stroke="#4a7c59" stroke-width="2.2" fill="none" opacity="0.65"/>
                <path d="M75 90 C50 68 32 62 26 76 C20 90 36 114 62 118 C80 120 84 102 75 90 Z" fill="#5a9468" opacity="0.88"/>
                <path d="M75 90 L46 96" stroke="#3a6645" stroke-width="0.9" fill="none" opacity="0.5"/>
                <path d="M65 100 L40 105" stroke="#4a7c59" stroke-width="0.6" fill="none" opacity="0.4"/>
                <path d="M55 110 L35 114" stroke="#4a7c59" stroke-width="0.6" fill="none" opacity="0.35"/>
                <path d="M110 130 C88 110 68 106 63 118 C58 130 72 150 96 152 C112 152 116 140 110 130 Z" fill="#4e8a62" opacity="0.85"/>
                <path d="M110 130 L84 136" stroke="#3d6b4a" stroke-width="0.8" fill="none" opacity="0.48"/>
                <path d="M18 40 C5 28 -2 28 0 38 C2 48 14 56 26 52 C34 48 30 40 18 40 Z" fill="#7ac285" opacity="0.78"/>
                <path d="M42 8 C30 0 20 2 20 12 C20 22 32 28 44 24 C52 20 50 12 42 8 Z" fill="#6aaa78" opacity="0.72"/>
            </svg>
        </div>
        <!-- Coin bas droit -->
        <div class="leaf-deco leaf-deco--br" aria-hidden="true" style="z-index:2; opacity:0.72;">
            <svg viewBox="0 0 280 280" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M280 280 Q240 230 205 190 Q170 150 145 118" stroke="#4a7c59" stroke-width="2.2" fill="none" opacity="0.65"/>
                <path d="M205 190 C228 168 248 163 255 176 C262 190 248 214 224 218 C206 220 198 204 205 190 Z" fill="#5a9468" opacity="0.88"/>
                <path d="M205 190 L232 196" stroke="#3a6645" stroke-width="0.9" fill="none" opacity="0.5"/>
                <path d="M212 200 L236 205" stroke="#4a7c59" stroke-width="0.6" fill="none" opacity="0.4"/>
                <path d="M170 150 C192 128 210 124 216 136 C222 148 210 168 186 172 C168 172 162 160 170 150 Z" fill="#4e8a62" opacity="0.85"/>
                <path d="M170 150 L196 156" stroke="#3d6b4a" stroke-width="0.8" fill="none" opacity="0.48"/>
                <path d="M255 244 C268 232 274 232 276 242 C278 252 266 260 254 256 C246 252 248 244 255 244 Z" fill="#7ac285" opacity="0.78"/>
                <path d="M236 270 C248 262 256 264 256 274 C256 280 244 280 236 276 C230 272 232 268 236 270 Z" fill="#6aaa78" opacity="0.72"/>
                <path d="M145 118 C164 98 182 94 188 106 C194 118 182 136 160 140 C144 140 138 128 145 118 Z" fill="#578f68" opacity="0.82"/>
                <path d="M145 118 L170 124" stroke="#4a7c59" stroke-width="0.7" fill="none" opacity="0.45"/>
            </svg>
        </div>

        <!-- Floral décorations coins (désactivées) -->
        <div class="floral-accent floral-accent--cta-tl" aria-hidden="true" style="display:none;">
            <svg viewBox="0 0 300 300" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="40"  cy="40"  r="32" fill="#C4857A" opacity="0.18"/>
                <circle cx="80"  cy="25"  r="20" fill="#DBAAA4" opacity="0.22"/>
                <circle cx="25"  cy="80"  r="20" fill="#DBAAA4" opacity="0.22"/>
                <circle cx="110" cy="50"  r="13" fill="#C4857A" opacity="0.16"/>
                <circle cx="50"  cy="115" r="13" fill="#C4857A" opacity="0.16"/>
                <path d="M40 40 Q70 10 110 25"   stroke="#8BA888" stroke-width="2" fill="none" opacity="0.3"/>
                <path d="M40 40 Q10 70 25 110"   stroke="#8BA888" stroke-width="2" fill="none" opacity="0.3"/>
                <ellipse cx="88"  cy="30"  rx="18" ry="7" fill="#8BA888" opacity="0.22" transform="rotate(-35 88 30)"/>
                <ellipse cx="30"  cy="88"  rx="18" ry="7" fill="#8BA888" opacity="0.22" transform="rotate(55 30 88)"/>
            </svg>
        </div>
        <div class="floral-accent floral-accent--cta-br" aria-hidden="true" style="display:none;">
            <svg viewBox="0 0 300 300" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="260" cy="260" r="32" fill="#C4857A" opacity="0.18"/>
                <circle cx="220" cy="275" r="20" fill="#DBAAA4" opacity="0.22"/>
                <circle cx="275" cy="220" r="20" fill="#DBAAA4" opacity="0.22"/>
                <circle cx="190" cy="250" r="13" fill="#C4857A" opacity="0.16"/>
                <circle cx="250" cy="190" r="13" fill="#C4857A" opacity="0.16"/>
                <path d="M260 260 Q230 290 190 275" stroke="#8BA888" stroke-width="2" fill="none" opacity="0.3"/>
                <path d="M260 260 Q290 230 275 190" stroke="#8BA888" stroke-width="2" fill="none" opacity="0.3"/>
                <ellipse cx="212" cy="270" rx="18" ry="7" fill="#8BA888" opacity="0.22" transform="rotate(20 212 270)"/>
                <ellipse cx="270" cy="212" rx="18" ry="7" fill="#8BA888" opacity="0.22" transform="rotate(-70 270 212)"/>
            </svg>
        </div>

        <div class="cta__content" data-reveal>
            <!-- Badge circulaire centré -->
            <div style="display:flex; justify-content:center; margin-bottom: 20px;">
                <div class="round-badge round-badge--md" style="background: rgba(255,255,255,0.9);">
                    <span class="round-badge__crown">♛</span>
                    <span class="round-badge__title">Depuis<br>toujours</span>
                    <span class="round-badge__stars">★ ★ ★</span>
                </div>
            </div>
            <span class="t-label">Commencez l'expérience</span>
            <div class="divider divider--center"><span class="divider__dot">◆</span></div>
            <h2 class="cta__title">Prête à élever<br><em>votre table ?</em></h2>
            <p class="cta__sub">Plus de 100 produits d'exception du terroir québécois, livrés chez vous. Sans contrat, sans compromis sur la qualité.</p>
            <div class="cta__btns">
                <a href="<?php echo esc_url( ad_home_section( 'collections' ) ); ?>" class="btn btn-blush">Commander maintenant</a>
                <a href="<?php echo esc_url( ad_home_section( 'contact' ) ); ?>" class="btn btn-outline-charcoal">Nous contacter</a>
            </div>
        </div>
    </section>

    <!-- ===== INVITATION BLOCK ===== -->
    <div style="background: var(--cream-2); padding: 56px 0; border-top: 1px solid var(--border); border-bottom: 1px solid var(--border);">
        <div class="container" style="max-width: 700px;">
            <div class="ornate-frame" style="text-align: center;">
                <span class="ornate-frame-corner ornate-frame-corner--tl"></span>
                <span class="ornate-frame-corner ornate-frame-corner--tr"></span>
                <span class="ornate-frame-corner ornate-frame-corner--bl"></span>
                <span class="ornate-frame-corner ornate-frame-corner--br"></span>

                <div class="ornate-divider ornate-divider--center" style="margin-top: 0;">
                    <div class="ornate-divider__line"></div>
                    <div class="ornate-divider__center">✦ &nbsp; ◆ &nbsp; ✦</div>
                    <div class="ornate-divider__line ornate-divider__line--right"></div>
                </div>

                <p style="font-family: var(--font-serif); font-size: 0.75rem; font-weight: 400; letter-spacing: 0.25em; text-transform: uppercase; color: var(--blush); margin-bottom: 12px;">
                    Satisfait ou remboursé
                </p>
                <h3 style="font-family: var(--font-serif); font-size: clamp(1.4rem, 2.5vw, 2rem); font-weight: 400; color: var(--charcoal); line-height: 1.25; margin-bottom: 16px;">
                    Votre satisfaction,<br><em style="font-style:italic; color:var(--blush)">notre garantie.</em>
                </h3>
                <p style="font-size: 0.82rem; color: var(--charcoal-2); line-height: 1.85; max-width: 500px; margin: 0 auto 28px;">
                    Chaque produit est garanti à la hauteur de vos attentes. Si vous n'êtes pas entièrement satisfait de votre commande, nous nous engageons à vous rembourser — sans question, sans contrat.
                </p>

                <div style="display: inline-flex; align-items: center; gap: 10px; background: var(--blush-pale); border: 1px solid var(--blush-light); border-radius: 50px; padding: 10px 28px;">
                    <span style="font-size: 0.7rem; font-weight: 500; letter-spacing: 0.18em; text-transform: uppercase; color: var(--blush);">✦ &nbsp; Satisfaction garantie &nbsp; ✦</span>
                </div>

                <div class="ornate-divider ornate-divider--center" style="margin-bottom: 0;">
                    <div class="ornate-divider__line"></div>
                    <div class="ornate-divider__center">✦ &nbsp; ◆ &nbsp; ✦</div>
                    <div class="ornate-divider__line ornate-divider__line--right"></div>
                </div>
            </div>
        </div>
    </div>

    </main>
<?php get_footer(); ?>
