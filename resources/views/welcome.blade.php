<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/jdm_distributors_logo.jpeg') }}">

    <title>JDM Distributors – Wholesale American Groceries & Snacks</title>

    {{-- Basic SEO --}}
    <meta name="description"
          content="JDM Distributors are UK importers and distributors of American and international groceries, confectionery and beverages. Explore our wholesale product catalogue and request pricing.">
    <meta name="keywords"
          content="JDM Distributors, American sweets wholesale, American groceries UK, confectionery distributor, imported snacks, wholesale drinks, UK food distributor">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    {{-- Icons --}}
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>
        :root {
            --jdm-blue: #0b3c5d;
            --jdm-red:  #f35252;
            --jdm-soft-bg: #f3f4f6;
            --jdm-card-radius: 18px;
            --jdm-shadow-soft: 0 8px 22px rgba(15, 23, 42, 0.16);
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Figtree', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            background: radial-gradient(circle at top, rgba(15,23,42,0.12), transparent 55%);
            background-color: var(--jdm-soft-bg);
            color: #111827;
        }

        /* Top nav */
        .jdm-navbar {
            background-color: #ffffff;
            border-bottom: 1px solid rgba(148, 163, 184, 0.25);
            position: sticky;
            top: 0;
            z-index: 20;
        }

        .jdm-navbar-brand {
            display: flex;
            align-items: center;
            gap: .5rem;
            font-weight: 600;
            letter-spacing: .04em;
        }
        .jdm-navbar-brand span {
            font-size: 0.9rem;
        }

        /* Hero / flag strip */
        .jdm-hero-strip {
            position: relative;
            overflow: hidden;
            border-radius: 0 0 18px 18px;
            box-shadow: 0 12px 35px rgba(15,23,42,0.28);
            background-image:
                linear-gradient(120deg, rgba(11,60,93,0.95), rgba(243,82,82,0.93)),
                url("https://upload.wikimedia.org/wikipedia/commons/a/a4/Flag_of_the_United_States.svg");
            background-size: cover;
            background-position: center;
            color: #ffffff;
        }

        .jdm-hero-overlay {
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 10% 10%, rgba(255,255,255,0.08), transparent 55%),
                linear-gradient(to bottom, rgba(15,23,42,0.26), transparent 60%);
        }

        .jdm-hero-content {
            position: relative;
            padding: 1.5rem 1.25rem;
        }

        @media (min-width: 768px) {
            .jdm-hero-content {
                padding: 2.25rem 2rem;
            }
        }

        .jdm-hero-title {
            font-size: 1.3rem;
            letter-spacing: .1em;
            text-transform: uppercase;
            opacity: .95;
        }

        .jdm-hero-heading {
            margin-top: .3rem;
            font-size: 1.65rem;
            font-weight: 700;
        }

        @media (min-width: 992px) {
            .jdm-hero-heading {
                font-size: 2rem;
            }
        }

        .jdm-hero-sub {
            font-size: .9rem;
            opacity: .95;
        }

        .jdm-hero-pill {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            padding: .15rem .7rem;
            font-size: .75rem;
            border-radius: 999px;
            background-color: rgba(15,23,42,0.35);
            border: 1px solid rgba(255,255,255,0.35);
        }

        .jdm-hero-kpis {
            display: flex;
            flex-wrap: wrap;
            gap: 0.8rem;
            margin-top: 1.25rem;
        }

        .jdm-hero-kpi {
            flex: 1 1 100%;
            padding: .65rem .75rem;
            border-radius: 12px;
            background: rgba(15,23,42,0.35);
            border: 1px solid rgba(248,250,252,0.2);
            backdrop-filter: blur(10px);
            min-width: 120px;
        }

        .jdm-hero-kpi-label {
            font-size: .7rem;
            opacity: .85;
        }

        .jdm-hero-kpi-value {
            font-size: 1rem;
            font-weight: 600;
        }

        @media (min-width: 768px) {
            .jdm-hero-kpi {
                flex: 1 1 calc(33.333% - .8rem);
            }
        }

        /* Main content wrapper card */
        .jdm-main-card {
            margin-top: 1.5rem;
            margin-bottom: 2.5rem;
            background-color: #f9fafb;
            border-radius: var(--jdm-card-radius);
            box-shadow: var(--jdm-shadow-soft);
            padding: 1.25rem 1rem;
        }

        @media (min-width: 992px) {
            .jdm-main-card {
                padding: 1.75rem 1.75rem 2rem;
            }
        }

        /* Filter chips */
        .jdm-filters {
            display: flex;
            flex-wrap: nowrap;
            overflow-x: auto;
            gap: .5rem;
            padding-bottom: .35rem;
        }
        .jdm-filters::-webkit-scrollbar {
            height: 4px;
        }
        .jdm-filters::-webkit-scrollbar-thumb {
            background: rgba(148,163,184,0.7);
            border-radius: 999px;
        }

        .jdm-filter-pill {
            white-space: nowrap;
            border-radius: 999px;
            border: 1px solid #e5e7eb;
            background-color: #ffffff;
            padding: .25rem .9rem;
            font-size: .8rem;
            cursor: pointer;
            transition: all .15s ease-out;
        }

        .jdm-filter-pill.active,
        .jdm-filter-pill:hover {
            background: linear-gradient(120deg, var(--jdm-blue), var(--jdm-red));
            color: #ffffff;
            border-color: transparent;
            box-shadow: 0 6px 16px rgba(15,23,42,0.25);
        }

        /* Search */
        .jdm-search-input {
            border-radius: 999px;
            border: 1px solid #e5e7eb;
            font-size: .9rem;
            padding-right: 2.25rem;
        }

        .jdm-search-wrap {
            position: relative;
        }
        .jdm-search-wrap .fa-search {
            position: absolute;
            right: .9rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: .85rem;
            color: #9ca3af;
        }

        /* Product grid */
        .product-card {
            background-color: #ffffff;
            border-radius: 16px;
            border: 1px solid rgba(148,163,184,0.35);
            padding: .75rem .75rem 0.85rem;
            height: 100%;
            display: flex;
            flex-direction: column;
            transition: transform .15s ease, box-shadow .15s ease, border-color .15s ease;
        }

        .product-card:hover {
            transform: translateY(-3px);
            border-color: rgba(79,70,229,0.75);
            box-shadow: 0 10px 22px rgba(15,23,42,0.18);
        }

        .product-image-wrap {
            border-radius: 12px;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            min-height: 130px;
        }

        .product-image-wrap img {
            max-height: 150px;
            width: 100%;
            object-fit: contain;
        }

        .product-meta {
            margin-top: .65rem;
            font-size: .75rem;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: .1em;
        }

        .product-title {
            margin-top: .15rem;
            font-size: .9rem;
            font-weight: 600;
            color: #111827;
            min-height: 2.4em;
        }

        .product-tags {
            margin-top: .35rem;
            display: flex;
            flex-wrap: wrap;
            gap: .3rem;
        }

        .product-tag {
            font-size: .7rem;
            padding: .1rem .4rem;
            border-radius: 999px;
            background: #eff6ff;
            color: #1d4ed8;
        }

        .product-card-footer {
            margin-top: .7rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: .5rem;
        }

        .btn-view-details {
            border-radius: 999px;
            font-size: .8rem;
            padding: .35rem .9rem;
        }

        .badge-new {
            font-size: .7rem;
            border-radius: 999px;
            padding: .15rem .6rem;
            background: #ecfdf3;
            color: #166534;
        }

        /* About + generic cards */
        .about-card {
            border-radius: 18px;
            background: #ffffff;
            border: 1px solid rgba(148,163,184,0.4);
            padding: 1.5rem 1.4rem;
        }

        @media (min-width: 992px) {
            .about-card {
                padding: 1.75rem 1.8rem;
            }
        }

        .about-highlight {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            font-size: .78rem;
            text-transform: uppercase;
            letter-spacing: .12em;
            color: #6b7280;
        }

        .about-highlight span {
            width: 7px;
            height: 7px;
            border-radius: 999px;
            background: linear-gradient(120deg, var(--jdm-blue), var(--jdm-red));
        }

        .about-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-top: .5rem;
            margin-bottom: .3rem;
        }

        .about-text {
            font-size: .9rem;
            color: #4b5563;
        }

        .about-kpis {
            margin-top: 1rem;
            display: grid;
            grid-template-columns: repeat(2,minmax(0,1fr));
            gap: .6rem;
        }

        @media (min-width: 768px) {
            .about-kpis {
                grid-template-columns: repeat(4,minmax(0,1fr));
            }
        }

        .about-kpi-card {
            border-radius: 14px;
            border: 1px dashed rgba(148,163,184,0.7);
            padding: .5rem .6rem;
            background: #f9fafb;
            font-size: .8rem;
        }

        .about-kpi-value {
            display: block;
            font-weight: 600;
            font-size: .95rem;
            color: #111827;
        }

        .about-kpi-label {
            font-size: .75rem;
            color: #6b7280;
        }

        /* Category cards */
        .category-card {
            border-radius: 18px;
            background: #ffffff;
            border: 1px solid rgba(148,163,184,0.4);
            padding: 1.1rem 1rem;
            height: 100%;
            display: flex;
            flex-direction: column;
            gap: .4rem;
            font-size: .9rem;
        }

        .category-icon {
            width: 36px;
            height: 36px;
            border-radius: 999px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: radial-gradient(circle at 30% 30%, #e5f0ff, #dbeafe);
            margin-bottom: .2rem;
        }

        .category-title {
            font-weight: 600;
        }

        .category-list {
            font-size: .8rem;
            color: #6b7280;
        }

        /* Why work with us */
        .feature-card {
            border-radius: 18px;
            background: #f9fafb;
            border: 1px solid rgba(209,213,219,0.8);
            padding: 1rem .95rem;
            height: 100%;
            font-size: .9rem;
        }

        .feature-badge {
            font-size: .75rem;
            padding: .1rem .6rem;
            border-radius: 999px;
            background: #eff6ff;
            color: #1d4ed8;
        }

        /* Photo gallery / strip */
        .gallery-strip {
            display: flex;
            gap: .75rem;
            overflow-x: auto;
            padding-bottom: .5rem;
        }
        .gallery-strip::-webkit-scrollbar {
            height: 4px;
        }
        .gallery-strip::-webkit-scrollbar-thumb {
            background: rgba(148,163,184,0.7);
            border-radius: 999px;
        }

        .gallery-card {
            min-width: 220px;
            max-width: 260px;
            border-radius: 18px;
            overflow: hidden;
            background: #000;
            color: #fff;
            position: relative;
            box-shadow: 0 10px 26px rgba(15,23,42,0.4);
        }

        .gallery-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            opacity: .9;
        }

        .gallery-caption {
            position: absolute;
            inset: auto 0 0 0;
            padding: .55rem .7rem .65rem;
            background: linear-gradient(to top, rgba(0,0,0,0.78), transparent);
            font-size: .78rem;
        }

        /* Timeline */
        .timeline {
            list-style: none;
            padding-left: 0;
            margin-bottom: 0;
        }

        .timeline-item {
            position: relative;
            padding-left: 1.6rem;
            padding-bottom: .7rem;
            font-size: .85rem;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: .5rem;
            top: .2rem;
            width: 9px;
            height: 9px;
            border-radius: 999px;
            background: linear-gradient(120deg, var(--jdm-blue), var(--jdm-red));
        }

        .timeline-item::after {
            content: '';
            position: absolute;
            left: .87rem;
            top: .9rem;
            width: 1px;
            bottom: -.1rem;
            background: rgba(209,213,219,0.9);
        }

        .timeline-item:last-child::after {
            display: none;
        }

        /* Footer */
        footer {
            font-size: .78rem;
            color: #6b7280;
        }

        /* Small tweaks */
        .section-heading {
            font-size: 1.05rem;
            font-weight: 600;
        }

        .section-subtitle {
            font-size: .85rem;
            color: #6b7280;
        }

        @media (max-width: 575.98px) {
            .jdm-main-card {
                margin-top: 1rem;
                margin-bottom: 1.5rem;
                padding: 1rem .75rem 1.3rem;
            }

            .jdm-hero-heading {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>

<div class="min-vh-100 d-flex flex-column">

    {{-- Top nav --}}
    <nav class="navbar navbar-expand-md jdm-navbar">
        <div class="container-fluid px-3 px-md-4">
            <a href="#" class="navbar-brand jdm-navbar-brand">
                <img src="{{ asset('assets/jdm_distributors_logo.jpeg') }}"
                     alt="JDM Distributors"
                     style="width:32px;height:32px;border-radius:999px;object-fit:contain;">
                <span>JDM Distributors</span>
            </a>

            <button class="navbar-toggler" type="button"
                    data-bs-toggle="collapse" data-bs-target="#topNav"
                    aria-controls="topNav" aria-expanded="false"
                    aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse mt-2 mt-md-0" id="topNav">
                <ul class="navbar-nav ms-auto small">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#products">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#gallery">Photos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#enquiry">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- Hero --}}
    <header class="container-fluid px-0">
        <div class="jdm-hero-strip mt-0">
            <div class="jdm-hero-overlay"></div>
            <div class="jdm-hero-content container">
                <div class="row g-4 align-items-center">
                    <div class="col-lg-7">
                        <div class="jdm-hero-pill mb-2">
                            <i class="fa fa-star"></i>
                            Wholesale American & International Groceries
                        </div>
                        <div class="jdm-hero-title">
                            JDM DISTRIBUTORS – WHOLESALE PORTAL
                        </div>
                        <h1 class="jdm-hero-heading mb-1">
                            Discover Our Trade-Only Product Catalogue
                        </h1>
                        <p class="jdm-hero-sub mb-3 mb-md-4">
                            Browse our range of imported groceries, confectionery and beverages.
                            View product details, then request pricing and availability from the JDM team.
                        </p>
                    </div>

                    <div class="col-lg-5">
                        <div class="jdm-hero-kpis">
                            <div class="jdm-hero-kpi">
                                <div class="jdm-hero-kpi-label">Import Experience</div>
                                <div class="jdm-hero-kpi-value">20+ Years</div>
                            </div>
                            <div class="jdm-hero-kpi">
                                <div class="jdm-hero-kpi-label">Core Focus</div>
                                <div class="jdm-hero-kpi-value">American FMCG</div>
                            </div>
                            <div class="jdm-hero-kpi">
                                <div class="jdm-hero-kpi-label">Ordering</div>
                                <div class="jdm-hero-kpi-value">Flexible & Fast</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- Main content --}}
    <main class="flex-grow-1 py-3 py-md-4">
        <div class="container">

            {{-- PRODUCTS MAIN CARD --}}
            <div class="jdm-main-card" id="products">
                <div class="row g-3 g-md-4 align-items-center mb-3 mb-md-4">
                    <div class="col-md-6">
                        <h2 class="section-heading mb-1">Shop Products</h2>
                        <p class="section-subtitle mb-0">
                            Explore our current lines of imported confectionery, drinks and grocery items.
                            Prices are hidden – request a trade quote straight from this page.
                        </p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <div class="jdm-search-wrap mb-2 mb-md-0">
                            <input type="text" class="form-control jdm-search-input"
                                   placeholder="Search by product name, brand or category…"
                                   id="searchInput">
                            <i class="fa fa-search"></i>
                        </div>
                    </div>
                </div>

                {{-- Filters --}}
                <div class="mb-3 mb-md-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="small text-muted">Browse by category</span>
                        <span class="badge rounded-pill text-bg-light small d-none d-sm-inline">
                            Trade-only catalogue
                        </span>
                    </div>
                    <div class="jdm-filters">
                        <button class="jdm-filter-pill active" data-filter="all">All Products</button>
                        <button class="jdm-filter-pill" data-filter="confectionery">Confectionery</button>
                        <button class="jdm-filter-pill" data-filter="chocolate">Chocolate Bars</button>
                        <button class="jdm-filter-pill" data-filter="snacks">Snacks & Crisps</button>
                        <button class="jdm-filter-pill" data-filter="drinks">Soft Drinks</button>
                        <button class="jdm-filter-pill" data-filter="seasonal">Seasonal Picks</button>
                        <button class="jdm-filter-pill" data-filter="new">New Arrivals</button>
                    </div>
                </div>

                {{-- Product grid – in real app, loop your products here --}}
                <div class="row g-3 g-md-4" id="productGrid">

                    {{-- SAMPLE PRODUCTS (replace with @foreach) --}}
                    <div class="col-6 col-md-4 col-lg-3 product-item" data-category="chocolate confectionery new">
                        <div class="product-card">
                            <div class="product-image-wrap">
                                <img src="{{ asset('assets/sample_products/balance_dark_70.png') }}"
                                     alt="Balance Dark Chocolate 70%">
                            </div>
                            <div class="product-meta">
                                Balance · Tablet 100g
                            </div>
                            <div class="product-title">
                                Balance Dark Chocolate 70% Cocoa Tablet 100g
                            </div>
                            <div class="product-tags">
                                <span class="product-tag">No Added Sugar</span>
                                <span class="product-tag">Belgian Chocolate</span>
                            </div>
                            <div class="product-card-footer">
                                <button class="btn btn-sm btn-dark btn-view-details"
                                        data-bs-toggle="modal"
                                        data-bs-target="#productModal"
                                        data-product="Balance Dark Chocolate 70% Cocoa Tablet 100g"
                                        data-brand="Balance"
                                        data-category="Chocolate · Confectionery"
                                        data-notes="Rich dark chocolate with 70% cocoa solids – ideal for premium gifting and better-for-you ranges.">
                                    View details
                                </button>
                                <span class="badge-new">New</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-md-4 col-lg-3 product-item" data-category="chocolate confectionery">
                        <div class="product-card">
                            <div class="product-image-wrap">
                                <img src="{{ asset('assets/sample_products/balance_milk.png') }}"
                                     alt="Balance Milk Chocolate Tablet">
                            </div>
                            <div class="product-meta">
                                Balance · Tablet 100g
                            </div>
                            <div class="product-title">
                                Balance Milk Chocolate Tablet 100g
                            </div>
                            <div class="product-tags">
                                <span class="product-tag">Everyday</span>
                                <span class="product-tag">Shelf Ready</span>
                            </div>
                            <div class="product-card-footer">
                                <button class="btn btn-sm btn-dark btn-view-details"
                                        data-bs-toggle="modal"
                                        data-bs-target="#productModal"
                                        data-product="Balance Milk Chocolate Tablet 100g"
                                        data-brand="Balance"
                                        data-category="Chocolate · Confectionery"
                                        data-notes="Smooth milk chocolate tablet suitable for grocery fixtures, kiosks and impulse racks.">
                                    View details
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-md-4 col-lg-3 product-item" data-category="snacks confectionery seasonal">
                        <div class="product-card">
                            <div class="product-image-wrap">
                                <img src="{{ asset('assets/sample_products/butterfinger.png') }}"
                                     alt="Butterfinger Single Bar">
                            </div>
                            <div class="product-meta">
                                Butterfinger · Bar 36 x
                            </div>
                            <div class="product-title">
                                Butterfinger Chocolate Bar Single 36-Count Case
                            </div>
                            <div class="product-tags">
                                <span class="product-tag">American Icon</span>
                            </div>
                            <div class="product-card-footer">
                                <button class="btn btn-sm btn-dark btn-view-details"
                                        data-bs-toggle="modal"
                                        data-bs-target="#productModal"
                                        data-product="Butterfinger Chocolate Bar Single Case"
                                        data-brand="Butterfinger"
                                        data-category="Snacks · Confectionery"
                                        data-notes="Classic American peanut-butter crisp bar – strong performer for US candy bays and seasonal features.">
                                    View details
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-md-4 col-lg-3 product-item" data-category="drinks">
                        <div class="product-card">
                            <div class="product-image-wrap">
                                <img src="{{ asset('assets/sample_products/drink_can_placeholder.png') }}"
                                     alt="Imported Soda">
                            </div>
                            <div class="product-meta">
                                Imported Soda · 12 x 355ml
                            </div>
                            <div class="product-title">
                                Assorted American Soda Flavours 12 x 355ml
                            </div>
                            <div class="product-tags">
                                <span class="product-tag">Chilled & Ambient</span>
                                <span class="product-tag">US Favourites</span>
                            </div>
                            <div class="product-card-footer">
                                <button class="btn btn-sm btn-dark btn-view-details"
                                        data-bs-toggle="modal"
                                        data-bs-target="#productModal"
                                        data-product="Assorted American Soda 12 x 355ml"
                                        data-brand="Various"
                                        data-category="Drinks · Soft Drinks"
                                        data-notes="Core American soda flavours – ideal for chillers, meal deals and American themed promotions.">
                                    View details
                                </button>
                            </div>
                        </div>
                    </div>
                    {{-- /SAMPLE PRODUCTS --}}
                </div>
            </div>

            {{-- PRODUCT CATEGORIES OVERVIEW --}}
            <section class="mt-3 mt-md-4">
                <div class="row g-3 g-md-4">
                    <div class="col-lg-4">
                        <div class="about-card h-100">
                            <div class="about-highlight">
                                <span></span>
                                PRODUCT RANGE
                            </div>
                            <h2 class="about-title">
                                A Range Built For UK Retailers
                            </h2>
                            <p class="about-text mb-2">
                                Our catalogue focuses on lines that work in UK convenience, supermarket and
                                specialist formats – from single chocolate bars and sharing bags to full case
                                formats for wholesalers and cash & carry.
                            </p>
                            <p class="about-text mb-0">
                                We monitor performance across our customer base and focus on bringing in proven
                                sellers alongside the most-requested new launches from the US and beyond.
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="row g-3 g-md-4">
                            <div class="col-sm-6">
                                <div class="category-card">
                                    <div class="category-icon">
                                        <i class="fa fa-cube"></i>
                                    </div>
                                    <div class="category-title">Confectionery & Chocolate</div>
                                    <div class="category-list">
                                        Iconic US candy bars, gifting blocks, pouches, gums and seasonal treats
                                        from leading global brands.
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="category-card">
                                    <div class="category-icon">
                                        <i class="fa fa-glass"></i>
                                    </div>
                                    <div class="category-title">Soft Drinks & Beverages</div>
                                    <div class="category-list">
                                        Imported sodas, juices and specialist drinks – perfect for chillers,
                                        meal deals and feature ends.
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="category-card">
                                    <div class="category-icon">
                                        <i class="fa fa-cutlery"></i>
                                    </div>
                                    <div class="category-title">Ambient Groceries</div>
                                    <div class="category-list">
                                        Sauces, breakfast items, baking lines and cupboard favourites that
                                        bring authentic US flavours to UK homes.
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="category-card">
                                    <div class="category-icon">
                                        <i class="fa fa-gift"></i>
                                    </div>
                                    <div class="category-title">Seasonal & Limited Editions</div>
                                    <div class="category-list">
                                        Halloween, Christmas, Valentine’s and summer specials curated to drive
                                        excitement and incremental spend.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- ABOUT / COMPANY STORY --}}
            <section class="mt-4 mt-md-5" id="about">
                <div class="about-card">
                    <div class="row g-3 g-lg-4 align-items-start">
                        <div class="col-lg-7">
                            <div class="about-highlight">
                                <span></span>
                                ABOUT JDM DISTRIBUTORS
                            </div>
                            <h2 class="about-title">
                                Importers & Distributors Of American And International Groceries
                            </h2>
                            <p class="about-text mb-2">
                                JDM Distributors specialise in sourcing iconic American food and drink brands
                                and supplying them to retailers across the UK. Over the last two decades we’ve
                                built one of the most comprehensive catalogues of American groceries, confectionery
                                and soft drinks available to the trade.
                            </p>
                            <p class="about-text mb-2">
                                Based in Watford, our team focuses on quick, reliable delivery and flexible order
                                quantities – helping independents, symbol groups and regional chains keep shelves
                                stocked with products customers recognise from the US and beyond.
                            </p>
                            <p class="about-text mb-0">
                                We continually review our range, delisting slower lines and replacing them with
                                high-demand products, ensuring our partners get a fresh, relevant offer that works
                                in real UK stores.
                            </p>
                        </div>
                        <div class="col-lg-5">
                            <div class="about-kpis">
                                <div class="about-kpi-card">
                                    <span class="about-kpi-value">20+ Years</span>
                                    <span class="about-kpi-label">Experience importing US & international FMCG</span>
                                </div>
                                <div class="about-kpi-card">
                                    <span class="about-kpi-value">Watford, UK</span>
                                    <span class="about-kpi-label">Warehouse & office location</span>
                                </div>
                                <div class="about-kpi-card">
                                    <span class="about-kpi-value">Curated Range</span>
                                    <span class="about-kpi-label">From classic US candy to premium grocery</span>
                                </div>
                                <div class="about-kpi-card">
                                    <span class="about-kpi-value">Trade-Only</span>
                                    <span class="about-kpi-label">Focused on retailers, wholesalers & foodservice</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- WHY WORK WITH JDM + ORDER FLOW --}}
            <section class="mt-4 mt-md-5">
                <div class="row g-3 g-md-4">
                    <div class="col-lg-6">
                        <div class="about-card h-100">
                            <div class="about-highlight">
                                <span></span>
                                WHY RETAILERS WORK WITH US
                            </div>
                            <h2 class="about-title">
                                Service Built Around Real Stores
                            </h2>
                            <div class="row g-3">
                                <div class="col-12">
                                    <div class="feature-card">
                                        <span class="feature-badge mb-1">Range & Availability</span>
                                        <p class="mb-1 mt-2">
                                            We focus on depth in core lines rather than listing everything.
                                            That means better stock cover and fewer gaps on shelf.
                                        </p>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="feature-card">
                                        <span class="feature-badge mb-1">Flexible Ordering</span>
                                        <p class="mb-1 mt-2">
                                            From full pallets to mixed cases, we work with you to build an
                                            order that matches your store size and sales pattern.
                                        </p>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="feature-card">
                                        <span class="feature-badge mb-1">Logistics & Support</span>
                                        <p class="mb-1 mt-2">
                                            Our team can advise on core American fixtures, seasonal plans and
                                            bestsellers, helping you turn imported ranges into repeat sales.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="about-card h-100">
                            <div class="about-highlight">
                                <span></span>
                                HOW ORDERING WORKS
                            </div>
                            <h2 class="about-title">
                                Simple Steps From Enquiry To Delivery
                            </h2>
                            <ul class="timeline">
                                <li class="timeline-item">
                                    Send us a quick enquiry with your business details and the product
                                    categories you’re interested in.
                                </li>
                                <li class="timeline-item">
                                    We share our latest product list and indicative lead times, along with
                                    any new or seasonal lines we think will fit your store.
                                </li>
                                <li class="timeline-item">
                                    You confirm quantities and delivery details; our team books stock and
                                    finalises the order with you.
                                </li>
                                <li class="timeline-item">
                                    Your order is picked, checked and dispatched from our Watford warehouse,
                                    ready to go straight to shelf.
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

            {{-- PHOTO HIGHLIGHTS / GALLERY --}}
            <section class="mt-4 mt-md-5" id="gallery">
                <div class="about-card">
                    <div class="about-highlight">
                        <span></span>
                        PHOTO HIGHLIGHTS
                    </div>
                    <h2 class="about-title">
                        A Glimpse Inside JDM Distributors
                    </h2>
                    <p class="about-text mb-3">
                        Show your customers what you stock – from colourful candy bays and American soda
                        chillers to pallets of new arrivals ready to go out to stores. Use these images
                        across your own marketing once you’re working with us.
                    </p>

                    <div class="gallery-strip">
                        {{-- Replace image paths with your own warehouse / product photos --}}
                        <div class="gallery-card">
                            <img src="{{ asset('assets/gallery/warehouse_aisle.jpg') }}"
                                 alt="JDM warehouse aisle">
                            <div class="gallery-caption">
                                Warehouse aisles stocked with American confectionery and grocery cases.
                            </div>
                        </div>
                        <div class="gallery-card">
                            <img src="{{ asset('assets/gallery/chilled_drinks.jpg') }}"
                                 alt="Chilled American drinks">
                            <div class="gallery-caption">
                                Chilled American drinks display – ready-made inspiration for in-store chillers.
                            </div>
                        </div>
                        <div class="gallery-card">
                            <img src="{{ asset('assets/gallery/usa_sweets_bay.jpg') }}"
                                 alt="American sweets bay">
                            <div class="gallery-caption">
                                American sweets bay featuring top-selling US chocolate bars and candy.
                            </div>
                        </div>
                        <div class="gallery-card">
                            <img src="{{ asset('assets/gallery/pallets_new_stock.jpg') }}"
                                 alt="New stock pallets">
                            <div class="gallery-caption">
                                Pallets of incoming stock arriving at our Watford site, ready for distribution.
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- CONTACT / ENQUIRY --}}
            <section class="mt-4 mt-md-5" id="enquiry">
                <div class="row g-3 g-md-4">
                    <div class="col-lg-6">
                        <div class="about-card h-100">
                            <div class="about-highlight">
                                <span></span>
                                TRADE ENQUIRIES
                            </div>
                            <h2 class="about-title">
                                Request Pricing Or A Product List
                            </h2>
                            <p class="about-text mb-3">
                                Ready to place an order or need our latest wholesale price list?
                                Send us a quick message and the JDM team will come back to you with
                                current pricing, case sizes and availability.
                            </p>

                            <form method="POST" action="{{ route('trade.enquiry.store') }}">
                                @csrf

                                <div class="mb-2">
                                    <label class="form-label small">Business name</label>
                                    <input type="text" name="business_name"
                                           class="form-control form-control-sm @error('business_name') is-invalid @enderror"
                                           value="{{ old('business_name') }}"
                                           placeholder="Shop / company name">
                                    @error('business_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-2">
                                    <label class="form-label small">Contact email</label>
                                    <input type="email" name="email"
                                           class="form-control form-control-sm @error('email') is-invalid @enderror"
                                           value="{{ old('email') }}"
                                           placeholder="you@example.com">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-2">
                                    <label class="form-label small">Phone number</label>
                                    <input type="text" name="phone"
                                           class="form-control form-control-sm @error('phone') is-invalid @enderror"
                                           value="{{ old('phone') }}"
                                           placeholder="+44…">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-2">
                                    <label class="form-label small">What are you looking for?</label>
                                    <textarea name="message"
                                              class="form-control form-control-sm @error('message') is-invalid @enderror"
                                              rows="3"
                                              placeholder="Tell us which brands, categories or products you’re interested in.">{{ old('message') }}</textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-dark btn-sm mt-1">
                                    Submit enquiry
                                </button>

                                @if(session('success'))
                                    <div class="alert alert-success mt-2 mb-0 py-1 px-2 small">
                                        {{ session('success') }}
                                    </div>
                                @endif
                            </form>

                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="about-card h-100">
                            <div class="about-highlight">
                                <span></span>
                                CONTACT & DETAILS
                            </div>
                            <h2 class="about-title">
                                JDM Distributors Ltd – Contact
                            </h2>
                            <p class="about-text small mb-2">
                                For general trade enquiries, memberships and logistics questions, please use the form
                                or the contact details below.
                            </p>

                            <ul class="list-unstyled small mb-3">
                                <li class="mb-1">
                                    <i class="fa fa-envelope me-2 text-muted"></i>
                                    info@jdmdistributors.co.uk
                                </li>
                                <li class="mb-1">
                                    <i class="fa fa-phone me-2 text-muted"></i>
                                    Tel 1: +44 7525 345520
                                </li>
                                <li class="mb-1">
                                    <i class="fa fa-phone me-2 text-muted"></i>
                                    Tel 2: 0129 659 6050
                                </li>
                                <li class="mt-2">
                                    <i class="fa fa-map-marker me-2 text-muted"></i>
                                    11 Greenhill Crescent, Watford, WD18 8QU
                                </li>
                            </ul>

                            <p class="about-text small mb-0">
                                Once your trade account is set up, you’ll be able to browse our full wholesale catalogue,
                                view product details online and request stock for upcoming promotions, seasonal events and
                                core ranges.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </main>

    {{-- Product details modal --}}
    <div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalTitle">Product details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-1 small text-muted" id="productModalBrand"></p>
                    <p class="mb-2 small text-muted" id="productModalCategory"></p>
                    <p class="mb-0" id="productModalNotes"></p>
                </div>
                <div class="modal-footer justify-content-between">
                    <span class="small text-muted">
                        Prices are available to approved trade customers. Use the enquiry form for quotes.
                    </span>
                    <button type="button" class="btn btn-dark btn-sm"
                            data-bs-dismiss="modal"
                            onclick="document.getElementById('enquiry').scrollIntoView({behavior:'smooth'})">
                        Request price &amp; stock
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <footer class="py-3 mt-3 mt-md-4 bg-white border-top">
        <div class="container d-flex flex-column flex-md-row align-items-center justify-content-between gap-2">
            <span>&copy; {{ date('Y') }} JDM Distributors Ltd. All rights reserved.</span>
            <span class="small">
                Distributors &amp; importers of American groceries, confectionery and beverages for UK retailers.
            </span>
        </div>
    </footer>

</div>

{{-- Scripts --}}
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Filter chips
    const filterButtons = document.querySelectorAll('.jdm-filter-pill');
    const productItems   = document.querySelectorAll('.product-item');

    filterButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            filterButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');

            const filter = btn.getAttribute('data-filter');
            productItems.forEach(item => {
                const cat = item.getAttribute('data-category') || '';
                if (filter === 'all' || cat.includes(filter)) {
                    item.classList.remove('d-none');
                } else {
                    item.classList.add('d-none');
                }
            });
        });
    });

    // Simple search
    document.getElementById('searchInput').addEventListener('input', function () {
        const term = this.value.toLowerCase();
        productItems.forEach(item => {
            const text = item.innerText.toLowerCase();
            item.classList.toggle('d-none', !text.includes(term));
        });
    });

    // Populate product modal
    const productModal = document.getElementById('productModal');
    if (productModal) {
        productModal.addEventListener('show.bs.modal', function (event) {
            const button  = event.relatedTarget;
            const name    = button.getAttribute('data-product') || '';
            const brand   = button.getAttribute('data-brand') || '';
            const cat     = button.getAttribute('data-category') || '';
            const notes   = button.getAttribute('data-notes') || '';

            document.getElementById('productModalTitle').innerText   = name;
            document.getElementById('productModalBrand').innerText   = brand ? 'Brand: ' + brand : '';
            document.getElementById('productModalCategory').innerText = cat;
            document.getElementById('productModalNotes').innerText   = notes;
        });
    }
</script>

</body>
</html>
