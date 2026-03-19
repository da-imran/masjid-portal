import React, { useState, useEffect } from 'react';
import api from '@/lib/api';
import type { Announcement } from '@/types';

const PengumumanPage: React.FC = () => {
    const [pengumumanList, setPengumumanList] = useState<Announcement[]>([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const fetchPengumuman = async () => {
            try {
                const response = await api.get<Announcement[]>('/api/v1/pengumuman');
                // Ensure response.data is an array
                const data = Array.isArray(response.data) ? response.data : [];
                setPengumumanList(data);
            } catch (err) {
                console.error('Failed to fetch pengumuman:', err);
                setPengumumanList([]);
            } finally {
                setLoading(false);
            }
        };

        fetchPengumuman();
    }, []);

    const formatDate = (dateString: string): string => {
        const date = new Date(dateString);
        return date.toLocaleDateString('ms-MY', { day: 'numeric', month: 'short', year: 'numeric' });
    };

    return (
        <div className="row">
            <div className="col-md-10">
                <h6 className="text mt-3" style={{ fontSize: '20px' }}><b>Pengumuman</b></h6>
                <div className="mt-5 mb-5 p-3 border">
                    {loading ? (
                        <p>Memuat pengumuman...</p>
                    ) : pengumumanList.length > 0 ? (
                        <table className="table table-striped" style={{ width: '100%' }}>
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Tajuk</th>
                                    <th>Tarikh Dicipta</th>
                                </tr>
                            </thead>
                            <tbody>
                                {pengumumanList.map((item, index) => (
                                    <tr key={item.id}>
                                        <td>{index + 1}</td>
                                        <td><a href={`/information/pengumuman/${item.id}`}>{item.title_ms}</a></td>
                                        <td>{formatDate(item.created_at)}</td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    ) : (
                        <p>Tiada pengumuman buat masa ini.</p>
                    )}
                </div>
            </div>
        </div>
    );
};

export default PengumumanPage;
