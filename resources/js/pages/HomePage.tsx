import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import api from '@/lib/api';
import type { NewsItem, Announcement } from '@/types';

const HomePage: React.FC = () => {
    const [beritaList, setBeritaList] = useState<NewsItem[]>([]);
    const [pengumumanList, setPengumumanList] = useState<Announcement[]>([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const fetchData = async () => {
            try {
                const [beritaResponse, pengumumanResponse] = await Promise.all([
                    api.get('/api/berita'),
                    api.get('/api/pengumuman')
                ]);

                // Handle response data - ensure we're working with arrays
                const beritaData = Array.isArray(beritaResponse.data) ? beritaResponse.data : [];
                const pengumumanData = Array.isArray(pengumumanResponse.data) ? pengumumanResponse.data : [];

                setBeritaList(beritaData);
                setPengumumanList(pengumumanData);
            } catch (err) {
                console.error('Failed to fetch data:', err);
                setBeritaList([]);
                setPengumumanList([]);
            } finally {
                setLoading(false);
            }
        };

        fetchData();
    }, []);

    const truncateText = (text: string, maxLength: number): string => {
        if (text.length <= maxLength) return text;
        return text.slice(0, maxLength) + '...';
    };

    const stripHtml = (html: string): string => {
        const tmp = document.createElement('div');
        tmp.innerHTML = html;
        return tmp.textContent || tmp.innerText || '';
    };

    const chunkArray = <T,>(arr: T[], size: number): T[][] => {
        if (!Array.isArray(arr) || arr.length === 0) return [];
        const chunks: T[][] = [];
        for (let i = 0; i < arr.length; i += size) {
            chunks.push(arr.slice(i, i + size));
        }
        return chunks;
    };

    return (
        <div className="top">
            {/* Image Carousel */}
            <div className="banner">
                <div className="container-fluid px-0">
                    <div className="row">
                        <div className="col-12">
                            <div id="carouselImages" className="carousel slide" data-ride="carousel" data-bs-ride="carousel" style={{ margin: '15px 0px', maxWidth: '100%', height: 'auto' }}>
                                <ol className="carousel-indicators d-none d-md-block">
                                    <li data-bs-target="#carouselImages" data-bs-slide-to="0" className="active" aria-current="true"></li>
                                    <li data-bs-target="#carouselImages" data-bs-slide-to="1"></li>
                                    <li data-bs-target="#carouselImages" data-bs-slide-to="2"></li>
                                    <li data-bs-target="#carouselImages" data-bs-slide-to="3"></li>
                                </ol>
                                <div className="carousel-inner">
                                    <div className="carousel-item active">
                                        <img className="img-fluid w-100 d-block" src="/images/banner_1.png" alt="Banner 1" style={{ maxHeight: '600px', objectFit: 'cover' }} />
                                    </div>
                                    <div className="carousel-item">
                                        <img className="img-fluid w-100 d-block" src="/images/slide1.jpg" alt="Slide 1" style={{ maxHeight: '600px', objectFit: 'cover' }} />
                                    </div>
                                    <div className="carousel-item">
                                        <img className="img-fluid w-100 d-block" src="/images/slide3.jpg" alt="Slide 3" style={{ maxHeight: '600px', objectFit: 'cover' }} />
                                    </div>
                                    <div className="carousel-item">
                                        <img className="img-fluid w-100 d-block" src="/images/slide4.jpg" alt="Slide 4" style={{ maxHeight: '600px', objectFit: 'cover' }} />
                                    </div>
                                </div>
                                <a className="carousel-control-prev" href="#carouselImages" role="button" data-bs-slide="prev">
                                    <span className="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span className="visually-hidden">&#10094;</span>
                                </a>
                                <a className="carousel-control-next" href="#carouselImages" role="button" data-bs-slide="next">
                                    <span className="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span className="visually-hidden">&#10095;</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {/* Berita and Pengumuman Section */}
            <div className="container mb-4">
                <div className="row g-4">
                    <div className="col-12 col-md-6 news">
                        <h6 className="text section-title-responsive"><b>Berita</b></h6>
                        {loading ? (
                            <p className="text-muted">Memuat berita...</p>
                        ) : beritaList.length > 0 ? (
                            <div id="carouselBerita" className="carousel slide" data-ride="carousel" data-bs-ride="carousel" style={{ margin: '15px 0px', maxWidth: '100%', height: 'auto' }}>
                                <ol className="carousel-indicators d-none d-md-block">
                                    {chunkArray(beritaList, 3).map((_, index) => (
                                        <li
                                            key={index}
                                            data-bs-target="#carouselBerita"
                                            data-bs-slide-to={index}
                                            className={index === 0 ? 'active' : ''}
                                            aria-current={index === 0 ? 'true' : undefined}
                                        ></li>
                                    ))}
                                </ol>
                                <div className="carousel-inner">
                                    {chunkArray(beritaList, 3).map((beritaRow, index) => (
                                        <div key={index} className={`carousel-item ${index === 0 ? 'active' : ''}`}>
                                            <div className="list-group">
                                                {Array.isArray(beritaRow) && beritaRow.map((ls, idx) => (
                                                    <div key={ls.id} className={`list-group-item ${idx < beritaRow.length - 1 ? 'border-bottom' : ''} border-0 px-0`}>
                                                        <h5 className="fs-6 fs-md-5 mb-2">{ls.title_ms}</h5>
                                                        <p className="d-none d-md-block mb-2 small text-muted">{truncateText(stripHtml(ls.content_ms || ls.description_ms), 180)}</p>
                                                        <p className="d-md-none mb-2 small text-muted">{truncateText(stripHtml(ls.content_ms || ls.description_ms), 100)}</p>
                                                        <Link to={`/information/berita/${ls.id}`} className="btn btn-sm btn-outline-primary">Baca Selanjutnya</Link>
                                                    </div>
                                                ))}
                                            </div>
                                        </div>
                                    ))}
                                </div>
                                <a className="carousel-control-prev" href="#carouselBerita" role="button" data-bs-slide="prev">
                                    <span className="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span className="visually-hidden">&#10094;</span>
                                </a>
                                <a className="carousel-control-next" href="#carouselBerita" role="button" data-bs-slide="next">
                                    <span className="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span className="visually-hidden">&#10095;</span>
                                </a>
                            </div>
                        ) : (
                            <p className="text-muted">Tiada berita buat masa ini.</p>
                        )}
                    </div>
                    <div className="col-12 col-md-6 announcements">
                        <h6 className="text section-title-responsive"><b>Pengumuman</b></h6>
                        {loading ? (
                            <p className="text-muted">Memuat pengumuman...</p>
                        ) : pengumumanList.length > 0 ? (
                            <div id="carouselPengumuman" className="carousel slide" data-ride="carousel" data-bs-ride="carousel" style={{ margin: '15px 0px', maxWidth: '100%', height: 'auto' }}>
                                <ol className="carousel-indicators d-none d-md-block">
                                    {chunkArray(pengumumanList, 3).map((_, index) => (
                                        <li
                                            key={index}
                                            data-bs-target="#carouselPengumuman"
                                            data-bs-slide-to={index}
                                            className={index === 0 ? 'active' : ''}
                                            aria-current={index === 0 ? 'true' : undefined}
                                        ></li>
                                    ))}
                                </ol>
                                <div className="carousel-inner">
                                    {chunkArray(pengumumanList, 3).map((pengumumanRow, index) => (
                                        <div key={index} className={`carousel-item ${index === 0 ? 'active' : ''}`}>
                                            <div className="list-group">
                                                {Array.isArray(pengumumanRow) && pengumumanRow.map((ls, idx) => (
                                                    <div key={ls.id} className={`list-group-item ${idx < pengumumanRow.length - 1 ? 'border-bottom' : ''} border-0 px-0`}>
                                                        <h5 className="fs-6 fs-md-5 mb-2">{ls.title_ms}</h5>
                                                        <p className="d-none d-md-block mb-2 small text-muted">{truncateText(stripHtml(ls.content_ms || ls.description_ms), 180)}</p>
                                                        <p className="d-md-none mb-2 small text-muted">{truncateText(stripHtml(ls.content_ms || ls.description_ms), 100)}</p>
                                                        <Link to={`/information/pengumuman/${ls.id}`} className="btn btn-sm btn-outline-primary">Baca Selanjutnya</Link>
                                                    </div>
                                                ))}
                                            </div>
                                        </div>
                                    ))}
                                </div>
                                <a className="carousel-control-prev" href="#carouselPengumuman" role="button" data-bs-slide="prev">
                                    <span className="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span className="visually-hidden">&#10094;</span>
                                </a>
                                <a className="carousel-control-next" href="#carouselPengumuman" role="button" data-bs-slide="next">
                                    <span className="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span className="visually-hidden">&#10095;</span>
                                </a>
                            </div>
                        ) : (
                            <p className="text-muted">Tiada pengumuman buat masa ini.</p>
                        )}
                    </div>
                </div>
            </div>

            <div className="container mt-4 mb-4"><hr /></div>

            <style>{`
                .section-title-responsive {
                    font-size: 18px;
                }
                .list-group-item {
                    background-color: transparent;
                    border: none;
                    padding: 0.75rem 0;
                }
                .list-group-item.border-bottom {
                    border-bottom: 1px solid #dee2e6 !important;
                }
                @media (min-width: 576px) {
                    .section-title-responsive {
                        font-size: 20px;
                    }
                }
                @media (max-width: 768px) {
                    .banner {
                        margin: 0 !important;
                    }
                    .carousel-item img {
                        max-height: 300px !important;
                    }
                    .news, .announcements {
                        margin-bottom: 1rem;
                    }
                }
                @media (min-width: 769px) and (max-width: 992px) {
                    .carousel-item img {
                        max-height: 450px !important;
                    }
                }
            `}</style>
        </div>
    );
};

export default HomePage;
