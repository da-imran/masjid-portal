import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import api from '@/lib/api';
import type { PrayerTimes } from '@/types';

interface PrayerTimesRowProps {
    prayerTimes: PrayerTimes | null;
    prayerError: string | null;
}

const PrayerTimesRow: React.FC<PrayerTimesRowProps> = ({ prayerTimes, prayerError }) => {
    if (prayerError) {
        return (
            <tr>
                <td colSpan={2} className="text-center text-danger fw-bold" style={{ fontSize: '11px' }}>
                    {prayerError}
                </td>
            </tr>
        );
    }

    if (!prayerTimes) {
        return (
            <tr>
                <td colSpan={2} className="text-center" style={{ fontSize: '11px' }}>
                    Memuat naik waktu solat...
                </td>
            </tr>
        );
    }

    return (
        <>
            <tr>
                <td className="text-start" style={{ fontSize: '11px' }}><strong>SUBUH</strong></td>
                <td className="text-end" style={{ fontSize: '11px' }}>{prayerTimes.subuh}</td>
            </tr>
            <tr>
                <td className="text-start" style={{ fontSize: '11px' }}><strong>SYURUK</strong></td>
                <td className="text-end" style={{ fontSize: '11px' }}>{prayerTimes.syuruk}</td>
            </tr>
            <tr>
                <td className="text-start" style={{ fontSize: '11px' }}><strong>ZOHOR</strong></td>
                <td className="text-end" style={{ fontSize: '11px' }}>{prayerTimes.zohor}</td>
            </tr>
            <tr>
                <td className="text-start" style={{ fontSize: '11px' }}><strong>ASAR</strong></td>
                <td className="text-end" style={{ fontSize: '11px' }}>{prayerTimes.asar}</td>
            </tr>
            <tr>
                <td className="text-start" style={{ fontSize: '11px' }}><strong>MAGHRIB</strong></td>
                <td className="text-end" style={{ fontSize: '11px' }}>{prayerTimes.maghrib}</td>
            </tr>
            <tr>
                <td className="text-start" style={{ fontSize: '11px' }}><strong>ISYAK</strong></td>
                <td className="text-end" style={{ fontSize: '11px' }}>{prayerTimes.isyak}</td>
            </tr>
        </>
    );
};

const Header: React.FC = () => {
    const [prayerTimes, setPrayerTimes] = useState<PrayerTimes | null>(null);
    const [prayerError, setPrayerError] = useState<string | null>(null);
    const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
    const [activeDropdown, setActiveDropdown] = useState<string | null>(null);

    useEffect(() => {
        const fetchPrayerTimes = async () => {
            try {
                const response = await api.get<{ data: PrayerTimes }>('/solat');
                setPrayerTimes(response.data.data);
            } catch (err) {
                setPrayerError('Gagal memuat naik waktu solat');
            }
        };

        fetchPrayerTimes();
    }, []);

    const toggleDropdown = (dropdownName: string) => {
        setActiveDropdown(activeDropdown === dropdownName ? null : dropdownName);
    };

    return (
        <>
            {/* Top Bar with Prayer Times */}
            <div className="top-bar bg-light border-bottom">
                <div className="container">
                    <div className="row">
                        {/* Desktop Prayer Times */}
                        <div className="col-12 d-none d-md-block pt-1 pb-1">
                            <table width="100%" border={0} style={{ fontSize: '12px' }}>
                                <tbody>
                                    <tr>
                                        {prayerError ? (
                                            <td colSpan={14} align="center" valign="top" style={{ color: 'red', fontWeight: 'bold' }}>
                                                {prayerError}
                                            </td>
                                        ) : prayerTimes ? (
                                            <>
                                                <td colSpan={2} align="center" valign="top" style={{ color: '#000000' }}>
                                                    <a href="https://www.e-solat.gov.my/" target="_blank" rel="noopener noreferrer" style={{ color: '#000000' }}>
                                                        <u>WAKTU SOLAT</u>
                                                    </a> BAGI PULAU PINANG
                                                </td>
                                                <td align="center" valign="top"><strong>SUBUH</strong></td>
                                                <td align="center" valign="top"> : <label style={{ fontWeight: 700 }}>{prayerTimes.subuh}</label> | </td>
                                                <td align="center" valign="top"><strong>SYURUK</strong></td>
                                                <td align="center" valign="top"> : <label style={{ fontWeight: 700 }}>{prayerTimes.syuruk}</label> | </td>
                                                <td align="center" valign="top"><strong>ZOHOR</strong></td>
                                                <td align="center" valign="top"> : <label style={{ fontWeight: 700 }}>{prayerTimes.zohor}</label> | </td>
                                                <td align="center" valign="top"><strong>ASAR</strong></td>
                                                <td align="center" valign="top"> : <label style={{ fontWeight: 700 }}>{prayerTimes.asar}</label> | </td>
                                                <td align="center" valign="top"><strong>MAGHRIB</strong></td>
                                                <td align="center" valign="top"> : <label style={{ fontWeight: 700 }}>{prayerTimes.maghrib}</label> | </td>
                                                <td align="center" valign="top"><strong>ISYAK</strong></td>
                                                <td align="center" valign="top"> : <label style={{ fontWeight: 700 }}>{prayerTimes.isyak}</label> </td>
                                            </>
                                        ) : (
                                            <td colSpan={14} align="center" valign="top">
                                                Memuat naik waktu solat...
                                            </td>
                                        )}
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        {/* Mobile Prayer Times */}
                        <div className="col-12 d-md-none pt-1 pb-1">
                            <div className="d-flex justify-content-between align-items-center">
                                <a href="https://www.e-solat.gov.my/" target="_blank" rel="noopener noreferrer" style={{ fontSize: '10px', color: '#000' }}>
                                    <u>WAKTU SOLAT</u>
                                </a>
                                <button
                                    className="btn btn-sm btn-outline-secondary btn-sm py-0"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#prayerTimesCollapse"
                                    aria-expanded="false"
                                    aria-controls="prayerTimesCollapse"
                                    style={{ fontSize: '10px' }}
                                >
                                    Lihat
                                </button>
                            </div>
                            <div className="collapse mt-1" id="prayerTimesCollapse">
                                <table className="table table-sm table-bordered mb-0" style={{ fontSize: '11px' }}>
                                    <tbody>
                                        <PrayerTimesRow prayerTimes={prayerTimes} prayerError={prayerError} />
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {/* Logo Section */}
            <div className="image-text-container">
                <img
                    src="/images/masjid.jpeg"
                    alt="Masjid Al Mustaghfirin"
                    style={{ marginRight: '10px', height: '3rem', width: 'auto', maxWidth: '100%', objectFit: 'contain' }}
                    className="d-none d-sm-inline"
                />
                <img
                    src="/images/masjid.jpeg"
                    alt="Masjid Al Mustaghfirin"
                    style={{ marginRight: '8px', height: '2.5rem', width: 'auto', maxWidth: '100%', objectFit: 'contain' }}
                    className="d-inline d-sm-none"
                />
                <p className="d-none d-sm-inline">Masjid Al Mustaghfirin, Bayan Lepas</p>
                <p className="d-inline d-sm-none mb-0" style={{ fontSize: '1.2rem' }}>Masjid Al Mustaghfirin</p>
            </div>

            {/* Navigation */}
            <div id="menu" className="bg-light border-bottom">
                <div className="container">
                    <nav className="navbar navbar-expand-lg navbar-light">
                        <button
                            className="navbar-toggler"
                            type="button"
                            onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
                            aria-label="Toggle navigation"
                            aria-expanded={mobileMenuOpen}
                        >
                            <span className="navbar-toggler-icon"></span>
                        </button>
                        <div className={`collapse navbar-collapse justify-content-center ${mobileMenuOpen ? 'show' : ''}`} id="navbarTogglerDemo02">
                            <ul className="navbar-nav">
                                <li className="nav-item">
                                    <Link className="nav-link" to="/" onClick={() => setMobileMenuOpen(false)}>UTAMA</Link>
                                </li>
                                <li className="nav-item dropdown">
                                    <a
                                        className="nav-link dropdown-toggle"
                                        href="#"
                                        role="button"
                                        onClick={(e) => {
                                            e.preventDefault();
                                            toggleDropdown('corporate');
                                        }}
                                        aria-expanded={activeDropdown === 'corporate'}
                                    >
                                        INFO KORPORAT
                                    </a>
                                    <div className={`dropdown-menu ${activeDropdown === 'corporate' && mobileMenuOpen ? 'show' : ''}`}>
                                        <Link className="dropdown-item" to="/corporate/perutusan" onClick={() => { setMobileMenuOpen(false); setActiveDropdown(null); }}>Perutusan Imam Besar</Link>
                                        <Link className="dropdown-item" to="/corporate/sejarah" onClick={() => { setMobileMenuOpen(false); setActiveDropdown(null); }}>Sejarah Masjid</Link>
                                        <Link className="dropdown-item" to="/corporate/profil" onClick={() => { setMobileMenuOpen(false); setActiveDropdown(null); }}>Profil Korporat</Link>
                                        <Link className="dropdown-item" to="/corporate/logo" onClick={() => { setMobileMenuOpen(false); setActiveDropdown(null); }}>Logo</Link>
                                        <Link className="dropdown-item" to="/corporate/carta" onClick={() => { setMobileMenuOpen(false); setActiveDropdown(null); }}>Carta Organisasi</Link>
                                        <Link className="dropdown-item" to="/corporate/direktori" onClick={() => { setMobileMenuOpen(false); setActiveDropdown(null); }}>Direktori Kakitangan</Link>
                                    </div>
                                </li>
                                <li className="nav-item dropdown">
                                    <a
                                        className="nav-link dropdown-toggle"
                                        href="#"
                                        role="button"
                                        onClick={(e) => {
                                            e.preventDefault();
                                            toggleDropdown('informasi');
                                        }}
                                        aria-expanded={activeDropdown === 'informasi'}
                                    >
                                        INFORMASI
                                    </a>
                                    <div className={`dropdown-menu ${activeDropdown === 'informasi' && mobileMenuOpen ? 'show' : ''}`}>
                                        <Link className="dropdown-item" to="/information/berita" onClick={() => { setMobileMenuOpen(false); setActiveDropdown(null); }}>Berita Semasa</Link>
                                        <Link className="dropdown-item" to="/information/pengumuman" onClick={() => { setMobileMenuOpen(false); setActiveDropdown(null); }}>Pengumuman</Link>
                                        <Link className="dropdown-item" to="/information/kemudahan" onClick={() => { setMobileMenuOpen(false); setActiveDropdown(null); }}>Kemudahan</Link>
                                        <Link className="dropdown-item" to="/information/takwim" onClick={() => { setMobileMenuOpen(false); setActiveDropdown(null); }}>Takwim</Link>
                                        <Link className="dropdown-item" to="/information/kutipan" onClick={() => { setMobileMenuOpen(false); setActiveDropdown(null); }}>Kutipan Tabung Masjid</Link>
                                    </div>
                                </li>
                                <li className="nav-item dropdown">
                                    <a
                                        className="nav-link dropdown-toggle"
                                        href="#"
                                        role="button"
                                        onClick={(e) => {
                                            e.preventDefault();
                                            toggleDropdown('download');
                                        }}
                                        aria-expanded={activeDropdown === 'download'}
                                    >
                                        MUAT TURUN
                                    </a>
                                    <div className={`dropdown-menu ${activeDropdown === 'download' && mobileMenuOpen ? 'show' : ''}`}>
                                        <Link className="dropdown-item" to="/download/jadual" onClick={() => { setMobileMenuOpen(false); setActiveDropdown(null); }}>Jadual Kuliah</Link>
                                        <Link className="dropdown-item" to="/download/nota" onClick={() => { setMobileMenuOpen(false); setActiveDropdown(null); }}>Nota Kuliah</Link>
                                        <Link className="dropdown-item" to="/download/borang" onClick={() => { setMobileMenuOpen(false); setActiveDropdown(null); }}>Borang</Link>
                                    </div>
                                </li>
                                <li className="nav-item">
                                    <Link className="nav-link" to="/contact" onClick={() => setMobileMenuOpen(false)}>HUBUNGI KAMI</Link>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>

            <style>{`
                .image-text-container {
                    align-items: center;
                    background-color: rgb(31, 31, 31);
                    color: white;
                    display: flex;
                    font-weight: 600;
                    font-style: italic;
                    font-size: 1.5rem;
                    justify-content: center;
                    letter-spacing: 1px;
                    text-decoration: none;
                    height: 5em;
                    padding: 0 15px;
                }
                @media (max-width: 576px) {
                    .image-text-container {
                        font-size: 1.2rem;
                        height: 4em;
                        padding: 0 10px;
                    }
                }
                .image-text-container img {
                    margin-right: 10px;
                }
                @media (max-width: 576px) {
                    .image-text-container img {
                        margin-right: 8px;
                    }
                }
                li.nav-item {
                    font-weight: bold;
                    color: black;
                }
                .nav-link {
                    cursor: pointer;
                    padding: 0.5rem 1rem !important;
                }
                @media (max-width: 991px) {
                    .nav-link {
                        padding: 0.75rem 1rem !important;
                        border-bottom: 1px solid #dee2e6;
                    }
                    .dropdown-menu {
                        border: none;
                        padding-left: 1.5rem;
                        background-color: #f8f9fa;
                    }
                }
                li.nav-link:hover {
                    background-color: white;
                    color: red;
                }
                .dropdown:hover .dropdown-menu {
                    display: block;
                    margin-top: 0;
                }
                @media (min-width: 992px) {
                    .navbar {
                        justify-content: center !important;
                    }
                    .navbar .nav-item:not(:last-child) {
                        margin-right: 35px;
                    }
                }
                @media (max-width: 991px) {
                    .navbar-nav {
                        width: 100%;
                    }
                    .nav-item {
                        width: 100%;
                    }
                }
            `}</style>
        </>
    );
};

export default Header;
