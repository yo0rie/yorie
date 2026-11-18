import React, { useEffect, useRef, useState } from 'react';
import { createRoot } from 'react-dom/client';
import { AnimatePresence, MotionConfig, motion, useReducedMotion } from 'framer-motion';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

const money = (value) => new Intl.NumberFormat('id-ID').format(value);

const categoryIcons = {
    'Pakaian': '✿',
    'Alat Tulis': '✎',
    'Aksesoris': '✧',
    'Makanan': '♡',
};

function SearchIcon() {
    return <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.8" /><path d="m16 16 4.5 4.5" /></svg>;
}

function BagIcon() {
    return <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 8h14l1.2 12H3.8L5 8Z" /><path d="M9 9V6a3 3 0 0 1 6 0v3" /></svg>;
}

function ProductIllustration({ categoryId, name, large = false }) {
    return (
        <div className={`product-art art-${categoryId}${large ? ' product-art-large' : ''}`} aria-label={`Ilustrasi ${name}`}>
            <span className="art-sun" />
            <span className="art-shape">{categoryIcons[name] || '✿'}</span>
            <span className="art-sparkle sparkle-one">✦</span>
            <span className="art-sparkle sparkle-two">✧</span>
            <span className="art-ground" />
        </div>
    );
}

function ProductCard({ product, index = 0 }) {
    const reducedMotion = useReducedMotion();

    return (
        <motion.article
            className="product-card"
            initial={reducedMotion ? false : { opacity: 0, y: 22 }}
            whileInView={reducedMotion ? undefined : { opacity: 1, y: 0 }}
            viewport={{ once: true, amount: 0.15 }}
            transition={{ duration: 0.5, delay: Math.min(index % 4, 3) * 0.07, ease: [0.22, 1, 0.36, 1] }}
            whileHover={reducedMotion ? undefined : { y: -7 }}
        >
            <a className="product-image" href={product.url} aria-label={`Lihat ${product.name}`}>
                {product.image ? (
                    <img className="catalog-product-photo" src={product.image} alt={product.name} loading="lazy" />
                ) : (
                    <ProductIllustration categoryId={product.categoryId} name={product.category} />
                )}
                {product.stock <= 3 && product.stock > 0 && <span className="stock-badge">Hampir habis</span>}
                {product.stock < 1 && <span className="stock-badge stock-sold">Habis</span>}
                <span className="quick-view">Lihat detail <span>↗</span></span>
            </a>
            <div className="product-info">
                <span className="product-category">{product.category}</span>
                <h3><a href={product.url}>{product.name}</a></h3>
                <div className="product-bottom">
                    <strong>Rp{money(product.price)}</strong>
                    <a className="add-button" href={product.url} aria-label={`Lihat ${product.name}`}>↗</a>
                </div>
            </div>
        </motion.article>
    );
}

function Header({ page }) {
    const [search, setSearch] = useState(window.storefrontPage.search || '');
    const user = window.storefrontPage.user;

    return (
        <>
            <div className="announcement">
                <span className="announcement-star">✳</span>
                Belanja lebih ceria — gratis ongkir untuk transaksi minimal Rp500.000
                <span className="announcement-star">✳</span>
            </div>
            <header className="site-header">
                <div className="container header-inner">
                    <a className="brand" href={window.storefrontPage.homeUrl} aria-label="Jeli Store, beranda">
                        <span className="brand-mark"><img src="/images/logo-photo.jfif" alt="" /></span>
                        <span className="brand-name">jeli<span>store</span></span>
                    </a>
                    <form className="search" action={window.storefrontPage.homeUrl} method="get" role="search">
                        <SearchIcon />
                        {window.storefrontPage.selectedCategory && <input type="hidden" name="kategori" value={window.storefrontPage.selectedCategory} />}
                        <input
                            type="search"
                            name="q"
                            value={search}
                            onChange={(event) => setSearch(event.target.value)}
                            placeholder="Cari barang favoritmu..."
                            aria-label="Cari produk"
                        />
                        <button type="submit">Cari</button>
                    </form>
                    <nav className="header-actions" aria-label="Navigasi utama">
                        <a className={`nav-link${page === 'catalog' ? ' nav-link-active' : ''}`} href={window.storefrontPage.homeUrl}>Beranda</a>
                        <a className="nav-link nav-collection" href={`${window.storefrontPage.homeUrl}#koleksi`}>Koleksi</a>
                        {user?.role === 'admin' ? (
                            <a className="nav-link nav-dashboard" href={window.storefrontPage.dashboardUrl}>Dashboard</a>
                        ) : user ? (
                            <span className="nav-user">Hai, {user.name}</span>
                        ) : (
                            <a className="nav-link nav-login" href={window.storefrontPage.loginUrl}>Masuk</a>
                        )}
                        {user && (
                            <form className="header-logout-form" action={window.storefrontPage.logoutUrl} method="post">
                                <input type="hidden" name="_token" value={window.storefrontPage.csrfToken} />
                                <button className="header-logout" type="submit">Keluar</button>
                            </form>
                        )}
                        <button className="cart-button" type="button" aria-label="Keranjang belanja">
                            <BagIcon /><span className="cart-dot" />
                        </button>
                    </nav>
                </div>
            </header>
        </>
    );
}

function Hero() {
    const reducedMotion = useReducedMotion();

    return (
        <section className="hero">
            <div className="hero-orb hero-orb-one" />
            <div className="hero-orb hero-orb-two" />
            <div className="container hero-inner">
                <motion.div
                    className="hero-copy"
                    initial={reducedMotion ? false : { opacity: 0, x: -24 }}
                    animate={{ opacity: 1, x: 0 }}
                    transition={{ duration: 0.7, ease: [0.22, 1, 0.36, 1] }}
                >
                    <span className="eyebrow"><span className="eyebrow-dot" /> Koperasi sekolah pilihanmu</span>
                    <h1>Hari baru,<br />semangat <em>baru.</em></h1>
                    <p>Temukan teman terbaik untuk belajar, berkarya, dan bikin hari-harimu jadi lebih berwarna.</p>
                    <a className="primary-button" href="#koleksi">Jelajahi koleksi <span>↗</span></a>
                    <div className="hero-note"><span className="note-avatars"><i>J</i><i>♡</i><i>✿</i></span><span>Teman baik untuk hari-hari hebatmu</span></div>
                </motion.div>
                <motion.div
                    className="hero-art"
                    initial={reducedMotion ? false : { opacity: 0, scale: 0.92, y: 16 }}
                    animate={{ opacity: 1, scale: 1, y: 0 }}
                    transition={{ duration: 0.8, delay: 0.12, ease: [0.22, 1, 0.36, 1] }}
                >
                    <div className="hero-art-circle" />
                    <div className="hero-flower flower-a">✿</div>
                    <div className="hero-flower flower-b">✳</div>
                    <div className="hero-product hero-product-main">
                        <img className="hero-photo" src="/images/hero-cookie.jfif" alt="Foto pilihan untuk Jeli Store" fetchPriority="high" />
                    </div>
                    <motion.div className="floating-note note-top" animate={reducedMotion ? undefined : { y: [0, -7, 0], rotate: [4, 7, 4] }} transition={{ duration: 4, repeat: Infinity, ease: 'easeInOut' }}>✦ cute things</motion.div>
                    <motion.div className="floating-note note-bottom" animate={reducedMotion ? undefined : { y: [0, 7, 0], rotate: [-5, -2, -5] }} transition={{ duration: 4.6, repeat: Infinity, ease: 'easeInOut' }}>made for you ♡</motion.div>
                    <div className="hero-sticker"><span>good<br />mood</span><i>☺</i></div>
                </motion.div>
            </div>
        </section>
    );
}

function CategorySection({ categories, selectedCategory }) {
    const links = [{ name: 'Semua', slug: '', icon: '✳', count: categories.reduce((sum, item) => sum + item.count, 0) }, ...categories.map((category) => ({ ...category, icon: categoryIcons[category.name] || '✿' }))];

    return (
        <section className="category-section container" id="koleksi">
            <div className="section-heading gsap-reveal">
                <div><span className="eyebrow">Pilih yang kamu suka</span><h2>Belanja by <em>kategori</em></h2></div>
                <span className="section-aside">Hal kecil, bahagia besar <span>♡</span></span>
            </div>
            <div className="category-grid">
                {links.map((category, index) => {
                    const active = selectedCategory === category.slug;
                    const href = category.slug ? `${window.storefrontPage.homeUrl}?kategori=${encodeURIComponent(category.slug)}` : window.storefrontPage.homeUrl;
                    return (
                        <motion.a
                            key={category.slug || 'all'}
                            className={`category-card${active ? ' category-active' : ''}`}
                            href={href}
                            initial={{ opacity: 0, y: 14 }}
                            whileInView={{ opacity: 1, y: 0 }}
                            viewport={{ once: true }}
                            transition={{ duration: 0.4, delay: index * 0.06 }}
                            whileHover={{ y: -4 }}
                        >
                            <span className="category-icon">{category.icon}</span>
                            <span className="category-label"><strong>{category.name}</strong><small>{category.count} produk</small></span>
                            <span className="category-arrow">↗</span>
                        </motion.a>
                    );
                })}
            </div>
        </section>
    );
}

function Catalog({ data }) {
    const pageNumbers = Array.from({ length: data.pagination.last }, (_, index) => index + 1);
    const queryForPage = (page) => {
        const params = new URLSearchParams(window.location.search);
        if (page === 1) params.delete('page');
        else params.set('page', page);
        const query = params.toString();
        return `${data.homeUrl}${query ? `?${query}` : ''}`;
    };

    return (
        <>
            <Hero />
            <CategorySection categories={data.categories} selectedCategory={data.selectedCategory} />
            <section className="product-section">
                <div className="container">
                    <div className="section-heading product-heading gsap-reveal">
                        <div>
                            <span className="eyebrow">Disiapkan dengan cinta</span>
                            <h2>{data.selectedCategory ? 'Pilihan ' + (data.categories.find((item) => item.slug === data.selectedCategory)?.name || '') : 'Favorit minggu ini'}<span className="heading-heart">♡</span></h2>
                        </div>
                        <span className="results-count">{data.products.length} produk ditemukan</span>
                    </div>
                    {data.products.length ? (
                        <div className="product-grid">
                            <AnimatePresence mode="popLayout">
                                {data.products.map((product, index) => <ProductCard key={product.slug} product={product} index={index} />)}
                            </AnimatePresence>
                        </div>
                    ) : (
                        <div className="empty-state"><span>⌕</span><h3>Belum ketemu, nih!</h3><p>Coba kata kunci atau kategori yang berbeda, yuk.</p></div>
                    )}
                    {data.pagination.last > 1 && (
                        <nav className="pagination" aria-label="Halaman produk">
                            <a className="page-arrow" href={data.pagination.current > 1 ? queryForPage(data.pagination.current - 1) : undefined} aria-disabled={data.pagination.current <= 1}>←</a>
                            {pageNumbers.map((page) => <a key={page} className={`page-number${page === data.pagination.current ? ' page-current' : ''}`} href={queryForPage(page)} aria-current={page === data.pagination.current ? 'page' : undefined}>{page}</a>)}
                            <a className="page-arrow" href={data.pagination.current < data.pagination.last ? queryForPage(data.pagination.current + 1) : undefined} aria-disabled={data.pagination.current >= data.pagination.last}>→</a>
                        </nav>
                    )}
                </div>
            </section>
            <section className="newsletter">
                <div className="container newsletter-inner gsap-reveal">
                    <div className="newsletter-flower">✿</div>
                    <div><span className="eyebrow">Sedikit kabar baik</span><h2>Hari ceria dimulai<br />dari hal kecil.</h2><p>Temukan barang yang bikin rutinitas sekolahmu terasa lebih menyenangkan.</p></div>
                    <a className="newsletter-link" href="#koleksi">Cari favoritmu <span>↗</span></a>
                    <span className="newsletter-sparkle">✧</span>
                </div>
            </section>
        </>
    );
}

function ProductDetail({ product, relatedProducts }) {
    return (
        <>
            <section className="detail-section">
                <div className="container">
                    <nav className="breadcrumb" aria-label="Breadcrumb"><a href={window.storefrontPage.homeUrl}>Beranda</a><span>♡</span><a href={`${window.storefrontPage.homeUrl}?kategori=${encodeURIComponent(product.categorySlug)}`}>{product.category}</a><span>♡</span><span>{product.name}</span></nav>
                    <div className="detail-grid">
                        <motion.div className="detail-image" initial={{ opacity: 0, scale: 0.96 }} animate={{ opacity: 1, scale: 1 }} transition={{ duration: 0.6 }}>
                            {product.image ? (
                                <img className="detail-photo" src={product.image} alt={product.name} />
                            ) : (
                                <ProductIllustration categoryId={product.categoryId} name={product.category} large />
                            )}
                            <span className="detail-image-note">a little thing to love ♡</span>
                        </motion.div>
                        <motion.div className="detail-copy" initial={{ opacity: 0, x: 20 }} animate={{ opacity: 1, x: 0 }} transition={{ duration: 0.6, delay: 0.1 }}>
                            <span className="eyebrow"><span className="eyebrow-dot" /> {product.category}</span>
                            <h1>{product.name}</h1>
                            <div className="detail-rating"><span>★★★★★</span> pilihan koperasi untukmu</div>
                            <strong className="detail-price">Rp{money(product.price)}</strong>
                            <p>{product.description}</p>
                            <div className={`stock-info${product.stock > 0 ? '' : ' stock-unavailable'}`}><span />{product.stock > 0 ? `${product.stock} tersedia untuk jadi teman belajarmu` : 'Untuk sementara sedang habis'}</div>
                            <button className="primary-button detail-button" type="button" disabled={product.stock < 1}>Tambah ke keranjang <span>♡</span></button>
                            <div className="detail-perks"><span>♡ Dibungkus dengan cinta</span><span>✿ Produk pilihan koperasi</span></div>
                        </motion.div>
                    </div>
                </div>
            </section>
            {relatedProducts.length > 0 && <section className="product-section related-section"><div className="container"><div className="section-heading gsap-reveal"><div><span className="eyebrow">Mungkin kamu juga suka</span><h2>Teman <em>seru</em> lainnya</h2></div></div><div className="product-grid">{relatedProducts.map((item, index) => <ProductCard key={item.slug} product={item} index={index} />)}</div></div></section>}
        </>
    );
}

function Footer() {
    return (
        <footer className="site-footer">
            <div className="container footer-inner">
                <div><a className="brand footer-brand" href={window.storefrontPage.homeUrl}><span className="brand-mark"><img src="/images/logo-photo.jfif" alt="" /></span><span className="brand-name">jeli<span>store</span></span></a><p>Teman kecil untuk hari-hari<br />hebatmu di sekolah.</p></div>
                <div className="footer-note"><span>Made with <i>♡</i> for your happy days</span><small>© {new Date().getFullYear()} Jeli Store · Koperasi Sekolah</small></div>
                <a className="back-top" href="#top" aria-label="Kembali ke atas">↑</a>
            </div>
        </footer>
    );
}

function Storefront() {
    const rootRef = useRef(null);
    const data = window.storefrontPage;

    useEffect(() => {
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return undefined;

        const context = gsap.context(() => {
            gsap.utils.toArray('.gsap-reveal').forEach((element) => {
                gsap.fromTo(element, { y: 24, opacity: 0 }, {
                    y: 0,
                    opacity: 1,
                    duration: 0.75,
                    ease: 'power3.out',
                    scrollTrigger: { trigger: element, start: 'top 88%', once: true },
                });
            });
            const heroCircle = rootRef.current?.querySelector('.hero-art-circle');
            if (heroCircle) {
                gsap.to(heroCircle, {
                    y: -10,
                    rotate: 5,
                    duration: 4,
                    repeat: -1,
                    yoyo: true,
                    ease: 'sine.inOut',
                });
            }
        }, rootRef);

        return () => context.revert();
    }, []);

    return (
        <MotionConfig reducedMotion="user">
            <div ref={rootRef} id="top" className="storefront">
                <Header page={data.page} />
                <main>{data.page === 'product' ? <ProductDetail product={data.product} relatedProducts={data.relatedProducts} /> : <Catalog data={data} />}</main>
                <Footer />
            </div>
        </MotionConfig>
    );
}

const mountNode = document.getElementById('storefront');
if (mountNode) createRoot(mountNode).render(<Storefront />);
