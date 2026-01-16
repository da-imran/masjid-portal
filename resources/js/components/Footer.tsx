import React, { useState, useEffect } from 'react';
import api from '@/lib/api';
import type { VisitorCount } from '@/types';

const Footer: React.FC = () => {
    const [visitorCount, setVisitorCount] = useState<VisitorCount | null>(null);

    useEffect(() => {
        const fetchVisitorCount = async () => {
            try {
                const response = await api.get<VisitorCount>('/api/visitor-count');
                setVisitorCount(response.data);
            } catch (err) {
                console.error('Failed to fetch visitor count:', err);
            }
        };

        fetchVisitorCount();
    }, []);

    return (
        <div className="footer mt-auto">
            <div className="container">
                <div className="row">
                    <div className="col-6 col-md-4 mt-3 mb-3">
                        <div><h1 style={{ fontSize: '25px' }}><b>Jumlah Pelawat</b></h1></div>
                        <div className="row">
                            <div className="col-md-3">
                                <div>Hari Ini</div>
                                <div>Bulan Ini</div>
                                <div>Keseluruhan</div>
                            </div>
                            <div className="col-md-3">
                                <div>{visitorCount?.dailyCount ?? '-'}</div>
                                <div>{visitorCount?.monthlyCount ?? '-'}</div>
                                <div>{visitorCount?.overallCount ?? '-'}</div>
                            </div>
                        </div>
                    </div>
                    <div className="col-6 col-md-4 mt-3 mb-3">
                        <div><h1 style={{ fontSize: '25px' }}><b>Pautan Web</b></h1></div>
                        <div>
                            <a href="#" className="text" title="Data Terbuka">Data Terbuka</a> <br />
                            <a href="#" className="text" title="Dasar Privasi">Dasar Privasi</a> <br />
                            <a href="https://infaqpay.my/go/masjidalmustaghfirinsungaitiram" className="text" title="Jom Infaq">Jom Infaq</a> <br />
                            <a href="https://www.facebook.com/MasjidAlMustaghfirinSungaiTiram" className="text" title="Laman Facebook">Laman Facebook</a> <br />
                        </div>
                    </div>
                    <div className="col-6 col-md-4 mt-3 mb-3">
                        <div><h1 style={{ fontSize: '25px' }}><b>Alamat</b></h1></div>
                        <p>Masjid Al Mustaghfirin, Bayan Lepas</p>
                        <p>Jalan Sultan Azlan Shah, Kampung Sungai Tiram,</p>
                        <p>11900 Bayan Lepas,</p>
                        <p>Pulau Pinang</p>
                        <p className="font-italic">Telefon: +019-320-0799</p>
                    </div>
                </div>
            </div>

            <style>{`
                .footer {
                    color: white;
                    background-color: rgb(31, 31, 31);
                    margin-top: auto;
                }
                .footer a.text {
                    color: white;
                    text-decoration: none;
                }
                .footer a.text:hover {
                    text-decoration: underline;
                }
            `}</style>
        </div>
    );
};

export default Footer;
