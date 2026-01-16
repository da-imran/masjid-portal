import React, { useState, useEffect } from 'react';
import api from '@/lib/api';

interface TakwimItem {
    id: number;
    title_ms: string;
    description_ms: string;
    event_date: string;
    event_time: string;
    location_ms: string;
    image_name: string;
    created_at: string;
}

const TakwimPage: React.FC = () => {
    const [takwimList, setTakwimList] = useState<TakwimItem[]>([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const fetchTakwim = async () => {
            try {
                const response = await api.get<TakwimItem[]>('/api/takwim');
                // Ensure response.data is an array
                const data = Array.isArray(response.data) ? response.data : [];
                setTakwimList(data);
            } catch (err) {
                console.error('Failed to fetch takwim:', err);
                setTakwimList([]);
            } finally {
                setLoading(false);
            }
        };

        fetchTakwim();
    }, []);

    const formatDate = (dateString: string): string => {
        const date = new Date(dateString);
        return date.toLocaleDateString('ms-MY', { day: 'numeric', month: 'short', year: 'numeric' });
    };

    const formatTime = (timeString: string): string => {
        if (!timeString) return '-';
        const [hours, minutes] = timeString.split(':');
        return `${hours}:${minutes}`;
    };

    const getImageUrl = (imageName?: string): string => {
        if (imageName) return `/storage/${imageName}`;
        return '/images/berita/default.jpg';
    };

    return (
        <div className="row">
            <div className="col-md-10">
                <h6 className="text mt-3" style={{ fontSize: '20px' }}><b>Takwim</b></h6>
                <div className="mt-5 mb-5 p-3 border">
                    {loading ? (
                        <p>Memuat takwim...</p>
                    ) : takwimList.length > 0 ? (
                        <table id="takwim" className="table table-striped" style={{ width: '100%' }}>
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Tajuk</th>
                                    <th>Tarikh & Masa</th>
                                    <th>Lokasi</th>
                                    <th>Tarikh Dicipta</th>
                                </tr>
                            </thead>
                            <tbody>
                                {takwimList.map((item, index) => (
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
                                        <td>
                                            <div>{formatDate(item.event_date)}</div>
                                            <small className="text-muted">{formatTime(item.event_time)}</small>
                                        </td>
                                        <td>{item.location_ms || '-'}</td>
                                        <td>{formatDate(item.created_at)}</td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    ) : (
                        <p>Tiada takwim buat masa ini.</p>
                    )}
                </div>
            </div>
        </div>
    );
};

export default TakwimPage;
