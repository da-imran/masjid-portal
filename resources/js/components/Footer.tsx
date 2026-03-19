import React, { useState, useEffect, useCallback } from 'react';
import api from '@/lib/api';
import type { VisitorCount } from '@/types';

const ADMIN_URL = import.meta.env.VITE_ADMIN_URL ?? '/admin';

const Footer: React.FC = () => {
    const [visitorCount, setVisitorCount] = useState<VisitorCount>({
        dailyCount: 0,
        monthlyCount: 0,
        overallCount: 0,
    });

    // Fetch visitor count
    const fetchVisitorCount = useCallback(async () => {
        try {
            const response = await api.get<VisitorCount>('/visitor-count');
            setVisitorCount(response.data);
        } catch (error) {
            console.error('Failed to fetch visitor count:', error);
        }
    }, []);

    useEffect(() => {
        fetchVisitorCount();
    }, [fetchVisitorCount]);

    const handleAdminRedirect = () => {
        window.location.href = ADMIN_URL;
    };

    const visitorData = [
        { label: 'Hari Ini', value: visitorCount.dailyCount },
        { label: 'Bulan Ini', value: visitorCount.monthlyCount },
        { label: 'Keseluruhan', value: visitorCount.overallCount },
    ];

    const webLinks = [
        { label: 'Data Terbuka', href: '#', title: 'Data Terbuka' },
        { label: 'Dasar Privasi', href: '#', title: 'Dasar Privasi' },
        { label: 'Jom Infaq', href: 'https://infaqpay.my/go/masjidalmustaghfirinsungaitiram', title: 'Jom Infaq' },
        { label: 'Laman Facebook', href: 'https://www.facebook.com/MasjidAlMustaghfirinSungaiTiram', title: 'Laman Facebook' },
    ];

    return (
        <footer className="footer mt-auto">
            <div className="container">
                <div className="row">
                    {/* Visitor Count */}
                    <div className="col-6 col-md-4 mt-3 mb-3">
                        <h1 style={{ fontSize: '25px' }}><b>Jumlah Pelawat</b></h1>
                        <div className="row">
                            <div className="col-md-3">
                                {visitorData.map((item) => (
                                    <div key={item.label}>{item.label}</div>
                                ))}
                            </div>
                            <div className="col-md-3">
                                {visitorData.map((item) => (
                                    <div key={item.label}>{item.value ?? '-'}</div>
                                ))}
                            </div>
                        </div>
                    </div>

                    {/* Web Links */}
                    <div className="col-6 col-md-4 mt-3 mb-3">
                        <h1 style={{ fontSize: '25px' }}><b>Pautan Web</b></h1>
                        <div>
                            {webLinks.map(({ label, href, title }) => (
                                <a key={label} href={href} className="text" title={title}>{label}<br /></a>
                            ))}
                            <button
                                onClick={handleAdminRedirect}
                                className="text"
                                title="Laman Staf"
                                style={{
                                    background: 'none',
                                    border: 'none',
                                    color: 'white',
                                    padding: 0,
                                    cursor: 'pointer',
                                    textDecoration: 'none',
                                    font: 'inherit',
                                }}
                            >
                                Laman Staf
                            </button>
                        </div>
                    </div>

                    {/* Address */}
                    <div className="col-6 col-md-4 mt-3 mb-3">
                        <h1 style={{ fontSize: '25px' }}><b>Alamat</b></h1>
                        <p>
                            Masjid Al Mustaghfirin, Bayan Lepas <br />
                            Jalan Sultan Azlan Shah, Kampung Sungai Tiram, <br />
                            11900 Bayan Lepas, <br />
                            Pulau Pinang
                        </p>
                        <p className="font-italic">Telefon: +019-320-0799</p>
                    </div>
                </div>
            </div>

            <style>{`
                .footer {
                    color: white;
                    background-color: rgb(31, 31, 31);
                }
                .footer a.text, .footer button.text {
                    color: white;
                    text-decoration: none;
                }
                .footer a.text:hover,
                .footer button.text:hover {
                    text-decoration: underline;
                }
            `}</style>
        </footer>
    );
};

export default Footer;