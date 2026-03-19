import React, { useState, useEffect } from 'react';
import api from '@/lib/api';
import type { NewsItem } from '@/types';

const BeritaSemasaPage: React.FC = () => {
    const [beritaList, setBeritaList] = useState<NewsItem[]>([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const fetchBerita = async () => {
            try {
                const response = await api.get<NewsItem[]>('/api/v1/berita');
                // Ensure response.data is an array
                const data = Array.isArray(response.data) ? response.data : [];
                setBeritaList(data);
            } catch (err) {
                console.error('Failed to fetch berita:', err);
                setBeritaList([]);
            } finally {
                setLoading(false);
            }
        };

        fetchBerita();
    }, []);

    const formatDate = (dateString: string): string => {
        const date = new Date(dateString);
        return date.toLocaleDateString('ms-MY', { day: 'numeric', month: 'short', year: 'numeric' });
    };

    return (
        <div className="row">
            <div className="col-md-10">
                <h6 className="text mt-3" style={{ fontSize: '20px' }}><b>Berita Semasa</b></h6>
                <div className="mt-5 mb-5 p-3 border">
                    {loading ? (
                        <p>Memuat berita...</p>
                    ) : beritaList.length > 0 ? (
                        <table id="beritaSemasa" className="table table-striped" style={{ width: '100%' }}>
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Tajuk</th>
                                    <th>Tarikh Dicipta</th>
                                    <th>Dikunjungi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {beritaList.map((item, index) => (
                                    <tr key={item.id}>
                                        <td>{index + 1}</td>
                                        <td><a href={`/information/berita/${item.id}`}>{item.title_ms}</a></td>
                                        <td>{formatDate(item.created_at)}</td>
                                        <td>{item.view_count}</td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    ) : (
                        <p>Tiada berita buat masa ini.</p>
                    )}
                </div>
            </div>
        </div>
    );
};

export default BeritaSemasaPage;
