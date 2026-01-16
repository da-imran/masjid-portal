import React, { useState, useEffect } from 'react';
import api from '@/lib/api';

interface KemudahanItem {
    id: number;
    title_ms: string;
    description_ms: string;
    icon_name: string;
    image_name: string;
    created_at: string;
}

const KemudahanPage: React.FC = () => {
    const [kemudahanList, setKemudahanList] = useState<KemudahanItem[]>([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const fetchKemudahan = async () => {
            try {
                const response = await api.get<KemudahanItem[]>('/api/kemudahan');
                // Ensure response.data is an array
                const data = Array.isArray(response.data) ? response.data : [];
                setKemudahanList(data);
            } catch (err) {
                console.error('Failed to fetch kemudahan:', err);
                setKemudahanList([]);
            } finally {
                setLoading(false);
            }
        };

        fetchKemudahan();
    }, []);

    const formatDate = (dateString: string): string => {
        const date = new Date(dateString);
        return date.toLocaleDateString('ms-MY', { day: 'numeric', month: 'short', year: 'numeric' });
    };

    const getImageUrl = (imageName?: string): string => {
        if (imageName) return `/storage/${imageName}`;
        return '/images/berita/default.jpg';
    };

    return (
        <div className="row">
            <div className="col-md-10">
                <h6 className="text mt-3" style={{ fontSize: '20px' }}><b>Kemudahan</b></h6>
                <div className="mt-5 mb-5 p-3 border">
                    {loading ? (
                        <p>Memuat kemudahan...</p>
                    ) : kemudahanList.length > 0 ? (
                        <table id="kemudahan" className="table table-striped" style={{ width: '100%' }}>
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Kemudahan</th>
                                    <th>Penerangan</th>
                                    <th>Tarikh Dicipta</th>
                                </tr>
                            </thead>
                            <tbody>
                                {kemudahanList.map((item, index) => (
                                    <tr key={item.id}>
                                        <td>{index + 1}</td>
                                        <td>
                                            <div className="d-flex align-items-center">
                                                {item.image_name && (
                                                    <img
                                                        src={getImageUrl(item.image_name)}
                                                        alt={item.title_ms}
                                                        style={{
                                                            width: '50px',
                                                            height: '50px',
                                                            objectFit: 'cover',
                                                            marginRight: '10px'
                                                        }}
                                                    />
                                                )}
                                                <span>{item.title_ms}</span>
                                            </div>
                                        </td>
                                        <td>{item.description_ms?.substring(0, 100)}{item.description_ms && item.description_ms.length > 100 ? '...' : ''}</td>
                                        <td>{formatDate(item.created_at)}</td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    ) : (
                        <p>Tiada kemudahan buat masa ini.</p>
                    )}
                </div>
            </div>
        </div>
    );
};

export default KemudahanPage;
